<?php

class PanierController
{
    private Panier $panier;
    private Produit $produitModel;

    public function __construct()
    {
        $this->panier       = new Panier();
        $this->produitModel = new Produit();
    }

    public function index(): void
    {
        $contenu = $this->panier->getContenu();
        $total   = $this->panier->calculerTotal();
        require_once __DIR__ . '/../../views/panier/index.php';
    }

    public function ajouter(): void
    {
        $produitId = (int)($_POST['produit_id'] ?? $_GET['id'] ?? 0);
        $quantite  = max(1, (int)($_POST['quantite'] ?? 1));

        if (!$produitId) {
            $this->jsonResponse(['success' => false, 'message' => 'Produit invalide.']);
        }

        $unite = $_POST['unite'] ?? 'kg';
        if (!in_array($unite, ['kg', 'carton'], true)) {
            $unite = 'kg';
        }

        $produit = $this->produitModel->getById($produitId);
        if (!$produit) {
            $this->jsonResponse(['success' => false, 'message' => 'Produit introuvable.']);
        }

        if ($unite === 'carton') {
            if (($produit['stock_cartons'] ?? 0) < $quantite) {
                $this->jsonResponse(['success' => false, 'message' => 'Stock insuffisant.']);
            }
        } else {
            if (($produit['stock_kg'] ?? 0) < $quantite) {
                $this->jsonResponse(['success' => false, 'message' => 'Stock insuffisant.']);
            }
        }

        $this->panier->ajouter($produitId, $quantite, $produit, $unite);
        $nbArticles = $this->panier->nombreArticles();

        if ($this->isAjax()) {
            $this->jsonResponse([
                'success'    => true,
                'message'    => 'Produit ajouté au panier.',
                'nb_articles'=> $nbArticles,
                'total'      => formatPrice($this->panier->calculerTotal()),
            ]);
        }

        flashMessage('success', '"' . $produit['nom'] . '" ajouté au panier.');
        redirect('panier');
    }

    public function retirer(): void
    {
        // Priorité à la clé string (ex: "3_kg"), fallback sur id pour compatibilité
        $key = $_GET['key'] ?? '';
        if ($key === '') {
            $id  = (int)($_GET['id'] ?? 0);
            $key = $id . '_kg';
        }

        if ($key) {
            $this->panier->retirer($key);
            flashMessage('info', 'Article retiré du panier.');
        }

        redirect('panier');
    }

    public function modifier(): void
    {
        $key      = $_POST['key'] ?? '';
        $quantite = (int)($_POST['quantite'] ?? 0);

        if ($key) {
            if ($quantite <= 0) {
                $this->panier->retirer($key);
            } else {
                // Extraire produitId et unite depuis la clé
                [$produitId, $unite] = explode('_', $key . '_kg', 3);
                $produitId = (int)$produitId;
                $produit   = $this->produitModel->getById($produitId);
                $stockOk   = false;
                if ($produit) {
                    if ($unite === 'carton') {
                        $stockOk = ($produit['stock_cartons'] ?? 0) >= $quantite;
                    } else {
                        $stockOk = ($produit['stock_kg'] ?? 0) >= $quantite;
                    }
                }
                if ($stockOk) {
                    $this->panier->modifierQuantite($key, $quantite);
                } else {
                    flashMessage('error', 'Stock insuffisant pour cette quantité.');
                }
            }
        }

        redirect('panier');
    }

    public function vider(): void
    {
        $this->panier->vider();
        flashMessage('info', 'Panier vidé.');
        redirect('panier');
    }

    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function jsonResponse(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}
