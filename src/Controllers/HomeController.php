<?php

class HomeController
{
    private Produit $produitModel;
    private Categorie $categorieModel;

    public function __construct()
    {
        $this->produitModel   = new Produit();
        $this->categorieModel = new Categorie();
    }

    public function index(): void
    {
        $produitsFeatured = $this->produitModel->getFeatured(8);
        $categories       = $this->categorieModel->getWithProductCount();
        $db = Database::getInstance();
        $promotionsActives = $db->fetchAll(
            "SELECT * FROM promotions WHERE actif = 1
             AND (date_debut IS NULL OR date_debut <= CURDATE())
             AND (date_fin IS NULL OR date_fin >= CURDATE())
             AND (usage_max IS NULL OR usage_count < usage_max)
             ORDER BY valeur DESC"
        );
        require_once __DIR__ . '/../../views/home/index.php';
    }

    public function apropos(): void
    {
        require_once __DIR__ . '/../../views/home/apropos.php';
    }

    public function contact(): void
    {
        $success = '';
        $error   = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom     = sanitize($_POST['nom'] ?? '');
            $email   = sanitize($_POST['email'] ?? '');
            $sujet   = sanitize($_POST['sujet'] ?? '');
            $message = sanitize($_POST['message'] ?? '');

            if (empty($nom) || empty($email) || empty($message)) {
                $error = 'Veuillez remplir tous les champs obligatoires.';
            } elseif (!isValidEmail($email)) {
                $error = 'Adresse email invalide.';
            } else {
                // Enregistrer le message en base ou envoyer un email
                $db = Database::getInstance();
                $db->query(
                    "INSERT INTO contacts (nom, email, sujet, message, created_at) VALUES (:nom, :email, :sujet, :message, NOW())",
                    [':nom' => $nom, ':email' => $email, ':sujet' => $sujet, ':message' => $message]
                );
                $success = 'Votre message a été envoyé. Nous vous répondrons dans les plus brefs délais.';
            }
        }

        require_once __DIR__ . '/../../views/home/contact.php';
    }
}
