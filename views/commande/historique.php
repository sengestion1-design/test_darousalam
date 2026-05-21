<?php
$pageTitle = 'Mes commandes — ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
:root{--vert:#1a5c2a;--vert-l:#2d8a42;--orange:#f97316;--or:#d4a017;}
*{box-sizing:border-box;}

/* HERO */
.hist-hero{
  background:linear-gradient(135deg,#050d07 0%,#0f2d16 55%,#1a5c2a 100%);
  border-radius:22px;padding:44px 44px 40px;margin-bottom:28px;
  position:relative;overflow:hidden;
}
.hist-hero::before{content:'';position:absolute;right:-80px;top:-80px;width:260px;height:260px;border-radius:50%;background:rgba(212,160,23,.07);pointer-events:none;}
.hist-hero::after{content:'';position:absolute;left:-60px;bottom:-60px;width:200px;height:200px;border-radius:50%;background:rgba(249,115,22,.06);pointer-events:none;}
.hist-hero-inner{display:flex;align-items:flex-start;justify-content:space-between;gap:20px;flex-wrap:wrap;position:relative;z-index:2;}
.hist-hero-title{font-family:'Playfair Display',Georgia,serif;font-size:2rem;font-weight:900;color:#fff;margin:0 0 8px;display:flex;align-items:center;gap:12px;}
.hist-hero-title i{color:var(--orange);}
.hist-hero-sub{font-size:.87rem;color:rgba(255,255,255,.5);display:flex;align-items:center;gap:7px;margin-bottom:20px;}
.hist-hero-sub i{color:var(--or);}
.hist-pills{display:flex;gap:10px;flex-wrap:wrap;}
.hist-pill{display:inline-flex;align-items:center;gap:7px;padding:9px 18px;border-radius:50px;font-size:.78rem;font-weight:700;}
.hist-pill-ghost{background:rgba(255,255,255,.1);color:rgba(255,255,255,.8);border:1px solid rgba(255,255,255,.15);}
.hist-pill-orange{background:var(--orange);color:#fff;box-shadow:0 4px 14px rgba(249,115,22,.3);}

/* STATS */
.hist-stats{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;}
@media(max-width:900px){.hist-stats{grid-template-columns:repeat(2,1fr);}}
@media(max-width:480px){.hist-stats{grid-template-columns:1fr;}}
.stat-card{background:#fff;border-radius:18px;padding:22px 20px;border:1.5px solid #f0f0f0;box-shadow:0 2px 12px rgba(0,0,0,.05);display:flex;align-items:center;gap:16px;}
.stat-icon{width:50px;height:50px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;}
.stat-icon-green{background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#16a34a;}
.stat-icon-orange{background:linear-gradient(135deg,#fff7ed,#fed7aa);color:#ea580c;}
.stat-icon-blue{background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;}
.stat-icon-purple{background:linear-gradient(135deg,#faf5ff,#ede9fe);color:#7c3aed;}
.stat-val{font-family:'Playfair Display',serif;font-size:1.35rem;font-weight:900;color:#1c1917;line-height:1.1;}
.stat-label{font-size:.72rem;color:#9ca3af;margin-top:3px;}

/* TABLE CARD */
.hist-card{background:#fff;border-radius:22px;border:1.5px solid #f0f0f0;box-shadow:0 4px 20px rgba(0,0,0,.06);overflow:hidden;margin-bottom:24px;}
.hist-card-hd{padding:18px 24px;border-bottom:1.5px solid #f5f5f5;display:flex;align-items:center;justify-content:space-between;gap:12px;background:#fafafa;}
.hist-card-title{font-size:.95rem;font-weight:800;color:#1c1917;display:flex;align-items:center;gap:9px;margin:0;}
.hist-card-title i{color:var(--vert);}
.hist-count{font-size:.78rem;color:#9ca3af;font-weight:500;}

/* TABLE */
.hist-table{width:100%;border-collapse:collapse;}
.hist-table th{padding:12px 20px;font-size:.7rem;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:.07em;background:#fafafa;border-bottom:1.5px solid #f0f0f0;white-space:nowrap;}
.hist-table td{padding:18px 20px;border-bottom:1px solid #f5f5f5;vertical-align:middle;}
.hist-table tr:last-child td{border-bottom:none;}
.hist-table tr:hover td{background:#fafcff;}

.ref-badge{display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;border-radius:8px;padding:5px 11px;font-size:.78rem;font-weight:800;}
.date-cell{font-size:.82rem;color:#374151;}
.date-sub{font-size:.7rem;color:#d1d5db;margin-top:2px;}
.price-cell{font-family:'Playfair Display',serif;font-size:1rem;font-weight:900;color:var(--vert);}

/* Statut */
.s-badge{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap;}
.s-en_attente{background:#fffbeb;color:#92400e;border:1px solid #fde68a;}
.s-confirmee{background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;}
.s-en_preparation{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
.s-en_livraison{background:#f0f9ff;color:#0369a1;border:1px solid #bae6fd;}
.s-livree{background:#f0fdf4;color:#15803d;border:1px solid #86efac;}
.s-annulee{background:#fef2f2;color:#dc2626;border:1px solid #fecaca;}

/* Aperçu articles */
.items-preview{display:flex;align-items:center;gap:6px;flex-wrap:wrap;}
.item-thumb{width:34px;height:34px;border-radius:8px;object-fit:cover;border:2px solid #f0fdf4;flex-shrink:0;}
.item-thumb-ph{width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);display:flex;align-items:center;justify-content:center;color:#16a34a;font-size:.85rem;flex-shrink:0;}
.items-more{font-size:.72rem;color:#9ca3af;font-weight:600;white-space:nowrap;}

.btn-voir{display:inline-flex;align-items:center;gap:5px;padding:8px 15px;border-radius:10px;background:#f0fdf4;color:var(--vert);border:1.5px solid #bbf7d0;font-size:.78rem;font-weight:700;text-decoration:none;transition:all .15s;}
.btn-voir:hover{background:var(--vert);color:#fff;border-color:var(--vert);}
.btn-annuler{display:inline-flex;align-items:center;gap:4px;padding:8px 10px;border-radius:10px;background:#fef2f2;color:#dc2626;border:1.5px solid #fecaca;font-size:.78rem;font-weight:700;text-decoration:none;transition:all .15s;margin-left:6px;}
.btn-annuler:hover{background:#dc2626;color:#fff;border-color:#dc2626;}
.btn-pdf{display:inline-flex;align-items:center;gap:4px;padding:8px 10px;border-radius:10px;background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;font-size:.78rem;font-weight:700;text-decoration:none;transition:all .15s;margin-left:6px;}
.btn-pdf:hover{background:#2563eb;color:#fff;border-color:#2563eb;}

/* REASSURANCE */
.reassurance{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
@media(max-width:900px){.reassurance{grid-template-columns:repeat(2,1fr);}}
.rea-card{background:#fff;border-radius:16px;padding:18px 16px;border:1.5px solid #f0f0f0;text-align:center;}
.rea-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin:0 auto 10px;}
.rea-title{font-size:.82rem;font-weight:800;color:#1c1917;margin-bottom:3px;}
.rea-sub{font-size:.72rem;color:#9ca3af;}

/* AIDE CARD */
.aide-card{background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:20px;border:1.5px solid #bbf7d0;padding:28px 28px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;margin-bottom:24px;}
.aide-left{display:flex;align-items:center;gap:16px;}
.aide-icon{width:52px;height:52px;border-radius:15px;background:#fff;display:flex;align-items:center;justify-content:center;font-size:1.3rem;color:var(--vert);box-shadow:0 2px 8px rgba(26,92,42,.1);flex-shrink:0;}
.aide-title{font-size:1rem;font-weight:800;color:#1c1917;margin-bottom:3px;}
.aide-sub{font-size:.8rem;color:#6b7280;}
.aide-btns{display:flex;gap:10px;flex-wrap:wrap;}
.aide-btn-wa{display:inline-flex;align-items:center;gap:7px;padding:11px 20px;background:#25d366;color:#fff;border-radius:12px;font-size:.84rem;font-weight:700;text-decoration:none;transition:all .2s;}
.aide-btn-wa:hover{background:#1da851;color:#fff;}
.aide-btn-cat{display:inline-flex;align-items:center;gap:7px;padding:11px 20px;background:#fff;color:var(--vert);border:1.5px solid #bbf7d0;border-radius:12px;font-size:.84rem;font-weight:700;text-decoration:none;transition:all .2s;}
.aide-btn-cat:hover{background:var(--vert);color:#fff;border-color:var(--vert);}

/* VIDE */
.hist-vide{padding:80px 24px;text-align:center;}
.hist-vide-icon{width:110px;height:110px;border-radius:50%;background:linear-gradient(135deg,#f0fdf4,#eff6ff);display:flex;align-items:center;justify-content:center;margin:0 auto 22px;font-size:2.8rem;color:#d1d5db;}
.hist-vide h3{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:800;color:#1c1917;margin-bottom:8px;}
.hist-vide p{color:#9ca3af;font-size:.88rem;margin-bottom:24px;}
.btn-shop{display:inline-flex;align-items:center;gap:9px;padding:14px 28px;background:var(--vert);color:#fff;border-radius:14px;font-weight:700;font-size:.9rem;text-decoration:none;box-shadow:0 4px 14px rgba(26,92,42,.25);transition:all .2s;}
.btn-shop:hover{background:var(--vert-l);color:#fff;transform:translateY(-2px);}
</style>

<div class="container py-4">

<!-- HERO -->
<div class="hist-hero">
  <div class="hist-hero-inner">
    <div>
      <div class="hist-hero-title"><i class="bi bi-bag-check-fill"></i> Mes commandes</div>
      <div class="hist-hero-sub"><i class="bi bi-shield-fill-check"></i> Historique complet de vos achats chez Darou Salam</div>
      <div class="hist-pills">
        <span class="hist-pill hist-pill-ghost"><i class="bi bi-clock-history"></i> <?= count($commandes) ?> commande<?= count($commandes) > 1 ? 's' : '' ?></span>
        <span class="hist-pill hist-pill-ghost"><i class="bi bi-truck-front-fill"></i> Livraison Dakar & sous-région</span>
      </div>
    </div>
    <a href="<?= BASE_URL ?>?page=catalogue" class="hist-pill hist-pill-orange" style="text-decoration:none;">
      <i class="bi bi-plus-circle-fill"></i> Nouvelle commande
    </a>
  </div>
</div>

<?php if (!empty($commandes)):
  $totalDepense = array_sum(array_column($commandes, 'total'));
  $nbLivrees    = count(array_filter($commandes, fn($c) => $c['statut'] === 'livree'));
  $nbEnCours    = count(array_filter($commandes, fn($c) => !in_array($c['statut'], ['livree','annulee'])));
  $nbAnnulees   = count(array_filter($commandes, fn($c) => $c['statut'] === 'annulee'));

  // Charger les lignes de chaque commande pour aperçu
  $commandeModel = new Commande();
  $lignesParCmd  = [];
  foreach ($commandes as $cmd) {
      $lignesParCmd[$cmd['id']] = $commandeModel->getLignes($cmd['id']);
  }
?>

<!-- STATS -->
<div class="hist-stats">
  <div class="stat-card">
    <div class="stat-icon stat-icon-green"><i class="bi bi-bag-fill"></i></div>
    <div>
      <div class="stat-val"><?= count($commandes) ?></div>
      <div class="stat-label">Commandes totales</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-orange"><i class="bi bi-currency-exchange"></i></div>
    <div>
      <div class="stat-val"><?= formatPrice($totalDepense) ?></div>
      <div class="stat-label">Total dépensé</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-blue"><i class="bi bi-hourglass-split"></i></div>
    <div>
      <div class="stat-val"><?= $nbEnCours ?></div>
      <div class="stat-label">En cours</div>
    </div>
  </div>
  <div class="stat-card">
    <div class="stat-icon stat-icon-purple"><i class="bi bi-check-circle-fill"></i></div>
    <div>
      <div class="stat-val"><?= $nbLivrees ?></div>
      <div class="stat-label">Livrées</div>
    </div>
  </div>
</div>

<!-- TABLEAU -->
<div class="hist-card">
  <div class="hist-card-hd">
    <h5 class="hist-card-title"><i class="bi bi-list-ul"></i> Liste des commandes</h5>
    <span class="hist-count"><?= count($commandes) ?> résultat<?= count($commandes) > 1 ? 's' : '' ?></span>
  </div>
  <div style="overflow-x:auto;">
    <table class="hist-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Articles</th>
          <th>Date</th>
          <th>Total</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($commandes as $cmd):
          $statutLabels = [
            'en_attente'     => ['label' => 'En attente',     'icon' => 'bi-hourglass'],
            'confirmee'      => ['label' => 'Confirmée',      'icon' => 'bi-check-circle'],
            'en_preparation' => ['label' => 'En préparation', 'icon' => 'bi-box-seam'],
            'en_livraison'   => ['label' => 'En livraison',   'icon' => 'bi-truck'],
            'livree'         => ['label' => 'Livrée',         'icon' => 'bi-check-circle-fill'],
            'annulee'        => ['label' => 'Annulée',        'icon' => 'bi-x-circle-fill'],
          ];
          $s = $statutLabels[$cmd['statut']] ?? ['label' => $cmd['statut'], 'icon' => 'bi-circle'];
          $lignes = $lignesParCmd[$cmd['id']] ?? [];
          $maxShow = 3;
        ?>
        <tr data-commande-id="<?= $cmd['id'] ?>">
          <td>
            <span class="ref-badge">
              <i class="bi bi-receipt"></i>
              <?= htmlspecialchars($cmd['reference']) ?>
            </span>
          </td>
          <td>
            <div class="items-preview">
              <?php foreach (array_slice($lignes, 0, $maxShow) as $lg):
                $img = !empty($lg['image_principale']) ? BASE_URL . 'public/uploads/' . $lg['image_principale'] : null;
              ?>
                <?php if ($img): ?>
                <img src="<?= htmlspecialchars($img) ?>" class="item-thumb" alt="<?= htmlspecialchars($lg['nom_produit']) ?>"
                     title="<?= htmlspecialchars($lg['nom_produit']) ?>"
                     onerror="this.style.display='none'">
                <?php else: ?>
                <div class="item-thumb-ph" title="<?= htmlspecialchars($lg['nom_produit']) ?>"><i class="bi bi-basket2-fill"></i></div>
                <?php endif; ?>
              <?php endforeach; ?>
              <?php if (count($lignes) > $maxShow): ?>
              <span class="items-more">+<?= count($lignes) - $maxShow ?> art.</span>
              <?php elseif (!empty($lignes)): ?>
              <span style="font-size:.75rem;color:#6b7280;font-weight:600;margin-left:2px;"><?= count($lignes) ?> art.</span>
              <?php else: ?>
              <span style="font-size:.75rem;color:#d1d5db;">—</span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <div class="date-cell"><i class="bi bi-calendar3" style="color:var(--or);margin-right:4px;"></i><?= date('d/m/Y', strtotime($cmd['created_at'])) ?></div>
            <div class="date-sub"><?= date('H:i', strtotime($cmd['created_at'])) ?></div>
          </td>
          <td class="price-cell"><?= formatPrice((float)$cmd['total']) ?></td>
          <td>
            <span class="s-badge s-<?= $cmd['statut'] ?>" data-statut="<?= $cmd['statut'] ?>">
              <i class="bi <?= $s['icon'] ?>"></i> <span><?= $s['label'] ?></span>
            </span>
          </td>
          <td>
            <a href="<?= BASE_URL ?>?page=commande_detail&id=<?= $cmd['id'] ?>" class="btn-voir">
              <i class="bi bi-eye-fill"></i> Voir
            </a>
            <?php if (in_array($cmd['statut'], ['confirmee','en_preparation','en_cours','en_livraison','livree'])): ?>
            <a href="<?= BASE_URL ?>?page=commande_facture&id=<?= $cmd['id'] ?>"
               class="btn-pdf" title="Télécharger la facture PDF" target="_blank">
              <i class="bi bi-file-earmark-pdf-fill"></i>
            </a>
            <?php endif; ?>
            <?php if (in_array($cmd['statut'], ['en_attente', 'confirmee'])): ?>
            <button type="button" class="btn-annuler" onclick="ouvrirModalAnnulation(<?= $cmd['id'] ?>)" title="Annuler">
              <i class="bi bi-x-lg"></i>
            </button>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php if ($paginationHtml): ?>
<div class="mb-4"><?= $paginationHtml ?></div>
<?php endif; ?>

<!-- AIDE & CONTACT -->
<div class="aide-card">
  <div class="aide-left">
    <div class="aide-icon"><i class="bi bi-headset"></i></div>
    <div>
      <div class="aide-title">Besoin d'aide avec une commande ?</div>
      <div class="aide-sub">Notre équipe vous répond 7j/7 de 07h à 19h. Réponse sous 2h.</div>
    </div>
  </div>
  <div class="aide-btns">
    <a href="https://wa.me/221774715353" target="_blank" class="aide-btn-wa">
      <i class="bi bi-whatsapp"></i> WhatsApp
    </a>
    <a href="<?= BASE_URL ?>?page=catalogue" class="aide-btn-cat">
      <i class="bi bi-grid-3x3-gap-fill"></i> Catalogue
    </a>
  </div>
</div>

<!-- REASSURANCE -->
<div class="reassurance">
  <div class="rea-card">
    <div class="rea-icon" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);color:#16a34a;"><i class="bi bi-truck-front-fill"></i></div>
    <div class="rea-title">Livraison rapide</div>
    <div class="rea-sub">Dakar & sous-région sous 24–48h</div>
  </div>
  <div class="rea-card">
    <div class="rea-icon" style="background:linear-gradient(135deg,#fff7ed,#fed7aa);color:#ea580c;"><i class="bi bi-shield-fill-check"></i></div>
    <div class="rea-title">Qualité garantie</div>
    <div class="rea-sub">Fruits frais sélectionnés à la main</div>
  </div>
  <div class="rea-card">
    <div class="rea-icon" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);color:#2563eb;"><i class="bi bi-arrow-repeat"></i></div>
    <div class="rea-title">Retour facile</div>
    <div class="rea-sub">Problème résolu sous 24h</div>
  </div>
  <div class="rea-card">
    <div class="rea-icon" style="background:linear-gradient(135deg,#faf5ff,#ede9fe);color:#7c3aed;"><i class="bi bi-star-fill"></i></div>
    <div class="rea-title">Service premium</div>
    <div class="rea-sub">Clients satisfaits depuis 2015</div>
  </div>
</div>

<?php else: ?>

<!-- VIDE -->
<div class="hist-card">
  <div class="hist-vide">
    <div class="hist-vide-icon"><i class="bi bi-bag-x"></i></div>
    <h3>Aucune commande pour l'instant</h3>
    <p>Découvrez notre sélection de fruits frais premium<br>et passez votre première commande.</p>
    <a href="<?= BASE_URL ?>?page=catalogue" class="btn-shop">
      <i class="bi bi-grid-3x3-gap-fill"></i> Explorer le catalogue
    </a>
  </div>
</div>

<?php endif; ?>

</div><!-- /container -->

<?php if (!empty($commandes)): ?>
<script>
(function() {
    var terminalStatuts = ['livree', 'annulee'];
    var icons = {
        'en_attente':     'bi-hourglass',
        'confirmee':      'bi-check-circle',
        'en_preparation': 'bi-box-seam',
        'en_livraison':   'bi-truck',
        'livree':         'bi-check-circle-fill',
        'annulee':        'bi-x-circle-fill'
    };
    var labels = {
        'en_attente':     'En attente',
        'confirmee':      'Confirmée',
        'en_preparation': 'En préparation',
        'en_livraison':   'En livraison',
        'livree':         'Livrée',
        'annulee':        'Annulée'
    };

    // IDs des commandes non terminales
    var commandeIds = [];
    <?php foreach ($commandes as $cmd): ?>
    <?php if (!in_array($cmd['statut'], ['livree', 'annulee'])): ?>
    commandeIds.push(<?= (int)$cmd['id'] ?>);
    <?php endif; ?>
    <?php endforeach; ?>

    if (commandeIds.length === 0) return;

    function pollStatuts() {
        commandeIds.forEach(function(id) {
            fetch('<?= BASE_URL ?>?page=api_commande_statut&id=' + id)
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.error) return;

                    var row = document.querySelector('tr[data-commande-id="' + id + '"]');
                    if (!row) return;

                    var badge = row.querySelector('.s-badge');
                    if (!badge) return;

                    var oldStatut = badge.getAttribute('data-statut');
                    if (oldStatut === data.statut) return;

                    // Mettre à jour la classe CSS
                    badge.className = 's-badge s-' + data.statut;
                    badge.setAttribute('data-statut', data.statut);

                    // Mettre à jour l'icône
                    var iconEl = badge.querySelector('i');
                    if (iconEl) iconEl.className = 'bi ' + (icons[data.statut] || 'bi-circle');

                    // Mettre à jour le label
                    var labelEl = badge.querySelector('span');
                    if (labelEl) labelEl.textContent = labels[data.statut] || data.statut;

                    // Retirer de la liste si statut terminal
                    if (terminalStatuts.indexOf(data.statut) !== -1) {
                        commandeIds = commandeIds.filter(function(i){ return i !== id; });
                    }
                })
                .catch(function() {});
        });

        if (commandeIds.length === 0) clearInterval(timer);
    }

    var timer = setInterval(pollStatuts, 30000);
})();
</script>
<?php endif; ?>

<!-- MODAL ANNULATION -->
<div id="modalAnnulation" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);align-items:center;justify-content:center;padding:16px;">
  <div style="background:#fff;border-radius:22px;max-width:480px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
    <div style="background:linear-gradient(135deg,#7f1d1d,#dc2626);padding:22px 26px;display:flex;align-items:center;gap:14px;">
      <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;flex-shrink:0;">
        <i class="bi bi-x-circle-fill"></i>
      </div>
      <div>
        <div style="font-size:1.05rem;font-weight:800;color:#fff;">Annuler la commande</div>
        <div style="font-size:.78rem;color:rgba(255,255,255,.7);">Cette action est irréversible</div>
      </div>
    </div>
    <div style="padding:26px;">
      <p style="font-size:.88rem;color:#374151;margin-bottom:20px;">
        <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i>
        Êtes-vous sûr de vouloir annuler cette commande ? Le stock sera automatiquement remis à jour.
      </p>
      <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;">
        Raison de l'annulation <span style="color:#dc2626;">*</span>
      </label>
      <select id="raisonAnnulation" style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:11px;font-size:.88rem;font-family:inherit;background:#fafafa;margin-bottom:8px;outline:none;">
        <option value="">— Choisir une raison —</option>
        <option value="changement_avis">Changement d'avis</option>
        <option value="erreur_commande">Erreur dans la commande</option>
        <option value="delai_trop_long">Délai de livraison trop long</option>
        <option value="prix_eleve">Prix trop élevé</option>
        <option value="commande_doublon">Commande en double</option>
        <option value="autre">Autre raison</option>
      </select>
      <div id="raisonErreur" style="display:none;font-size:.78rem;color:#dc2626;margin-bottom:12px;">
        <i class="bi bi-exclamation-circle"></i> Veuillez choisir une raison.
      </div>
      <div style="display:flex;gap:10px;margin-top:16px;">
        <button type="button" onclick="fermerModalAnnulation()"
          style="flex:1;padding:12px;border:1.5px solid #e5e7eb;border-radius:11px;background:#f9fafb;font-size:.88rem;font-weight:600;color:#374151;cursor:pointer;">
          <i class="bi bi-arrow-left"></i> Garder la commande
        </button>
        <button type="button" onclick="confirmerAnnulation()"
          style="flex:1;padding:12px;border:none;border-radius:11px;background:#dc2626;color:#fff;font-size:.88rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(220,38,38,.3);">
          <i class="bi bi-x-circle-fill"></i> Confirmer l'annulation
        </button>
      </div>
    </div>
  </div>
</div>
<script>
var _annulationId = 0;
function ouvrirModalAnnulation(id) {
    _annulationId = id;
    document.getElementById('raisonAnnulation').value = '';
    document.getElementById('raisonErreur').style.display = 'none';
    var m = document.getElementById('modalAnnulation');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function fermerModalAnnulation() {
    document.getElementById('modalAnnulation').style.display = 'none';
    document.body.style.overflow = '';
}
function confirmerAnnulation() {
    var raison = document.getElementById('raisonAnnulation').value;
    if (!raison) { document.getElementById('raisonErreur').style.display = 'block'; return; }
    window.location.href = '<?= BASE_URL ?>?page=commande_annuler&id=' + _annulationId + '&raison=' + encodeURIComponent(raison);
}
document.getElementById('modalAnnulation').addEventListener('click', function(e) {
    if (e.target === this) fermerModalAnnulation();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
