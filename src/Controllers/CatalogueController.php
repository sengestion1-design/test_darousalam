<?php

class CatalogueController
{
    private Produit $produitModel;
    private Categorie $categorieModel;
    private int $perPage = 12;

    public function __construct()
    {
        $this->produitModel   = new Produit();
        $this->categorieModel = new Categorie();
    }

    public function index(): void
    {
        $page      = max(1, (int)($_GET['p'] ?? 1));
        $catId     = (int)($_GET['cat'] ?? 0);
        $prixMax   = isset($_GET['max']) ? (int)$_GET['max'] : 0;
        $sortBy    = $_GET['sort'] ?? 'default';
        $categories = $this->categorieModel->getAll();

        $produits = $this->produitModel->getAllFiltered($page, $this->perPage, $catId, $prixMax, $sortBy);
        $total    = $this->produitModel->countFiltered($catId, $prixMax);

        $paginationHtml = pagination($total, $this->perPage, $page, 'p',
            array_filter(['cat' => $catId ?: null, 'max' => $prixMax ?: null, 'sort' => $sortBy !== 'default' ? $sortBy : null])
        );

        $db = Database::getInstance();
        $promotionsActives = $db->fetchAll(
            "SELECT * FROM promotions WHERE actif = 1
             AND (date_debut IS NULL OR date_debut <= CURDATE())
             AND (date_fin IS NULL OR date_fin >= CURDATE())
             AND (usage_max IS NULL OR usage_count < usage_max)
             ORDER BY valeur DESC"
        );

        require_once __DIR__ . '/../../views/catalogue/index.php';
    }

    public function categorie(): void
    {
        $categorieId = (int)($_GET['id'] ?? 0);
        if (!$categorieId) {
            redirect('catalogue');
        }

        $categorie = $this->categorieModel->getById($categorieId);
        if (!$categorie) {
            flashMessage('error', 'Catégorie introuvable.');
            redirect('catalogue');
        }

        $page     = max(1, (int)($_GET['p'] ?? 1));
        $produits = $this->produitModel->getByCategorie($categorieId, $page, $this->perPage);
        $total    = $this->produitModel->countByCategorie($categorieId);
        $categories = $this->categorieModel->getAll();
        $paginationHtml = pagination($total, $this->perPage, $page, 'p', ['id' => $categorieId]);
        require_once __DIR__ . '/../../views/catalogue/categorie.php';
    }

    public function produit(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) {
            redirect('catalogue');
        }

        $produit = $this->produitModel->getById($id);
        if (!$produit) {
            flashMessage('error', 'Produit introuvable.');
            redirect('catalogue');
        }

        $categories  = $this->categorieModel->getAll();
        // Produits similaires (même catégorie)
        $similaires  = $this->produitModel->getByCategorie($produit['categorie_id'], 1, 4);
        $similaires  = array_filter($similaires, fn($p) => $p['id'] !== $produit['id']);

        require_once __DIR__ . '/../../views/catalogue/produit.php';
    }

    public function recherche(): void
    {
        $terme    = trim($_GET['q'] ?? '');
        $page     = max(1, (int)($_GET['p'] ?? 1));
        $produits = [];
        $total    = 0;
        $paginationHtml = '';

        if (!empty($terme)) {
            $produits = $this->produitModel->recherche($terme, $page, $this->perPage);
            $total    = $this->produitModel->countRecherche($terme);
            $paginationHtml = pagination($total, $this->perPage, $page, 'p', ['q' => $terme]);
        }

        $categories = $this->categorieModel->getAll();
        require_once __DIR__ . '/../../views/catalogue/recherche.php';
    }
}
