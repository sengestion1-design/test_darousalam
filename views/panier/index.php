<?php
$pageTitle = 'Mon panier — ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
$nbArticles = array_sum(array_column($contenu, 'quantite'));
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600;700&display=swap');
*{box-sizing:border-box;}
:root{--vert:#1a5c2a;--vert-l:#2d8a42;--orange:#f97316;--or:#d4a017;}

/* HERO */
.p-hero{
  background:linear-gradient(135deg,#050d07 0%,#0f2d16 55%,#1a5c2a 100%);
  border-radius:22px;padding:44px 40px 40px;margin-bottom:36px;
  display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;
  position:relative;overflow:hidden;
}
.p-hero::after{
  content:'';position:absolute;right:-80px;top:-80px;
  width:260px;height:260px;border-radius:50%;
  background:rgba(212,160,23,.06);pointer-events:none;
}
.p-hero-title{
  font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;
  color:#fff;margin:0 0 8px;display:flex;align-items:center;gap:12px;
}
.p-hero-title i{color:var(--orange);font-size:1.7rem;}
.p-hero-sub{font-size:.9rem;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:8px;}
.p-hero-sub i{color:var(--or);}
.p-pills{display:flex;gap:10px;flex-wrap:wrap;}
.p-pill{
  display:inline-flex;align-items:center;gap:7px;
  padding:10px 20px;border-radius:50px;font-size:.8rem;font-weight:700;letter-spacing:.02em;
}
.p-pill-orange{background:var(--orange);color:#fff;box-shadow:0 4px 14px rgba(249,115,22,.35);}
.p-pill-ghost{background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.15);}

/* GRID */
.p-grid{display:grid;grid-template-columns:1fr 400px;gap:32px;align-items:start;}
@media(max-width:1024px){.p-grid{grid-template-columns:1fr;}}

/* TOOLBAR */
.p-bar{
  display:flex;align-items:center;justify-content:space-between;
  margin-bottom:20px;padding:14px 18px;
  background:#fff;border-radius:14px;border:1.5px solid #f3f4f6;
}
.p-bar-info{font-size:.83rem;color:#6b7280;}
.p-bar-info strong{color:#1c1917;font-weight:800;}
.btn-vider{
  display:inline-flex;align-items:center;gap:6px;
  padding:8px 16px;border-radius:10px;
  background:#fef2f2;border:1.5px solid #fecaca;
  color:#dc2626;font-size:.78rem;font-weight:600;
  text-decoration:none;transition:all .18s;cursor:pointer;
}
.btn-vider:hover{background:#dc2626;color:#fff;border-color:#dc2626;}

/* ITEM CARD */
.p-card{
  background:#fff;border-radius:20px;border:1.5px solid #f0f0f0;
  box-shadow:0 2px 16px rgba(0,0,0,.05);
  display:grid;grid-template-columns:100px 1fr auto;
  gap:20px;align-items:center;padding:20px 22px;
  margin-bottom:14px;transition:all .2s;
}
.p-card:hover{box-shadow:0 8px 30px rgba(0,0,0,.1);border-color:#bbf7d0;transform:translateY(-1px);}

.p-card-img{
  width:100px;height:100px;border-radius:16px;
  object-fit:cover;border:2px solid #f0fdf4;display:block;flex-shrink:0;
}
.p-card-img-ph{
  width:100px;height:100px;border-radius:16px;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  display:flex;align-items:center;justify-content:center;
  font-size:2.2rem;color:#16a34a;flex-shrink:0;
}

.p-card-body{min-width:0;}
.p-card-name{font-weight:800;font-size:1rem;color:#1c1917;margin-bottom:6px;}
.p-card-badges{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:14px;}
.p-badge{
  display:inline-flex;align-items:center;gap:4px;
  padding:3px 10px;border-radius:20px;font-size:.7rem;font-weight:600;
}
.p-badge-green{background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;}
.p-badge-blue{background:#eff6ff;color:#2563eb;border:1px solid #bfdbfe;}

/* Qty stepper */
.qty-stepper{display:inline-flex;align-items:center;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:12px;overflow:hidden;}
.qty-btn{
  width:36px;height:36px;border:none;background:transparent;
  color:#374151;font-size:1.1rem;font-weight:800;cursor:pointer;
  display:flex;align-items:center;justify-content:center;transition:background .15s;
}
.qty-btn:hover{background:#e5e7eb;}
.qty-num{
  width:46px;height:36px;border:none;background:transparent;
  text-align:center;font-size:.95rem;font-weight:800;color:#1c1917;
  font-family:'DM Sans',sans-serif;
}
.qty-num:focus{outline:none;}
.qty-form{display:flex;align-items:center;gap:10px;}
.btn-ok{
  height:36px;padding:0 14px;border-radius:10px;
  border:1.5px solid #d1fae5;background:#f0fdf4;
  color:#16a34a;font-size:.78rem;font-weight:700;cursor:pointer;
  font-family:'DM Sans',sans-serif;transition:all .15s;
}
.btn-ok:hover{background:#16a34a;color:#fff;border-color:#16a34a;}

.p-card-right{display:flex;flex-direction:column;align-items:flex-end;gap:14px;min-width:110px;}
.p-subtotal{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:900;color:var(--vert);}
.p-unit-detail{font-size:.7rem;color:#9ca3af;margin-top:2px;text-align:right;}
.btn-rm{
  width:36px;height:36px;border-radius:10px;
  border:1.5px solid #fecaca;background:#fef2f2;
  color:#dc2626;text-decoration:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:.88rem;transition:all .15s;
}
.btn-rm:hover{background:#dc2626;color:#fff;border-color:#dc2626;}

/* RECAP */
.recap{
  background:#fff;border-radius:22px;
  border:1.5px solid #f0f0f0;
  box-shadow:0 6px 32px rgba(0,0,0,.08);
  position:sticky;top:120px;
  max-height:calc(100vh - 140px);
  overflow-y:auto;
  scrollbar-width:none;
}
.recap::-webkit-scrollbar{display:none;}
.recap-hd{
  background:linear-gradient(135deg,#050d07 0%,#1a5c2a 100%);
  padding:22px 26px;
  border-radius:22px 22px 0 0;
}
.recap-hd-title{
  font-family:'Playfair Display',serif;font-size:1.15rem;
  font-weight:800;color:#fff;display:flex;align-items:center;gap:10px;margin:0;
}
.recap-hd-title i{color:var(--orange);}
.recap-bd{padding:24px 26px;}

.recap-lines{margin-bottom:18px;}
.r-line{
  display:flex;justify-content:space-between;align-items:center;
  padding:8px 0;border-bottom:1px dashed #f3f4f6;
}
.r-line:last-child{border:none;}
.r-line-name{font-size:.8rem;color:#6b7280;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.r-line-val{font-size:.8rem;font-weight:700;color:#1c1917;flex-shrink:0;margin-left:8px;}

.r-sep{height:1px;background:linear-gradient(90deg,transparent,#e5e7eb 30%,#e5e7eb 70%,transparent);margin:16px 0;}

.r-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:11px;}
.r-row-label{font-size:.84rem;color:#6b7280;display:flex;align-items:center;gap:6px;}
.r-row-label i{font-size:.88rem;}
.r-row-val{font-size:.84rem;font-weight:700;color:#1c1917;}
.r-total-row{
  display:flex;justify-content:space-between;align-items:center;
  padding:16px 18px;margin:16px 0 0;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  border-top:2px solid #bbf7d0;
  border-radius:12px;
}
.r-total-label{font-size:1rem;font-weight:800;color:#1c1917;}
.r-total-val{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:var(--vert);}

.btn-commander{
  display:flex;align-items:center;justify-content:center;gap:10px;
  width:100%;padding:16px;margin-top:20px;
  background:linear-gradient(135deg,#1a5c2a,#2d8a42);
  color:#fff;border:none;border-radius:14px;
  font-size:.95rem;font-weight:700;font-family:'DM Sans',sans-serif;
  text-decoration:none;box-shadow:0 6px 20px rgba(26,92,42,.3);
  transition:all .2s;
}
.btn-commander:hover{background:linear-gradient(135deg,#2d8a42,#3aaa56);transform:translateY(-2px);color:#fff;box-shadow:0 10px 28px rgba(26,92,42,.35);}

.btn-wa{
  display:flex;align-items:center;justify-content:center;gap:8px;
  width:100%;padding:13px;margin-top:10px;
  background:#25d366;color:#fff;border:none;border-radius:14px;
  font-size:.88rem;font-weight:700;font-family:'DM Sans',sans-serif;
  text-decoration:none;transition:all .2s;
}
.btn-wa:hover{background:#1da851;color:#fff;transform:translateY(-1px);}

.recap-login{
  margin-top:14px;padding:11px 14px;
  background:#fafafa;border-radius:11px;border:1px solid #f0f0f0;
  text-align:center;font-size:.78rem;color:#9ca3af;
}
.recap-login a{color:var(--vert);font-weight:700;text-decoration:none;}

.trust-grid{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:16px;}
.trust-it{
  display:flex;align-items:center;gap:6px;padding:8px 10px;
  border-radius:10px;background:#f9fafb;border:1px solid #f0f0f0;
  font-size:.7rem;color:#6b7280;font-weight:500;
}
.trust-it i{color:var(--vert);font-size:.82rem;flex-shrink:0;}

/* VIDE */
.p-vide{
  grid-column:1/-1;text-align:center;
  padding:90px 24px;background:#fff;border-radius:22px;
  border:2px dashed #e5e7eb;
}
.p-vide-icon{
  width:120px;height:120px;border-radius:50%;margin:0 auto 24px;
  background:linear-gradient(135deg,#f0fdf4,#eff6ff);
  display:flex;align-items:center;justify-content:center;
  font-size:3rem;color:#d1d5db;
}
.p-vide h3{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:800;color:#1c1917;margin-bottom:8px;}
.p-vide p{color:#9ca3af;font-size:.9rem;margin-bottom:28px;}
.btn-cat{
  display:inline-flex;align-items:center;gap:10px;
  padding:14px 32px;background:var(--vert);color:#fff;
  border-radius:14px;font-weight:700;font-size:.92rem;
  text-decoration:none;box-shadow:0 4px 16px rgba(26,92,42,.25);transition:all .2s;
}
.btn-cat:hover{background:var(--vert-l);color:#fff;transform:translateY(-2px);}

/* CONTINUER */
.btn-back{
  display:inline-flex;align-items:center;gap:8px;
  padding:12px 22px;border:1.5px solid #e5e7eb;border-radius:12px;
  color:#374151;font-size:.84rem;font-weight:600;text-decoration:none;
  background:#fff;margin-top:14px;transition:all .2s;
}
.btn-back:hover{border-color:var(--vert);color:var(--vert);background:#f0fdf4;}

@media(max-width:640px){
  .p-hero{padding:22px 20px;}
  .p-hero-title{font-size:1.5rem;}
  .p-card{grid-template-columns:80px 1fr;gap:14px;}
  .p-card-img,.p-card-img-ph{width:80px;height:80px;}
  .p-card-right{grid-column:1/-1;flex-direction:row;align-items:center;justify-content:space-between;}
}
</style>

<div class="container py-4">

<!-- HERO -->
<div class="p-hero">
  <div>
    <div class="p-hero-title">
      <i class="bi bi-bag-check-fill"></i> Mon panier
    </div>
    <div class="p-hero-sub">
      <?php if($nbArticles > 0): ?>
        <i class="bi bi-geo-alt-fill"></i>
        <?= $nbArticles ?> article<?= $nbArticles > 1 ? 's' : '' ?> &mdash; Livraison Dakar &amp; sous-région
      <?php else: ?>
        <i class="bi bi-info-circle"></i> Votre panier est vide
      <?php endif; ?>
    </div>
  </div>
  <?php if($nbArticles > 0): ?>
  <div class="p-pills">
    <span class="p-pill p-pill-orange"><i class="bi bi-truck-front-fill"></i> Livraison rapide</span>
    <span class="p-pill p-pill-ghost"><i class="bi bi-shield-fill-check"></i> Qualité garantie</span>
  </div>
  <?php endif; ?>
</div>

<?php if (empty($contenu)): ?>
<div class="p-vide">
  <div class="p-vide-icon"><i class="bi bi-bag-x"></i></div>
  <h3>Votre panier est vide</h3>
  <p>Découvrez notre sélection de fruits frais premium<br>directement importés pour vous.</p>
  <a href="<?= BASE_URL ?>?page=catalogue" class="btn-cat">
    <i class="bi bi-grid-3x3-gap-fill"></i> Explorer le catalogue
  </a>
</div>

<?php else: ?>
<div class="p-grid">

  <!-- GAUCHE -->
  <div>
    <!-- Toolbar -->
    <div class="p-bar">
      <span class="p-bar-info">
        <strong><?= count($contenu) ?></strong> produit<?= count($contenu)>1?'s':'' ?> &nbsp;·&nbsp;
        <strong><?= $nbArticles ?></strong> article<?= $nbArticles > 1 ? 's' : '' ?>
      </span>
      <a href="<?= BASE_URL ?>?page=panier_vider" class="btn-vider"
         onclick="return confirm('Vider tout le panier ?')">
        <i class="bi bi-trash3-fill"></i> Vider le panier
      </a>
    </div>

    <!-- Items -->
    <?php foreach ($contenu as $key => $item):
      $imgUrl   = !empty($item['image']) ? BASE_URL . 'public/uploads/' . $item['image'] : null;
      $isCarton = ($item['unite'] ?? 'kg') === 'carton';
    ?>
    <div class="p-card">

      <!-- Photo -->
      <?php if($imgUrl): ?>
      <img src="<?= htmlspecialchars($imgUrl) ?>" class="p-card-img"
           alt="<?= htmlspecialchars($item['nom']) ?>"
           onerror="this.outerHTML='<div class=\'p-card-img-ph\'><i class=\'bi bi-basket2-fill\'></i></div>'">
      <?php else: ?>
      <div class="p-card-img-ph"><i class="bi bi-basket2-fill"></i></div>
      <?php endif; ?>

      <!-- Body -->
      <div class="p-card-body">
        <div class="p-card-name"><?= htmlspecialchars($item['nom']) ?></div>
        <div class="p-card-badges">
          <?php if ($isCarton): ?>
          <span class="p-badge p-badge-blue"><i class="bi bi-tag-fill"></i> <?= formatPrice($item['prix']) ?> / carton</span>
          <span class="p-badge p-badge-blue"><i class="bi bi-box-seam"></i> Vente au carton</span>
          <?php else: ?>
          <span class="p-badge p-badge-green"><i class="bi bi-tag-fill"></i> <?= formatPrice($item['prix']) ?> / kg</span>
          <span class="p-badge p-badge-green"><i class="bi bi-box-seam"></i> Vente au kg</span>
          <?php endif; ?>
        </div>
        <form action="<?= BASE_URL ?>?page=panier_modifier" method="POST" class="qty-form">
          <input type="hidden" name="key" value="<?= htmlspecialchars($key) ?>">
          <div class="qty-stepper">
            <button type="button" class="qty-btn" onclick="chgQty(this,-1)">−</button>
            <input type="number" name="quantite" value="<?= $item['quantite'] ?>" min="0" class="qty-num"
                   onchange="this.form.submit()">
            <button type="button" class="qty-btn" onclick="chgQty(this,1)">+</button>
          </div>
          <button type="submit" class="btn-ok"><i class="bi bi-check2"></i> Mettre à jour</button>
        </form>
      </div>

      <!-- Droite -->
      <div class="p-card-right">
        <div>
          <div class="p-subtotal"><?= formatPrice($item['prix'] * $item['quantite']) ?></div>
          <?php if ($isCarton): ?>
          <div class="p-unit-detail">
              <?= (int)$item['quantite'] ?> carton<?= $item['quantite'] > 1 ? 's' : '' ?>
              <?php if (!empty($item['poids_carton_kg'])): ?>&nbsp;· <?= number_format((float)$item['poids_carton_kg'], 0) ?> kg net / carton<?php endif; ?>
              <br>&times; <?= formatPrice($item['prix']) ?> / carton
          </div>
          <?php else: ?>
          <div class="p-unit-detail"><?= (int)$item['quantite'] ?> kg &times; <?= formatPrice($item['prix']) ?></div>
          <?php endif; ?>
        </div>
        <a href="<?= BASE_URL ?>?page=panier_retirer&key=<?= urlencode($key) ?>"
           class="btn-rm" onclick="return confirm('Retirer cet article ?')" title="Supprimer">
          <i class="bi bi-trash3-fill"></i>
        </a>
      </div>

    </div>
    <?php endforeach; ?>

    <a href="<?= BASE_URL ?>?page=catalogue" class="btn-back">
      <i class="bi bi-arrow-left"></i> Continuer mes achats
    </a>
  </div>

  <!-- DROITE : RECAP -->
  <div>
    <div class="recap">
      <div class="recap-hd">
        <h5 class="recap-hd-title"><i class="bi bi-receipt-cutoff"></i> Récapitulatif de commande</h5>
      </div>
      <div class="recap-bd">

        <!-- Lignes produits -->
        <div class="recap-lines">
          <?php foreach ($contenu as $item):
            $isCar = ($item['unite'] ?? 'kg') === 'carton';
          ?>
          <div class="r-line">
            <span class="r-line-name">
              <?= htmlspecialchars(mb_strimwidth($item['nom'],0,26,'…')) ?> &times;<?= (int)$item['quantite'] ?> <?= $isCar ? 'carton'.($item['quantite']>1?'s':'') : 'kg' ?>
            </span>
            <span class="r-line-val"><?= formatPrice($item['prix'] * $item['quantite']) ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="r-sep"></div>

        <div class="r-row">
          <span class="r-row-label"><i class="bi bi-calculator" style="color:var(--vert);"></i> Sous-total</span>
          <span class="r-row-val"><?= formatPrice($total) ?></span>
        </div>
        <div class="r-row">
          <span class="r-row-label"><i class="bi bi-truck" style="color:var(--vert);"></i> Livraison</span>
          <span class="r-row-val" style="color:var(--vert);">À définir</span>
        </div>
        <div class="r-row">
          <span class="r-row-label"><i class="bi bi-clock" style="color:var(--vert);"></i> Délai</span>
          <span class="r-row-val">24h – 48h</span>
        </div>

        <div class="r-total-row">
          <span class="r-total-label">Total estimé</span>
          <span class="r-total-val"><?= formatPrice($total) ?></span>
        </div>

      </div>

      <div style="padding:24px 26px;border-top:1px solid #f3f4f6;">
        <a href="<?= BASE_URL ?>?page=checkout" class="btn-commander">
          <i class="bi bi-bag-check-fill"></i> Passer la commande
        </a>

        <?php
          $waMsg = "Bonjour Darou Salam 🌿, je souhaite commander :\n";
          foreach($contenu as $it)
            $waMsg .= '• '.$it['nom'].' — '.$it['quantite'].' '.($it['unite']==='carton'?'carton(s)':'kg').' — '.number_format($it['prix']*$it['quantite'],0,',',' ')." FCFA\n";
          $waMsg .= "\n💰 Total : ".number_format($total,0,',',' ').' FCFA';
        ?>
        <a href="https://wa.me/221774715353?text=<?= urlencode($waMsg) ?>" target="_blank" class="btn-wa">
          <i class="bi bi-whatsapp"></i> Commander via WhatsApp
        </a>

        <?php if (!isLoggedIn()): ?>
        <div class="recap-login">
          <i class="bi bi-person-lock" style="color:var(--vert);"></i>
          <a href="<?= BASE_URL ?>?page=login">Connectez-vous</a> pour suivre votre commande en temps réel
        </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Trust badges hors sticky -->
    <div class="trust-grid" style="margin-top:14px;">
      <div class="trust-it"><i class="bi bi-shield-fill-check"></i> Paiement sécurisé</div>
      <div class="trust-it"><i class="bi bi-arrow-repeat"></i> Retour facile</div>
      <div class="trust-it"><i class="bi bi-truck-front"></i> Livraison rapide</div>
      <div class="trust-it"><i class="bi bi-star-fill"></i> Qualité garantie</div>
    </div>
  </div>

</div>
<?php endif; ?>

</div><!-- /container -->

<script>
function chgQty(btn, delta) {
  const form = btn.closest('form');
  const inp  = form.querySelector('.qty-num');
  const nv   = Math.max(0, parseInt(inp.value || 1) + delta);
  if (nv === 0 && !confirm('Retirer cet article du panier ?')) return;
  inp.value = nv;
  form.submit();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
