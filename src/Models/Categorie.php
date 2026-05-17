<?php

class Categorie
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAll(): array
    {
        return $this->db->fetchAll("SELECT * FROM categories ORDER BY nom ASC");
    }

    public function getById(int $id): array|false
    {
        return $this->db->fetch("SELECT * FROM categories WHERE id = :id", [':id' => $id]);
    }

    public function create(string $nom, string $description = '', int|null $parentId = null): int
    {
        $this->db->query(
            "INSERT INTO categories (nom, description, parent_id) VALUES (:nom, :description, :parent_id)",
            [':nom' => sanitize($nom), ':description' => sanitize($description), ':parent_id' => $parentId]
        );
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, string $nom, string $description = ''): bool
    {
        $this->db->query(
            "UPDATE categories SET nom = :nom, description = :description WHERE id = :id",
            [':nom' => sanitize($nom), ':description' => sanitize($description), ':id' => $id]
        );
        return true;
    }

    public function delete(int $id): bool
    {
        $this->db->query("DELETE FROM categories WHERE id = :id", [':id' => $id]);
        return true;
    }

    public function getWithProductCount(): array
    {
        return $this->db->fetchAll(
            "SELECT c.*, COUNT(p.id) as nb_produits
             FROM categories c
             LEFT JOIN produits p ON p.categorie_id = c.id AND p.actif = 1
             GROUP BY c.id
             ORDER BY c.nom ASC"
        );
    }
}
