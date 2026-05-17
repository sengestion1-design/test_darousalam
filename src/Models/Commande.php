<?php

class Commande
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function creer(int $clientId, array $panier, array $adresseLivraison, string $modePaiement = 'a_la_livraison', ?string $codePromo = null, int $remise = 0, int $fraisLivraison = 0): int|false
    {
        if (empty($panier)) return false;

        $sousTotal = 0;
        foreach ($panier as $item) {
            $sousTotal += $item['prix'] * $item['quantite'];
        }
        $total = max(0, $sousTotal - $remise + $fraisLivraison);

        // Mapper le mode de paiement aux valeurs enum de la DB
        $modeMap = [
            'a_la_livraison' => 'cash',
            'orange_money'   => 'orange_money',
            'wave'           => 'wave',
        ];
        $modePaiementDb = $modeMap[$modePaiement] ?? 'cash';

        // Stocker adresse + ville + téléphone en JSON dans adresse_livraison
        $adresseJson = json_encode([
            'adresse'   => $adresseLivraison['adresse'] ?? '',
            'ville'     => $adresseLivraison['ville'] ?? '',
            'telephone' => $adresseLivraison['telephone'] ?? '',
        ], JSON_UNESCAPED_UNICODE);

        $reference = 'CMD-' . strtoupper(substr(uniqid(), -8));

        $pdo = $this->db->getPdo();
        $pdo->beginTransaction();

        try {
            $this->db->query(
                "INSERT INTO commandes (reference, client_id, sous_total, remise, frais_livraison, code_promo, total, adresse_livraison, mode_paiement, statut, created_at)
                 VALUES (:reference, :client_id, :sous_total, :remise, :frais_livraison, :code_promo, :total, :adresse, :mode_paiement, 'en_attente', NOW())",
                [
                    ':reference'      => $reference,
                    ':client_id'      => $clientId,
                    ':sous_total'     => $sousTotal,
                    ':remise'         => $remise,
                    ':frais_livraison'=> $fraisLivraison,
                    ':code_promo'     => $codePromo,
                    ':total'          => $total,
                    ':adresse'        => $adresseJson,
                    ':mode_paiement'  => $modePaiementDb,
                ]
            );
            $commandeId = (int)$this->db->lastInsertId();

            foreach ($panier as $item) {
                $unite = $item['unite'] ?? 'kg';
                $poidsCarton = ($unite === 'carton') ? (float)($item['poids_carton_kg'] ?? 0) : null;

                $this->db->query(
                    "INSERT INTO commande_details (commande_id, produit_id, nom_produit, prix_unitaire, quantite, unite, poids_carton_kg, total_ligne)
                     VALUES (:commande_id, :produit_id, :nom, :prix, :quantite, :unite, :poids_carton_kg, :total_ligne)",
                    [
                        ':commande_id'     => $commandeId,
                        ':produit_id'      => $item['produit_id'],
                        ':nom'             => $item['nom'],
                        ':prix'            => $item['prix'],
                        ':quantite'        => $item['quantite'],
                        ':unite'           => $unite,
                        ':poids_carton_kg' => $poidsCarton,
                        ':total_ligne'     => $item['prix'] * $item['quantite'],
                    ]
                );
            }

            if ($codePromo) {
                $this->db->query(
                    "UPDATE promotions SET usage_count = usage_count + 1 WHERE code = :code",
                    [':code' => $codePromo]
                );
            }

            $pdo->commit();
            return $commandeId;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log('Commande::creer() error: ' . $e->getMessage());
            return false;
        }
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetch(
            "SELECT co.*, cl.nom, cl.prenom, cl.email FROM commandes co
             LEFT JOIN clients cl ON cl.id = co.client_id
             WHERE co.id = :id",
            [':id' => $id]
        );
    }

    public function getByReference(string $reference): array|false
    {
        return $this->db->fetch(
            "SELECT co.*, cl.nom, cl.prenom, cl.email FROM commandes co
             LEFT JOIN clients cl ON cl.id = co.client_id
             WHERE co.reference = :reference",
            [':reference' => $reference]
        );
    }

    public function getLignes(int $commandeId): array
    {
        return $this->db->fetchAll(
            "SELECT cd.*, p.image_principale as image FROM commande_details cd
             LEFT JOIN produits p ON p.id = cd.produit_id
             WHERE cd.commande_id = :commande_id",
            [':commande_id' => $commandeId]
        );
    }

    public function getHistoriqueClient(int $clientId, int $page = 1, int $perPage = 10): array
    {
        $offset = ($page - 1) * $perPage;
        return $this->db->fetchAll(
            "SELECT * FROM commandes WHERE client_id = :client_id ORDER BY created_at DESC LIMIT :limit OFFSET :offset",
            [':client_id' => $clientId, ':limit' => $perPage, ':offset' => $offset]
        );
    }

    public function countHistoriqueClient(int $clientId): int
    {
        $row = $this->db->fetch(
            "SELECT COUNT(*) as total FROM commandes WHERE client_id = :client_id",
            [':client_id' => $clientId]
        );
        return (int)($row['total'] ?? 0);
    }

    public function changerStatut(int $id, string $statut): bool
    {
        $statuts = ['en_attente', 'confirmee', 'en_preparation', 'expediee', 'livree', 'annulee'];
        if (!in_array($statut, $statuts)) return false;
        $this->db->query(
            "UPDATE commandes SET statut = :statut, updated_at = NOW() WHERE id = :id",
            [':statut' => $statut, ':id' => $id]
        );
        return true;
    }

    public function getAll(int $page = 1, int $perPage = 20, string $statut = ''): array
    {
        $offset = ($page - 1) * $perPage;
        $where  = $statut ? "WHERE co.statut = :statut" : '';
        $params = $statut ? [':statut' => $statut, ':limit' => $perPage, ':offset' => $offset]
                          : [':limit' => $perPage, ':offset' => $offset];
        return $this->db->fetchAll(
            "SELECT co.*, cl.nom, cl.prenom FROM commandes co
             LEFT JOIN clients cl ON cl.id = co.client_id
             $where ORDER BY co.created_at DESC LIMIT :limit OFFSET :offset",
            $params
        );
    }

    public function countAll(string $statut = ''): int
    {
        if ($statut) {
            $row = $this->db->fetch("SELECT COUNT(*) as total FROM commandes WHERE statut = :statut", [':statut' => $statut]);
        } else {
            $row = $this->db->fetch("SELECT COUNT(*) as total FROM commandes");
        }
        return (int)($row['total'] ?? 0);
    }
}
