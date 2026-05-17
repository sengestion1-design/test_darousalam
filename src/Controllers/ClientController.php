<?php

class ClientController
{
    private Client $clientModel;

    public function __construct()
    {
        $this->clientModel = new Client();
    }

    public function dashboard(): void
    {
        requireLogin();

        $client        = $this->clientModel->findById($_SESSION['client_id']);
        $commandeModel = new Commande();
        $dernieresCommandes = $commandeModel->getHistoriqueClient($_SESSION['client_id'], 1, 5);
        $totalCommandes     = $commandeModel->countHistoriqueClient($_SESSION['client_id']);

        require_once __DIR__ . '/../../views/client/dashboard.php';
    }

    public function adresses(): void
    {
        requireLogin();
        $client = $this->clientModel->findById($_SESSION['client_id']);
        require_once __DIR__ . '/../../views/client/adresses.php';
    }

    public function favoris(): void
    {
        requireLogin();
        // Fonctionnalité favoris (stockée en session pour simplifier)
        $favoris = $_SESSION['favoris'] ?? [];
        $produits = [];
        if (!empty($favoris)) {
            $produitModel = new Produit();
            foreach ($favoris as $id) {
                $p = $produitModel->getById($id);
                if ($p) $produits[] = $p;
            }
        }
        require_once __DIR__ . '/../../views/client/favoris.php';
    }

    public function ajouterFavori(): void
    {
        requireLogin();
        $produitId = (int)($_GET['id'] ?? 0);
        if ($produitId) {
            $_SESSION['favoris'][$produitId] = $produitId;
        }
        redirect('favoris');
    }

    public function retirerFavori(): void
    {
        requireLogin();
        $produitId = (int)($_GET['id'] ?? 0);
        if ($produitId) {
            unset($_SESSION['favoris'][$produitId]);
        }
        redirect('favoris');
    }
}
