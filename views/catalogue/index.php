<?php
$pageTitle  = 'Catalogue Fruits Premium — Darou Salam Business Company';
$activePage = 'catalogue';
require_once __DIR__ . '/../layouts/header.php';

$filterCat   = (int)($_GET['cat']  ?? 0);
$filterMax   = isset($_GET['max']) ? (int)$_GET['max'] : 5000;
$searchQuery = trim($_GET['q'] ?? '');
$sortBy      = $_GET['sort'] ?? 'default';

function buildCatalogUrl(array $override): string {
    $params = array_merge($_GET, $override);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return BASE_URL . '?' . http_build_query($params);
}
?>

<style>
:root { --vert:#1a5c2a; --orange:#f97316; --or:#d4a017; --creme:#fdf8ee; --sombre:#0f2d16; }

.cat-page-header { background:linear-gradient(135deg,var(--sombre) 0%,#1a5c2a 100%);padding:56px 0 46px;position:relative;overflow:hidden; }
.cat-page-header::before { content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Ccircle cx='20' cy='20' r='3' fill='%23fff' fill-opacity='.03'/%3E%3C/svg%3E"); }
.cat-page-title { font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:900;color:#fff; }
.cat-page-title em { color:var(--or);font-style:italic; }
.breadcrumb-item a { color:rgba(255,255,255,.6);text-decoration:none; }
.breadcrumb-item.active { color:rgba(255,255,255,.9); }
.breadcrumb-item+.breadcrumb-item::before { color:rgba(255,255,255,.4); }

/* SIDEBAR */
.filter-sidebar { background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(0,0,0,.07);overflow:hidden;position:sticky;top:100px; }
.filter-section { padding:18px 20px;border-bottom:1px solid #f0f0ea; }
.filter-section:last-child { border-bottom:none; }
.filter-title { font-family:'Playfair Display',serif;font-weight:700;font-size:.92rem;color:var(--sombre);margin-bottom:12px;display:flex;align-items:center;gap:7px; }
.filter-title i { color:var(--orange); }
.cat-radio { display:none; }
.cat-label { display:flex;align-items:center;justify-content:space-between;padding:7px 11px;border-radius:9px;cursor:pointer;font-size:.85rem;color:#444;transition:all .2s;margin-bottom:2px; }
.cat-label:hover { background:#f4f4f0;color:var(--vert); }
.cat-radio:checked + .cat-label { background:rgba(26,92,42,.09);color:var(--vert);font-weight:600; }
.cat-label .cat-dot { width:7px;height:7px;border-radius:50%;background:#ddd;flex-shrink:0; }
.cat-radio:checked + .cat-label .cat-dot { background:var(--vert); }
.price-range-input { -webkit-appearance:none;width:100%;height:4px;border-radius:4px;background:linear-gradient(90deg,var(--vert) var(--pct,100%),#e5e5e0 var(--pct,100%));outline:none;cursor:pointer; }
.price-range-input::-webkit-slider-thumb { -webkit-appearance:none;width:18px;height:18px;border-radius:50%;background:var(--vert);border:3px solid #fff;box-shadow:0 2px 8px rgba(26,92,42,.3);cursor:pointer; }
.price-display { display:flex;justify-content:space-between;font-size:.78rem;color:#888;margin-top:9px; }

/* TOOLBAR */
.cat-toolbar { background:#fff;border-radius:14px;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;box-shadow:0 2px 12px rgba(0,0,0,.06);margin-bottom:22px; }
.result-count { font-size:.85rem;color:#888; }
.result-count strong { color:var(--vert); }
.sort-select { border:1.5px solid #e5e5e0;border-radius:9px;padding:6px 12px;font-size:.83rem;color:#444;background:#fff;outline:none;cursor:pointer; }
.sort-select:focus { border-color:var(--vert); }
.view-toggle { display:flex;gap:4px; }
.view-btn { width:34px;height:34px;border-radius:8px;border:1.5px solid #e5e5e0;background:#fff;color:#aaa;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s; }
.view-btn.active,.view-btn:hover { background:var(--vert);border-color:var(--vert);color:#fff; }

/* PRODUCT CARD */
.product-card { background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 2px 16px rgba(0,0,0,.06);transition:transform .3s,box-shadow .3s;height:100%;display:flex;flex-direction:column; }
.product-card:hover { transform:translateY(-7px);box-shadow:0 18px 50px rgba(26,92,42,.14); }
.product-img-wrap { height:200px;overflow:hidden;position:relative;flex-shrink:0; }
.product-img-wrap img { width:100%;height:100%;object-fit:cover;transition:transform .5s; }
.product-card:hover .product-img-wrap img { transform:scale(1.07); }
.product-img-placeholder { width:100%;height:100%;background:#f5f3ee;display:flex;align-items:center;justify-content:center; }
.product-overlay { position:absolute;inset:0;background:rgba(15,45,22,.58);display:flex;align-items:center;justify-content:center;gap:10px;opacity:0;transition:opacity .3s; }
.product-card:hover .product-overlay { opacity:1; }
.overlay-btn { width:42px;height:42px;border-radius:50%;border:2px solid rgba(255,255,255,.8);background:rgba(255,255,255,.15);color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;backdrop-filter:blur(4px);transition:all .2s;text-decoration:none;font-size:1rem; }
.overlay-btn:hover { background:var(--orange);border-color:var(--orange);color:#fff; }
.product-badge { position:absolute;top:11px;left:11px;font-size:.63rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;border-radius:50px;padding:4px 10px;z-index:2;background:var(--orange);color:#fff; }
.product-badge.saison { background:var(--vert); }
.product-badge.promo  { background:#dc2626; }
.rupture-overlay { position:absolute;inset:0;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;z-index:3; }
.rupture-label { background:#dc2626;color:#fff;font-size:.72rem;font-weight:700;padding:6px 14px;border-radius:50px;letter-spacing:.05em; }

.product-body { padding:16px 16px 18px;flex:1;display:flex;flex-direction:column; }
.product-name { font-family:'Playfair Display',serif;font-weight:700;font-size:1rem;color:#0f2d16;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.product-origine { font-size:.73rem;color:#999;margin-bottom:10px; }

/* PRIX TOGGLE KG / CARTON */
.price-display-block { margin-bottom:14px; }

/* Vue kg */
.price-kg-view { display:flex;align-items:baseline;gap:6px;padding:10px 14px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px; }
.price-kg-main { font-weight:900;font-size:1.35rem;color:#0f2d16;line-height:1; }
.price-kg-unit { font-size:.75rem;color:#16a34a;font-weight:700; }
.price-kg-sub  { font-size:.7rem;color:#6b7280;margin-left:auto; }

/* Vue carton */
.price-carton-view { display:none;align-items:baseline;gap:6px;padding:10px 14px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:12px; }
.price-carton-main { font-weight:900;font-size:1.35rem;color:#0c4a6e;line-height:1; }
.price-carton-unit { font-size:.75rem;color:#0284c7;font-weight:700; }
.price-carton-sub  { font-size:.7rem;color:#0284c7;margin-left:auto; }
.price-nocarton    { display:none;padding:10px 14px;background:#f9fafb;border:1.5px solid #f3f4f6;border-radius:12px;text-align:center;font-size:.75rem;color:#9ca3af; }

/* Mode carton actif */
body.mode-carton .price-kg-view      { display:none; }
body.mode-carton .price-carton-view  { display:flex; }
body.mode-carton .price-nocarton     { display:block; }
body.mode-carton .price-kg-view + .price-carton-view { display:flex; }

.btn-cart-full { width:100%;background:linear-gradient(135deg,#1a5c2a,#236b34);color:#fff;border:none;border-radius:10px;padding:10px;font-weight:600;font-size:.83rem;cursor:pointer;transition:all .25s;display:flex;align-items:center;justify-content:center;gap:7px;font-family:'DM Sans',sans-serif;margin-top:auto; }
.btn-cart-full:hover { background:linear-gradient(135deg,#f97316,#ea580c);box-shadow:0 4px 16px rgba(249,115,22,.35);transform:translateY(-1px); }
.btn-cart-full:disabled { background:#d1d5db;cursor:not-allowed;transform:none;box-shadow:none; }

/* LIST VIEW */
.products-list .product-card { flex-direction:row;height:auto;border-radius:16px; }
.products-list .product-img-wrap { width:150px;height:120px;flex-shrink:0;border-radius:16px 0 0 16px; }
.products-list .product-body { padding:14px 18px; }
.products-list .product-overlay { border-radius:16px 0 0 16px; }
@media (max-width:576px) {
    .products-list .product-card { flex-direction:column; }
    .products-list .product-img-wrap { width:100%;height:160px;border-radius:16px 16px 0 0; }
}

/* PAGINATION */
.dsbc-pagination { display:flex;gap:6px;justify-content:center;flex-wrap:wrap;margin-top:38px; }
.page-link-dsbc { width:38px;height:38px;border-radius:9px;border:1.5px solid #e5e5e0;background:#fff;color:#444;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:.85rem;font-weight:500;transition:all .2s; }
.page-link-dsbc:hover { border-color:var(--vert);color:var(--vert); }
.page-link-dsbc.active { background:var(--vert);border-color:var(--vert);color:#fff;font-weight:700; }
.page-link-dsbc.disabled { opacity:.4;pointer-events:none; }
.empty-state { text-align:center;padding:70px 20px; }
.empty-state i { font-size:3.5rem;color:#d1d5db;margin-bottom:14px;display:block; }
.empty-state h4 { font-family:'Playfair Display',serif;font-weight:700;color:#0f2d16;margin-bottom:7px; }
.empty-state p  { color:#888;font-size:.88rem; }
@media (max-width:991px) { .filter-sidebar { position:static; } }
</style>

<!-- HEADER -->
<div class="cat-page-header">
    <div class="container position-relative">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb" style="background:transparent;padding:0;margin-bottom:10px;">
                <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
                <li class="breadcrumb-item active">Catalogue</li>
            </ol>
        </nav>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:18px;">
            <div>
                <h1 class="cat-page-title" style="margin-bottom:4px;">Catalogue <em>Fruits Premium</em></h1>
                <p style="color:rgba(255,255,255,.65);font-size:.88rem;margin:0;">
                    <?= count($produits) ?> produit<?= count($produits)>1?'s':'' ?> disponible<?= count($produits)>1?'s':'' ?>
                </p>
            </div>
            <!-- Toggle kg / carton -->
            <div style="display:flex;background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.15);border-radius:18px;padding:6px;gap:6px;box-shadow:inset 0 1px 3px rgba(0,0,0,.3),0 8px 24px rgba(0,0,0,.2);" id="price-mode-toggle">
                <button id="btn-mode-kg" onclick="setPriceMode('kg')"
                    style="padding:12px 28px;border-radius:12px;border:none;font-size:.92rem;font-weight:800;cursor:pointer;font-family:inherit;
                           background:linear-gradient(135deg,#2ecc71 0%,#1a5c2a 100%);
                           color:#fff;
                           transition:all .22s cubic-bezier(.4,0,.2,1);
                           display:flex;align-items:center;gap:9px;white-space:nowrap;
                           box-shadow:0 4px 16px rgba(26,92,42,.6),0 2px 4px rgba(0,0,0,.2);
                           letter-spacing:.01em;text-shadow:0 1px 2px rgba(0,0,0,.2);">
                    <i class="bi bi-speedometer2" style="font-size:1.05rem;"></i> Par kg
                </button>
                <button id="btn-mode-carton" onclick="setPriceMode('carton')"
                    style="padding:12px 28px;border-radius:12px;border:none;font-size:.92rem;font-weight:800;cursor:pointer;font-family:inherit;
                           background:transparent;
                           color:rgba(255,255,255,.65);
                           transition:all .22s cubic-bezier(.4,0,.2,1);
                           display:flex;align-items:center;gap:9px;white-space:nowrap;
                           letter-spacing:.01em;"
                    onmouseover="if(!this.classList.contains('active-btn')){this.style.background='rgba(255,255,255,.12)';this.style.color='#fff'}"
                    onmouseout="if(!this.classList.contains('active-btn')){this.style.background='transparent';this.style.color='rgba(255,255,255,.65)'}">
                    <i class="bi bi-box-seam" style="font-size:1.05rem;"></i> Par carton
                </button>
            </div>
        </div>
    </div>
</div>

<section style="background:var(--creme);padding:44px 0 80px;">
    <div class="container">
        <div class="row g-4">

            <!-- SIDEBAR -->
            <div class="col-lg-3">
                <button class="btn w-100 mb-3 d-lg-none" style="background:#1a5c2a;color:#fff;border-radius:12px;font-weight:600;padding:11px;" type="button" data-bs-toggle="collapse" data-bs-target="#filterPanel">
                    <i class="bi bi-funnel-fill me-2"></i> Filtrer les produits
                </button>
                <div class="collapse d-lg-block" id="filterPanel">
                    <form method="GET" action="<?= BASE_URL ?>" id="filter-form">
                        <input type="hidden" name="page" value="catalogue">
                        <?php if ($sortBy !== 'default'): ?><input type="hidden" name="sort" value="<?= htmlspecialchars($sortBy) ?>"><?php endif; ?>

                        <div class="filter-sidebar">
                            <div class="filter-section">
                                <div class="filter-title"><i class="bi bi-grid-3x3-gap-fill"></i> Catégories</div>
                                <input type="radio" name="cat" value="" id="cat_all" class="cat-radio" <?= !$filterCat ? 'checked' : '' ?>>
                                <label class="cat-label" for="cat_all">Tous les fruits <span class="cat-dot"></span></label>
                                <?php foreach ($categories as $cat): ?>
                                <input type="radio" name="cat" value="<?= (int)$cat['id'] ?>" id="cat_<?= (int)$cat['id'] ?>" class="cat-radio" <?= $filterCat === (int)$cat['id'] ? 'checked' : '' ?>>
                                <label class="cat-label" for="cat_<?= (int)$cat['id'] ?>">
                                    <?= htmlspecialchars($cat['nom']) ?>
                                    <span class="cat-dot"></span>
                                </label>
                                <?php endforeach; ?>
                            </div>

                            <div class="filter-section">
                                <div class="filter-title"><i class="bi bi-currency-exchange"></i> Prix max (FCFA/kg)</div>
                                <input type="range" name="max" min="0" max="5000" step="100"
                                    value="<?= min($filterMax, 5000) ?>"
                                    class="price-range-input"
                                    id="priceSlider"
                                    oninput="document.getElementById('priceMaxDisplay').textContent=parseInt(this.value).toLocaleString('fr-FR');this.style.setProperty('--pct',(this.value/5000*100)+'%')">
                                <div class="price-display">
                                    <span>0</span>
                                    <span><span id="priceMaxDisplay"><?= number_format(min($filterMax,5000),0,',',' ') ?></span> FCFA/kg</span>
                                </div>
                            </div>

                            <div class="filter-section" style="background:#f9f9f5;">
                                <button type="submit" class="btn w-100 mb-2" style="background:linear-gradient(135deg,#1a5c2a,#236b34);color:#fff;border-radius:10px;font-weight:600;padding:10px;">
                                    <i class="bi bi-search me-2"></i> Appliquer
                                </button>
                                <a href="<?= BASE_URL ?>?page=catalogue" class="btn btn-outline-secondary btn-sm w-100 rounded-pill" style="font-size:.78rem;">
                                    <i class="bi bi-x-circle me-1"></i> Réinitialiser
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="mt-4 p-4 text-center" style="background:linear-gradient(135deg,#0f2d16,#1a5c2a);border-radius:20px;color:#fff;">
                    <i class="bi bi-whatsapp" style="font-size:2rem;color:#25D366;display:block;margin-bottom:9px;"></i>
                    <div style="font-family:'Playfair Display',serif;font-weight:700;font-size:.95rem;margin-bottom:5px;">Commander par WhatsApp</div>
                    <p style="font-size:.78rem;color:rgba(255,255,255,.7);margin-bottom:12px;">Envoyez-nous votre liste directement.</p>
                    <a href="https://wa.me/221774715353?text=Bonjour%2C%20je%20souhaite%20passer%20une%20commande." target="_blank"
                       style="background:#25D366;color:#fff;border-radius:50px;padding:9px 18px;font-weight:600;font-size:.82rem;text-decoration:none;display:inline-flex;align-items:center;gap:7px;">
                        <i class="bi bi-whatsapp"></i> 77 471 53 53
                    </a>
                </div>
            </div>

            <!-- PRODUITS -->
            <div class="col-lg-9">
                <div class="cat-toolbar">
                    <div class="result-count">
                        <strong><?= count($produits) ?></strong> produit<?= count($produits)>1?'s':'' ?> trouvé<?= count($produits)>1?'s':'' ?>
                    </div>
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <?php $baseSort = BASE_URL . '?page=catalogue' . ($filterCat ? '&cat='.$filterCat : ''); ?>
                        <select class="sort-select" onchange="location.href=this.value">
                            <option value="<?= $baseSort ?>&sort=default"    <?= $sortBy==='default'   ?'selected':'' ?>>Par défaut</option>
                            <option value="<?= $baseSort ?>&sort=price_asc"  <?= $sortBy==='price_asc' ?'selected':'' ?>>Prix croissant</option>
                            <option value="<?= $baseSort ?>&sort=price_desc" <?= $sortBy==='price_desc'?'selected':'' ?>>Prix décroissant</option>
                            <option value="<?= $baseSort ?>&sort=name_asc"   <?= $sortBy==='name_asc'  ?'selected':'' ?>>A–Z</option>
                        </select>
                        <div class="view-toggle">
                            <button class="view-btn active" id="grid-btn" onclick="setView('grid')"><i class="bi bi-grid-3x3-gap-fill"></i></button>
                            <button class="view-btn"        id="list-btn" onclick="setView('list')"><i class="bi bi-list-ul"></i></button>
                        </div>
                    </div>
                </div>

                <?php if (empty($produits)): ?>
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h4>Aucun produit trouvé</h4>
                    <p>Essayez de modifier vos filtres.</p>
                    <a href="<?= BASE_URL ?>?page=catalogue" class="btn mt-3" style="background:#1a5c2a;color:#fff;border-radius:50px;padding:10px 26px;font-weight:600;">
                        Voir tous les produits
                    </a>
                </div>
                <?php else: ?>
                <div class="row g-4" id="products-container">
                    <?php foreach ($produits as $p):
                        $imgUrl  = !empty($p['image_principale']) ? '/darousalam/' . $p['image_principale'] : null;
                        $prixKg  = (float)($p['prix_kg'] ?? 0);
                        $prixCar = (float)($p['prix_carton'] ?? 0);
                        $poidsC  = (float)($p['poids_carton_kg'] ?? 0);
                        $badge   = $p['badge'] ?? '';
                        $badgeClass = '';
                        if ($badge && preg_match('/saison|local|bio/i', $badge)) $badgeClass = 'saison';
                        if ($badge && preg_match('/promo|solde/i', $badge))      $badgeClass = 'promo';
                        $inStock = (float)$p['stock_kg'] > 0;
                    ?>
                    <div class="col-sm-6 col-xl-4 product-col">
                        <div class="product-card h-100" data-id="<?= (int)$p['id'] ?>">
                            <div class="product-img-wrap">
                                <?php if ($imgUrl): ?>
                                <img src="<?= htmlspecialchars($imgUrl) ?>"
                                     alt="<?= htmlspecialchars($p['nom']) ?>"
                                     loading="lazy"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                                <div class="product-img-placeholder" style="display:none;">
                                    <i class="bi bi-image" style="font-size:3rem;color:#d1d5db;"></i>
                                </div>
                                <?php else: ?>
                                <div class="product-img-placeholder">
                                    <i class="bi bi-image" style="font-size:3rem;color:#d1d5db;"></i>
                                </div>
                                <?php endif; ?>

                                <?php if ($badge): ?>
                                <span class="product-badge <?= $badgeClass ?>"><?= htmlspecialchars($badge) ?></span>
                                <?php endif; ?>

                                <?php if (!$inStock): ?>
                                <div class="rupture-overlay">
                                    <span class="rupture-label">RUPTURE</span>
                                </div>
                                <?php endif; ?>

                                <div class="product-overlay">
                                    <a href="<?= BASE_URL ?>?page=produit&id=<?= (int)$p['id'] ?>" class="overlay-btn" title="Voir le détail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if ($inStock): ?>
                                    <form method="POST" action="<?= BASE_URL ?>?page=panier_ajouter" style="display:inline;">
                                        <input type="hidden" name="produit_id" value="<?= (int)$p['id'] ?>">
                                        <input type="hidden" name="quantite" value="1">
                                        <input type="hidden" name="unite" id="unite-overlay-<?= $p['id'] ?>" value="kg">
                                        <button type="submit" class="overlay-btn" title="Ajouter au panier">
                                            <i class="bi bi-bag-plus"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="product-body">
                                <div class="product-name"><?= htmlspecialchars($p['nom']) ?></div>
                                <div class="product-origine">
                                    <?php if (!empty($p['origine'])): ?>
                                    <i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($p['origine']) ?>
                                    <?php else: ?>&nbsp;<?php endif; ?>
                                </div>

                                <!-- PRIX toggle kg / carton -->
                                <div class="price-display-block">
                                    <!-- Vue KG (défaut) -->
                                    <div class="price-kg-view">
                                        <span class="price-kg-main"><?= number_format($prixKg, 0, ',', ' ') ?></span>
                                        <span class="price-kg-unit">FCFA</span>
                                        <span class="price-kg-sub">/ kg</span>
                                    </div>
                                    <!-- Vue CARTON -->
                                    <?php if ($prixCar > 0): ?>
                                    <div class="price-carton-view">
                                        <span class="price-carton-main"><?= number_format($prixCar, 0, ',', ' ') ?></span>
                                        <span class="price-carton-unit">FCFA</span>
                                        <span class="price-carton-sub"><?= $poidsC > 0 ? number_format($poidsC,0).' kg net / carton' : '/ carton' ?></span>
                                    </div>
                                    <?php else: ?>
                                    <div class="price-nocarton">
                                        <i class="bi bi-box-seam me-1"></i>Non disponible en carton
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <form method="POST" action="<?= BASE_URL ?>?page=panier_ajouter">
                                    <input type="hidden" name="produit_id" value="<?= (int)$p['id'] ?>">
                                    <input type="hidden" name="quantite" value="1">
                                    <input type="hidden" name="unite" id="unite-<?= $p['id'] ?>" value="kg">
                                    <button type="submit" class="btn-cart-full" <?= !$inStock ? 'disabled' : '' ?>>
                                        <?php if ($inStock): ?>
                                        <i class="bi bi-bag-plus-fill"></i> Ajouter au panier
                                        <?php else: ?>
                                        <i class="bi bi-clock"></i> Rupture de stock
                                        <?php endif; ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (!empty($paginationHtml)): ?>
                <div class="dsbc-pagination"><?= $paginationHtml ?></div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<script>
function setPriceMode(mode) {
    const btnKg     = document.getElementById('btn-mode-kg');
    const btnCarton = document.getElementById('btn-mode-carton');
    if (mode === 'carton') {
        document.body.classList.add('mode-carton');
        // kg → inactif
        btnKg.style.background   = 'transparent';
        btnKg.style.color        = 'rgba(255,255,255,.65)';
        btnKg.style.boxShadow    = 'none';
        btnKg.style.textShadow   = 'none';
        btnKg.classList.remove('active-btn');
        // carton → actif
        btnCarton.style.background  = 'linear-gradient(135deg,#38bdf8 0%,#0284c7 100%)';
        btnCarton.style.color       = '#fff';
        btnCarton.style.boxShadow   = '0 4px 16px rgba(2,132,199,.6),0 2px 4px rgba(0,0,0,.2)';
        btnCarton.style.textShadow  = '0 1px 2px rgba(0,0,0,.2)';
        btnCarton.classList.add('active-btn');
    } else {
        document.body.classList.remove('mode-carton');
        // kg → actif
        btnKg.style.background   = 'linear-gradient(135deg,#2ecc71 0%,#1a5c2a 100%)';
        btnKg.style.color        = '#fff';
        btnKg.style.boxShadow    = '0 4px 16px rgba(26,92,42,.6),0 2px 4px rgba(0,0,0,.2)';
        btnKg.style.textShadow   = '0 1px 2px rgba(0,0,0,.2)';
        btnKg.classList.add('active-btn');
        // carton → inactif
        btnCarton.style.background  = 'transparent';
        btnCarton.style.color       = 'rgba(255,255,255,.65)';
        btnCarton.style.boxShadow   = 'none';
        btnCarton.style.textShadow  = 'none';
        btnCarton.classList.remove('active-btn');
    }
    document.querySelectorAll('input[id^="unite-"]').forEach(inp => {
        inp.value = mode === 'carton' ? 'carton' : 'kg';
    });
    localStorage.setItem('dsbc_price_mode', mode);
}

function setView(v) {
    const container = document.getElementById('products-container');
    if (!container) return;
    const cols    = container.querySelectorAll('.product-col');
    const gridBtn = document.getElementById('grid-btn');
    const listBtn = document.getElementById('list-btn');
    if (v === 'list') {
        container.classList.add('products-list');
        cols.forEach(c => { c.className = 'col-12 product-col'; });
        gridBtn.classList.remove('active');
        listBtn.classList.add('active');
    } else {
        container.classList.remove('products-list');
        cols.forEach(c => { c.className = 'col-sm-6 col-xl-4 product-col'; });
        gridBtn.classList.add('active');
        listBtn.classList.remove('active');
    }
    localStorage.setItem('dsbc_view', v);
}
const savedView      = localStorage.getItem('dsbc_view');
if (savedView) setView(savedView);

const savedPriceMode = localStorage.getItem('dsbc_price_mode');
if (savedPriceMode === 'carton') setPriceMode('carton');

// Prix slider fill
const slider = document.getElementById('priceSlider');
if (slider) slider.style.setProperty('--pct', (slider.value / slider.max * 100) + '%');

// Catégorie → auto-submit
document.querySelectorAll('.cat-radio').forEach(r => {
    r.addEventListener('change', () => document.getElementById('filter-form').submit());
});

// ── AJOUT PANIER AJAX + FLY-TO-CART ───────────────────────────────
(function() {

  /* ---------- styles injectés ---------- */
  const style = document.createElement('style');
  style.textContent = `
    @keyframes flyBall {
      0%   { transform: scale(1); opacity: 1; }
      60%  { transform: scale(.55); opacity: .9; }
      100% { transform: scale(.15); opacity: 0; }
    }
    @keyframes cartShake {
      0%,100% { transform: scale(1) rotate(0deg); }
      20%     { transform: scale(1.35) rotate(-12deg); }
      40%     { transform: scale(1.2)  rotate(10deg); }
      60%     { transform: scale(1.3)  rotate(-6deg); }
      80%     { transform: scale(1.15) rotate(4deg); }
    }
    @keyframes btnSuccess {
      0%   { background:#1a5c2a; transform:scale(1); }
      30%  { background:#16a34a; transform:scale(1.04); }
      100% { background:#1a5c2a; transform:scale(1); }
    }
    @keyframes toastIn {
      from { opacity:0; transform:translateX(-50%) translateY(20px) scale(.92); }
      to   { opacity:1; transform:translateX(-50%) translateY(0) scale(1); }
    }
    @keyframes toastOut {
      from { opacity:1; transform:translateX(-50%) translateY(0) scale(1); }
      to   { opacity:0; transform:translateX(-50%) translateY(16px) scale(.94); }
    }
    .fly-clone {
      position: fixed;
      border-radius: 50%;
      pointer-events: none;
      z-index: 99999;
      overflow: hidden;
      box-shadow: 0 8px 24px rgba(0,0,0,.35);
      border: 3px solid #fff;
      transition: none;
    }
    .btn-cart-full.adding {
      animation: btnSuccess .5s ease forwards;
      pointer-events: none;
    }
    .btn-cart-full.added {
      background: #16a34a !important;
    }
    #dsbc-toast {
      position: fixed;
      bottom: 28px;
      left: 50%;
      transform: translateX(-50%) translateY(20px);
      background: #fff;
      color: #0f2d16;
      padding: 12px 20px 12px 14px;
      border-radius: 60px;
      font-size: .84rem;
      font-weight: 700;
      box-shadow: 0 12px 40px rgba(0,0,0,.18), 0 2px 8px rgba(0,0,0,.08);
      z-index: 99998;
      display: flex;
      align-items: center;
      gap: 10px;
      pointer-events: none;
      opacity: 0;
      border: 1.5px solid #e5e7eb;
      min-width: 220px;
      max-width: 360px;
      white-space: nowrap;
    }
    #dsbc-toast .t-ico {
      width: 34px; height: 34px;
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-size: 1rem;
      flex-shrink: 0;
    }
    #dsbc-toast .t-thumb {
      width: 36px; height: 36px;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
      border: 1.5px solid #e5e7eb;
    }
    #dsbc-toast .t-text { flex:1; min-width:0; }
    #dsbc-toast .t-name { font-weight:800; font-size:.82rem; color:#0f2d16; }
    #dsbc-toast .t-sub  { font-size:.72rem; color:#6b7280; font-weight:500; margin-top:1px; }
    #dsbc-toast .t-close {
      width:22px;height:22px;border-radius:50%;background:#f3f4f6;
      border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;
      font-size:.65rem;color:#9ca3af;flex-shrink:0;pointer-events:all;
    }
    #dsbc-toast.show { animation: toastIn .32s cubic-bezier(.34,1.56,.64,1) forwards; }
    #dsbc-toast.hide { animation: toastOut .24s ease forwards; }
  `;
  document.head.appendChild(style);

  /* ---------- toast DOM ---------- */
  const toast = document.createElement('div');
  toast.id = 'dsbc-toast';
  toast.innerHTML = `
    <div class="t-ico" style="background:#f0fdf4;color:#16a34a;"><i class="bi bi-bag-check-fill"></i></div>
    <div class="t-text">
      <div class="t-name">Ajouté au panier !</div>
      <div class="t-sub">Voir le panier →</div>
    </div>
    <button class="t-close" onclick="this.parentElement.classList.remove('show');this.parentElement.classList.add('hide');">✕</button>
  `;
  document.body.appendChild(toast);

  let toastTimer;
  function showToast(name, thumbSrc, nb, isError) {
    clearTimeout(toastTimer);
    toast.classList.remove('show','hide');

    const ico  = toast.querySelector('.t-ico');
    const tTxt = toast.querySelector('.t-name');
    const tSub = toast.querySelector('.t-sub');

    // Remplacer l'icône par la miniature si dispo
    const existing = toast.querySelector('.t-thumb');
    if (existing) existing.remove();
    if (thumbSrc && !isError) {
      const img = document.createElement('img');
      img.className = 't-thumb';
      img.src = thumbSrc;
      img.onerror = () => img.remove();
      ico.replaceWith(img);
    } else {
      if (!toast.querySelector('.t-ico')) {
        const newIco = document.createElement('div');
        newIco.className = 't-ico';
        newIco.style.cssText = isError
          ? 'background:#fef2f2;color:#dc2626;' : 'background:#f0fdf4;color:#16a34a;';
        newIco.innerHTML = isError
          ? '<i class="bi bi-exclamation-circle-fill"></i>'
          : '<i class="bi bi-bag-check-fill"></i>';
        const firstChild = toast.querySelector('.t-text');
        toast.insertBefore(newIco, firstChild);
      }
    }

    tTxt.textContent = isError ? (name || 'Erreur') : (name ? '"' + name + '" ajouté !' : 'Ajouté au panier !');
    tSub.innerHTML   = isError ? '' : (nb ? nb + ' article(s) dans le panier' : 'Voir le panier →');
    toast.style.borderColor = isError ? '#fecaca' : '#bbf7d0';
    toast.style.background  = '#fff';

    void toast.offsetWidth;
    toast.classList.add('show');
    toastTimer = setTimeout(() => {
      toast.classList.remove('show');
      toast.classList.add('hide');
    }, 3200);
  }

  /* ---------- badge panier ---------- */
  function updateCartBadge(nb) {
    const badge = document.getElementById('cart-count');
    if (!badge) return;
    badge.textContent = nb > 0 ? nb : '';
    badge.classList.remove('bump');
    void badge.offsetWidth;
    badge.classList.add('bump');
  }

  /* ---------- animation fly-to-cart ---------- */
  function flyToCart(sourceEl, imgSrc) {
    const cartIcon = document.getElementById('cart-icon');
    if (!cartIcon) return;

    const srcRect  = sourceEl.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();

    const size = Math.min(srcRect.width, srcRect.height, 90);
    const clone = document.createElement('div');
    clone.className = 'fly-clone';
    clone.style.cssText = `
      width:${size}px; height:${size}px;
      left:${srcRect.left + srcRect.width/2 - size/2}px;
      top:${srcRect.top  + srcRect.height/2 - size/2}px;
      background: ${imgSrc ? 'url('+imgSrc+') center/cover' : '#f0fdf4'};
    `;
    if (!imgSrc) clone.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:1.6rem;">🛒</div>';
    document.body.appendChild(clone);

    /* calcul du vecteur de déplacement */
    const destX = cartRect.left + cartRect.width/2  - (srcRect.left + srcRect.width/2);
    const destY = cartRect.top  + cartRect.height/2 - (srcRect.top  + srcRect.height/2);

    const dur = 680;
    const start = performance.now();

    function easeInBack(t) {
      const c1 = 1.70158, c3 = c1 + 1;
      return 1 + c3 * Math.pow(t - 1, 3) + c1 * Math.pow(t - 1, 2);
    }
    function easeOut(t) { return 1 - Math.pow(1 - t, 3); }

    (function tick(now) {
      const t = Math.min((now - start) / dur, 1);
      const prog = easeOut(t);
      const x = destX * prog;
      const y = destY * prog;
      const sc = 1 - easeInBack(t) * .88;
      const op = t < .7 ? 1 : 1 - (t - .7) / .3;
      clone.style.transform = `translate(${x}px, ${y}px) scale(${Math.max(sc,.05)})`;
      clone.style.opacity   = Math.max(op, 0);
      if (t < 1) requestAnimationFrame(tick);
      else {
        clone.remove();
        /* shake l'icône panier */
        const bagIcon = cartIcon.querySelector('i') || cartIcon;
        bagIcon.style.animation = 'none';
        void bagIcon.offsetWidth;
        bagIcon.style.animation = 'cartShake .5s ease';
        setTimeout(() => bagIcon.style.animation = '', 500);
      }
    })(start);
  }

  /* ---------- intercepter les submits ---------- */
  document.addEventListener('submit', function(e) {
    const form = e.target.closest('form[action*="panier_ajouter"]');
    if (!form) return;
    e.preventDefault();

    const btn     = form.querySelector('button[type="submit"]');
    const card    = form.closest('.product-card') || form.closest('[class*="product"]');
    const imgEl   = card ? card.querySelector('img') : null;
    const imgSrc  = imgEl ? imgEl.src : null;
    const nameEl  = card ? card.querySelector('.product-name') : null;
    const prodName = nameEl ? nameEl.textContent.trim() : '';

    /* source de l'animation : image ou bouton */
    const flySource = imgEl || btn || form;

    if (btn) {
      btn.disabled = true;
      btn.classList.add('adding');
      btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Ajout…';
    }

    /* lancer l'animation immédiatement, sans attendre le réseau */
    flyToCart(flySource, imgSrc);

    fetch(form.action, {
      method : 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body   : new FormData(form)
    })
    .then(r => r.json())
    .then(res => {
      if (res.success) {
        updateCartBadge(res.nb_articles);
        showToast(prodName, imgSrc, res.nb_articles, false);
        if (btn) {
          btn.classList.remove('adding');
          btn.classList.add('added');
          btn.innerHTML = '<i class="bi bi-check2-circle"></i> Ajouté !';
          setTimeout(() => {
            btn.classList.remove('added');
            btn.innerHTML = '<i class="bi bi-bag-plus-fill"></i> Ajouter au panier';
            btn.disabled = false;
          }, 2000);
        }
      } else {
        showToast(res.message || 'Stock insuffisant', null, 0, true);
        if (btn) {
          btn.classList.remove('adding');
          btn.innerHTML = '<i class="bi bi-bag-plus-fill"></i> Ajouter au panier';
          btn.disabled = false;
        }
      }
    })
    .catch(() => {
      showToast('Erreur réseau', null, 0, true);
      if (btn) { btn.classList.remove('adding'); btn.disabled = false; btn.innerHTML = '<i class="bi bi-bag-plus-fill"></i> Ajouter au panier'; }
    });
  });

})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
