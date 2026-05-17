<?php

class AdminController
{
    private function getDb(): Database
    {
        return Database::getInstance();
    }

    private function requireAdmin(): void
    {
        if (empty($_SESSION['admin_id'])) {
            redirect('admin_login');
        }
    }

    public function login(): void
    {
        if (!empty($_SESSION['admin_id'])) {
            redirect('admin_dashboard');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } else {
                try {
                    $db    = $this->getDb();
                    $admin = $db->fetch(
                        "SELECT * FROM admins WHERE email = :email AND actif = 1",
                        [':email' => $email]
                    );

                    if ($admin && password_verify($password, $admin['mot_de_passe'])) {
                        $_SESSION['admin_id']   = $admin['id'];
                        $_SESSION['admin_nom']  = $admin['nom'];
                        $_SESSION['admin_role'] = $admin['role'];
                        redirect('admin_dashboard');
                    } else {
                        $error = 'Email ou mot de passe incorrect.';
                    }
                } catch (\Exception $e) {
                    $error = 'Erreur serveur : ' . $e->getMessage();
                }
            }
        }

        require_once __DIR__ . '/../../views/admin/login.php';
    }

    public function logout(): void
    {
        unset($_SESSION['admin_id'], $_SESSION['admin_nom'], $_SESSION['admin_role']);
        redirect('admin_login');
    }

    public function dashboard(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        // Stats aujourd'hui
        $today = date('Y-m-d');
        $hier  = date('Y-m-d', strtotime('-1 day'));

        $statsToday = $db->fetch(
            "SELECT COUNT(*) as nb_commandes, COALESCE(SUM(total),0) as ca
             FROM commandes WHERE DATE(created_at) = :d", [':d' => $today]
        ) ?: ['nb_commandes'=>0,'ca'=>0];

        $statsHier = $db->fetch(
            "SELECT COUNT(*) as nb_commandes, COALESCE(SUM(total),0) as ca
             FROM commandes WHERE DATE(created_at) = :d", [':d' => $hier]
        ) ?: ['nb_commandes'=>0,'ca'=>0];

        $clientsToday = $db->fetch(
            "SELECT COUNT(*) as nb FROM clients WHERE DATE(created_at) = :d", [':d' => $today]
        ) ?: ['nb'=>0];
        $clientsHier = $db->fetch(
            "SELECT COUNT(*) as nb FROM clients WHERE DATE(created_at) = :d", [':d' => $hier]
        ) ?: ['nb'=>0];

        $stockAlertes = $db->fetchAll(
            "SELECT COUNT(*) as nb FROM produits WHERE stock_kg <= unite_min_kg AND actif = 1"
        );
        $nbStockAlertes = (int)($stockAlertes[0]['nb'] ?? 0);

        $enAttente = $db->fetch(
            "SELECT COUNT(*) as nb FROM commandes WHERE statut = 'en_attente'"
        ) ?: ['nb'=>0];

        // 7 derniers jours
        $semaine = [];
        $joursSemaine = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-$i days"));
            $r = $db->fetch("SELECT COUNT(*) as nb FROM commandes WHERE DATE(created_at) = :d", [':d'=>$d]);
            $semaine[]     = (int)($r['nb'] ?? 0);
            $joursSemaine[] = date('d/m', strtotime("-$i days"));
        }

        // Commandes récentes
        $commandesRecentes = $db->fetchAll(
            "SELECT c.id, c.reference, c.total, c.statut, c.created_at,
                    cl.nom as client_nom, cl.prenom as client_prenom
             FROM commandes c
             LEFT JOIN clients cl ON c.client_id = cl.id
             ORDER BY c.created_at DESC LIMIT 6"
        );

        // Stock avec photos
        $stockProduits = $db->fetchAll(
            "SELECT id, nom, stock_kg, unite_min_kg, image_principale
             FROM produits WHERE actif = 1
             ORDER BY stock_kg ASC LIMIT 6"
        );

        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }

    public function commandes(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $commandes = $db->fetchAll(
            "SELECT c.*,
                    cl.nom as client_nom, cl.prenom as client_prenom, cl.email as client_email,
                    (SELECT COUNT(*) FROM commande_details cd WHERE cd.commande_id = c.id) as nb_articles,
                    (SELECT COALESCE(SUM(cd2.quantite),0) FROM commande_details cd2 WHERE cd2.commande_id = c.id AND cd2.unite = 'kg') as total_kg,
                    (SELECT COALESCE(SUM(cd3.quantite),0) FROM commande_details cd3 WHERE cd3.commande_id = c.id AND cd3.unite = 'carton') as total_cartons,
                    lv.id as livraison_id, lv.statut as livraison_statut,
                    lr.nom as livreur_nom, lr.telephone as livreur_tel
             FROM commandes c
             LEFT JOIN clients cl ON c.client_id = cl.id
             LEFT JOIN livraisons lv ON lv.commande_id = c.id
             LEFT JOIN livreurs lr ON lr.id = lv.livreur_id
             ORDER BY c.created_at DESC"
        );
        $livreurs = $db->fetchAll("SELECT * FROM livreurs WHERE actif = 1 ORDER BY nom");
        require_once __DIR__ . '/../../views/admin/commandes.php';
    }

    public function checkNouvellesCommandes(): void
    {
        $this->requireAdmin();
        header('Content-Type: application/json');
        $db = $this->getDb();

        // Nouvelles commandes depuis since_id (ou toutes les en_attente si since_id=0)
        $sinceId = (int)($_GET['since_id'] ?? 0);
        if ($sinceId === 0) {
            // Premier chargement : retourner toutes les commandes en_attente
            $nouvelles = $db->fetchAll(
                "SELECT c.id, c.reference, c.total, c.statut, c.created_at,
                        cl.nom as client_nom, cl.prenom as client_prenom
                 FROM commandes c
                 LEFT JOIN clients cl ON c.client_id = cl.id
                 WHERE c.statut = 'en_attente'
                 ORDER BY c.id ASC"
            );
        } else {
            $nouvelles = $db->fetchAll(
                "SELECT c.id, c.reference, c.total, c.statut, c.created_at,
                        cl.nom as client_nom, cl.prenom as client_prenom
                 FROM commandes c
                 LEFT JOIN clients cl ON c.client_id = cl.id
                 WHERE c.id > :sid
                 ORDER BY c.id ASC",
                [':sid' => $sinceId]
            );
        }

        // Statuts actuels des commandes dont les ids sont suivis (pour détecter les traitées)
        $watchIds = array_filter(array_map('intval', explode(',', $_GET['watch'] ?? '')));
        $statuts  = [];
        if (!empty($watchIds)) {
            $placeholders = implode(',', array_fill(0, count($watchIds), '?'));
            $rows = $db->fetchAll(
                "SELECT id, statut FROM commandes WHERE id IN ($placeholders)",
                array_values($watchIds)
            );
            foreach ($rows as $r) {
                $statuts[(int)$r['id']] = $r['statut'];
            }
        }

        echo json_encode(['nouvelles' => $nouvelles, 'statuts' => $statuts]);
        exit;
    }

    public function commandeDetail(): void
    {
        $this->requireAdmin();
        $db  = $this->getDb();
        $id  = (int)($_GET['id'] ?? 0);
        if (!$id) { redirect('admin_commandes'); }

        $commande = $db->fetch(
            "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom,
                    cl.email as client_email, cl.telephone as client_tel
             FROM commandes c
             LEFT JOIN clients cl ON c.client_id = cl.id
             WHERE c.id = :id",
            [':id' => $id]
        );
        if (!$commande) { redirect('admin_commandes&error=' . urlencode('Commande introuvable.')); }

        $lignes = $db->fetchAll(
            "SELECT cd.*, p.image_principale
             FROM commande_details cd
             LEFT JOIN produits p ON p.id = cd.produit_id
             WHERE cd.commande_id = :id",
            [':id' => $id]
        );

        $livraison = $db->fetch(
            "SELECT lv.*, lr.nom as livreur_nom, lr.telephone as livreur_tel, lr.zone as livreur_zone
             FROM livraisons lv
             LEFT JOIN livreurs lr ON lr.id = lv.livreur_id
             WHERE lv.commande_id = :id",
            [':id' => $id]
        );
        $livreurs = $db->fetchAll("SELECT * FROM livreurs WHERE actif = 1 ORDER BY nom");

        require_once __DIR__ . '/../../views/admin/commande_detail.php';
    }

    public function changerStatutCommande(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_commandes'); }

        $db     = $this->getDb();
        $id     = (int)($_POST['id'] ?? 0);
        $statut = $_POST['statut'] ?? '';
        $statutsValides = ['en_attente','confirmee','en_preparation','en_livraison','livree','annulee'];

        if (!$id || !in_array($statut, $statutsValides)) {
            redirect('admin_commandes&error=' . urlencode('Données invalides.'));
        }

        // Récupérer l'ancien statut et les infos client pour l'email
        $commande = $db->fetch(
            "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom, cl.email as client_email
             FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id
             WHERE c.id = :id",
            [':id' => $id]
        );
        $ancienStatut = $commande['statut'] ?? '';

        $db->query("UPDATE commandes SET statut = :s WHERE id = :id", [':s'=>$statut, ':id'=>$id]);

        // Création automatique d'une livraison quand la commande est confirmée
        if ($ancienStatut !== 'confirmee' && $statut === 'confirmee') {
            $existante = $db->fetch(
                "SELECT id FROM livraisons WHERE commande_id = :cid",
                [':cid' => $id]
            );
            if (!$existante) {
                $db->query(
                    "INSERT INTO livraisons (commande_id, statut, date_prevue, created_at)
                     VALUES (:cid, 'en_attente', DATE_ADD(CURDATE(), INTERVAL 2 DAY), NOW())",
                    [':cid' => $id]
                );
            }
        }

        // Synchroniser le statut de livraison quand commande livrée ou annulée
        if ($ancienStatut !== $statut && in_array($statut, ['livree', 'annulee'])) {
            $statutLivraison = ($statut === 'livree') ? 'livree' : 'echec';
            $dateLivree      = ($statut === 'livree') ? date('Y-m-d H:i:s') : null;
            $db->query(
                "UPDATE livraisons SET statut = :sl, date_livree = :dl
                 WHERE commande_id = :cid AND statut NOT IN ('livree','echec')",
                [':sl' => $statutLivraison, ':dl' => $dateLivree, ':cid' => $id]
            );
        }

        // Déduction stock + historique mouvements quand commande livrée
        if ($ancienStatut !== 'livree' && $statut === 'livree') {
            $lignes = $db->fetchAll(
                "SELECT cd.produit_id, cd.quantite, cd.nom_produit, cd.unite
                 FROM commande_details cd
                 WHERE cd.commande_id = :cid",
                [':cid' => $id]
            );
            foreach ($lignes as $ligne) {
                $qte = (float)$ligne['quantite'];
                // Déduire du stock
                $db->query(
                    "UPDATE produits SET stock_kg = GREATEST(0, stock_kg - :q) WHERE id = :pid",
                    [':q' => $qte, ':pid' => $ligne['produit_id']]
                );
                // Enregistrer le mouvement
                $cartons = ($ligne['unite'] === 'carton') ? (int)$ligne['quantite'] : 0;
                $qteKg   = ($ligne['unite'] === 'kg')     ? $qte : 0;
                $unite   = $ligne['unite'] === 'carton' ? 'carton' : 'kg';
                $db->query(
                    "INSERT INTO stock_mouvements (produit_id, type, quantite_kg, quantite_cartons, unite, motif, reference, admin_id, created_at)
                     VALUES (:pid, 'commande', :qte, :cartons, :unite, :motif, :ref, :aid, NOW())",
                    [
                        ':pid'     => $ligne['produit_id'],
                        ':qte'     => $qteKg ?: $qte,
                        ':cartons' => $cartons,
                        ':unite'   => $unite,
                        ':motif'   => 'Commande livrée — ' . $ligne['nom_produit'],
                        ':ref'     => $commande['reference'] ?? null,
                        ':aid'     => $_SESSION['admin_id'] ?? null,
                    ]
                );
            }
        }

        // Envoyer email si le statut a changé et que le client a un email
        if ($commande && $ancienStatut !== $statut && !empty($commande['client_email'])) {
            require_once __DIR__ . '/../Services/EmailService.php';
            $emailService = new EmailService();
            $emailService->envoyerChangementStatut($commande, $ancienStatut, $statut);
        }

        $redirectPage = $_POST['redirect'] ?? 'admin_commandes';
        redirect($redirectPage . '&success=' . urlencode('Statut mis à jour.'));
    }

    public function changerStatutPaiement(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_commandes'); return; }

        $db     = $this->getDb();
        $id     = (int)($_POST['id'] ?? 0);
        $statut = $_POST['statut_paiement'] ?? '';
        $valides = ['en_attente', 'partiel', 'paye', 'rembourse'];

        if (!$id || !in_array($statut, $valides)) { redirect('admin_commandes'); return; }

        $db->query(
            "UPDATE commandes SET statut_paiement = :s WHERE id = :id",
            [':s' => $statut, ':id' => $id]
        );

        // Envoyer la facture par email si statut = paye
        if ($statut === 'paye') {
            try {
                $commande = $db->fetch(
                    "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom,
                            cl.email as client_email, cl.telephone as client_tel
                     FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id
                     WHERE c.id = :id",
                    [':id' => $id]
                );
                $lignes = $db->fetchAll(
                    "SELECT * FROM commande_details WHERE commande_id = :id",
                    [':id' => $id]
                );
                if ($commande && !empty($commande['client_email'])) {
                    require_once __DIR__ . '/../Services/EmailService.php';
                    require_once __DIR__ . '/../Services/FacturePDFService.php';
                    (new EmailService())->envoyerFacture($commande, $lignes);
                }
            } catch (\Exception $e) {
                // Ne pas bloquer si l'email échoue
            }
        }

        flashMessage('success', 'Statut de paiement mis à jour.');
        redirect('admin_commande_detail', ['id' => $id]);
    }

    public function telechargerFacture(): void
    {
        $this->requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { redirect('admin_commandes'); return; }

        $db = $this->getDb();
        $commande = $db->fetch(
            "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom,
                    cl.email as client_email, cl.telephone as client_tel
             FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id
             WHERE c.id = :id",
            [':id' => $id]
        );
        $lignes = $db->fetchAll(
            "SELECT cd.*, p.image_principale FROM commande_details cd
             LEFT JOIN produits p ON p.id = cd.produit_id
             WHERE cd.commande_id = :id",
            [':id' => $id]
        );

        if (!$commande) { redirect('admin_commandes'); return; }

        require_once __DIR__ . '/../Services/FacturePDFService.php';
        (new FacturePDFService())->generer($commande, $lignes);
    }

    public function produits(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $produits = $db->fetchAll("SELECT p.*, cat.nom as categorie_nom FROM produits p LEFT JOIN categories cat ON p.categorie_id = cat.id ORDER BY p.created_at DESC, p.nom ASC");
        $categories = $db->fetchAll("SELECT * FROM categories ORDER BY nom ASC");
        require_once __DIR__ . '/../../views/admin/produits.php';
    }

    public function clients(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $clients = $db->fetchAll("SELECT c.*, COUNT(cmd.id) as nb_commandes, COALESCE(SUM(cmd.total),0) as total_depense FROM clients c LEFT JOIN commandes cmd ON c.id = cmd.client_id GROUP BY c.id ORDER BY c.created_at DESC");
        require_once __DIR__ . '/../../views/admin/clients.php';
    }

    public function stocks(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $produits = $db->fetchAll("SELECT p.*, cat.nom as categorie_nom FROM produits p LEFT JOIN categories cat ON p.categorie_id = cat.id ORDER BY p.stock_kg ASC");
        require_once __DIR__ . '/../../views/admin/stocks.php';
    }

    public function stockMouvements(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        // Filtres
        $produitId = (int)($_GET['produit_id'] ?? 0);
        $type      = $_GET['type'] ?? '';
        $dateDebut = $_GET['date_debut'] ?? '';
        $dateFin   = $_GET['date_fin']   ?? '';

        $where  = ['1=1'];
        $params = [];
        if ($produitId) { $where[] = 'm.produit_id = :pid'; $params[':pid'] = $produitId; }
        if ($type)      { $where[] = 'm.type = :type';      $params[':type'] = $type; }
        if ($dateDebut) { $where[] = 'DATE(m.created_at) >= :dd'; $params[':dd'] = $dateDebut; }
        if ($dateFin)   { $where[] = 'DATE(m.created_at) <= :df'; $params[':df'] = $dateFin; }

        $mouvements = $db->fetchAll(
            "SELECT m.*, p.nom as produit_nom, p.image_principale,
                    a.nom as admin_nom
             FROM stock_mouvements m
             LEFT JOIN produits p ON p.id = m.produit_id
             LEFT JOIN admins a   ON a.id = m.admin_id
             WHERE " . implode(' AND ', $where) . "
             ORDER BY m.created_at DESC
             LIMIT 200",
            $params
        );

        $produits = $db->fetchAll("SELECT id, nom FROM produits WHERE actif = 1 ORDER BY nom ASC");

        // Stats rapides
        $statsTotal = $db->fetch(
            "SELECT
               COALESCE(SUM(CASE WHEN type='entree' THEN quantite_kg ELSE 0 END),0) as total_entrees,
               COALESCE(SUM(CASE WHEN type IN('sortie','commande') THEN quantite_kg ELSE 0 END),0) as total_sorties,
               COALESCE(SUM(CASE WHEN type='entree' THEN quantite_cartons ELSE 0 END),0) as total_entrees_cartons,
               COALESCE(SUM(CASE WHEN type IN('sortie','commande') THEN quantite_cartons ELSE 0 END),0) as total_sorties_cartons,
               COUNT(*) as nb
             FROM stock_mouvements m WHERE " . implode(' AND ', $where),
            $params
        ) ?: ['total_entrees'=>0,'total_sorties'=>0,'total_entrees_cartons'=>0,'total_sorties_cartons'=>0,'nb'=>0];

        require_once __DIR__ . '/../../views/admin/stock_mouvements.php';
    }

    public function ajouterMouvement(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_stock_mouvements'); }

        $db             = $this->getDb();
        $produitId      = (int)($_POST['produit_id'] ?? 0);
        $type           = $_POST['type'] ?? '';
        $unite          = $_POST['unite'] ?? 'kg'; // 'kg', 'carton', 'les_deux'
        $quantiteKg     = (float)($_POST['quantite_kg'] ?? 0);
        $quantiteCartons= (int)($_POST['quantite_cartons'] ?? 0);
        $motif          = trim($_POST['motif'] ?? '');
        $reference      = trim($_POST['reference'] ?? '');

        $typesValides = ['entree','sortie','ajustement'];
        $unitesValides = ['kg','carton','les_deux'];

        if (!$produitId || !in_array($type, $typesValides) || !in_array($unite, $unitesValides)) {
            redirect('admin_stock_mouvements&error=' . urlencode('Données invalides.'));
        }
        if ($unite === 'kg' && $quantiteKg <= 0) {
            redirect('admin_stock_mouvements&error=' . urlencode('Quantité invalide.'));
        }
        if ($unite === 'carton' && $quantiteCartons <= 0) {
            redirect('admin_stock_mouvements&error=' . urlencode('Quantité invalide.'));
        }
        if ($unite === 'les_deux' && $quantiteKg <= 0 && $quantiteCartons <= 0) {
            redirect('admin_stock_mouvements&error=' . urlencode('Quantité invalide.'));
        }

        // Si carton uniquement, convertir en kg via poids_carton_kg du produit
        if ($unite === 'carton' || ($unite === 'les_deux' && $quantiteCartons > 0)) {
            $produit = $db->fetch("SELECT poids_carton_kg FROM produits WHERE id = :id", [':id'=>$produitId]);
            $poidsCarton = (float)($produit['poids_carton_kg'] ?? 0);
            if ($unite === 'carton') {
                $quantiteKg = $quantiteCartons * $poidsCarton;
            } else {
                $quantiteKg += $quantiteCartons * $poidsCarton;
            }
        }

        // Enregistre le mouvement
        $db->query(
            "INSERT INTO stock_mouvements (produit_id, type, quantite_kg, quantite_cartons, unite, motif, reference, admin_id, created_at)
             VALUES (:pid, :type, :qte, :cartons, :unite, :motif, :ref, :aid, NOW())",
            [
                ':pid'     => $produitId,
                ':type'    => $type,
                ':qte'     => $quantiteKg,
                ':cartons' => $quantiteCartons,
                ':unite'   => $unite,
                ':motif'   => $motif ?: null,
                ':ref'     => $reference ?: null,
                ':aid'     => $_SESSION['admin_id'] ?? null,
            ]
        );

        // Met à jour le stock du produit (kg + cartons)
        if ($type === 'entree') {
            $db->query(
                "UPDATE produits SET stock_kg = stock_kg + :q, stock_cartons = stock_cartons + :c WHERE id = :id",
                [':q'=>$quantiteKg, ':c'=>$quantiteCartons, ':id'=>$produitId]
            );
        } elseif ($type === 'sortie') {
            $db->query(
                "UPDATE produits SET stock_kg = GREATEST(0, stock_kg - :q), stock_cartons = GREATEST(0, stock_cartons - :c) WHERE id = :id",
                [':q'=>$quantiteKg, ':c'=>$quantiteCartons, ':id'=>$produitId]
            );
        } elseif ($type === 'ajustement') {
            $setKg      = $unite !== 'carton'  ? 'stock_kg = :q,'      : '';
            $setCartons = $unite !== 'kg'       ? 'stock_cartons = :c'  : 'stock_cartons = stock_cartons';
            if ($unite === 'kg') {
                $db->query("UPDATE produits SET stock_kg = :q WHERE id = :id", [':q'=>$quantiteKg, ':id'=>$produitId]);
            } elseif ($unite === 'carton') {
                $db->query("UPDATE produits SET stock_cartons = :c WHERE id = :id", [':c'=>$quantiteCartons, ':id'=>$produitId]);
            } else {
                $db->query("UPDATE produits SET stock_kg = :q, stock_cartons = :c WHERE id = :id", [':q'=>$quantiteKg, ':c'=>$quantiteCartons, ':id'=>$produitId]);
            }
        }

        redirect('admin_stock_mouvements&success=' . urlencode('Mouvement enregistré.'));
    }

    public function categories(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $categories = $db->fetchAll("SELECT c.*, COUNT(p.id) as nb_produits FROM categories c LEFT JOIN produits p ON p.categorie_id = c.id GROUP BY c.id ORDER BY c.ordre ASC, c.nom ASC");
        require_once __DIR__ . '/../../views/admin/categories.php';
    }

    public function ajouterCategorie(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_categories'); }

        $db     = $this->getDb();
        $nom    = trim($_POST['nom'] ?? '');
        $desc   = trim($_POST['description'] ?? '');
        $couleur= trim($_POST['couleur'] ?? '#1a5c2a');
        $ordre  = (int)($_POST['ordre'] ?? 0);
        $actif  = (int)($_POST['actif'] ?? 1);

        if (empty($nom)) {
            redirect('admin_categories&error=' . urlencode('Le nom est obligatoire.'));
        }

        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nom));
        $slug = trim($slug, '-');
        $existing = $db->fetch("SELECT id FROM categories WHERE slug = :s", [':s' => $slug]);
        if ($existing) { $slug .= '-' . time(); }

        $imagePath = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 5 * 1024 * 1024) {
                $filename = 'cat_' . uniqid() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'photos/' . $filename;
                }
            }
        }

        $db->query(
            "INSERT INTO categories (nom, slug, description, couleur, ordre, actif, image) VALUES (:nom, :slug, :desc, :couleur, :ordre, :actif, :image)",
            [':nom'=>$nom, ':slug'=>$slug, ':desc'=>$desc?:null, ':couleur'=>$couleur, ':ordre'=>$ordre, ':actif'=>$actif, ':image'=>$imagePath]
        );

        redirect('admin_categories&success=' . urlencode('Catégorie "' . $nom . '" créée avec succès.'));
    }

    public function modifierCategorie(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_categories'); }

        $db     = $this->getDb();
        $id     = (int)($_POST['id'] ?? 0);
        $nom    = trim($_POST['nom'] ?? '');
        $desc   = trim($_POST['description'] ?? '');
        $couleur= trim($_POST['couleur'] ?? '#1a5c2a');
        $ordre  = (int)($_POST['ordre'] ?? 0);
        $actif  = (int)($_POST['actif'] ?? 1);

        if (!$id || empty($nom)) {
            redirect('admin_categories&error=' . urlencode('Données invalides.'));
        }

        $imageUpdate = '';
        $params = [':nom'=>$nom, ':desc'=>$desc?:null, ':couleur'=>$couleur, ':ordre'=>$ordre, ':actif'=>$actif, ':id'=>$id];
        if (!empty($_FILES['photo']['name'])) {
            $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 5 * 1024 * 1024) {
                $filename = 'cat_' . uniqid() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../photos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                    $imageUpdate = ', image=:image';
                    $params[':image'] = 'photos/' . $filename;
                }
            }
        }

        $db->query(
            "UPDATE categories SET nom=:nom, description=:desc, couleur=:couleur, ordre=:ordre, actif=:actif$imageUpdate WHERE id=:id",
            $params
        );

        redirect('admin_categories&success=' . urlencode('Catégorie modifiée avec succès.'));
    }

    public function ajouterProduit(): void
    {
        $this->requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin_produits');
        }

        $db = $this->getDb();

        $nom           = trim($_POST['nom'] ?? '');
        $categorieId   = (int)($_POST['categorie_id'] ?? 0);
        $origine       = trim($_POST['origine'] ?? '');
        $prixKg        = (float)($_POST['prix_kg'] ?? 0);
        $prixCarton    = $_POST['prix_carton'] !== '' ? (float)$_POST['prix_carton'] : null;
        $stockKg       = (float)($_POST['stock_kg'] ?? 0);
        $stockCartons  = (int)($_POST['stock_cartons'] ?? 0);
        $poidsCarton   = $_POST['poids_carton_kg'] !== '' ? (float)$_POST['poids_carton_kg'] : null;
        $uniteMin      = (float)($_POST['unite_min_kg'] ?? 1);
        $description   = trim($_POST['description'] ?? '');
        $badge         = trim($_POST['badge'] ?? '');
        $saison        = trim($_POST['saison'] ?? '');
        $actif         = (int)($_POST['actif'] ?? 1);

        if (empty($nom) || $categorieId === 0) {
            redirect('admin_produits&error=' . urlencode('Nom et catégorie obligatoires.'));
        }

        // Slug unique
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $nom));
        $slug = trim($slug, '-');
        $existing = $db->fetch("SELECT id FROM produits WHERE slug = :s", [':s' => $slug]);
        if ($existing) {
            $slug .= '-' . time();
        }

        // Upload photo
        $imagePath = null;
        if (!empty($_FILES['photo']['name'])) {
            $ext      = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 5 * 1024 * 1024) {
                $filename  = 'PHOTO-' . date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../photos/';
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                    $imagePath = 'photos/' . $filename;
                }
            }
        }

        $db->query(
            "INSERT INTO produits (categorie_id, nom, slug, description, origine, prix_kg, prix_carton, poids_carton_kg, stock_kg, stock_cartons, unite_min_kg, image_principale, badge, saison, actif, created_at)
             VALUES (:cat, :nom, :slug, :desc, :orig, :pkg, :pcar, :poids, :skg, :scar, :umin, :img, :badge, :saison, :actif, NOW())",
            [
                ':cat'   => $categorieId,
                ':nom'   => $nom,
                ':slug'  => $slug,
                ':desc'  => $description ?: null,
                ':orig'  => $origine ?: null,
                ':pkg'   => $prixKg,
                ':pcar'  => $prixCarton,
                ':poids' => $poidsCarton,
                ':skg'   => $stockKg,
                ':scar'  => $stockCartons,
                ':umin'  => $uniteMin,
                ':img'   => $imagePath,
                ':badge' => $badge ?: null,
                ':saison'=> $saison ?: null,
                ':actif' => $actif,
            ]
        );

        redirect('admin_produits&success=' . urlencode('Produit "' . $nom . '" ajouté avec succès.'));
    }

    public function modifierProduit(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('admin_produits');

        $db  = $this->getDb();
        $id  = (int)($_POST['id'] ?? 0);
        if (!$id) redirect('admin_produits&error=' . urlencode('Produit introuvable.'));

        $nom          = trim($_POST['nom'] ?? '');
        $categorieId  = (int)($_POST['categorie_id'] ?? 0);
        $origine      = trim($_POST['origine'] ?? '');
        $prixKg       = (float)($_POST['prix_kg'] ?? 0);
        $prixCarton   = $_POST['prix_carton'] !== '' ? (float)$_POST['prix_carton'] : null;
        $stockKg      = (float)($_POST['stock_kg'] ?? 0);
        $stockCartons = (int)($_POST['stock_cartons'] ?? 0);
        $poidsCarton  = $_POST['poids_carton_kg'] !== '' ? (float)$_POST['poids_carton_kg'] : null;
        $uniteMin     = (float)($_POST['unite_min_kg'] ?? 1);
        $description  = trim($_POST['description'] ?? '');
        $badge        = trim($_POST['badge'] ?? '');
        $saison       = trim($_POST['saison'] ?? '');
        $actif        = (int)($_POST['actif'] ?? 1);

        if (empty($nom) || !$categorieId) {
            redirect('admin_produits&error=' . urlencode('Nom et catégorie obligatoires.'));
        }

        // Upload nouvelle photo si fournie
        $imageUpdate = '';
        if (!empty($_FILES['photo']['name'])) {
            $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];
            if (in_array($ext, $allowed) && $_FILES['photo']['size'] < 5 * 1024 * 1024) {
                $filename  = 'PHOTO-' . date('Y-m-d-H-i-s') . '-' . uniqid() . '.' . $ext;
                $uploadDir = __DIR__ . '/../../photos/';
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadDir . $filename)) {
                    $imageUpdate = ', image_principale = :img';
                }
            }
        }

        $params = [
            ':id'    => $id,
            ':cat'   => $categorieId,
            ':nom'   => $nom,
            ':orig'  => $origine ?: null,
            ':pkg'   => $prixKg,
            ':pcar'  => $prixCarton,
            ':poids' => $poidsCarton,
            ':skg'   => $stockKg,
            ':scar'  => $stockCartons,
            ':umin'  => $uniteMin,
            ':desc'  => $description ?: null,
            ':badge' => $badge ?: null,
            ':saison'=> $saison ?: null,
            ':actif' => $actif,
        ];
        if ($imageUpdate) {
            $params[':img'] = 'photos/' . $filename;
        }

        $db->query(
            "UPDATE produits SET categorie_id=:cat, nom=:nom, origine=:orig, prix_kg=:pkg,
             prix_carton=:pcar, poids_carton_kg=:poids, stock_kg=:skg, stock_cartons=:scar,
             unite_min_kg=:umin, description=:desc, badge=:badge, saison=:saison, actif=:actif
             $imageUpdate WHERE id=:id",
            $params
        );

        redirect('admin_produits&success=' . urlencode('Produit "' . $nom . '" modifié avec succès.'));
    }

    public function supprimerProduit(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_produits'); return; }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { redirect('admin_produits'); return; }

        $db = $this->getDb();
        // Vérifier qu'il n'est pas dans des commandes actives
        $enCommande = $db->fetch(
            "SELECT COUNT(*) as nb FROM commande_details WHERE produit_id = :id", [':id' => $id]
        );
        if ((int)($enCommande['nb'] ?? 0) > 0) {
            redirect('admin_produits&error=' . urlencode('Ce produit est lié à des commandes et ne peut pas être supprimé. Désactivez-le à la place.'));
            return;
        }

        $produit = $db->fetch("SELECT image_principale FROM produits WHERE id = :id", [':id' => $id]);
        $db->query("DELETE FROM produits WHERE id = :id", [':id' => $id]);

        // Supprimer la photo si elle existe
        if (!empty($produit['image_principale'])) {
            $path = __DIR__ . '/../../' . $produit['image_principale'];
            if (file_exists($path)) @unlink($path);
        }

        redirect('admin_produits&success=' . urlencode('Produit supprimé.'));
    }

    public function supprimerClient(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_clients'); return; }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { redirect('admin_clients'); return; }

        $db = $this->getDb();
        $enCommande = $db->fetch(
            "SELECT COUNT(*) as nb FROM commandes WHERE client_id = :id", [':id' => $id]
        );
        if ((int)($enCommande['nb'] ?? 0) > 0) {
            redirect('admin_clients&error=' . urlencode('Ce client a des commandes existantes. Impossible de le supprimer.'));
            return;
        }

        $db->query("DELETE FROM clients WHERE id = :id", [':id' => $id]);
        redirect('admin_clients&success=' . urlencode('Client supprimé.'));
    }

    public function supprimerCategorie(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_categories'); return; }

        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { redirect('admin_categories'); return; }

        $db = $this->getDb();
        $hasProduits = $db->fetch(
            "SELECT COUNT(*) as nb FROM produits WHERE categorie_id = :id", [':id' => $id]
        );
        if ((int)($hasProduits['nb'] ?? 0) > 0) {
            redirect('admin_categories&error=' . urlencode('Cette catégorie contient des produits. Déplacez-les avant de la supprimer.'));
            return;
        }

        $db->query("DELETE FROM categories WHERE id = :id", [':id' => $id]);
        redirect('admin_categories&success=' . urlencode('Catégorie supprimée.'));
    }

    public function contacts(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        // Marquer tous comme lus si demandé
        if (isset($_GET['marquer_lu'])) {
            $db->query("UPDATE contacts SET lu = 1 WHERE id = :id", [':id' => (int)$_GET['marquer_lu']]);
            redirect('admin_contacts');
            return;
        }
        if (isset($_GET['supprimer'])) {
            $db->query("DELETE FROM contacts WHERE id = :id", [':id' => (int)$_GET['supprimer']]);
            redirect('admin_contacts');
            return;
        }

        $messages = $db->fetchAll("SELECT * FROM contacts ORDER BY created_at DESC") ?: [];
        $nbNonLus = (int)($db->fetch("SELECT COUNT(*) as nb FROM contacts WHERE lu = 0")['nb'] ?? 0);

        // Charger les réponses pour chaque message
        $reponses = [];
        if (!empty($messages)) {
            $ids = implode(',', array_column($messages, 'id'));
            $toutesReponses = $db->fetchAll(
                "SELECT cr.*, a.nom as admin_nom FROM contact_reponses cr
                 LEFT JOIN admins a ON a.id = cr.admin_id
                 WHERE cr.contact_id IN ($ids)
                 ORDER BY cr.created_at ASC"
            ) ?: [];
            foreach ($toutesReponses as $r) {
                $reponses[$r['contact_id']][] = $r;
            }
        }

        $pageTitle = 'Messages de contact';
        $adminPage = 'contacts';
        require_once __DIR__ . '/../../views/admin/contacts.php';
    }

    public function repondreContact(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_contacts'); return; }

        $id      = (int)($_POST['contact_id'] ?? 0);
        $sujet   = trim($_POST['sujet'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$id || empty($message)) {
            $_SESSION['flash_error'] = 'Message vide.';
            redirect('admin_contacts');
            return;
        }

        $db      = $this->getDb();
        $contact = $db->fetch("SELECT * FROM contacts WHERE id = :id", [':id' => $id]);

        if (!$contact) {
            redirect('admin_contacts');
            return;
        }

        // 1. Sauvegarder en base immédiatement
        $db->query(
            "INSERT INTO contact_reponses (contact_id, admin_id, message, sujet, created_at)
             VALUES (:cid, :aid, :msg, :sujet, NOW())",
            [
                ':cid'   => $id,
                ':aid'   => $_SESSION['admin_id'] ?? null,
                ':msg'   => $message,
                ':sujet' => $sujet,
            ]
        );
        $db->query("UPDATE contacts SET lu = 1 WHERE id = :id", [':id' => $id]);
        $_SESSION['flash_success'] = 'Réponse envoyée à ' . $contact['email'] . '.';

        // 2. Envoyer le header de redirection sans exit, vider le buffer, terminer la requête
        $redirectUrl = BASE_URL . '?page=admin_contacts';
        header('Location: ' . $redirectUrl);

        // 3. Terminer la réponse HTTP immédiatement (le navigateur redirige)
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            ob_end_flush();
            flush();
        }
        ignore_user_abort(true);
        set_time_limit(60);

        // 4. Envoyer l'email en arrière-plan (après que le navigateur a reçu la réponse)
        require_once __DIR__ . '/../Services/EmailService.php';
        $html = '
        <div style="font-family:Arial,sans-serif;max-width:600px;margin:0 auto;padding:32px 24px;background:#fff;">
          <div style="text-align:center;margin-bottom:28px;">
            <img src="cid:logo_darousalam" width="60" height="60" alt="Darou Salam" style="border-radius:50%;">
            <h2 style="font-family:Georgia,serif;color:#1a5c2a;margin:10px 0 0;">Darou Salam Business Company</h2>
          </div>
          <p style="font-size:.9rem;color:#374151;">Bonjour <strong>' . htmlspecialchars($contact['nom']) . '</strong>,</p>
          <div style="background:#f9fafb;border-left:4px solid #1a5c2a;border-radius:8px;padding:18px 20px;font-size:.88rem;color:#1c1917;line-height:1.7;margin:16px 0;">
            ' . nl2br(htmlspecialchars($message)) . '
          </div>
          <hr style="border:none;border-top:1px solid #e5e7eb;margin:24px 0;">
          <p style="font-size:.78rem;color:#9ca3af;text-align:center;">
            Darou Salam Business Company · Dakar Médina, Rue 47×37<br>
            <a href="tel:+221774715353" style="color:#1a5c2a;">+221 77 471 53 53</a>
          </p>
        </div>';
        (new EmailService())->send($contact['email'], $sujet ?: 'Réponse à votre message', $html);
    }

    public function exportCommandes(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        $periode  = $_GET['periode'] ?? 'mois';
        $periodes = ['semaine' => '7 DAY', 'mois' => '1 MONTH', 'trimestre' => '3 MONTH', 'annee' => '1 YEAR'];
        $interval = $periodes[$periode] ?? '1 MONTH';

        $commandes = $db->fetchAll(
            "SELECT c.reference, c.created_at, c.statut, c.mode_paiement, c.statut_paiement,
                    c.sous_total, c.remise, c.frais_livraison, c.total, c.code_promo,
                    cl.nom, cl.prenom, cl.email, cl.telephone,
                    c.adresse_livraison
             FROM commandes c
             LEFT JOIN clients cl ON cl.id = c.client_id
             WHERE c.created_at >= DATE_SUB(NOW(), INTERVAL $interval)
             ORDER BY c.created_at DESC"
        ) ?: [];

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="commandes-' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');

        $out = fopen('php://output', 'w');
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8 pour Excel

        fputcsv($out, ['Référence','Date','Client','Email','Téléphone','Adresse','Ville','Statut','Paiement','Statut paiement','Sous-total','Remise','Code promo','Frais livraison','Total'], ';');

        foreach ($commandes as $c) {
            $adresse = json_decode($c['adresse_livraison'] ?? '{}', true) ?: [];
            fputcsv($out, [
                $c['reference'],
                date('d/m/Y H:i', strtotime($c['created_at'])),
                trim(($c['prenom'] ?? '') . ' ' . ($c['nom'] ?? '')),
                $c['email'] ?? '',
                $c['telephone'] ?? '',
                $adresse['adresse'] ?? '',
                $adresse['ville'] ?? '',
                $c['statut'],
                $c['mode_paiement'],
                $c['statut_paiement'],
                $c['sous_total'],
                $c['remise'],
                $c['code_promo'] ?? '',
                $c['frais_livraison'],
                $c['total'],
            ], ';');
        }

        fclose($out);
        exit;
    }

    public function ajouterClient(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('admin_clients');

        $db       = $this->getDb();
        $nom      = trim($_POST['nom'] ?? '');
        $prenom   = trim($_POST['prenom'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $mdp      = $_POST['mot_de_passe'] ?? '';

        if (empty($nom) || empty($email) || empty($mdp)) {
            redirect('admin_clients&error=' . urlencode('Nom, email et mot de passe obligatoires.'));
        }

        // Vérifier email unique
        $exist = $db->fetch("SELECT id FROM clients WHERE email = :e", [':e' => $email]);
        if ($exist) {
            redirect('admin_clients&error=' . urlencode('Cet email est déjà utilisé.'));
        }

        $db->query(
            "INSERT INTO clients (civilite, type, nom, prenom, email, telephone, entreprise, adresse, ville, mot_de_passe, statut, verified, created_at)
             VALUES (:civ, :type, :nom, :prenom, :email, :tel, :ent, :adr, :ville, :mdp, :statut, 1, NOW())",
            [
                ':civ'    => $_POST['civilite'] ?? 'M.',
                ':type'   => $_POST['type'] ?? 'particulier',
                ':nom'    => $nom,
                ':prenom' => $prenom,
                ':email'  => $email,
                ':tel'    => trim($_POST['telephone'] ?? '') ?: null,
                ':ent'    => trim($_POST['entreprise'] ?? '') ?: null,
                ':adr'    => trim($_POST['adresse'] ?? '') ?: null,
                ':ville'  => trim($_POST['ville'] ?? 'Dakar'),
                ':mdp'    => password_hash($mdp, PASSWORD_DEFAULT),
                ':statut' => $_POST['statut'] ?? 'actif',
            ]
        );

        redirect('admin_clients&success=' . urlencode('Utilisateur "' . $prenom . ' ' . $nom . '" créé avec succès.'));
    }

    public function settings(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();
        $settings = $db->fetchAll("SELECT * FROM settings ORDER BY groupe, `key`");
        require_once __DIR__ . '/../../views/admin/settings.php';
    }

    public function saveSettings(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_settings'); }

        $db     = $this->getDb();
        $groupe = $_POST['groupe'] ?? 'general';
        $allowed = [
            'general'   => ['site_nom','site_telephone','site_whatsapp','site_adresse'],
            'email'     => ['smtp_username','smtp_password','smtp_from_name','smtp_host','smtp_port'],
            'livraison' => ['frais_livraison','livraison_gratuite_min'],
        ];

        $keys = $allowed[$groupe] ?? [];
        foreach ($keys as $key) {
            if (!isset($_POST[$key])) continue;
            $val = trim($_POST[$key]);
            $db->query(
                "INSERT INTO settings (`key`, `value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `value`=:v2",
                [':k'=>$key, ':v'=>$val, ':v2'=>$val]
            );
        }

        // Mettre à jour mail.php dynamiquement si groupe email
        if ($groupe === 'email') {
            $this->regenererConfigMail($db);
        }

        redirect('admin_settings&success=' . urlencode('Paramètres sauvegardés avec succès.'));
    }

    private function regenererConfigMail($db): void
    {
        $keys = ['smtp_host','smtp_port','smtp_username','smtp_password','smtp_from_name'];
        $cfg  = [];
        foreach ($keys as $k) {
            $row = $db->fetch("SELECT `value` FROM settings WHERE `key`=:k", [':k'=>$k]);
            $cfg[$k] = $row['value'] ?? '';
        }

        $content = "<?php\n"
            . "define('MAIL_HOST',     " . var_export($cfg['smtp_host'] ?: 'smtp.gmail.com', true) . ");\n"
            . "define('MAIL_PORT',     " . (int)($cfg['smtp_port'] ?: 587) . ");\n"
            . "define('MAIL_USERNAME', " . var_export($cfg['smtp_username'], true) . ");\n"
            . "define('MAIL_PASSWORD', " . var_export($cfg['smtp_password'], true) . ");\n"
            . "define('MAIL_FROM',     " . var_export($cfg['smtp_username'], true) . ");\n"
            . "define('MAIL_FROM_NAME'," . var_export($cfg['smtp_from_name'] ?: 'Darou Salam Business Company', true) . ");\n"
            . "define('MAIL_REPLY_TO', " . var_export($cfg['smtp_username'], true) . ");\n";

        file_put_contents(__DIR__ . '/../../config/mail.php', $content);
    }

    public function testEmail(): void
    {
        $this->requireAdmin();
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['success'=>false, 'message'=>'Adresse email invalide.']);
            exit;
        }

        require_once __DIR__ . '/../Services/EmailService.php';
        $emailService = new EmailService();
        $ok = $emailService->send(
            $email,
            'Test email — Darou Salam',
            '<div style="font-family:Arial,sans-serif;padding:30px;background:#f5f5f0;">
              <div style="background:#fff;border-radius:16px;padding:30px;max-width:500px;margin:0 auto;text-align:center;">
                <div style="font-size:2rem;margin-bottom:16px;">✅</div>
                <h2 style="color:#1a5c2a;margin-bottom:8px;">Configuration email OK !</h2>
                <p style="color:#6b7280;font-size:.9rem;">Votre configuration SMTP fonctionne correctement.<br>Les emails seront bien envoyés à vos clients.</p>
                <div style="margin-top:20px;padding:12px;background:#f0fdf4;border-radius:10px;font-size:.8rem;color:#166534;">
                  Darou Salam Business Company
                </div>
              </div>
            </div>'
        );

        echo json_encode([
            'success' => $ok,
            'message' => $ok ? 'Email envoyé avec succès ! Vérifiez votre boîte.' : 'Échec — vérifiez vos identifiants SMTP.',
        ]);
        exit;
    }

    public function admins(): void
    {
        $this->requireAdmin();
        $db     = $this->getDb();
        $admins = $db->fetchAll("SELECT * FROM admins ORDER BY created_at DESC");
        require_once __DIR__ . '/../../views/admin/admins.php';
    }

    public function ajouterAdmin(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_admins'); }

        $db    = $this->getDb();
        $nom   = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mot_de_passe'] ?? '';
        $role  = $_POST['role'] ?? 'admin';

        if (empty($nom) || empty($email) || empty($mdp)) {
            redirect('admin_admins&error=' . urlencode('Nom, email et mot de passe obligatoires.'));
        }

        $exist = $db->fetch("SELECT id FROM admins WHERE email = :e", [':e' => $email]);
        if ($exist) {
            redirect('admin_admins&error=' . urlencode('Cet email est déjà utilisé.'));
        }

        $db->query(
            "INSERT INTO admins (nom, email, mot_de_passe, role, actif, created_at) VALUES (:nom, :email, :mdp, :role, 1, NOW())",
            [':nom'=>$nom, ':email'=>$email, ':mdp'=>password_hash($mdp, PASSWORD_DEFAULT), ':role'=>$role]
        );

        redirect('admin_admins&success=' . urlencode('Administrateur "' . $nom . '" créé avec succès.'));
    }

    public function modifierAdmin(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_admins'); }

        $db    = $this->getDb();
        $id    = (int)($_POST['id'] ?? 0);
        $nom   = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $role  = $_POST['role'] ?? 'admin';
        $actif = (int)($_POST['actif'] ?? 1);
        $mdp   = $_POST['mot_de_passe'] ?? '';

        if (!$id || empty($nom) || empty($email)) {
            redirect('admin_admins&error=' . urlencode('Données invalides.'));
        }

        $emailExist = $db->fetch("SELECT id FROM admins WHERE email = :e AND id != :id", [':e'=>$email, ':id'=>$id]);
        if ($emailExist) {
            redirect('admin_admins&error=' . urlencode('Cet email est déjà utilisé par un autre admin.'));
        }

        if (!empty($mdp)) {
            $db->query(
                "UPDATE admins SET nom=:nom, email=:email, role=:role, actif=:actif, mot_de_passe=:mdp WHERE id=:id",
                [':nom'=>$nom, ':email'=>$email, ':role'=>$role, ':actif'=>$actif, ':mdp'=>password_hash($mdp, PASSWORD_DEFAULT), ':id'=>$id]
            );
        } else {
            $db->query(
                "UPDATE admins SET nom=:nom, email=:email, role=:role, actif=:actif WHERE id=:id",
                [':nom'=>$nom, ':email'=>$email, ':role'=>$role, ':actif'=>$actif, ':id'=>$id]
            );
        }

        redirect('admin_admins&success=' . urlencode('Administrateur "' . $nom . '" modifié avec succès.'));
    }

    public function toggleAdmin(): void
    {
        $this->requireAdmin();
        $db  = $this->getDb();
        $id  = (int)($_GET['id'] ?? 0);

        if (!$id || $id === (int)($_SESSION['admin_id'] ?? 0)) {
            redirect('admin_admins&error=' . urlencode('Action non autorisée.'));
        }

        $admin = $db->fetch("SELECT actif FROM admins WHERE id = :id", [':id' => $id]);
        if (!$admin) { redirect('admin_admins&error=' . urlencode('Administrateur introuvable.')); }

        $newActif = $admin['actif'] ? 0 : 1;
        $db->query("UPDATE admins SET actif = :a WHERE id = :id", [':a'=>$newActif, ':id'=>$id]);

        $msg = $newActif ? 'Administrateur activé.' : 'Administrateur désactivé.';
        redirect('admin_admins&success=' . urlencode($msg));
    }

    public function rapports(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        // --- Période ---
        $periode = $_GET['periode'] ?? 'mois';
        $periodes = ['semaine' => '7 DAY', 'mois' => '1 MONTH', 'trimestre' => '3 MONTH', 'annee' => '1 YEAR'];
        $interval = $periodes[$periode] ?? '1 MONTH';

        // --- KPIs globaux ---
        $kpiTotal = $db->fetch(
            "SELECT COUNT(*) as nb, COALESCE(SUM(total),0) as ca FROM commandes WHERE statut != 'annulee'"
        ) ?: ['nb' => 0, 'ca' => 0];

        $kpiMois = $db->fetch(
            "SELECT COUNT(*) as nb, COALESCE(SUM(total),0) as ca
             FROM commandes
             WHERE statut != 'annulee' AND created_at >= DATE_SUB(NOW(), INTERVAL $interval)"
        ) ?: ['nb' => 0, 'ca' => 0];

        $panierMoyen = $kpiTotal['nb'] > 0
            ? round($kpiTotal['ca'] / $kpiTotal['nb'])
            : 0;

        // --- CA par mois (12 derniers mois) ---
        $caMois = $db->fetchAll(
            "SELECT DATE_FORMAT(created_at, '%Y-%m') as mois,
                    DATE_FORMAT(created_at, '%b %Y') as mois_label,
                    COALESCE(SUM(total),0) as ca,
                    COUNT(*) as nb
             FROM commandes
             WHERE statut != 'annulee' AND created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
             GROUP BY DATE_FORMAT(created_at, '%Y-%m')
             ORDER BY mois ASC"
        ) ?: [];

        // Remplir les mois manquants
        $moisLabels = [];
        $moisCA     = [];
        $moisNb     = [];
        $indexed    = array_column($caMois, null, 'mois');
        for ($i = 11; $i >= 0; $i--) {
            $key   = date('Y-m', strtotime("-$i month"));
            $label = date('M Y', strtotime("-$i month"));
            $moisLabels[] = $label;
            $moisCA[]     = isset($indexed[$key]) ? (int)$indexed[$key]['ca'] : 0;
            $moisNb[]     = isset($indexed[$key]) ? (int)$indexed[$key]['nb'] : 0;
        }

        // --- Répartition par statut ---
        $parStatut = $db->fetchAll(
            "SELECT statut, COUNT(*) as nb FROM commandes GROUP BY statut ORDER BY nb DESC"
        ) ?: [];

        // --- Top 5 produits ---
        $topProduits = $db->fetchAll(
            "SELECT cd.nom_produit,
                    SUM(cd.quantite) as qte_totale,
                    SUM(cd.total_ligne) as ca_total,
                    COUNT(DISTINCT cd.commande_id) as nb_commandes
             FROM commande_details cd
             JOIN commandes c ON c.id = cd.commande_id
             WHERE c.statut != 'annulee'
               AND c.created_at >= DATE_SUB(NOW(), INTERVAL $interval)
             GROUP BY cd.nom_produit
             ORDER BY ca_total DESC
             LIMIT 5"
        ) ?: [];

        // --- Top 5 clients ---
        $topClients = $db->fetchAll(
            "SELECT cl.nom, cl.prenom, cl.email,
                    COUNT(c.id) as nb_commandes,
                    COALESCE(SUM(c.total),0) as total_depense
             FROM clients cl
             JOIN commandes c ON c.client_id = cl.id
             WHERE c.statut != 'annulee'
               AND c.created_at >= DATE_SUB(NOW(), INTERVAL $interval)
             GROUP BY cl.id
             ORDER BY total_depense DESC
             LIMIT 5"
        ) ?: [];

        // --- Répartition kg vs cartons ---
        $repartitionUnites = $db->fetch(
            "SELECT
                COALESCE(SUM(CASE WHEN cd.unite = 'kg' THEN cd.quantite ELSE 0 END), 0) as total_kg,
                COALESCE(SUM(CASE WHEN cd.unite = 'carton' THEN cd.quantite ELSE 0 END), 0) as total_cartons,
                COALESCE(SUM(CASE WHEN cd.unite = 'kg' THEN cd.total_ligne ELSE 0 END), 0) as ca_kg,
                COALESCE(SUM(CASE WHEN cd.unite = 'carton' THEN cd.total_ligne ELSE 0 END), 0) as ca_cartons
             FROM commande_details cd
             JOIN commandes c ON c.id = cd.commande_id
             WHERE c.statut != 'annulee'
               AND c.created_at >= DATE_SUB(NOW(), INTERVAL $interval)"
        ) ?: ['total_kg' => 0, 'total_cartons' => 0, 'ca_kg' => 0, 'ca_cartons' => 0];

        // --- Évolution nb commandes & CA semaine glissante ---
        $evolutionSemaine = $db->fetchAll(
            "SELECT DATE(created_at) as jour,
                    DATE_FORMAT(created_at,'%a') as jour_label,
                    COUNT(*) as nb,
                    COALESCE(SUM(total),0) as ca
             FROM commandes
             WHERE statut != 'annulee' AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
             GROUP BY DATE(created_at)
             ORDER BY jour ASC"
        ) ?: [];

        $pageTitle  = 'Rapports & Statistiques';
        $adminPage  = 'rapports';

        require_once __DIR__ . '/../../views/admin/rapports.php';
    }

    /* =============================================
       LIVRAISONS
       ============================================= */

    public function livraisons(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        // KPIs
        $kpiAttente = (int)($db->fetch(
            "SELECT COUNT(*) as nb FROM livraisons WHERE statut IN ('en_attente','assignee')"
        )['nb'] ?? 0);

        $kpiEnCours = (int)($db->fetch(
            "SELECT COUNT(*) as nb FROM livraisons WHERE statut = 'en_cours'"
        )['nb'] ?? 0);

        $kpiLivreesAujourdhui = (int)($db->fetch(
            "SELECT COUNT(*) as nb FROM livraisons WHERE statut = 'livree' AND DATE(date_livree) = CURDATE()"
        )['nb'] ?? 0);

        $kpiStats = $db->fetch(
            "SELECT COUNT(*) as total,
                    SUM(statut = 'livree') as livrees
             FROM livraisons"
        ) ?: ['total' => 0, 'livrees' => 0];
        $tauxSucces = $kpiStats['total'] > 0
            ? round(($kpiStats['livrees'] / $kpiStats['total']) * 100)
            : 0;

        // Liste livraisons avec jointures
        $livraisons = $db->fetchAll(
            "SELECT l.id, l.statut, l.date_prevue, l.date_livree, l.notes,
                    l.commande_id, l.livreur_id,
                    c.reference, c.adresse_livraison,
                    cl.nom as client_nom, cl.prenom as client_prenom,
                    lr.nom as livreur_nom
             FROM livraisons l
             JOIN commandes c ON c.id = l.commande_id
             LEFT JOIN clients cl ON cl.id = c.client_id
             LEFT JOIN livreurs lr ON lr.id = l.livreur_id
             ORDER BY l.created_at DESC"
        ) ?: [];

        // Livreurs + nb livraisons
        $livreurs = $db->fetchAll(
            "SELECT lr.*,
                    COUNT(l.id) as nb_livraisons,
                    SUM(l.statut = 'livree') as nb_livrees
             FROM livreurs lr
             LEFT JOIN livraisons l ON l.livreur_id = lr.id
             GROUP BY lr.id
             ORDER BY lr.nom ASC"
        ) ?: [];

        // Tous les livreurs actifs (pour selects inline)
        $livreursActifs = $db->fetchAll(
            "SELECT id, nom FROM livreurs WHERE actif = 1 ORDER BY nom ASC"
        ) ?: [];

        $pageTitle = 'Livraisons';
        $adminPage = 'livraisons';
        require_once __DIR__ . '/../../views/admin/livraisons.php';
    }

    public function livreurs(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        $livreurs = $db->fetchAll(
            "SELECT lr.*,
                    COUNT(l.id) as nb_livraisons,
                    SUM(l.statut = 'livree') as nb_livrees
             FROM livreurs lr
             LEFT JOIN livraisons l ON l.livreur_id = lr.id
             GROUP BY lr.id
             ORDER BY lr.nom ASC"
        ) ?: [];

        $pageTitle = 'Livreurs';
        $adminPage = 'livreurs';
        require_once __DIR__ . '/../../views/admin/livreurs.php';
    }

    public function livreurDetail(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        $id      = (int)($_GET['id'] ?? 0);
        $livreur = $db->fetch("SELECT * FROM livreurs WHERE id = :id", [':id' => $id]);

        if (!$livreur) {
            flashMessage('error', 'Livreur introuvable.');
            redirect('admin_livreurs');
            return;
        }

        $livraisons = $db->fetchAll(
            "SELECT l.*, c.reference, c.frais_livraison, c.adresse_livraison,
                    cl.nom as client_nom, cl.prenom as client_prenom, cl.telephone as client_telephone
             FROM livraisons l
             JOIN commandes c ON c.id = l.commande_id
             LEFT JOIN clients cl ON cl.id = c.client_id
             WHERE l.livreur_id = :lid
             ORDER BY l.created_at DESC",
            [':lid' => $id]
        ) ?: [];

        $pageTitle = 'Livreur — ' . $livreur['nom'];
        $adminPage = 'livreurs';
        require_once __DIR__ . '/../../views/admin/livreur_detail.php';
    }

    public function ajouterLivreur(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_livreurs'); return; }

        $nom       = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $zone      = trim($_POST['zone'] ?? '');
        $actif     = (int)($_POST['actif'] ?? 1);

        if ($nom === '' || $telephone === '') {
            $_SESSION['flash_error'] = 'Nom et téléphone sont obligatoires.';
            redirect('admin_livreurs');
            return;
        }

        $db = $this->getDb();
        $db->query(
            "INSERT INTO livreurs (nom, telephone, zone, actif) VALUES (:nom, :tel, :zone, :actif)",
            [':nom' => $nom, ':tel' => $telephone, ':zone' => $zone, ':actif' => $actif]
        );

        $_SESSION['flash_success'] = 'Livreur ajouté avec succès.';
        redirect('admin_livreurs');
    }

    public function modifierLivreur(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_livreurs'); return; }

        $id        = (int)($_POST['id'] ?? 0);
        $nom       = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $zone      = trim($_POST['zone'] ?? '');
        $actif     = (int)($_POST['actif'] ?? 1);

        if (!$id || $nom === '' || $telephone === '') {
            $_SESSION['flash_error'] = 'Données invalides.';
            redirect('admin_livreurs');
            return;
        }

        $db = $this->getDb();
        $db->query(
            "UPDATE livreurs SET nom = :nom, telephone = :tel, zone = :zone, actif = :actif WHERE id = :id",
            [':nom' => $nom, ':tel' => $telephone, ':zone' => $zone, ':actif' => $actif, ':id' => $id]
        );

        $_SESSION['flash_success'] = 'Livreur modifié avec succès.';
        redirect('admin_livreurs');
    }

    public function supprimerLivreur(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_livreurs'); return; }

        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $this->getDb()->query("DELETE FROM livreurs WHERE id = :id", [':id' => $id]);
            $_SESSION['flash_success'] = 'Livreur supprimé.';
        }
        redirect('admin_livreurs');
    }

    public function assignerLivreur(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_livraisons'); return; }

        $db          = $this->getDb();
        $livreurId   = (int)($_POST['livreur_id'] ?? 0);
        $commandeId  = (int)($_POST['commande_id'] ?? 0);
        $livraisonId = (int)($_POST['livraison_id'] ?? 0);
        $datePrevue  = !empty($_POST['date_prevue']) ? $_POST['date_prevue'] : null;
        $retour      = !empty($_POST['retour']) ? $_POST['retour'] : 'admin_livraisons';

        if ($commandeId > 0 && $livreurId > 0) {
            // Créer ou mettre à jour la livraison pour cette commande
            $existing = $db->fetch("SELECT id FROM livraisons WHERE commande_id = :cid", [':cid' => $commandeId]);
            if ($existing) {
                $db->query(
                    "UPDATE livraisons SET livreur_id = :lid, statut = 'assignee', date_prevue = :dp WHERE commande_id = :cid",
                    [':lid' => $livreurId, ':dp' => $datePrevue, ':cid' => $commandeId]
                );
            } else {
                $db->query(
                    "INSERT INTO livraisons (commande_id, livreur_id, statut, date_prevue) VALUES (:cid, :lid, 'assignee', :dp)",
                    [':cid' => $commandeId, ':lid' => $livreurId, ':dp' => $datePrevue]
                );
            }
            $_SESSION['flash_success'] = 'Livreur assigné avec succès.';
            if ($retour === 'admin_commandes') {
                redirect('admin_commandes');
            } else {
                redirect('admin_commande_detail', ['id' => $commandeId]);
            }
            return;
        }

        if ($livraisonId > 0) {
            // Depuis la liste livraisons
            if ($livreurId > 0) {
                $db->query(
                    "UPDATE livraisons SET livreur_id = :lid,
                             statut = IF(statut = 'en_attente', 'assignee', statut)
                     WHERE id = :id",
                    [':lid' => $livreurId, ':id' => $livraisonId]
                );
            } else {
                $db->query(
                    "UPDATE livraisons SET livreur_id = NULL,
                             statut = IF(statut = 'assignee', 'en_attente', statut)
                     WHERE id = :id",
                    [':id' => $livraisonId]
                );
            }
        }

        redirect('admin_livraisons');
    }

    public function changerStatutLivraison(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_livraisons'); return; }

        $livraisonId = (int)($_POST['livraison_id'] ?? 0);
        $statut      = $_POST['statut'] ?? '';
        $commandeId  = (int)($_POST['commande_id'] ?? 0);
        $retour      = $_POST['retour'] ?? '';
        $statutsValides = ['en_attente', 'assignee', 'en_cours', 'livree', 'echec'];

        if ($livraisonId <= 0 || !in_array($statut, $statutsValides, true)) {
            redirect('admin_livraisons');
            return;
        }

        $db = $this->getDb();
        $dateLivree = ($statut === 'livree') ? date('Y-m-d H:i:s') : null;

        $db->query(
            "UPDATE livraisons SET statut = :statut, date_livree = :dl WHERE id = :id",
            [':statut' => $statut, ':dl' => $dateLivree, ':id' => $livraisonId]
        );

        // Récupérer commande_id depuis la livraison si non fourni
        if (!$commandeId && $livraisonId) {
            $lv = $db->fetch("SELECT commande_id FROM livraisons WHERE id = :id", [':id' => $livraisonId]);
            $commandeId = (int)($lv['commande_id'] ?? 0);
        }

        if ($retour === 'admin_commandes') {
            redirect('admin_commandes');
        } elseif ($commandeId > 0) {
            redirect('admin_commande_detail', ['id' => $commandeId]);
        } else {
            redirect('admin_livraisons');
        }
    }

    // -------------------------------------------------------------------------
    // ZONES DE LIVRAISON
    // -------------------------------------------------------------------------

    public function zonesLivraison(): void
    {
        $this->requireAdmin();
        $db    = $this->getDb();
        $zones = $db->fetchAll("SELECT * FROM zones_livraison ORDER BY nom ASC") ?: [];
        require_once __DIR__ . '/../../views/admin/zones_livraison.php';
    }

    public function ajouterZone(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin_zones_livraison');
        }

        $db          = $this->getDb();
        $nom              = trim($_POST['nom'] ?? '');
        $description      = trim($_POST['description'] ?? '');
        $frais            = (float)($_POST['frais'] ?? 0);
        $delai            = max(1, (int)($_POST['delai_jours'] ?? 1));
        $actif            = (int)($_POST['actif'] ?? 1);
        $fraisGratuitSi   = $_POST['frais_gratuit_si'] !== '' ? (int)$_POST['frais_gratuit_si'] : null;
        $minCommande      = (int)($_POST['min_commande'] ?? 0);

        if (empty($nom)) {
            redirect('admin_zones_livraison&error=' . urlencode('Le nom de la zone est obligatoire.'));
        }

        $db->query(
            "INSERT INTO zones_livraison (nom, description, frais, delai_jours, actif, frais_gratuit_si, min_commande)
             VALUES (:nom, :desc, :frais, :delai, :actif, :fgs, :min)",
            [
                ':nom'   => $nom,
                ':desc'  => $description ?: null,
                ':frais' => $frais,
                ':delai' => $delai,
                ':actif' => $actif,
                ':fgs'   => $fraisGratuitSi,
                ':min'   => $minCommande,
            ]
        );

        redirect('admin_zones_livraison&success=' . urlencode('Zone "' . $nom . '" ajoutée avec succès.'));
    }

    public function modifierZone(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin_zones_livraison');
        }

        $db          = $this->getDb();
        $id               = (int)($_POST['id'] ?? 0);
        $nom              = trim($_POST['nom'] ?? '');
        $description      = trim($_POST['description'] ?? '');
        $frais            = (float)($_POST['frais'] ?? 0);
        $delai            = max(1, (int)($_POST['delai_jours'] ?? 1));
        $actif            = (int)($_POST['actif'] ?? 1);
        $fraisGratuitSi   = (isset($_POST['frais_gratuit_si']) && $_POST['frais_gratuit_si'] !== '') ? (int)$_POST['frais_gratuit_si'] : null;
        $minCommande      = (int)($_POST['min_commande'] ?? 0);

        if (!$id || empty($nom)) {
            redirect('admin_zones_livraison&error=' . urlencode('Données invalides.'));
        }

        $exist = $db->fetch("SELECT id FROM zones_livraison WHERE id = :id", [':id' => $id]);
        if (!$exist) {
            redirect('admin_zones_livraison&error=' . urlencode('Zone introuvable.'));
        }

        $db->query(
            "UPDATE zones_livraison SET nom=:nom, description=:desc, frais=:frais, delai_jours=:delai, actif=:actif, frais_gratuit_si=:fgs, min_commande=:min WHERE id=:id",
            [
                ':nom'   => $nom,
                ':desc'  => $description ?: null,
                ':frais' => $frais,
                ':delai' => $delai,
                ':actif' => $actif,
                ':fgs'   => $fraisGratuitSi,
                ':min'   => $minCommande,
                ':id'    => $id,
            ]
        );

        redirect('admin_zones_livraison&success=' . urlencode('Zone "' . $nom . '" modifiée avec succès.'));
    }

    public function supprimerZone(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin_zones_livraison');
        }

        $db = $this->getDb();
        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            redirect('admin_zones_livraison&error=' . urlencode('Zone introuvable.'));
        }

        $zone = $db->fetch("SELECT nom FROM zones_livraison WHERE id = :id", [':id' => $id]);
        if (!$zone) {
            redirect('admin_zones_livraison&error=' . urlencode('Zone introuvable.'));
        }

        $db->query("DELETE FROM zones_livraison WHERE id = :id", [':id' => $id]);

        redirect('admin_zones_livraison&success=' . urlencode('Zone "' . $zone['nom'] . '" supprimée.'));
    }

    // ---------------------------------------------------------------
    // PROMOTIONS / CODES PROMO
    // ---------------------------------------------------------------

    public function promotions(): void
    {
        $this->requireAdmin();
        $db = $this->getDb();

        $today      = date('Y-m-d');
        $promotions = $db->fetchAll("SELECT * FROM promotions ORDER BY created_at DESC") ?: [];

        $kpi = ['total' => count($promotions), 'actifs' => 0, 'expires' => 0, 'utilisations' => 0];

        foreach ($promotions as $p) {
            $kpi['utilisations'] += (int)$p['usage_count'];
            if (!$p['actif']) { continue; }
            if (($p['date_fin'] && $p['date_fin'] < $today) ||
                ($p['usage_max'] !== null && $p['usage_count'] >= $p['usage_max'])) {
                $kpi['expires']++;
            } else {
                $kpi['actifs']++;
            }
        }

        $pageTitle = 'Codes promo';
        $adminPage = 'promotions';
        require_once __DIR__ . '/../../views/admin/promotions.php';
    }

    public function ajouterPromotion(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_promotions'); }

        $db        = $this->getDb();
        $code      = strtoupper(trim($_POST['code'] ?? ''));
        $type      = in_array($_POST['type'] ?? '', ['pourcentage','fixe']) ? $_POST['type'] : 'pourcentage';
        $valeur    = (float)($_POST['valeur'] ?? 0);
        $minCmd    = (float)($_POST['min_commande'] ?? 0);
        $usageMax  = (isset($_POST['usage_max']) && $_POST['usage_max'] !== '') ? (int)$_POST['usage_max'] : null;
        $dateDebut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $dateFin   = !empty($_POST['date_fin'])   ? $_POST['date_fin']   : null;
        $actif     = (int)($_POST['actif'] ?? 1);

        if (empty($code) || $valeur <= 0) {
            redirect('admin_promotions&error=' . urlencode('Code et valeur sont obligatoires.'));
        }

        if ($db->fetch("SELECT id FROM promotions WHERE code = :c", [':c' => $code])) {
            redirect('admin_promotions&error=' . urlencode('Ce code existe déjà.'));
        }

        $db->query(
            "INSERT INTO promotions (code, type, valeur, min_commande, usage_max, date_debut, date_fin, actif, created_at)
             VALUES (:code,:type,:valeur,:minCmd,:usageMax,:dateDebut,:dateFin,:actif,NOW())",
            [':code'=>$code,':type'=>$type,':valeur'=>$valeur,':minCmd'=>$minCmd,
             ':usageMax'=>$usageMax,':dateDebut'=>$dateDebut,':dateFin'=>$dateFin,':actif'=>$actif]
        );

        redirect('admin_promotions&success=' . urlencode('Code promo "' . $code . '" créé avec succès.'));
    }

    public function modifierPromotion(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_promotions'); }

        $db       = $this->getDb();
        $id       = (int)($_POST['id'] ?? 0);
        $code     = strtoupper(trim($_POST['code'] ?? ''));
        $type     = in_array($_POST['type'] ?? '', ['pourcentage','fixe']) ? $_POST['type'] : 'pourcentage';
        $valeur   = (float)($_POST['valeur'] ?? 0);
        $minCmd   = (float)($_POST['min_commande'] ?? 0);
        $usageMax = (isset($_POST['usage_max']) && $_POST['usage_max'] !== '') ? (int)$_POST['usage_max'] : null;
        $dateDebut = !empty($_POST['date_debut']) ? $_POST['date_debut'] : null;
        $dateFin   = !empty($_POST['date_fin'])   ? $_POST['date_fin']   : null;
        $actif    = (int)($_POST['actif'] ?? 1);

        if (!$id || empty($code) || $valeur <= 0) {
            redirect('admin_promotions&error=' . urlencode('Données invalides.'));
        }

        if ($db->fetch("SELECT id FROM promotions WHERE code = :c AND id != :id", [':c'=>$code,':id'=>$id])) {
            redirect('admin_promotions&error=' . urlencode('Ce code est déjà utilisé par une autre promotion.'));
        }

        $db->query(
            "UPDATE promotions SET code=:code, type=:type, valeur=:valeur, min_commande=:minCmd,
             usage_max=:usageMax, date_debut=:dateDebut, date_fin=:dateFin, actif=:actif WHERE id=:id",
            [':code'=>$code,':type'=>$type,':valeur'=>$valeur,':minCmd'=>$minCmd,
             ':usageMax'=>$usageMax,':dateDebut'=>$dateDebut,':dateFin'=>$dateFin,':actif'=>$actif,':id'=>$id]
        );

        redirect('admin_promotions&success=' . urlencode('Code promo "' . $code . '" modifié avec succès.'));
    }

    public function supprimerPromotion(): void
    {
        $this->requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin_promotions'); }

        $db  = $this->getDb();
        $id  = (int)($_POST['id'] ?? 0);

        if (!$id) { redirect('admin_promotions&error=' . urlencode('Identifiant invalide.')); }

        $promo = $db->fetch("SELECT code FROM promotions WHERE id = :id", [':id' => $id]);
        if (!$promo) { redirect('admin_promotions&error=' . urlencode('Code promo introuvable.')); }

        $db->query("DELETE FROM promotions WHERE id = :id", [':id' => $id]);

        redirect('admin_promotions&success=' . urlencode('Code promo "' . $promo['code'] . '" supprimé.'));
    }
}
