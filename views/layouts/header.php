<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Darou Salam Business Company — Fruits Premium au Sénégal' ?></title>
    <meta name="description" content="Darou Salam Business Company — Fournisseur de fruits frais premium à Dakar. Mangues, avocats, oranges livrés aux supermarchés et hôtels du Sénégal.">

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
            padding-top: 110px; /* topbar 33px + navbar ~77px sur desktop */
        }
        @media (max-width: 767px) {
            body { padding-top: 64px; } /* mobile: sans topbar */
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
<body<?= isset($_bodyClass) ? ' class="'.htmlspecialchars($_bodyClass).'"' : '' ?>>


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
        <a class="navbar-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Darou Salam Business Company">
            <div class="brand-text">
                <span class="brand-name">Darou Salam</span>
                <span class="brand-tagline">Business Company</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navCollapse">
            <ul class="navbar-nav mx-auto gap-1">
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='home') ? 'active':'' ?>" href="/darousalam/">Accueil</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='catalogue') ? 'active':'' ?>" href="/darousalam/catalogue">Catalogue</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='apropos') ? 'active':'' ?>" href="/darousalam/apropos">À propos</a></li>
                <li class="nav-item"><a class="nav-link <?= (isset($activePage) && $activePage==='contact') ? 'active':'' ?>" href="/darousalam/contact">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3 mt-3 mt-lg-0">
                <!-- Search -->
                <form class="nav-search" action="/darousalam/catalogue" method="GET">
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
                <a href="/darousalam/panier" class="nav-icon-btn" title="Panier" id="cart-icon">
                    <i class="bi bi-bag"></i>
                    <?php
                        $panierSession = $_SESSION['panier_darousalam'] ?? [];
                        $nbPanier = array_sum(array_column($panierSession, 'quantite'));
                    ?>
                    <span class="cart-badge" id="cart-count"><?= $nbPanier > 0 ? $nbPanier : '' ?></span>
                </a>
            </div>
        </div>
    </div>
</nav>

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
                        <i class="bi bi-clock-fill me-1"></i>Expire le <?= date('d/m/Y', strtotime($_promo['date_fin'])) ?>
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
    form.action = '/darousalam/?page=panier_ajouter';
    const inp = document.createElement('input');
    inp.type = 'hidden'; inp.name = 'produit_id'; inp.value = id;
    const qty = document.createElement('input');
    qty.type = 'hidden'; qty.name = 'quantite'; qty.value = '1';
    form.appendChild(inp); form.appendChild(qty);
    document.body.appendChild(form);
    form.submit();
}
</script>
