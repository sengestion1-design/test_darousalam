<?php
$pageTitle = 'Finaliser ma commande — ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
:root{--vert:#1a5c2a;--vert-l:#2d8a42;--orange:#f97316;--or:#d4a017;}
*{box-sizing:border-box;}

.ck-wrap{display:grid;grid-template-columns:1fr 420px;gap:32px;align-items:start;}
@media(max-width:1024px){.ck-wrap{grid-template-columns:1fr;}}

/* HERO */
.ck-hero{
  background:linear-gradient(135deg,#050d07 0%,#0f2d16 55%,#1a5c2a 100%);
  border-radius:22px;padding:40px 44px 36px;margin-bottom:36px;
  display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;
  position:relative;overflow:hidden;
}
.ck-hero::after{
  content:'';position:absolute;right:-60px;top:-60px;
  width:240px;height:240px;border-radius:50%;
  background:rgba(212,160,23,.07);pointer-events:none;
}
.ck-hero-title{
  font-family:'Playfair Display',Georgia,serif;
  font-size:2rem;font-weight:900;color:#fff;
  margin:0 0 8px;display:flex;align-items:center;gap:12px;
}
.ck-hero-title i{color:var(--orange);font-size:1.7rem;}
.ck-hero-sub{font-size:.88rem;color:rgba(255,255,255,.55);display:flex;align-items:center;gap:8px;}
.ck-hero-sub i{color:var(--or);}
.ck-steps{display:flex;gap:10px;flex-wrap:wrap;}
.ck-step{
  display:inline-flex;align-items:center;gap:7px;
  padding:9px 18px;border-radius:50px;font-size:.78rem;font-weight:700;
}
.ck-step-done{background:rgba(255,255,255,.12);color:rgba(255,255,255,.6);border:1px solid rgba(255,255,255,.15);}
.ck-step-active{background:var(--orange);color:#fff;box-shadow:0 4px 14px rgba(249,115,22,.35);}

/* FORM CARD */
.ck-card{
  background:#fff;border-radius:22px;
  border:1.5px solid #f0f0f0;
  box-shadow:0 4px 24px rgba(0,0,0,.07);
  overflow:hidden;
}
.ck-card-hd{
  background:linear-gradient(135deg,#f9fafb,#fff);
  padding:22px 28px;border-bottom:1.5px solid #f0f0f0;
  display:flex;align-items:center;gap:12px;
}
.ck-card-hd-icon{
  width:44px;height:44px;border-radius:14px;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  display:flex;align-items:center;justify-content:center;
  color:var(--vert);font-size:1.2rem;flex-shrink:0;
}
.ck-card-hd-title{font-size:1.05rem;font-weight:800;color:#1c1917;margin:0;}
.ck-card-hd-sub{font-size:.78rem;color:#9ca3af;margin:2px 0 0;}
.ck-card-bd{padding:28px;}

/* Form fields */
.ck-label{
  display:block;font-size:.82rem;font-weight:700;
  color:#374151;margin-bottom:7px;letter-spacing:.01em;
}
.ck-label .req{color:#f97316;}
.ck-input{
  width:100%;padding:13px 16px;
  border:1.5px solid #e5e7eb;border-radius:13px;
  font-size:.9rem;font-family:'DM Sans',sans-serif;color:#1c1917;
  background:#fafafa;transition:all .2s;
}
.ck-input:focus{
  outline:none;border-color:var(--vert);
  background:#fff;box-shadow:0 0 0 4px rgba(26,92,42,.08);
}
.ck-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
@media(max-width:640px){.ck-row{grid-template-columns:1fr;}}
.ck-field{margin-bottom:20px;}

/* Alert */
.ck-alert{
  padding:14px 18px;border-radius:13px;
  background:#fef2f2;border:1.5px solid #fecaca;
  color:#dc2626;font-size:.84rem;font-weight:500;
  display:flex;align-items:center;gap:10px;margin-bottom:22px;
}
.ck-alert i{font-size:1rem;flex-shrink:0;}

/* Payment cards */
.pay-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:8px;}
@media(max-width:480px){.pay-grid{grid-template-columns:1fr;}}
.pay-opt{position:relative;}
.pay-opt input[type=radio]{position:absolute;opacity:0;width:0;height:0;}
.pay-lbl{
  display:flex;flex-direction:column;align-items:center;gap:10px;
  padding:18px 14px;border:2px solid #e5e7eb;border-radius:16px;
  cursor:pointer;transition:all .2s;background:#fafafa;
  text-align:center;
}
.pay-lbl:hover{border-color:#bbf7d0;background:#f0fdf4;}
.pay-opt input:checked + .pay-lbl{
  border-color:var(--vert);background:#f0fdf4;
  box-shadow:0 0 0 4px rgba(26,92,42,.1);
}
.pay-icon{
  width:52px;height:52px;border-radius:14px;
  display:flex;align-items:center;justify-content:center;
  font-size:1.5rem;
}
.pay-icon-cash{background:linear-gradient(135deg,#fffbeb,#fef3c7);color:#d97706;}
.pay-icon-om{background:linear-gradient(135deg,#fff7ed,#fed7aa);color:#ea580c;}
.pay-icon-wave{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;}
.pay-name{font-size:.82rem;font-weight:800;color:#1c1917;}
.pay-sub{font-size:.7rem;color:#9ca3af;}

/* Submit */
.btn-confirm{
  display:flex;align-items:center;justify-content:center;gap:10px;
  width:100%;padding:17px;margin-top:8px;
  background:linear-gradient(135deg,#1a5c2a,#2d8a42);
  color:#fff;border:none;border-radius:14px;
  font-size:1rem;font-weight:800;font-family:'DM Sans',sans-serif;
  cursor:pointer;box-shadow:0 6px 20px rgba(26,92,42,.3);
  transition:all .2s;letter-spacing:.02em;
}
.btn-confirm:hover{background:linear-gradient(135deg,#2d8a42,#3aaa56);transform:translateY(-2px);box-shadow:0 10px 28px rgba(26,92,42,.35);}

/* ORDER SUMMARY */
.recap{
  background:#fff;border-radius:22px;
  border:1.5px solid #f0f0f0;
  box-shadow:0 4px 24px rgba(0,0,0,.07);
  position:sticky;top:120px;
  max-height:calc(100vh - 140px);
  overflow-y:auto;
  scrollbar-width:none;
}
.recap::-webkit-scrollbar{display:none;}
.recap-hd{
  background:linear-gradient(135deg,#050d07 0%,#1a5c2a 100%);
  padding:22px 26px;
}
.recap-hd-title{
  font-family:'Playfair Display',serif;font-size:1.1rem;
  font-weight:800;color:#fff;display:flex;align-items:center;gap:10px;margin:0;
}
.recap-hd-title i{color:var(--orange);}
.recap-bd{padding:22px 24px;}

.r-item{
  display:flex;justify-content:space-between;align-items:center;
  padding:10px 0;border-bottom:1px dashed #f3f4f6;gap:12px;
}
.r-item:last-child{border:none;}
.r-item-left{display:flex;align-items:center;gap:11px;min-width:0;}
.r-item-img{
  width:46px;height:46px;border-radius:10px;
  object-fit:cover;flex-shrink:0;border:1.5px solid #f0fdf4;
}
.r-item-img-ph{
  width:46px;height:46px;border-radius:10px;flex-shrink:0;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  display:flex;align-items:center;justify-content:center;
  color:#16a34a;font-size:1.1rem;
}
.r-item-name{font-size:.82rem;font-weight:700;color:#1c1917;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:150px;}
.r-item-qty{font-size:.72rem;color:#9ca3af;}
.r-item-price{font-size:.88rem;font-weight:800;color:#1c1917;flex-shrink:0;}

.r-sep{height:1px;background:linear-gradient(90deg,transparent,#e5e7eb 30%,#e5e7eb 70%,transparent);margin:16px 0;}

.r-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;}
.r-row-label{font-size:.82rem;color:#6b7280;display:flex;align-items:center;gap:6px;}
.r-row-val{font-size:.82rem;font-weight:700;color:#1c1917;}

.r-total{
  display:flex;justify-content:space-between;align-items:center;
  padding:16px 18px;margin:16px 0 0;
  background:linear-gradient(135deg,#f0fdf4,#dcfce7);
  border-top:2px solid #bbf7d0;border-radius:12px;
}
.r-total-label{font-size:1rem;font-weight:800;color:#1c1917;}
.r-total-val{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:var(--vert);}

.recap-note{
  margin-top:16px;padding:12px 14px;
  background:#fffbeb;border-radius:11px;border:1px solid #fde68a;
  font-size:.75rem;color:#92400e;display:flex;align-items:flex-start;gap:8px;
}
.recap-note i{font-size:.9rem;flex-shrink:0;margin-top:1px;}

.btn-back-ck{
  display:flex;align-items:center;justify-content:center;gap:8px;
  margin-top:12px;padding:13px;
  border:1.5px solid #e5e7eb;border-radius:14px;
  color:#6b7280;font-size:.84rem;font-weight:600;
  text-decoration:none;transition:all .2s;background:#fff;
}
.btn-back-ck:hover{border-color:var(--vert);color:var(--vert);background:#f0fdf4;}
</style>

<div class="container py-4">

<!-- HERO -->
<div class="ck-hero">
  <div>
    <div class="ck-hero-title"><i class="bi bi-bag-check-fill"></i> Finaliser ma commande</div>
    <div class="ck-hero-sub"><i class="bi bi-lock-fill"></i> Commande sécurisée &mdash; Livraison Dakar &amp; sous-région</div>
  </div>
  <div class="ck-steps">
    <span class="ck-step ck-step-done"><i class="bi bi-bag-check"></i> Panier</span>
    <span class="ck-step ck-step-active"><i class="bi bi-truck"></i> Livraison</span>
    <span class="ck-step ck-step-done" style="opacity:.4;"><i class="bi bi-check-circle"></i> Confirmation</span>
  </div>
</div>

<div class="ck-wrap">

  <!-- GAUCHE : FORMULAIRE -->
  <div>
    <form method="POST" action="<?= BASE_URL ?>?page=checkout" novalidate id="ck-form">

      <div class="ck-card">
        <div class="ck-card-hd">
          <div class="ck-card-hd-icon"><i class="bi bi-geo-alt-fill"></i></div>
          <div>
            <div class="ck-card-hd-title">Adresse de livraison</div>
            <div class="ck-card-hd-sub">Renseignez vos informations pour recevoir votre commande</div>
          </div>
        </div>
        <div class="ck-card-bd">

          <?php if (!empty($error)): ?>
          <div class="ck-alert">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <?= htmlspecialchars($error) ?>
          </div>
          <?php endif; ?>

          <div class="ck-field">
            <label class="ck-label">Adresse complète <span class="req">*</span></label>
            <input type="text" class="ck-input" name="adresse" placeholder="Ex : Rue 47×37, Médina, Dakar"
                   value="<?= htmlspecialchars($_POST['adresse'] ?? ($client['adresse'] ?? '')) ?>" required>
          </div>

          <div class="ck-row ck-field">
            <div>
              <label class="ck-label">Zone de livraison <span class="req">*</span></label>
              <?php
              $db = Database::getInstance();
              $zonesDispos = $db->fetchAll("SELECT * FROM zones_livraison WHERE actif = 1 ORDER BY frais ASC, nom ASC");
              $selectedZoneId = (int)($_POST['zone_id'] ?? 0);
              ?>
              <select name="zone_id" id="zoneSelect" class="ck-input" required onchange="onZoneChange(this)">
                <option value="">— Choisir une zone —</option>
                <?php foreach ($zonesDispos as $z): ?>
                <option value="<?= $z['id'] ?>"
                  data-frais="<?= (int)$z['frais'] ?>"
                  data-gratuit="<?= $z['frais_gratuit_si'] ?? '' ?>"
                  data-min="<?= (int)$z['min_commande'] ?>"
                  data-nom="<?= htmlspecialchars($z['nom']) ?>"
                  data-delai="<?= (int)$z['delai_jours'] ?>"
                  <?= $selectedZoneId === (int)$z['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($z['nom']) ?>
                  <?php if ((float)$z['frais'] == 0): ?> — Gratuit
                  <?php elseif (!is_null($z['frais_gratuit_si'])): ?> — <?= number_format((int)$z['frais'], 0, ',', ' ') ?> FCFA (gratuit ≥ <?= number_format((int)$z['frais_gratuit_si'], 0, ',', ' ') ?> FCFA)
                  <?php else: ?> — <?= number_format((int)$z['frais'], 0, ',', ' ') ?> FCFA
                  <?php endif; ?>
                </option>
                <?php endforeach; ?>
              </select>
              <!-- Info zone sélectionnée -->
              <div id="zoneInfo" style="margin-top:8px;display:none;font-size:.78rem;padding:8px 12px;border-radius:8px;background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;"></div>
              <div id="zoneWarning" style="margin-top:8px;display:none;font-size:.78rem;padding:8px 12px;border-radius:8px;background:#fef3c7;border:1px solid #fde68a;color:#92400e;"></div>
            </div>
            <div>
              <label class="ck-label">Téléphone <span class="req">*</span></label>
              <input type="tel" class="ck-input" name="telephone" placeholder="Ex : 77 471 53 53"
                     value="<?= htmlspecialchars($_POST['telephone'] ?? ($client['telephone'] ?? '')) ?>" required>
            </div>
          </div>

          <!-- MODE DE PAIEMENT -->
          <div class="ck-field" style="margin-top:4px;margin-bottom:0;">
            <label class="ck-label" style="margin-bottom:10px;">Mode de paiement</label>
            <div class="pay-grid">

              <div class="pay-opt">
                <input type="radio" name="mode_paiement" value="a_la_livraison" id="mode_cash"
                       <?= ($_POST['mode_paiement'] ?? 'a_la_livraison') === 'a_la_livraison' ? 'checked' : '' ?>>
                <label class="pay-lbl" for="mode_cash">
                  <div class="pay-icon pay-icon-cash"><i class="bi bi-cash-coin"></i></div>
                  <div class="pay-name">À la livraison</div>
                  <div class="pay-sub">Cash à réception</div>
                </label>
              </div>

              <div class="pay-opt">
                <input type="radio" name="mode_paiement" value="orange_money" id="mode_om"
                       <?= ($_POST['mode_paiement'] ?? '') === 'orange_money' ? 'checked' : '' ?>>
                <label class="pay-lbl" for="mode_om">
                  <div class="pay-icon pay-icon-om"><i class="bi bi-phone-fill"></i></div>
                  <div class="pay-name">Orange Money</div>
                  <div class="pay-sub">Paiement mobile</div>
                </label>
              </div>

              <div class="pay-opt">
                <input type="radio" name="mode_paiement" value="wave" id="mode_wave"
                       <?= ($_POST['mode_paiement'] ?? '') === 'wave' ? 'checked' : '' ?>>
                <label class="pay-lbl" for="mode_wave">
                  <div class="pay-icon pay-icon-wave"><i class="bi bi-phone-fill"></i></div>
                  <div class="pay-name">Wave</div>
                  <div class="pay-sub">Paiement mobile</div>
                </label>
              </div>

            </div>
          </div>

        </div>
      </div>

      <!-- Bouton en dehors de la carte, toujours visible -->
      <button type="submit" class="btn-confirm" style="margin-top:16px;">
        <i class="bi bi-check-circle-fill"></i> Confirmer la commande
      </button>
      <a href="<?= BASE_URL ?>?page=panier" class="btn-back-ck">
        <i class="bi bi-arrow-left"></i> Retour au panier
      </a>

    </form>
  </div>

  <!-- DROITE : RÉCAPITULATIF -->
  <div>
    <div class="recap">
      <div class="recap-hd">
        <h5 class="recap-hd-title"><i class="bi bi-receipt-cutoff"></i> Votre commande</h5>
      </div>
      <div class="recap-bd">

        <?php foreach ($contenu as $item):
          $imgUrl = !empty($item['image']) ? '/darousalam/' . $item['image'] : null;
        ?>
        <div class="r-item">
          <div class="r-item-left">
            <?php if ($imgUrl): ?>
            <img src="<?= htmlspecialchars($imgUrl) ?>" class="r-item-img" alt=""
                 onerror="this.outerHTML='<div class=\'r-item-img-ph\'><i class=\'bi bi-basket2-fill\'></i></div>'">
            <?php else: ?>
            <div class="r-item-img-ph"><i class="bi bi-basket2-fill"></i></div>
            <?php endif; ?>
            <div>
              <div class="r-item-name"><?= htmlspecialchars($item['nom']) ?></div>
              <?php if (($item['unite'] ?? 'kg') === 'carton'): ?>
              <span style="display:inline-flex;align-items:center;gap:3px;font-size:.65rem;font-weight:700;color:#0284c7;background:#f0f9ff;border:1px solid #bae6fd;border-radius:6px;padding:1px 7px;margin-top:2px;">
                  <i class="bi bi-box-seam"></i> Carton
              </span>
              <?php endif; ?>
              <?php $isCarton = ($item['unite'] ?? 'kg') === 'carton'; ?>
              <div class="r-item-qty">
                  <?= (int)$item['quantite'] ?> <?= $isCarton ? 'carton'.($item['quantite']>1?'s':'') : 'kg' ?>
                  <?php if ($isCarton && !empty($item['poids_carton_kg'])): ?>&nbsp;· <?= number_format((float)$item['poids_carton_kg'], 0) ?> kg net / carton<?php endif; ?><br>
                  &times; <?= formatPrice($item['prix']) ?> / <?= $isCarton ? 'carton' : 'kg' ?>
              </div>
            </div>
          </div>
          <div class="r-item-price"><?= formatPrice($item['prix'] * $item['quantite']) ?></div>
        </div>
        <?php endforeach; ?>

        <div class="r-sep"></div>

        <!-- CODE PROMO -->
        <?php
        // Récupérer la promo active pour le bouton rapide
        try {
            $_dbCo = Database::getInstance();
            $_promoActive = $_dbCo->fetch(
                "SELECT code, type, valeur FROM promotions WHERE actif = 1
                 AND (date_debut IS NULL OR date_debut <= CURDATE())
                 AND (date_fin IS NULL OR date_fin >= CURDATE())
                 AND (usage_max IS NULL OR usage_count < usage_max)
                 ORDER BY valeur DESC LIMIT 1"
            );
        } catch (Exception $e) { $_promoActive = null; }
        ?>
        <div style="margin-bottom:14px;">
          <label style="font-size:.78rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:6px;">
            <i class="bi bi-tag-fill" style="color:var(--orange);"></i> Code promo
          </label>

          <?php if (!empty($_promoActive)): ?>
          <button type="button" onclick="utiliserPromoActive('<?= htmlspecialchars($_promoActive['code']) ?>')"
            style="width:100%;background:linear-gradient(90deg,#d4a017,#b8880e);color:#fff;border:none;border-radius:8px;padding:9px 14px;font-size:.85rem;font-weight:700;cursor:pointer;margin-bottom:8px;display:flex;align-items:center;justify-content:center;gap:8px;letter-spacing:.3px;transition:opacity .2s;">
            <i class="bi bi-lightning-charge-fill"></i>
            Utiliser le code
            <span style="background:rgba(255,255,255,.25);border-radius:5px;padding:1px 8px;font-family:monospace;letter-spacing:2px;"><?= htmlspecialchars($_promoActive['code']) ?></span>
            <span style="opacity:.85;font-weight:400;">
              (<?= $_promoActive['type'] === 'pourcentage' ? '-'.(int)$_promoActive['valeur'].'%' : '-'.number_format((float)$_promoActive['valeur'],0,',',' ').' FCFA' ?>)
            </span>
          </button>
          <?php endif; ?>

          <div style="display:flex;gap:8px;">
            <input type="text" id="promo-input" placeholder="Ou saisir un code manuellement"
              style="flex:1;border:1.5px solid #e5e7eb;border-radius:8px;padding:8px 12px;font-size:.9rem;font-family:monospace;text-transform:uppercase;outline:none;transition:border-color .2s;"
              onfocus="this.style.borderColor='var(--vert)'" onblur="this.style.borderColor='#e5e7eb'">
            <button type="button" id="promo-btn" onclick="appliquerPromo()"
              style="background:var(--vert);color:#fff;border:none;border-radius:8px;padding:8px 14px;font-size:.85rem;font-weight:600;cursor:pointer;white-space:nowrap;transition:background .2s;">
              Appliquer
            </button>
          </div>
          <div id="promo-msg" style="font-size:.8rem;margin-top:6px;display:none;"></div>
        </div>

        <div class="r-row">
          <span class="r-row-label"><i class="bi bi-calculator" style="color:var(--vert);"></i> Sous-total</span>
          <span class="r-row-val"><?= formatPrice($total) ?></span>
        </div>
        <div class="r-row" id="row-remise" style="display:none;">
          <span class="r-row-label"><i class="bi bi-scissors" style="color:#16a34a;"></i> Remise</span>
          <span class="r-row-val" id="val-remise" style="color:#16a34a;font-weight:700;"></span>
        </div>
        <div class="r-row" id="row-livraison">
          <span class="r-row-label"><i class="bi bi-truck" style="color:var(--vert);"></i> Livraison</span>
          <span class="r-row-val" id="val-livraison" style="color:#9ca3af;">Choisir une zone</span>
        </div>
        <div class="r-row" id="row-delai" style="display:none;">
          <span class="r-row-label"><i class="bi bi-clock" style="color:#9ca3af;"></i> Délai estimé</span>
          <span class="r-row-val" id="val-delai" style="color:#9ca3af;font-size:.82rem;"></span>
        </div>

        <div class="r-total">
          <span class="r-total-label">Total estimé</span>
          <span class="r-total-val" id="total-final"><?= formatPrice($total) ?></span>
        </div>

        <!-- Champs hidden pour le POST -->
        <input type="hidden" name="code_promo" id="hidden-code-promo" form="ck-form">
        <input type="hidden" name="remise_calculee" id="hidden-remise" form="ck-form" value="0">
        <input type="hidden" name="frais_livraison_calc" id="hidden-frais-livraison" form="ck-form" value="0">

        <div class="recap-note" id="recap-note-zone">
          <i class="bi bi-info-circle-fill"></i>
          Sélectionnez votre zone pour voir les frais de livraison.
        </div>

      </div>
    </div>
  </div>

</div><!-- /ck-wrap -->
</div><!-- /container -->

<script>
var sousTotal = <?= $total ?>;
var remiseAppliquee = 0;
var fraisLivraison = 0;

function recalcTotal() {
    var t = sousTotal - remiseAppliquee + fraisLivraison;
    document.getElementById('total-final').textContent = formatFCFA(Math.max(0, t));
}

function setInfoBox(el, iconClass, text) {
    el.style.display = 'block';
    el.textContent = '';
    var i = document.createElement('i');
    i.className = iconClass + ' me-1';
    el.appendChild(i);
    el.appendChild(document.createTextNode(text));
}

function onZoneChange(sel) {
    var opt = sel.options[sel.selectedIndex];
    var info     = document.getElementById('zoneInfo');
    var warn     = document.getElementById('zoneWarning');
    var valLiv   = document.getElementById('val-livraison');
    var rowDelai = document.getElementById('row-delai');
    var valDelai = document.getElementById('val-delai');
    var note     = document.getElementById('recap-note-zone');

    if (!opt.value) {
        fraisLivraison = 0;
        document.getElementById('hidden-frais-livraison').value = 0;
        valLiv.textContent = 'Choisir une zone';
        valLiv.style.color = '#9ca3af';
        info.style.display = 'none';
        warn.style.display = 'none';
        rowDelai.style.display = 'none';
        recalcTotal();
        return;
    }

    var frais   = parseInt(opt.dataset.frais)   || 0;
    var gratuit = opt.dataset.gratuit !== '' ? parseInt(opt.dataset.gratuit) : null;
    var min     = parseInt(opt.dataset.min)     || 0;
    var nom     = opt.dataset.nom;
    var delai   = parseInt(opt.dataset.delai)   || 1;
    var panier  = sousTotal - remiseAppliquee;

    warn.style.display = 'none';
    info.style.display = 'none';

    if (min > 0 && panier < min) {
        setInfoBox(warn, 'bi bi-exclamation-triangle-fill', 'Commande minimum de ' + formatFCFA(min) + ' requise pour ' + nom + '. Il manque ' + formatFCFA(min - panier) + '.');
    }

    var fraisReels = frais;
    if (gratuit !== null && panier >= gratuit) {
        fraisReels = 0;
        setInfoBox(info, 'bi bi-gift-fill', 'Livraison gratuite pour ' + nom + ' (commande >= ' + formatFCFA(gratuit) + ') !');
    } else if (frais === 0) {
        fraisReels = 0;
        setInfoBox(info, 'bi bi-gift-fill', 'Livraison gratuite pour la zone ' + nom + ' !');
    } else if (gratuit !== null) {
        setInfoBox(info, 'bi bi-info-circle', 'Livraison gratuite si >= ' + formatFCFA(gratuit) + ' (il manque ' + formatFCFA(gratuit - panier) + ').');
    }

    fraisLivraison = fraisReels;
    document.getElementById('hidden-frais-livraison').value = fraisReels;

    if (fraisReels === 0) {
        valLiv.textContent = 'Offerte';
        valLiv.style.color = '#16a34a';
    } else {
        valLiv.textContent = formatFCFA(fraisReels);
        valLiv.style.color = '#f97316';
    }

    rowDelai.style.display = '';
    valDelai.textContent = delai + ' jour' + (delai > 1 ? 's' : '');

    note.textContent = 'Zone : ' + nom + ' — Livraison sous ' + delai + 'j.';

    recalcTotal();
}

window.addEventListener('DOMContentLoaded', function() {
    var sel = document.getElementById('zoneSelect');
    if (sel && sel.value) onZoneChange(sel);
});

function formatFCFA(n) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(n)) + ' FCFA';
}

function utiliserPromoActive(code) {
    var input = document.getElementById('promo-input');
    input.value = code;
    input.disabled = false;
    appliquerPromo();
}

function appliquerPromo() {
    var code = document.getElementById('promo-input').value.trim().toUpperCase();
    var msg  = document.getElementById('promo-msg');
    var btn  = document.getElementById('promo-btn');

    if (!code) {
        afficherMsg('Veuillez saisir un code promo.', false);
        return;
    }

    btn.disabled = true;
    btn.textContent = '...';

    var fd = new FormData();
    fd.append('code', code);
    fd.append('total', sousTotal);

    fetch('/darousalam/?page=valider_code_promo', { method: 'POST', body: fd })
        .then(function(r){ return r.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.textContent = 'Appliquer';

            if (data.success) {
                remiseAppliquee = data.remise;
                document.getElementById('hidden-code-promo').value = code;
                document.getElementById('hidden-remise').value = remiseAppliquee;
                document.getElementById('row-remise').style.display = '';
                document.getElementById('val-remise').textContent = '-' + formatFCFA(remiseAppliquee);
                document.getElementById('total-final').textContent = formatFCFA(data.total_apres + fraisLivraison);
                document.getElementById('promo-input').disabled = true;
                btn.style.background = '#16a34a';
                btn.textContent = '✓ Appliqué';
                afficherMsg('Code "' + code + '" appliqué — ' + (data.type === 'pourcentage' ? '-' + data.valeur + '%' : '-' + formatFCFA(data.remise)), true);
            } else {
                remiseAppliquee = 0;
                document.getElementById('hidden-code-promo').value = '';
                document.getElementById('hidden-remise').value = 0;
                document.getElementById('row-remise').style.display = 'none';
                document.getElementById('total-final').textContent = formatFCFA(sousTotal + fraisLivraison);
                afficherMsg(data.message, false);
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.textContent = 'Appliquer';
            afficherMsg('Erreur réseau. Réessayez.', false);
        });
}

function afficherMsg(txt, ok) {
    var el = document.getElementById('promo-msg');
    el.style.display = 'block';
    el.style.color = ok ? '#16a34a' : '#dc2626';
    el.textContent = txt;
}

document.getElementById('promo-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); appliquerPromo(); }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
