<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Darou Salam Business Company — Fruits Premium au Sénégal' ?></title>
    <meta name="description" content="Darou Salam Business Company — Fournisseur de fruits frais premium à Dakar. Mangues, avocats, oranges livrés aux supermarchés et hôtels du Sénégal.">

    <!-- Open Graph -->
    <meta property="og:title"       content="<?= htmlspecialchars($pageTitle ?? 'Darou Salam Business Company — Fruits Premium au Sénégal') ?>">
    <meta property="og:description" content="Fournisseur de fruits frais au Sénégal. Vente au kilo et au carton pour professionnels et particuliers. Livraison Dakar &amp; sous-région.">
    <meta property="og:type"        content="website">
    <meta property="og:url"         content="<?= htmlspecialchars(BASE_URL . '?page=' . ($activePage ?? 'accueil')) ?>">
    <meta property="og:image"       content="<?= BASE_URL ?>logo.jpg">
    <meta property="og:locale"      content="fr_SN">
    <meta property="og:site_name"   content="<?= defined('APP_NAME') ? htmlspecialchars(APP_NAME) : 'Darou Salam Business Company' ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="<?= htmlspecialchars($pageTitle ?? 'Darou Salam Business Company — Fruits Premium au Sénégal') ?>">
    <meta name="twitter:description" content="Fournisseur de fruits frais au Sénégal. Vente au kilo et au carton pour professionnels et particuliers. Livraison Dakar &amp; sous-région.">

    <!-- JSON-LD LocalBusiness -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "LocalBusiness",
      "name": "Darou Salam Business Company",
      "description": "Fournisseur de fruits frais premium au Sénégal",
      "url": "https://darousalam-business.com",
      "telephone": "+221 77 000 00 00",
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "Rue 47×37",
        "addressLocality": "Dakar Médina",
        "addressCountry": "SN"
      },
      "openingHours": "Mo-Sa 07:00-19:00",
      "priceRange": "FCFA"
    }
    </script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts: Playfair Display + DM Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,300&display=swap" rel="stylesheet">

    <style>
        :root {
            --vert:    #1a5c2a;
            --vert-l:  #236b34;
            --vert-xl: #2d8a42;
            --orange:  #f97316;
            --or:      #d4a017;
            --creme:   #fdf8ee;
            --sombre:  #0f2d16;
            --gris:    #f4f4f0;
            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: #fff;
            color: #1a1a1a;
            padding-top: 110px;
        }
        @media (max-width: 767px) {
            body { padding-top: 70px; overflow-x: hidden; -webkit-overflow-scrolling: touch; }
            #main-nav { top: 0 !important; touch-action: pan-y; }
            .navbar-collapse { touch-action: pan-y; }
            .navbar-toggler { touch-action: manipulation; }
        }

        /* ========= TOP BAR ========= */
        #top-bar {
            background: var(--sombre);
            color: rgba(255,255,255,.78);
            font-size: .78rem;
            letter-spacing: .03em;
            padding: 6px 0;
            border-bottom: 1px solid rgba(212,160,23,.25);
        }
        #top-bar a { color: var(--or); text-decoration: none; }
        #top-bar a:hover { color: #fff; }
        #top-bar .separator { opacity: .35; margin: 0 10px; }

        /* ========= NAVBAR ========= */
        #main-nav {
            background: var(--vert);
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 1050;
            box-shadow: 0 4px 24px rgba(0,0,0,.25);
            transition: all .35s ease;
        }
        #main-nav.scrolled {
            background: rgba(15,45,22,.97);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .navbar-brand img {
            height: 90px;
            width: 90px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--or);
            box-shadow: 0 0 0 4px rgba(212,160,23,.25);
        }
        .brand-text { line-height: 1.2; }
        .brand-name {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.2rem;
            color: #fff;
            display: block;
        }
        .brand-tagline {
            font-size: .72rem;
            color: var(--or);
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        /* Nav links */
        .navbar-nav .nav-link {
            color: rgba(255,255,255,.85) !important;
            font-weight: 500;
            font-size: .88rem;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: 8px 14px !important;
            position: relative;
            transition: color .2s;
        }
        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 2px; left: 14px; right: 14px;
            height: 2px;
            background: var(--orange);
            border-radius: 2px;
            transform: scaleX(0);
            transition: transform .25s ease;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #fff !important;
        }
        .navbar-nav .nav-link:hover::after,
        .navbar-nav .nav-link.active::after { transform: scaleX(1); }

        /* Search bar */
        .nav-search {
            position: relative;
        }
        .nav-search input {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 50px;
            color: #fff;
            font-size: .84rem;
            padding: 6px 36px 6px 14px;
            width: 220px;
            transition: all .3s;
            font-family: var(--font-body);
        }
        .nav-search input::placeholder { color: rgba(255,255,255,.5); }
        .nav-search input:focus {
            outline: none;
            background: rgba(255,255,255,.18);
            border-color: var(--or);
            width: 260px;
        }
        .nav-search .search-btn {
            position: absolute;
            right: 10px; top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255,255,255,.6);
            cursor: pointer;
            padding: 0;
            font-size: .9rem;
        }

        /* Icon buttons */
        .nav-icon-btn {
            position: relative;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 50%;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            font-size: 1.05rem;
            transition: all .2s;
        }
        .nav-icon-btn:hover {
            background: var(--orange);
            border-color: var(--orange);
            color: #fff;
            transform: scale(1.08);
        }
        .cart-badge {
            position: absolute;
            top: -4px; right: -4px;
            background: var(--orange);
            color: #fff;
            font-size: .6rem;
            font-weight: 700;
            border-radius: 50%;
            width: 17px; height: 17px;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid var(--vert);
            transition: transform .2s;
        }
        .cart-badge.bump { animation: cartBump .35s ease; }

        @keyframes cartBump {
            0%   { transform: scale(1); }
            40%  { transform: scale(1.6); }
            70%  { transform: scale(.9); }
            100% { transform: scale(1); }
        }

        /* Hamburger */
        .navbar-toggler {
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 8px;
            padding: 6px 10px;
        }
        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255,255,255,.8)' stroke-width='2' stroke-linecap='round' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        @media (max-width: 991px) {
            .navbar-collapse {
                background: var(--sombre);
                margin-top: 8px;
                border-radius: 12px;
                padding: 12px;
            }
            .nav-search { width: 100%; }
            .nav-search input { width: 100% !important; box-sizing: border-box; }
            .nav-search input:focus { width: 100% !important; }
        }
    </style>
</head>
<body<?php
  $__bodyClasses = [];
  if (isset($_bodyClass)) $__bodyClasses[] = htmlspecialchars($_bodyClass);
  if (isLoggedIn() && (($_GET['page'] ?? 'accueil') === 'accueil')) $__bodyClasses[] = 'has-welcome-banner';
  echo $__bodyClasses ? ' class="' . implode(' ', $__bodyClasses) . '"' : '';
?>>


<!-- TOP BAR -->
<div id="top-bar" class="d-none d-md-block" style="position:fixed;top:0;left:0;right:0;z-index:1060;">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-geo-alt-fill me-1" style="color:var(--or)"></i> Dakar Médina, Rue 47×37
            <span class="separator">|</span>
            <i class="bi bi-telephone-fill me-1" style="color:var(--or)"></i>
            <a href="tel:+221774715353">77 471 53 53</a>
            <span class="separator">|</span>
            <a href="tel:+221701035050">70 103 50 50</a>
        </div>
        <div>
            <i class="bi bi-truck me-1"></i> Livraison Dakar & sous-région
            <span class="separator">|</span>
            <a href="#"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>
        </div>
    </div>
</div>

<!-- MAIN NAV -->
<nav id="main-nav" class="navbar navbar-expand-lg" style="<?= (isset($_topBarVisible) && $_topBarVisible === false) ? '' : 'top:33px;' ?>">
    <div class="container">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>logo.jpg" alt="Darou Salam Business Company">
            <div class="brand-text">
                <span class="brand-name">Darou Salam</span>
                <span class="brand-tagline">Business Company</span>
            </div>
        </a>

        <!-- Panier visible sur mobile à côté du toggler -->
        <a href="<?= BASE_URL ?>?page=panier" class="nav-icon-btn d-lg-none" title="Panier" style="position:relative;margin-left:auto;margin-right:8px;" id="cart-icon-mobile">
            <i class="bi bi-bag"></i>
            <?php
                $panierSessionMobile = $_SESSION['panier_darousalam'] ?? [];
                $nbPanierMobile = array_sum(array_column((array)$panierSessionMobile, 'quantite'));
            ?>
            <span class="cart-badge" id="cart-count-mobile"><?= $nbPanierMobile > 0 ? $nbPanierMobile : '' ?></span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navCollapse">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='home') ? 'active':'' ?>" href="<?= BASE_URL ?>">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='catalogue') ? 'active':'' ?>" href="<?= BASE_URL ?>?page=catalogue">Catalogue</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='apropos') ? 'active':'' ?>" href="<?= BASE_URL ?>?page=apropos">À propos</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='contact') ? 'active':'' ?>" href="<?= BASE_URL ?>?page=contact">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <!-- Search -->
                <form class="nav-search" action="catalogue" method="GET">
                    <input type="search" name="q" placeholder="Rechercher un fruit…" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button type="submit" class="search-btn"><i class="bi bi-search"></i></button>
                </form>

                <!-- Account -->
                <?php if (isLoggedIn()):
                    $client = getClientSession();
                    $prenom = htmlspecialchars($client['prenom'] ?? $client['nom'] ?? 'Mon compte');
                ?>
                <div style="position:relative;" id="accountWrap">
                  <button id="accountToggle"
                    style="display:flex;align-items:center;gap:7px;background:rgba(255,255,255,.12);border:1.5px solid rgba(255,255,255,.3);border-radius:30px;padding:6px 12px 6px 8px;cursor:pointer;font-family:inherit;font-size:.82rem;font-weight:700;color:#fff;transition:all .2s;"
                    onmouseover="this.style.background='rgba(255,255,255,.22)';this.style.borderColor='rgba(255,255,255,.6)'" onmouseout="this.style.background='rgba(255,255,255,.12)';this.style.borderColor='rgba(255,255,255,.3)'">
                    <span style="width:28px;height:28px;border-radius:50%;background:var(--orange);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.72rem;font-weight:800;flex-shrink:0;">
                      <?= strtoupper(substr($client['prenom'] ?? $client['nom'] ?? 'U', 0, 1)) ?>
                    </span>
                    <?= $prenom ?>
                    <i class="bi bi-chevron-down" style="font-size:.6rem;color:rgba(255,255,255,.7);"></i>
                  </button>
                  <div id="accountMenu" style="display:none;position:absolute;top:46px;right:0;background:#fff;border:1px solid #e5e7eb;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,.12);min-width:180px;z-index:999;overflow:hidden;">
                    <a href="<?= BASE_URL ?>?page=profil" style="display:flex;align-items:center;gap:8px;padding:11px 16px;font-size:.83rem;color:#374151;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                      <i class="bi bi-person-circle" style="color:var(--vert);"></i> Mon profil
                    </a>
                    <a href="<?= BASE_URL ?>?page=historique" style="display:flex;align-items:center;gap:8px;padding:11px 16px;font-size:.83rem;color:#374151;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='#fff'">
                      <i class="bi bi-clock-history" style="color:var(--vert);"></i> Mes commandes
                    </a>
                    <div style="height:1px;background:#f3f4f6;margin:4px 0;"></div>
                    <a href="<?= BASE_URL ?>?page=logout" style="display:flex;align-items:center;gap:8px;padding:11px 16px;font-size:.83rem;color:#dc2626;text-decoration:none;transition:background .15s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='#fff'">
                      <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                  </div>
                </div>
                <?php else: ?>
                <a href="<?= BASE_URL ?>?page=login"
                   style="display:flex;align-items:center;gap:6px;background:rgba(255,255,255,.1);border:1.5px solid rgba(255,255,255,.3);border-radius:30px;padding:6px 14px;color:#fff;font-size:.82rem;font-weight:600;text-decoration:none;transition:all .2s;white-space:nowrap;"
                   onmouseover="this.style.background='rgba(255,255,255,.22)';this.style.borderColor='rgba(255,255,255,.6)'" onmouseout="this.style.background='rgba(255,255,255,.1)';this.style.borderColor='rgba(255,255,255,.3)'">
                    <i class="bi bi-box-arrow-in-right" style="font-size:.88rem;"></i> Se connecter
                </a>
                <a href="<?= BASE_URL ?>?page=inscription"
                   style="display:flex;align-items:center;gap:6px;background:var(--orange);border:1.5px solid var(--orange);border-radius:30px;padding:6px 14px;color:#fff;font-size:.82rem;font-weight:700;text-decoration:none;transition:all .2s;white-space:nowrap;"
                   onmouseover="this.style.background='#ea6a0a';this.style.borderColor='#ea6a0a'" onmouseout="this.style.background='var(--orange)';this.style.borderColor='var(--orange)'">
                    <i class="bi bi-person-plus-fill" style="font-size:.88rem;"></i> Créer un compte
                </a>
                <?php endif; ?>

                <!-- Cart -->
                <a href="panier" class="nav-icon-btn" title="Panier" id="cart-icon">
                    <i class="bi bi-bag"></i>
                    <?php
                        $panierSession = $_SESSION['panier_darousalam'] ?? [];
                        $nbPanier = array_sum(array_column((array)$panierSession, 'quantite'));
                    ?>
                    <span class="cart-badge" id="cart-count"><?= $nbPanier > 0 ? $nbPanier : '' ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

<?php if (isLoggedIn() && (($_GET['page'] ?? 'accueil') === 'accueil')):
    $__c = getClientSession();
    $__prenom = htmlspecialchars($__c['prenom'] ?? $__c['nom'] ?? '');
?>
<div id="welcome-banner" style="background:linear-gradient(90deg,#0f2d16 0%,#1a5c2a 60%,#2d8a42 100%);color:#fff;padding:10px 0;border-bottom:2px solid #d4a017;position:fixed;top:0;left:0;right:0;z-index:1055;visibility:hidden;">
  <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
    <div class="d-flex align-items-center gap-3">
      <span style="width:36px;height:36px;border-radius:50%;background:#d4a017;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.95rem;color:#0f2d16;flex-shrink:0;">
        <?= strtoupper(substr($__prenom ?: 'U', 0, 1)) ?>
      </span>
      <div>
        <div style="font-size:.88rem;font-weight:700;">Bienvenue, <?= $__prenom ?> 👋</div>
        <div style="font-size:.75rem;color:rgba(255,255,255,.75);">Vous êtes dans votre espace — passez votre commande dès maintenant.</div>
      </div>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="<?= BASE_URL ?>?page=catalogue" style="background:#d4a017;color:#0f2d16;font-weight:700;font-size:.8rem;padding:7px 18px;border-radius:50px;text-decoration:none;white-space:nowrap;transition:background .2s;" onmouseover="this.style.background='#e6b800'" onmouseout="this.style.background='#d4a017'">
        <i class="bi bi-bag-fill me-1"></i> Commander
      </a>
      <button id="welcome-close" style="background:none;border:none;color:rgba(255,255,255,.5);font-size:1.1rem;cursor:pointer;padding:0 4px;line-height:1;" title="Fermer">×</button>
    </div>
  </div>
</div>
<script>
(function() {
    function positionBanner() {
        var banner = document.getElementById('welcome-banner');
        var nav    = document.getElementById('main-nav');
        var topBar = document.getElementById('top-bar');
        if (!banner || !nav) return;
        var navBottom = nav.getBoundingClientRect().bottom + window.scrollY;
        // top fixe = hauteur topbar + hauteur navbar
        var topBarH = topBar ? topBar.offsetHeight : 0;
        var navH    = nav.offsetHeight;
        var total   = topBarH + navH;
        banner.style.top = total + 'px';
        banner.style.visibility = 'visible';
        // Ajuster padding-top du body
        document.body.style.paddingTop = (total + banner.offsetHeight) + 'px';
    }
    document.addEventListener('DOMContentLoaded', positionBanner);
    window.addEventListener('resize', positionBanner);
    document.getElementById('welcome-close').addEventListener('click', function() {
        var banner = document.getElementById('welcome-banner');
        banner.style.display = 'none';
        // Restaurer le padding-top sans la bannière
        var nav    = document.getElementById('main-nav');
        var topBar = document.getElementById('top-bar');
        var topBarH = topBar ? topBar.offsetHeight : 0;
        var navH    = nav ? nav.offsetHeight : 0;
        document.body.style.paddingTop = (topBarH + navH) + 'px';
    });
})();
</script>
<?php endif; ?>

<?php
$_promosActives = [];
try {
    $_db = Database::getInstance();
    $_promosActives = $_db->fetchAll(
        "SELECT * FROM promotions WHERE actif = 1
         AND (date_debut IS NULL OR date_debut <= CURDATE())
         AND (date_fin IS NULL OR date_fin >= CURDATE())
         AND (usage_max IS NULL OR usage_count < usage_max)
         ORDER BY valeur DESC LIMIT 3"
    );
} catch (Exception $e) {}
if (!empty($_promosActives)): ?>
<style>
#promo-banner {
    background: linear-gradient(90deg, #d4a017 0%, #b8880e 40%, #1a5c2a 100%);
    color: #fff;
    padding: 11px 0;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.12), 0 3px 10px rgba(0,0,0,.18);
}
.promo-copy-badge {
    background: rgba(255,255,255,.15);
    border: 2px solid rgba(249,115,22,.9);
    border-radius: 8px;
    padding: 4px 14px;
    font-family: monospace;
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: 3px;
    cursor: pointer;
    user-select: all;
    color: #fff;
    transition: background .2s, border-color .2s;
}
.promo-copy-badge:hover { background: rgba(249,115,22,.3); border-color: #f97316; }
.promo-close-btn {
    background: none;
    border: none;
    color: #fff;
    font-size: 1.4rem;
    opacity: .75;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    min-width: 44px;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: background .2s, opacity .2s;
}
.promo-close-btn:hover { background: rgba(0,0,0,.15); opacity: 1; }
</style>
<div id="promo-banner">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-3 flex-wrap">
                <i class="bi bi-star-fill" style="font-size:1rem;color:#fdf8ee;opacity:.9;"></i>
                <?php foreach ($_promosActives as $_promo): ?>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <strong style="font-size:1.15rem;font-family:'Playfair Display',serif;letter-spacing:.3px;">
                        <?php if ($_promo['type'] === 'pourcentage'): ?>
                            -<?= (int)$_promo['valeur'] ?>%
                        <?php else: ?>
                            -<?= number_format((float)$_promo['valeur'], 0, ',', ' ') ?> FCFA
                        <?php endif; ?>
                    </strong>
                    <span style="font-size:.85rem;opacity:.85;">sur votre commande — code&nbsp;:</span>
                    <span class="promo-copy-badge" data-code="<?= htmlspecialchars($_promo['code']) ?>"
                        title="Cliquer pour copier"><?= htmlspecialchars($_promo['code']) ?></span>
                    <?php if (!empty($_promo['date_fin'])): ?>
                    <span style="font-size:.78rem;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.4);border-radius:20px;padding:3px 11px;font-weight:600;letter-spacing:.2px;">
                        <i class="bi bi-clock-fill me-1"></i>Expire le <?= date('d/m/Y', strtotime((string)$_promo['date_fin'])) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="promo-close-btn" title="Fermer" id="promo-close-btn">&times;</button>
        </div>
    </div>
</div>
<script>
document.getElementById('promo-close-btn').addEventListener('click', function() {
    var banner = document.getElementById('promo-banner');
    var h = banner.offsetHeight;
    banner.style.display = 'none';
    document.body.style.paddingTop = (parseInt(getComputedStyle(document.body).paddingTop || 0) - h) + 'px';
});
document.querySelectorAll('.promo-copy-badge').forEach(function(el) {
    el.addEventListener('click', function() {
        var code = el.getAttribute('data-code');
        navigator.clipboard.writeText(code).then(function() {
            var orig = el.textContent;
            el.textContent = 'Copié !';
            el.style.background = 'rgba(249,115,22,.45)';
            setTimeout(function(){ el.textContent = orig; el.style.background = 'rgba(255,255,255,.15)'; }, 1800);
        });
    });
});
(function() {
    var banner = document.getElementById('promo-banner');
    if (banner) {
        document.body.style.paddingTop = (parseInt(getComputedStyle(document.body).paddingTop || 0) + banner.offsetHeight) + 'px';
    }
})();
</script>
<?php endif; ?>

<script>
// Account dropdown
document.addEventListener('DOMContentLoaded', function() {
  var toggle = document.getElementById('accountToggle');
  if (toggle) {
    toggle.addEventListener('click', function(e) {
      e.stopPropagation();
      var m = document.getElementById('accountMenu');
      m.style.display = m.style.display === 'block' ? 'none' : 'block';
    });
    document.addEventListener('click', function(e) {
      var wrap = document.getElementById('accountWrap');
      var menu = document.getElementById('accountMenu');
      if (wrap && !wrap.contains(e.target)) menu.style.display = 'none';
    });
  }
});

// Scroll shrink
const nav = document.getElementById('main-nav');
window.addEventListener('scroll', () => {
    if (window.scrollY > 60) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
    // adjust top for topbar
    if (window.innerWidth >= 768) {
        nav.style.top = window.scrollY > 60 ? '0' : '33px';
    }
}, { passive: true });

function addToCart(id, name, price, img) {
    // Soumettre via formulaire POST pour synchroniser avec la session PHP
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '?page=panier_ajouter';
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'produit_id'; inp.value = id;
    const qty = document.createElement('input');
    qty.type = 'hidden'; qty.name = 'quantite'; qty.value = '1';
    form.appendChild(inp); form.appendChild(qty);
    document.body.appendChild(form);
    form.submit();
}

/* Son "pop" panier via Web Audio API — contexte unique réutilisé */
(function() {
    var _ctx = null;
    function getCtx() {
        if (!_ctx) _ctx = new (window.AudioContext || window.webkitAudioContext)();
        if (_ctx.state === 'suspended') _ctx.resume();
        return _ctx;
    }
    window.playCartSound = function() {
        try {
            var ctx = getCtx();
            var t = ctx.currentTime;
            var o1 = ctx.createOscillator(), g1 = ctx.createGain();
            o1.connect(g1); g1.connect(ctx.destination);
            o1.type = 'sine';
            o1.frequency.setValueAtTime(520, t);
            o1.frequency.exponentialRampToValueAtTime(680, t + 0.08);
            g1.gain.setValueAtTime(0.18, t);
            g1.gain.exponentialRampToValueAtTime(0.001, t + 0.18);
            o1.start(t); o1.stop(t + 0.18);
            var o2 = ctx.createOscillator(), g2 = ctx.createGain();
            o2.connect(g2); g2.connect(ctx.destination);
            o2.type = 'sine';
            o2.frequency.setValueAtTime(880, t + 0.07);
            o2.frequency.exponentialRampToValueAtTime(1100, t + 0.18);
            g2.gain.setValueAtTime(0.10, t + 0.07);
            g2.gain.exponentialRampToValueAtTime(0.001, t + 0.32);
            o2.start(t + 0.07); o2.stop(t + 0.32);
        } catch(e) {}
    };
})();

/* Synchronisation badge panier desktop ↔ mobile */
(function() {
    var desktopBadge = document.getElementById('cart-count');
    var mobileBadge  = document.getElementById('cart-count-mobile');
    if (!desktopBadge || !mobileBadge) return;
    var obs = new MutationObserver(function() {
        mobileBadge.textContent = desktopBadge.textContent;
        mobileBadge.style.animation = 'none';
        void mobileBadge.offsetWidth;
        mobileBadge.style.animation = desktopBadge.style.animation;
    });
    obs.observe(desktopBadge, { childList: true, characterData: true, subtree: true });
})();
</script>

<?php if (!isset($_SESSION['client_id'])): ?>
<!-- ===== POPUP CODE PROMO BIENVENUE ===== -->
<style>
#dsbc-promo-overlay {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 10000;
    background: rgba(0,0,0,.62);
    backdrop-filter: blur(3px);
    -webkit-backdrop-filter: blur(3px);
    align-items: center;
    justify-content: center;
}
#dsbc-promo-overlay.show { display: flex; animation: dsbcFadeIn .3s ease forwards; }
@keyframes dsbcFadeIn { from { opacity:0; } to { opacity:1; } }
#dsbc-promo-modal {
    background: #fff;
    border: 2px solid #1a5c2a;
    border-radius: 20px;
    max-width: 480px;
    width: calc(100% - 32px);
    box-shadow: 0 24px 64px rgba(0,0,0,.32);
    overflow: hidden;
    transform: scale(.8);
    opacity: 0;
    animation: dsbcModalIn .3s ease .08s forwards;
}
@keyframes dsbcModalIn { from { transform:scale(.8);opacity:0; } to { transform:scale(1);opacity:1; } }
#dsbc-promo-header {
    background: linear-gradient(135deg,#0f2d16 0%,#1a5c2a 60%,#2d8a42 100%);
    color: #fff;
    padding: 22px 24px 18px;
    position: relative;
    text-align: center;
}
#dsbc-promo-close {
    position: absolute; top: 12px; right: 14px;
    background: rgba(255,255,255,.12); border: none; color: rgba(255,255,255,.8);
    font-size: 1.3rem; width: 30px; height: 30px; border-radius: 50%;
    cursor: pointer; display: flex; align-items: center; justify-content: center;
    line-height: 1; transition: background .2s;
}
#dsbc-promo-close:hover { background: rgba(255,255,255,.28); color: #fff; }
.dsbc-badge-discount {
    display: inline-block;
    background: #d4a017; color: #0f2d16;
    font-size: 2.8rem; font-weight: 900;
    font-family: 'Playfair Display', Georgia, serif;
    border-radius: 16px; padding: 8px 28px; margin: 12px 0 6px;
    box-shadow: 0 4px 18px rgba(212,160,23,.45); letter-spacing: 1px;
}
#dsbc-promo-body { padding: 22px 28px 26px; text-align: center; }
.dsbc-code-wrap {
    display: flex; align-items: center; justify-content: center;
    gap: 10px; margin: 16px 0;
}
.dsbc-code-box {
    background: #f4f4f0; border: 2px dashed #1a5c2a; border-radius: 10px;
    font-family: monospace; font-size: 1.35rem; font-weight: 800;
    letter-spacing: 4px; color: #1a5c2a; padding: 10px 20px;
}
.dsbc-btn-copy {
    background: #1a5c2a; color: #fff; border: none; border-radius: 8px;
    padding: 10px 16px; font-size: .82rem; font-weight: 700; cursor: pointer;
    font-family: inherit; transition: background .2s; white-space: nowrap;
}
.dsbc-btn-copy:hover { background: #236b34; }
.dsbc-btn-use {
    display: block; width: 100%; background: #d4a017; color: #0f2d16;
    border: none; border-radius: 50px; padding: 13px 0;
    font-size: 1rem; font-weight: 800; cursor: pointer; font-family: inherit;
    letter-spacing: .5px; text-decoration: none;
    transition: background .2s, transform .15s; margin-top: 16px;
    text-align: center;
}
.dsbc-btn-use:hover { background: #e6b800; transform: scale(1.02); color: #0f2d16; }
.dsbc-validity { font-size: .78rem; color: #6b7280; margin-top: 12px; }
</style>

<div id="dsbc-promo-overlay" role="dialog" aria-modal="true" aria-label="Offre de bienvenue">
    <div id="dsbc-promo-modal">
        <div id="dsbc-promo-header">
            <button id="dsbc-promo-close" aria-label="Fermer">&times;</button>
            <i class="bi bi-gift-fill" style="font-size:1.8rem;color:#d4a017;"></i>
            <div style="font-family:'Playfair Display',Georgia,serif;font-size:1.3rem;font-weight:700;margin-top:6px;">Offre de bienvenue</div>
            <div class="dsbc-badge-discount">-10%</div>
            <div style="font-size:.85rem;color:rgba(255,255,255,.82);">Sur votre premi&egrave;re commande</div>
        </div>
        <div id="dsbc-promo-body">
            <div style="font-size:.95rem;color:#374151;font-weight:600;">Utilisez ce code &agrave; la commande&nbsp;:</div>
            <div class="dsbc-code-wrap">
                <div class="dsbc-code-box" id="dsbc-code-text">BIENVENUE10</div>
                <button class="dsbc-btn-copy" id="dsbc-copy-btn">
                    <i class="bi bi-clipboard me-1"></i>Copier
                </button>
            </div>
            <a href="<?= BASE_URL ?>?page=catalogue" class="dsbc-btn-use">
                <i class="bi bi-bag-fill me-1"></i> Utiliser maintenant
            </a>
            <div class="dsbc-validity"><i class="bi bi-clock me-1"></i>Valable 7 jours &mdash; offre r&eacute;serv&eacute;e aux nouveaux visiteurs</div>
        </div>
    </div>
</div>

<script>
(function() {
    function getCookie(name) {
        var v = document.cookie.match('(?:^|; )' + name + '=([^;]*)');
        return v ? decodeURIComponent(v[1]) : null;
    }
    function setCookie(name, value, days) {
        var d = new Date();
        d.setTime(d.getTime() + days * 864e5);
        document.cookie = name + '=' + encodeURIComponent(value) + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
    }
    function closePopup() {
        var overlay = document.getElementById('dsbc-promo-overlay');
        if (!overlay) return;
        overlay.style.transition = 'opacity .25s';
        overlay.style.opacity = '0';
        setTimeout(function() { overlay.style.display = 'none'; overlay.style.opacity = ''; }, 260);
    }
    function copyCode() {
        var btn = document.getElementById('dsbc-copy-btn');
        var fallback = function() {
            var el = document.getElementById('dsbc-code-text');
            var range = document.createRange();
            range.selectNodeContents(el);
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
            document.execCommand('copy');
            sel.removeAllRanges();
            btn.textContent = 'Copié ✓';
            setTimeout(function() { btn.textContent = 'Copier'; }, 2000);
        };
        if (navigator.clipboard) {
            navigator.clipboard.writeText('BIENVENUE10').then(function() {
                btn.textContent = 'Copié ✓';
                btn.style.background = '#2d8a42';
                setTimeout(function() { btn.textContent = 'Copier'; btn.style.background = ''; }, 2000);
            }).catch(fallback);
        } else { fallback(); }
    }
    document.addEventListener('DOMContentLoaded', function() {
        if (!getCookie('dsbc_first_visit')) {
            var overlay = document.getElementById('dsbc-promo-overlay');
            if (overlay) {
                setCookie('dsbc_first_visit', '1', 30);
                overlay.classList.add('show');
            }
        }
        var cl = document.getElementById('dsbc-promo-close');
        if (cl) cl.addEventListener('click', closePopup);
        var ov = document.getElementById('dsbc-promo-overlay');
        if (ov) ov.addEventListener('click', function(e) { if (e.target === ov) closePopup(); });
        var cp = document.getElementById('dsbc-copy-btn');
        if (cp) cp.addEventListener('click', copyCode);
    });
})();
</script>
<?php endif; ?>
<!-- ===== FIN POPUP CODE PROMO ===== -->
