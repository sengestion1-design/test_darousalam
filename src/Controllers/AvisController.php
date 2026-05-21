<?php

class AvisController
{
    private function getDb(): Database
    {
        return Database::getInstance();
    }

    /**
     * Page publique : liste des avis approuves + formulaire de soumission
     */
    public function index(): void
    {
        $db   = $this->getDb();
        $avis = $db->fetchAll(
            "SELECT * FROM avis WHERE statut = 'approuve' ORDER BY created_at DESC"
        ) ?: [];

        // Token CSRF pour le formulaire public
        if (empty($_SESSION['csrf_avis'])) {
            $_SESSION['csrf_avis'] = bin2hex(random_bytes(32));
        }

        $pageTitle  = 'Avis clients — Darou Salam Business Company';
        $activePage = 'avis';
        require_once __DIR__ . '/../../views/avis/index.php';
    }

    /**
     * Soumission d'un avis (POST, JSON)
     */
    public function soumettre(): void
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Methode non autorisee.']);
            exit;
        }

        // Verification CSRF
        $token = $_POST['csrf_token'] ?? '';
        if (empty($token) || $token !== ($_SESSION['csrf_avis'] ?? '')) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token de securite invalide. Rechargez la page.']);
            exit;
        }
        // Regenerer le token apres usage
        $_SESSION['csrf_avis'] = bin2hex(random_bytes(32));

        $nom         = trim($_POST['nom'] ?? '');
        $note        = (int)($_POST['note'] ?? 0);
        $commentaire = trim($_POST['commentaire'] ?? '');
        $clientId    = $_SESSION['client_id'] ?? null;

        // Validations
        if (empty($nom)) {
            echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire.']);
            exit;
        }
        if ($note < 1 || $note > 5) {
            echo json_encode(['success' => false, 'message' => 'La note doit etre entre 1 et 5 etoiles.']);
            exit;
        }
        if (mb_strlen($commentaire) < 20) {
            echo json_encode(['success' => false, 'message' => 'Le commentaire doit contenir au moins 20 caracteres.']);
            exit;
        }

        try {
            $db = $this->getDb();
            $db->query(
                "INSERT INTO avis (client_id, nom, note, commentaire, statut, created_at)
                 VALUES (:cid, :nom, :note, :commentaire, 'en_attente', NOW())",
                [
                    ':cid'         => $clientId,
                    ':nom'         => htmlspecialchars($nom, ENT_QUOTES, 'UTF-8'),
                    ':note'        => $note,
                    ':commentaire' => htmlspecialchars($commentaire, ENT_QUOTES, 'UTF-8'),
                ]
            );

            echo json_encode([
                'success' => true,
                'message' => 'Merci pour votre avis ! Il sera publie apres verification.',
            ]);
        } catch (Exception $e) {
            error_log('AvisController::soumettre error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur. Veuillez reessayer.']);
        }
        exit;
    }

    /**
     * Charge les N derniers avis approuves (usage interne HomeController)
     */
    public static function getDerniersAvis(int $limit = 3): array
    {
        try {
            $db = Database::getInstance();
            $lim = max(1, (int)$limit);
            return $db->fetchAll(
                "SELECT * FROM avis WHERE statut = 'approuve' ORDER BY created_at DESC LIMIT $lim"
            ) ?: [];
        } catch (Exception $e) {
            return [];
        }
    }
}
