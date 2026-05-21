<?php

class CommandeController
{
    private Commande $commandeModel;
    private Panier $panier;

    public function __construct()
    {
        $this->commandeModel = new Commande();
        $this->panier        = new Panier();
    }

    public function validerCodePromo(): void
    {
        header('Content-Type: application/json');
        $code  = strtoupper(trim($_POST['code'] ?? ''));
        $total = (float)($_POST['total'] ?? 0);

        if (empty($code)) {
            echo json_encode(['success' => false, 'message' => 'Code vide.']);
            exit;
        }

        $db = Database::getInstance();
        $promo = $db->fetch(
            "SELECT * FROM promotions WHERE code = :code AND actif = 1
             AND (date_debut IS NULL OR date_debut <= CURDATE())
             AND (date_fin IS NULL OR date_fin >= CURDATE())
             AND (usage_max IS NULL OR usage_count < usage_max)",
            [':code' => $code]
        );

        if (!$promo) {
            echo json_encode(['success' => false, 'message' => 'Code promo invalide ou expiré.']);
            exit;
        }

        if (!empty($promo['min_commande']) && $total < (float)$promo['min_commande']) {
            echo json_encode(['success' => false, 'message' => 'Commande minimum de ' . number_format((float)$promo['min_commande'], 0, ',', ' ') . ' FCFA requise.']);
            exit;
        }

        $remise = $promo['type'] === 'pourcentage'
            ? round($total * (float)$promo['valeur'] / 100)
            : min((float)$promo['valeur'], $total);

        echo json_encode([
            'success'    => true,
            'remise'     => $remise,
            'total_apres'=> $total - $remise,
            'type'       => $promo['type'],
            'valeur'     => (float)$promo['valeur'],
            'message'    => 'Code appliqué !',
        ]);
        exit;
    }

    public function checkout(): void
    {
        requireLogin();

        if ($this->panier->estVide()) {
            flashMessage('error', 'Votre panier est vide.');
            redirect('panier');
        }

        // Toujours lire depuis la DB pour avoir les dernières infos (téléphone, adresse, etc.)
        $clientModel = new Client();
        $client = $clientModel->findById($_SESSION['client_id']) ?: getClientSession();
        $error  = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db           = Database::getInstance();
            $zoneId       = (int)($_POST['zone_id'] ?? 0);
            $modePaiement = $_POST['mode_paiement'] ?? 'a_la_livraison';
            $codePromo    = strtoupper(trim($_POST['code_promo'] ?? ''));
            $remise       = 0;
            $fraisLivraison = 0;
            $zoneNom      = '';

            // Valider la zone
            $zone = null;
            if ($zoneId) {
                $zone = $db->fetch("SELECT * FROM zones_livraison WHERE id = :id AND actif = 1", [':id' => $zoneId]);
            }

            $adresse = [
                'adresse'   => trim($_POST['adresse'] ?? ''),
                'ville'     => $zone ? $zone['nom'] : trim($_POST['ville'] ?? ''),
                'telephone' => trim($_POST['telephone'] ?? ''),
                'zone_id'   => $zoneId,
            ];

            if (empty($adresse['adresse']) || !$zoneId || empty($adresse['telephone'])) {
                $error = 'Veuillez remplir tous les champs et choisir une zone de livraison.';
            } elseif (!$zone) {
                $error = 'Zone de livraison invalide.';
            } else {
                $sousTotal = $this->panier->calculerTotal();

                // Vérifier commande minimum de la zone
                if (false) {
                    // pas de commande minimum
                } else {
                    // Valider le code promo si fourni
                    if (!empty($codePromo)) {
                        $promo = $db->fetch(
                            "SELECT * FROM promotions WHERE code = :code AND actif = 1
                             AND (date_debut IS NULL OR date_debut <= CURDATE())
                             AND (date_fin IS NULL OR date_fin >= CURDATE())
                             AND (usage_max IS NULL OR usage_count < usage_max)",
                            [':code' => $codePromo]
                        );
                        if ($promo) {
                            if (empty($promo['min_commande']) || $sousTotal >= (float)$promo['min_commande']) {
                                $remise = $promo['type'] === 'pourcentage'
                                    ? round($sousTotal * (float)$promo['valeur'] / 100)
                                    : min((float)$promo['valeur'], $sousTotal);
                            }
                        }
                    }

                    // Calculer frais de livraison
                    $apresRemise = $sousTotal - $remise;
                    $fraisLivraison = (float)($zone['frais'] ?? 0);

                    $panier = $this->panier->getContenu();
                    $commandeId = $this->commandeModel->creer(
                        $_SESSION['client_id'],
                        $panier,
                        $adresse,
                        $modePaiement,
                        $codePromo ?: null,
                        (int)$remise,
                        (int)$fraisLivraison
                    );

                    if ($commandeId) {
                        $this->panier->vider();

                        // Emails post-commande
                        try {
                            $db = Database::getInstance();
                            $commande = $db->fetch(
                                "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom, cl.email as client_email
                                 FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id
                                 WHERE c.id = :id",
                                [':id' => $commandeId]
                            );
                            $lignes = $db->fetchAll(
                                "SELECT cd.* FROM commande_details cd WHERE cd.commande_id = :id",
                                [':id' => $commandeId]
                            );
                            if ($commande) {
                                require_once __DIR__ . '/../Services/EmailService.php';
                                $emailSvc = new EmailService();
                                // Confirmation au client
                                if (!empty($commande['client_email'])) {
                                    $emailSvc->envoyerConfirmationCommande($commande, $lignes);
                                }
                                // Notification admin
                                $emailSvc->envoyerNotificationAdmin($commande, $lignes);
                            }
                        } catch (\Exception $e) {
                            // Ne pas bloquer si l'email échoue
                            error_log('Email commande erreur: ' . $e->getMessage());
                        }

                        flashMessage('success', 'Commande passée avec succès !');

                        // Redirection Wave : lien de paiement direct
                        if ($modePaiement === 'wave') {
                            $montantTotal = $sousTotal - $remise + $fraisLivraison;
                            $wavePhone = '221778164018';
                            $waveMsg   = 'Commande #' . $commandeId . ' - Darou Salam Business';
                            $waveUrl   = 'https://wave.com/send?currency=XOF&to=' . $wavePhone
                                       . '&amount=' . (int)$montantTotal
                                       . '&note=' . urlencode($waveMsg);
                            header('Location: ' . $waveUrl);
                            exit;
                        }

                        redirect('commande_confirmation', ['id' => $commandeId]);
                    } else {
                        $error = 'Une erreur est survenue lors de la commande. Veuillez réessayer.';
                    }
                } // end else min_commande
            } // end else zone valide
        } // end POST

        $contenu = $this->panier->getContenu();
        $total   = $this->panier->calculerTotal();
        require_once __DIR__ . '/../../views/commande/checkout.php';
    }

    public function confirmation(): void
    {
        requireLogin();

        $commandeId = (int)($_GET['id'] ?? 0);
        $commande   = $this->commandeModel->getById($commandeId);

        if (!$commande || $commande['client_id'] != $_SESSION['client_id']) {
            flashMessage('error', 'Commande introuvable.');
            redirect('historique');
        }

        $lignes = $this->commandeModel->getLignes($commandeId);
        require_once __DIR__ . '/../../views/commande/confirmation.php';
    }

    public function historique(): void
    {
        requireLogin();

        $page      = max(1, (int)($_GET['p'] ?? 1));
        $perPage   = 10;
        $commandes = $this->commandeModel->getHistoriqueClient($_SESSION['client_id'], $page, $perPage);
        $total     = $this->commandeModel->countHistoriqueClient($_SESSION['client_id']);
        $paginationHtml = pagination($total, $perPage, $page);

        require_once __DIR__ . '/../../views/commande/historique.php';
    }

    public function detail(): void
    {
        requireLogin();

        $commandeId = (int)($_GET['id'] ?? 0);
        $commande   = $this->commandeModel->getById($commandeId);

        if (!$commande || $commande['client_id'] != $_SESSION['client_id']) {
            flashMessage('error', 'Commande introuvable.');
            redirect('historique');
        }

        $lignes = $this->commandeModel->getLignes($commandeId);

        $db = Database::getInstance();
        $livraison = $db->fetch(
            "SELECT lv.*, lr.nom as livreur_nom, lr.telephone as livreur_tel
             FROM livraisons lv
             LEFT JOIN livreurs lr ON lr.id = lv.livreur_id
             WHERE lv.commande_id = :id",
            [':id' => $commandeId]
        );

        require_once __DIR__ . '/../../views/commande/detail.php';
    }

    public function annuler(): void
    {
        requireLogin();

        $commandeId = (int)($_GET['id'] ?? 0);
        $commande   = $this->commandeModel->getById($commandeId);

        if (!$commande || $commande['client_id'] != $_SESSION['client_id']) {
            flashMessage('error', 'Commande introuvable.');
            redirect('historique');
        }

        if (!in_array($commande['statut'], ['en_attente', 'confirmee'])) {
            flashMessage('error', 'Cette commande ne peut plus être annulée.');
            redirect('commande_detail', ['id' => $commandeId]);
        }

        $this->commandeModel->changerStatut($commandeId, 'annulee');
        flashMessage('info', 'Commande annulée.');
        redirect('historique');
    }

    public function telechargerFacture(): void
    {
        requireLogin();

        $commandeId = (int)($_GET['id'] ?? 0);
        $db = Database::getInstance();

        $commande = $db->fetch(
            "SELECT c.*, cl.nom as client_nom, cl.prenom as client_prenom,
                    cl.email as client_email, cl.telephone as client_tel
             FROM commandes c LEFT JOIN clients cl ON c.client_id = cl.id
             WHERE c.id = :id AND c.client_id = :cid",
            [':id' => $commandeId, ':cid' => $_SESSION['client_id']]
        );

        if (!$commande) {
            flashMessage('error', 'Facture introuvable.');
            redirect('historique');
            return;
        }

        // Seules les commandes livrées ou avec paiement confirmé ont une facture
        if (!in_array($commande['statut'], ['livree', 'en_livraison', 'en_preparation', 'confirmee'])) {
            flashMessage('error', 'La facture sera disponible une fois la commande confirmée.');
            redirect('historique');
            return;
        }

        $lignes = $db->fetchAll(
            "SELECT cd.*, p.image_principale FROM commande_details cd
             LEFT JOIN produits p ON p.id = cd.produit_id
             WHERE cd.commande_id = :id",
            [':id' => $commandeId]
        );

        require_once __DIR__ . '/../Services/FacturePDFService.php';
        (new FacturePDFService())->generer($commande, $lignes);
    }

    public function apiStatut(): void
    {
        header('Content-Type: application/json');

        if (!isLoggedIn()) {
            echo json_encode(['error' => 'non_authentifie']);
            exit;
        }

        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            echo json_encode(['error' => 'id_manquant']);
            exit;
        }

        $db = Database::getInstance();
        $row = $db->fetch(
            "SELECT statut, updated_at FROM commandes WHERE id = :id AND client_id = :cid",
            [':id' => $id, ':cid' => $_SESSION['client_id']]
        );

        if (!$row) {
            echo json_encode(['error' => 'introuvable']);
            exit;
        }

        echo json_encode([
            'statut'     => $row['statut'],
            'updated_at' => $row['updated_at'],
        ]);
        exit;
    }
}
