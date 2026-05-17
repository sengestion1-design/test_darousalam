<?php

class Produit
{
    private Database $db;
    private int $defaultPerPage = 12;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(int $page = 1, int $perPage = 0): array
    {
        $perPage = $perPage ?: $this->defaultPerPage;
        $offset  = ($page - 1) * $perPage;
        return $this->db->fetchAll(
            "SELECT p.*, c.nom as categorie_nom
             FROM produits p
             LEFT JOIN categories c ON c.id = p.categorie_id
             WHERE p.actif = 1
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            [':limit' => $perPage, ':offset' => $offset]
        );
    }

    public function countAll(): int
    {
        $row = $this->db->fetch("SELECT COUNT(*) as total FROM produits WHERE actif = 1");
        return (int)($row['total'] ?? 0);
    }

    public function getAllFiltered(int $page, int $perPage, int $catId = 0, int $prixMax = 0, string $sort = 'default'): array
    {
        $offset = ($page - 1) * $perPage;
        $where  = ['p.actif = 1'];
        $params = [':limit' => $perPage, ':offset' => $offset];

        if ($catId > 0) {
            $where[]              = 'p.categorie_id = :cat_id';
            $params[':cat_id']    = $catId;
        }
        if ($prixMax > 0) {
            $where[]              = 'p.prix_kg <= :prix_max';
            $params[':prix_max']  = $prixMax;
        }

        $orderMap = [
            'price_asc'  => 'p.prix_kg ASC',
            'price_desc' => 'p.prix_kg DESC',
            'name_asc'   => 'p.nom ASC',
        ];
        $orderBy = $orderMap[$sort] ?? 'p.created_at DESC';

        $sql = "SELECT p.*, c.nom as categorie_nom
                FROM produits p
                LEFT JOIN categories c ON c.id = p.categorie_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY $orderBy
                LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, $params);
    }

    public function countFiltered(int $catId = 0, int $prixMax = 0): int
    {
        $where  = ['actif = 1'];
        $params = [];

        if ($catId > 0) {
            $where[]           = 'categorie_id = :cat_id';
            $params[':cat_id'] = $catId;
        }
        if ($prixMax > 0) {
            $where[]              = 'prix_kg <= :prix_max';
            $params[':prix_max']  = $prixMax;
        }

        $sql = "SELECT COUNT(*) as total FROM produits WHERE " . implode(' AND ', $where);
        $row = $this->db->fetch($sql, $params);
        return (int)($row['total'] ?? 0);
    }

    public function getByCategorie(int $categorieId, int $page = 1, int $perPage = 0): array
    {
        $perPage = $perPage ?: $this->defaultPerPage;
        $offset  = ($page - 1) * $perPage;
        return $this->db->fetchAll(
            "SELECT p.*, c.nom as categorie_nom
             FROM produits p
             LEFT JOIN categories c ON c.id = p.categorie_id
             WHERE p.categorie_id = :categorie_id AND p.actif = 1
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset",
            [':categorie_id' => $categorieId, ':limit' => $perPage, ':offset' => $offset]
        );
    }

    public function countByCategorie(int $categorieId): int
    {
        $row = $this->db->fetch(
            "SELECT COUNT(*) as total FROM produits WHERE categorie_id = :categorie_id AND actif = 1",
            [':categorie_id' => $categorieId]
        );
        return (int)($row['total'] ?? 0);
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetch(
            "SELECT p.*, c.nom as categorie_nom
             FROM produits p
             LEFT JOIN categories c ON c.id = p.categorie_id
             WHERE p.id = :id AND p.actif = 1",
            [':id' => $id]
        );
    }

    public function recherche(string $terme, int $page = 1, int $perPage = 0): array
    {
        $perPage = $perPage ?: $this->defaultPerPage;
        $offset  = ($page - 1) * $perPage;
        $like    = '%' . $terme . '%';
        return $this->db->fetchAll(
            "SELECT p.*, c.nom as categorie_nom
             FROM produits p
             LEFT JOIN categories c ON c.id = p.categorie_id
             WHERE p.actif = 1 AND (p.nom LIKE :terme1 OR p.description LIKE :terme2 OR p.origine LIKE :terme3)
             ORDER BY p.nom ASC
             LIMIT :limit OFFSET :offset",
            [':terme1' => $like, ':terme2' => $like, ':terme3' => $like, ':limit' => $perPage, ':offset' => $offset]
        );
    }

    public function countRecherche(string $terme): int
    {
        $like = '%' . $terme . '%';
        $row  = $this->db->fetch(
            "SELECT COUNT(*) as total FROM produits WHERE actif = 1 AND (nom LIKE :terme1 OR description LIKE :terme2 OR origine LIKE :terme3)",
            [':terme1' => $like, ':terme2' => $like, ':terme3' => $like]
        );
        return (int)($row['total'] ?? 0);
    }

    public function create(array $data): int
    {
        $this->db->query(
            "INSERT INTO produits (nom, slug, description, origine, prix_kg, prix_carton, poids_carton_kg, stock_kg, stock_cartons, unite_min_kg, image_principale, badge, saison, categorie_id, actif, created_at)
             VALUES (:nom, :slug, :description, :origine, :prix_kg, :prix_carton, :poids_carton_kg, :stock_kg, :stock_cartons, :unite_min_kg, :image_principale, :badge, :saison, :categorie_id, :actif, NOW())",
            [
                ':nom'             => sanitize($data['nom']),
                ':slug'            => sanitize($data['slug'] ?? strtolower(str_replace(' ', '-', $data['nom']))),
                ':description'     => sanitize($data['description'] ?? ''),
                ':origine'         => sanitize($data['origine'] ?? ''),
                ':prix_kg'         => (float)($data['prix_kg'] ?? 0),
                ':prix_carton'     => isset($data['prix_carton']) ? (float)$data['prix_carton'] : null,
                ':poids_carton_kg' => isset($data['poids_carton_kg']) ? (float)$data['poids_carton_kg'] : null,
                ':stock_kg'        => (float)($data['stock_kg'] ?? 0),
                ':stock_cartons'   => (int)($data['stock_cartons'] ?? 0),
                ':unite_min_kg'    => (float)($data['unite_min_kg'] ?? 1),
                ':image_principale'=> sanitize($data['image_principale'] ?? ''),
                ':badge'           => sanitize($data['badge'] ?? ''),
                ':saison'          => sanitize($data['saison'] ?? ''),
                ':categorie_id'    => (int)$data['categorie_id'],
                ':actif'           => (int)($data['actif'] ?? 1),
            ]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $fields  = [];
        $params  = [':id' => $id];
        $allowed = ['nom', 'description', 'origine', 'prix_kg', 'prix_carton', 'poids_carton_kg', 'stock_kg', 'stock_cartons', 'unite_min_kg', 'image_principale', 'badge', 'saison', 'categorie_id', 'actif'];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $fields[]       = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }
        if (empty($fields)) return false;
        $this->db->query("UPDATE produits SET " . implode(', ', $fields) . " WHERE id = :id", $params);
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->query("UPDATE produits SET actif = 0 WHERE id = :id", [':id' => $id]);
        return true;
    }

    public function getFeatured(int $limit = 8): array
    {
        return $this->db->fetchAll(
            "SELECT p.*, c.nom as categorie_nom FROM produits p
             LEFT JOIN categories c ON c.id = p.categorie_id
             WHERE p.actif = 1 AND p.stock_kg > 0
             ORDER BY p.created_at DESC LIMIT :limit",
            [':limit' => $limit]
        );
    }

    public function updateStock(int $id, float $qtKg, int $qtCartons = 0): bool
    {
        $this->db->query(
            "UPDATE produits SET stock_kg = stock_kg - :kg, stock_cartons = stock_cartons - :cartons WHERE id = :id",
            [':kg' => $qtKg, ':cartons' => $qtCartons, ':id' => $id]
        );
        return true;
    }
}
