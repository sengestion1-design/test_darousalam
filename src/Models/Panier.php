<?php

class Panier
{
    private string $sessionKey = 'panier_darousalam';

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION[$this->sessionKey])) {
            $_SESSION[$this->sessionKey] = [];
        }
    }

    private function &getItems(): array
    {
        return $_SESSION[$this->sessionKey];
    }

    public function ajouter(int $produitId, int $quantite = 1, array $infos = [], string $unite = 'kg'): void
    {
        $items = &$this->getItems();
        $key   = $produitId . '_' . $unite;

        if (isset($items[$key])) {
            $items[$key]['quantite'] += $quantite;
        } else {
            $prix = ($unite === 'carton')
                ? (float)($infos['prix_carton'] ?? 0)
                : (float)($infos['prix_kg'] ?? $infos['prix_promo'] ?? $infos['prix'] ?? 0);

            $items[$key] = [
                'produit_id'      => $produitId,
                'nom'             => $infos['nom'] ?? '',
                'prix'            => $prix,
                'image'           => $infos['image_principale'] ?? $infos['image'] ?? '',
                'reference'       => $infos['reference'] ?? '',
                'quantite'        => $quantite,
                'unite'           => $unite,
                'prix_carton'     => (float)($infos['prix_carton'] ?? 0),
                'poids_carton_kg' => (float)($infos['poids_carton_kg'] ?? 0),
            ];
        }
    }

    public function retirer(string $key): void
    {
        $items = &$this->getItems();
        unset($items[$key]);
    }

    public function modifierQuantite(string $key, int $quantite): void
    {
        $items = &$this->getItems();
        if ($quantite <= 0) {
            $this->retirer($key);
            return;
        }
        if (isset($items[$key])) {
            $items[$key]['quantite'] = $quantite;
        }
    }

    public function vider(): void
    {
        $_SESSION[$this->sessionKey] = [];
    }

    public function getContenu(): array
    {
        return $_SESSION[$this->sessionKey];
    }

    public function calculerTotal(): float
    {
        $total = 0.0;
        foreach ($this->getContenu() as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }

    public function nombreArticles(): int
    {
        $total = 0;
        foreach ($this->getContenu() as $item) {
            $total += $item['quantite'];
        }
        return $total;
    }

    public function estVide(): bool
    {
        return empty($_SESSION[$this->sessionKey]);
    }

    public function contientProduit(string $key): bool
    {
        return isset($_SESSION[$this->sessionKey][$key]);
    }
}
