<?php

class AuthController
{
    private Client $clientModel;

    public function __construct()
    {
        $this->clientModel = new Client();
    }

    public function login(): void
    {
        if (isLoggedIn()) {
            redirect('accueil');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $error = 'Veuillez remplir tous les champs.';
            } elseif (!isValidEmail($email)) {
                $error = 'Adresse email invalide.';
            } else {
                $client = $this->clientModel->verifyPassword($email, $password);

                if ($client) {
                    if (in_array($client['statut'], ['suspendu', 'banni'])) {
                        $error = 'Votre compte est suspendu. Contactez le support.';
                    } else {
                        $_SESSION['client_id'] = $client['id'];
                        $_SESSION['client']    = [
                            'id'        => $client['id'],
                            'nom'       => $client['nom'],
                            'prenom'    => $client['prenom'],
                            'email'     => $client['email'],
                            'type'      => $client['type'],
                            'telephone' => $client['telephone'] ?? '',
                            'adresse'   => $client['adresse'] ?? '',
                            'ville'     => $client['ville'] ?? '',
                            'pays'      => $client['pays'] ?? 'Sénégal',
                            'civilite'  => $client['civilite'] ?? '',
                        ];

                        // "Rester connecté 30 jours"
                        if (!empty($_POST['remember'])) {
                            $token   = bin2hex(random_bytes(32));
                            $expires = date('Y-m-d H:i:s', time() + 30 * 86400);
                            $db = Database::getInstance();
                            $db->query(
                                "UPDATE clients SET remember_token=:t, remember_expires=:e WHERE id=:id",
                                [':t' => $token, ':e' => $expires, ':id' => $client['id']]
                            );
                            setcookie('dsbc_remember', $token, time() + 30 * 86400, '/', '', false, true);
                        }

                        flashMessage('success', 'Bienvenue, ' . $client['prenom'] . ' !');
                        $redirectPage = $_GET['redirect'] ?? 'accueil';
                        redirect($redirectPage);
                    }
                } else {
                    $error = 'Email ou mot de passe incorrect.';
                }
            }
        }

        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function inscription(): void
    {
        if (isLoggedIn()) {
            redirect('accueil');
        }

        $error  = '';
        $success = '';
        $data   = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'            => trim($_POST['nom'] ?? ''),
                'prenom'         => trim($_POST['prenom'] ?? ''),
                'email'          => trim($_POST['email'] ?? ''),
                'telephone'      => trim($_POST['telephone'] ?? ''),
                'adresse'        => trim($_POST['adresse'] ?? ''),
                'ville'          => trim($_POST['ville'] ?? ''),
                'type_client'    => $_POST['type_client'] ?? 'particulier',
                'nom_entreprise' => trim($_POST['nom_entreprise'] ?? ''),
                'ninea'          => trim($_POST['ninea'] ?? ''),
                'password'       => $_POST['password'] ?? '',
                'password_conf'  => $_POST['password_conf'] ?? '',
            ];

            $error = $this->validerInscription($data);

            if (empty($error)) {
                $id = $this->clientModel->create($data);
                if ($id === false) {
                    $error = 'Cet email est déjà utilisé. Veuillez vous connecter.';
                } else {
                    flashMessage('success', 'Compte créé avec succès ! Vous pouvez maintenant vous connecter.');
                    redirect('login');
                }
            }
        }

        require_once __DIR__ . '/../../views/auth/inscription.php';
    }

    public function motDePasseOublie(): void
    {
        if (isLoggedIn()) redirect('accueil');

        $error   = '';
        $success = '';
        $resetLink = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            if (!isValidEmail($email)) {
                $_SESSION['mdp_oublie_error'] = 'Veuillez saisir une adresse email valide.';
            } else {
                $token = $this->clientModel->setResetToken($email);
                if ($token) {
                    $resetLink = BASE_URL . '?page=reinitialiser_mdp&token=' . $token;
                    require_once __DIR__ . '/../Services/EmailService.php';
                    (new EmailService())->envoyerReinitialisationMdp($email, $resetLink);
                    $_SESSION['mdp_oublie_link'] = $resetLink;
                }
                $_SESSION['mdp_oublie_success'] = 'Si cet email existe, un lien de réinitialisation a été envoyé.';
            }
            header('Location: ' . BASE_URL . '?page=mot_de_passe_oublie');
            exit;
        }

        // Récupérer les messages flash depuis la session
        $error     = $_SESSION['mdp_oublie_error'] ?? '';
        $success   = $_SESSION['mdp_oublie_success'] ?? '';
        $resetLink = $_SESSION['mdp_oublie_link'] ?? '';
        unset($_SESSION['mdp_oublie_error'], $_SESSION['mdp_oublie_success'], $_SESSION['mdp_oublie_link']);

        require_once __DIR__ . '/../../views/auth/mot_de_passe_oublie.php';
    }

    public function reinitialiserMdp(): void
    {
        if (isLoggedIn()) redirect('accueil');

        $token  = trim($_GET['token'] ?? '');
        $error  = '';
        $success = '';

        $client = $token ? $this->clientModel->findByResetToken($token) : null;

        if (!$client) {
            $error = 'Ce lien est invalide ou expiré. Veuillez faire une nouvelle demande.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $client) {
            $pwd  = $_POST['password'] ?? '';
            $conf = $_POST['password_conf'] ?? '';

            if (strlen($pwd) < 8) {
                $error = 'Le mot de passe doit contenir au moins 8 caractères.';
            } elseif ($pwd !== $conf) {
                $error = 'Les mots de passe ne correspondent pas.';
            } else {
                $ok = $this->clientModel->resetPassword($token, $pwd);
                if ($ok) {
                    flashMessage('success', 'Mot de passe réinitialisé ! Vous pouvez vous connecter.');
                    redirect('login');
                } else {
                    $error = 'Une erreur est survenue. Veuillez réessayer.';
                }
            }
        }

        require_once __DIR__ . '/../../views/auth/reinitialiser_mdp.php';
    }

    public function logout(): void
    {
        // Effacer le cookie "rester connecté"
        if (!empty($_COOKIE['dsbc_remember'])) {
            $db = Database::getInstance();
            $db->query(
                "UPDATE clients SET remember_token=NULL, remember_expires=NULL WHERE remember_token=:t",
                [':t' => $_COOKIE['dsbc_remember']]
            );
            setcookie('dsbc_remember', '', time() - 86400, '/', '', false, true);
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
        flashMessage('info', 'Vous avez été déconnecté.');
        redirect('accueil');
    }

    public function profil(): void
    {
        requireLogin();
        $client = $this->clientModel->findById($_SESSION['client_id']);
        $db = Database::getInstance();
        $cid = $_SESSION['client_id'];

        $statsCmd = $db->fetch(
            "SELECT COUNT(*) as nb, COALESCE(SUM(total),0) as total_depense,
                    SUM(statut='livree') as nb_livrees,
                    SUM(statut='en_attente') as nb_attente,
                    SUM(statut='annulee') as nb_annulees
             FROM commandes WHERE client_id = :id",
            [':id' => $cid]
        );
        $dernieresCommandes = $db->fetchAll(
            "SELECT id, total, statut, created_at FROM commandes
             WHERE client_id = :id ORDER BY created_at DESC LIMIT 3",
            [':id' => $cid]
        );
        // Récupérer created_at formaté directement depuis la DB pour éviter tout problème de type
        $clientDate = $db->fetch(
            "SELECT DATE_FORMAT(created_at, '%d/%m/%Y') as membre_depuis FROM clients WHERE id = :id",
            [':id' => $cid]
        );
        $membreDepuis = $clientDate['membre_depuis'] ?? '—';
        require_once __DIR__ . '/../../views/auth/profil.php';
    }

    public function modifierProfil(): void
    {
        requireLogin();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'            => trim($_POST['nom'] ?? ''),
                'prenom'         => trim($_POST['prenom'] ?? ''),
                'telephone'      => trim($_POST['telephone'] ?? ''),
                'adresse'        => trim($_POST['adresse'] ?? ''),
                'ville'          => trim($_POST['ville'] ?? ''),
                'nom_entreprise' => trim($_POST['nom_entreprise'] ?? ''),
                'ninea'          => trim($_POST['ninea'] ?? ''),
            ];

            if (!empty($_POST['password'])) {
                if ($_POST['password'] !== $_POST['password_conf']) {
                    $error = 'Les mots de passe ne correspondent pas.';
                } elseif (strlen($_POST['password']) < 8) {
                    $error = 'Le mot de passe doit faire au moins 8 caractères.';
                } else {
                    $data['password'] = $_POST['password'];
                }
            }

            if (empty($error)) {
                $this->clientModel->update($_SESSION['client_id'], $data);
                // Rafraîchir toute la session depuis la DB
                $updated = $this->clientModel->findById($_SESSION['client_id']);
                if ($updated) {
                    $_SESSION['client'] = [
                        'id'        => $updated['id'],
                        'nom'       => $updated['nom'],
                        'prenom'    => $updated['prenom'],
                        'email'     => $updated['email'],
                        'type'      => $updated['type'],
                        'telephone' => $updated['telephone'] ?? '',
                        'adresse'   => $updated['adresse'] ?? '',
                        'ville'     => $updated['ville'] ?? '',
                        'pays'      => $updated['pays'] ?? 'Sénégal',
                        'civilite'  => $updated['civilite'] ?? '',
                    ];
                }
                flashMessage('success', 'Profil mis à jour avec succès.');
                redirect('profil');
            }
        }

        $client = $this->clientModel->findById($_SESSION['client_id']);
        require_once __DIR__ . '/../../views/auth/modifier_profil.php';
    }

    private function validerInscription(array $data): string
    {
        if (empty($data['nom']) || empty($data['prenom'])) {
            return 'Le nom et le prénom sont obligatoires.';
        }
        if (!isValidEmail($data['email'])) {
            return 'Adresse email invalide.';
        }
        if (strlen($data['password']) < 8) {
            return 'Le mot de passe doit faire au moins 8 caractères.';
        }
        if ($data['password'] !== $data['password_conf']) {
            return 'Les mots de passe ne correspondent pas.';
        }
        if ($data['type_client'] === 'professionnel' && empty($data['nom_entreprise'])) {
            return 'Le nom de l\'entreprise est obligatoire pour un compte professionnel.';
        }
        return '';
    }
}
