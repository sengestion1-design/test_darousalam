<?php

declare(strict_types=1);

// --- Headers de sécurité HTTP ---
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://www.google.com https://www.gstatic.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net; img-src 'self' data: https:; frame-src https://www.google.com https://www.google.com/maps/; connect-src 'self';");
header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=()');

// --- Session sécurisée ---
ini_set('session.cookie_httponly', '1');
ini_set('session.use_strict_mode', '1');
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.cookie_secure', '1');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- Chargement de la configuration ---
require_once __DIR__ . '/config/database.php';

// --- Chargement des helpers ---
require_once __DIR__ . '/src/helpers.php';

// --- Auto-login via cookie "Rester connecté" ---
if (empty($_SESSION['client_id']) && !empty($_COOKIE['dsbc_remember'])) {
    try {
        $db = Database::getInstance();
        $row = $db->fetch(
            "SELECT * FROM clients WHERE remember_token=:t AND remember_expires > NOW() AND statut='actif' LIMIT 1",
            [':t' => $_COOKIE['dsbc_remember']]
        );
        if ($row) {
            $_SESSION['client_id'] = $row['id'];
            $_SESSION['client']    = [
                'id'        => $row['id'],
                'nom'       => $row['nom'],
                'prenom'    => $row['prenom'],
                'email'     => $row['email'],
                'type'      => $row['type_client'] ?? '',
                'telephone' => $row['telephone'] ?? '',
                'adresse'   => $row['adresse'] ?? '',
                'ville'     => $row['ville'] ?? '',
                'pays'      => $row['pays'] ?? 'Sénégal',
                'civilite'  => $row['civilite'] ?? '',
            ];
            // Renouveler le cookie pour 30 jours supplémentaires
            $newExpires = date('Y-m-d H:i:s', time() + 30 * 86400);
            $db->query(
                "UPDATE clients SET remember_expires=:e WHERE id=:id",
                [':e' => $newExpires, ':id' => $row['id']]
            );
            setcookie('dsbc_remember', $_COOKIE['dsbc_remember'], time() + 30 * 86400, '/', '', false, true);
        } else {
            setcookie('dsbc_remember', '', time() - 86400, '/', '', false, true);
        }
    } catch (Exception $e) {}
}

// --- Autoloading des Models et Controllers ---
spl_autoload_register(function (string $class): void {
    $dirs = [
        __DIR__ . '/src/Models/',
        __DIR__ . '/src/Controllers/',
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// --- Routeur ---
$page = $_GET['page'] ?? 'accueil';
$page = preg_replace('/[^a-zA-Z0-9_\-]/', '', $page);

$routes = [
    // Publiques
    'accueil'               => ['HomeController',      'index'],
    'apropos'               => ['HomeController',      'apropos'],
    'contact'               => ['HomeController',      'contact'],
    'catalogue'             => ['CatalogueController', 'index'],
    'categorie'             => ['CatalogueController', 'categorie'],
    'produit'               => ['CatalogueController', 'produit'],
    'recherche'             => ['CatalogueController', 'recherche'],

    // Panier
    'panier'                => ['PanierController',    'index'],
    'panier_ajouter'        => ['PanierController',    'ajouter'],
    'panier_retirer'        => ['PanierController',    'retirer'],
    'panier_modifier'       => ['PanierController',    'modifier'],
    'panier_vider'          => ['PanierController',    'vider'],

    // Auth
    'login'                 => ['AuthController',      'login'],
    'inscription'           => ['AuthController',      'inscription'],
    'logout'                => ['AuthController',      'logout'],
    'profil'                => ['AuthController',      'profil'],
    'modifier_profil'       => ['AuthController',      'modifierProfil'],
    'mot_de_passe_oublie'   => ['AuthController',      'motDePasseOublie'],
    'reinitialiser_mdp'     => ['AuthController',      'reinitialiserMdp'],

    // Client (espace personnel)
    'compte'                => ['ClientController',    'dashboard'],
    'dashboard'             => ['ClientController',    'dashboard'],
    'adresses'              => ['ClientController',    'adresses'],
    'favoris'               => ['ClientController',    'favoris'],
    'ajouter_favori'        => ['ClientController',    'ajouterFavori'],
    'retirer_favori'        => ['ClientController',    'retirerFavori'],

    // Admin
    'admin_login'     => ['AdminController', 'login'],
    'admin_logout'    => ['AdminController', 'logout'],
    'admin_dashboard' => ['AdminController', 'dashboard'],
    // alias courts pour contourner les filtres InfinityFree
    'adm_cmd'         => ['AdminController', 'commandes'],
    'adm_cmd_chk'     => ['AdminController', 'checkNouvellesCommandes'],
    'adm_cmd_det'     => ['AdminController', 'commandeDetail'],
    'adm_cmd_sta'     => ['AdminController', 'changerStatutCommande'],
    'adm_cmd_pai'     => ['AdminController', 'changerStatutPaiement'],
    'adm_prd'         => ['AdminController', 'produits'],
    'adm_clt'         => ['AdminController', 'clients'],
    'adm_stk'         => ['AdminController', 'stocks'],
    'adm_stk_mvt'     => ['AdminController', 'stockMouvements'],
    'adm_stk_add'     => ['AdminController', 'ajouterMouvement'],
    'adm_prd_add'     => ['AdminController', 'ajouterProduit'],
    'adm_prd_edt'     => ['AdminController', 'modifierProduit'],
    'adm_prd_del'     => ['AdminController', 'supprimerProduit'],
    'adm_clt_add'     => ['AdminController', 'ajouterClient'],
    'adm_clt_del'     => ['AdminController', 'supprimerClient'],
    'adm_clt_exp'     => ['AdminController', 'exportClients'],
    'adm_cat'         => ['AdminController', 'categories'],
    'adm_cat_add'     => ['AdminController', 'ajouterCategorie'],
    'adm_cat_edt'     => ['AdminController', 'modifierCategorie'],
    'adm_cat_del'     => ['AdminController', 'supprimerCategorie'],
    'adm_msg'         => ['AdminController', 'contacts'],
    'adm_msg_rep'     => ['AdminController', 'repondreContact'],
    'adm_cmd_exp'     => ['AdminController', 'exportCommandes'],
    'adm_cfg'         => ['AdminController', 'settings'],
    'adm_cfg_save'    => ['AdminController', 'saveSettings'],
    'adm_cfg_mail'    => ['AdminController', 'testEmail'],
    'adm_qr_save'     => ['AdminController', 'saveQrCodes'],
    'adm_usr'         => ['AdminController', 'admins'],
    'adm_usr_add'     => ['AdminController', 'ajouterAdmin'],
    'adm_usr_edt'     => ['AdminController', 'modifierAdmin'],
    'adm_usr_tog'     => ['AdminController', 'toggleAdmin'],
    'adm_rpt'         => ['AdminController', 'rapports'],
    'adm_zone'        => ['AdminController', 'zonesLivraison'],
    'adm_zone_add'    => ['AdminController', 'ajouterZone'],
    'adm_zone_edt'    => ['AdminController', 'modifierZone'],
    'adm_zone_del'    => ['AdminController', 'supprimerZone'],
    'adm_promo'       => ['AdminController', 'promotions'],
    'adm_promo_add'   => ['AdminController', 'ajouterPromotion'],
    'adm_promo_edt'   => ['AdminController', 'modifierPromotion'],
    'adm_promo_del'   => ['AdminController', 'supprimerPromotion'],
    'adm_ship'        => ['AdminController', 'livraisons'],
    'adm_drv'         => ['AdminController', 'livreurs'],
    'adm_drv_det'     => ['AdminController', 'livreurDetail'],
    'adm_drv_add'     => ['AdminController', 'ajouterLivreur'],
    'adm_drv_edt'     => ['AdminController', 'modifierLivreur'],
    'adm_drv_del'     => ['AdminController', 'supprimerLivreur'],
    'adm_ship_asgn'   => ['AdminController', 'assignerLivreur'],
    'adm_ship_sta'    => ['AdminController', 'changerStatutLivraison'],
    'adm_pdf'         => ['AdminController', 'telechargerFacture'],
    // anciens noms conservés
    'admin_commandes'         => ['AdminController', 'commandes'],
    'admin_check_commandes'   => ['AdminController', 'checkNouvellesCommandes'],
    'admin_commande_detail'   => ['AdminController', 'commandeDetail'],
    'admin_commande_statut'   => ['AdminController', 'changerStatutCommande'],
    'admin_commande_paiement' => ['AdminController', 'commandeDetail'],
    'admin_produits'  => ['AdminController', 'produits'],
    'admin_clients'   => ['AdminController', 'clients'],
    'admin_stocks'            => ['AdminController', 'stocks'],
    'admin_stock_mouvements'  => ['AdminController', 'stockMouvements'],
    'admin_stock_mouvement_ajouter' => ['AdminController', 'ajouterMouvement'],
    'admin_produit_ajouter'    => ['AdminController', 'ajouterProduit'],
    'admin_produit_modifier'   => ['AdminController', 'modifierProduit'],
    'admin_produit_supprimer'  => ['AdminController', 'supprimerProduit'],
    'admin_client_ajouter'     => ['AdminController', 'ajouterClient'],
    'admin_client_supprimer'   => ['AdminController', 'supprimerClient'],
    'admin_categories'         => ['AdminController', 'categories'],
    'admin_categorie_ajouter'  => ['AdminController', 'ajouterCategorie'],
    'admin_categorie_modifier' => ['AdminController', 'modifierCategorie'],
    'admin_categorie_supprimer'=> ['AdminController', 'supprimerCategorie'],
    'admin_contacts'           => ['AdminController', 'contacts'],
    'admin_contact_repondre'   => ['AdminController', 'repondreContact'],
    'admin_export_commandes'   => ['AdminController', 'exportCommandes'],
    'admin_settings'           => ['AdminController', 'settings'],
    'admin_settings_save'      => ['AdminController', 'saveSettings'],
    'admin_settings_test_email'=> ['AdminController', 'testEmail'],
    'admin_admins'             => ['AdminController', 'admins'],
    'admin_admin_ajouter'      => ['AdminController', 'ajouterAdmin'],
    'admin_admin_modifier'     => ['AdminController', 'modifierAdmin'],
    'admin_admin_toggle'       => ['AdminController', 'toggleAdmin'],
    'admin_rapports'                => ['AdminController', 'rapports'],
    'admin_zones_livraison'         => ['AdminController', 'zonesLivraison'],
    'admin_zone_ajouter'            => ['AdminController', 'ajouterZone'],
    'admin_zone_modifier'           => ['AdminController', 'modifierZone'],
    'admin_zone_supprimer'          => ['AdminController', 'supprimerZone'],
    'admin_promotions'              => ['AdminController', 'promotions'],
    'admin_promotion_ajouter'       => ['AdminController', 'ajouterPromotion'],
    'admin_promotion_modifier'      => ['AdminController', 'modifierPromotion'],
    'admin_promotion_supprimer'     => ['AdminController', 'supprimerPromotion'],
    'admin_livraisons'              => ['AdminController', 'livraisons'],
    'admin_livreurs'                => ['AdminController', 'livreurs'],
    'admin_livreur_detail'          => ['AdminController', 'livreurDetail'],
    'admin_livreur_ajouter'         => ['AdminController', 'ajouterLivreur'],
    'admin_livreur_modifier'        => ['AdminController', 'modifierLivreur'],
    'admin_livreur_supprimer'       => ['AdminController', 'supprimerLivreur'],
    'admin_livraison_assigner'      => ['AdminController', 'assignerLivreur'],
    'admin_livraison_statut'        => ['AdminController', 'changerStatutLivraison'],
    'admin_facture_pdf'             => ['AdminController', 'telechargerFacture'],

    // Newsletter
    'newsletter_subscribe'  => ['NewsletterController', 'subscribe'],

    // Commandes
    'checkout'              => ['CommandeController',  'checkout'],
    'valider_code_promo'    => ['CommandeController',  'validerCodePromo'],
    'commande_confirmation' => ['CommandeController',  'confirmation'],
    'historique'            => ['CommandeController',  'historique'],
    'commande_detail'       => ['CommandeController',  'detail'],
    'commande_annuler'      => ['CommandeController',  'annuler'],
    'commande_facture'      => ['CommandeController',  'telechargerFacture'],
    'api_commande_statut'   => ['CommandeController',  'apiStatut'],
];

// --- Routes directes : pages statiques ---
if ($page === 'tarifs_pro') {
    require_once __DIR__ . '/views/tarifs_pro.php';
    exit;
}
if ($page === 'mentions_legales') {
    require_once __DIR__ . '/views/mentions_legales.php';
    exit;
}
if ($page === 'cgv') {
    require_once __DIR__ . '/views/cgv.php';
    exit;
}
if ($page === 'faq') {
    require_once __DIR__ . '/views/faq.php';
    exit;
}

if (isset($routes[$page])) {
    [$controllerClass, $method] = $routes[$page];

    if (class_exists($controllerClass)) {
        $controller = new $controllerClass();
        if (method_exists($controller, $method)) {
            $controller->$method();
        } else {
            http_response_code(500);
            die('Méthode introuvable : ' . htmlspecialchars($method));
        }
    } else {
        http_response_code(500);
        die('Controller introuvable : ' . htmlspecialchars($controllerClass));
    }
} else {
    http_response_code(404);
    // Afficher une page 404 si elle existe
    $page404 = __DIR__ . '/views/errors/404.php';
    if (file_exists($page404)) {
        require_once $page404;
    } else {
        echo '<h1>404 - Page introuvable</h1>';
        echo '<p><a href="' . BASE_URL . '">Retour à l\'accueil</a></p>';
    }
}
