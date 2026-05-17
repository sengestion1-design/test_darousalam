<?php
$adminPage = 'stock_mouvements';
$pageTitle = 'Mouvements de stock';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error']   ?? '';

$typesMap = [
    'entree'      => ['lib'=>'Entrée',      'color'=>'#166534','bg'=>'#dcfce7','icon'=>'bi-arrow-down-circle-fill'],
    'sortie'      => ['lib'=>'Sortie',      'color'=>'#991b1b','bg'=>'#fee2e2','icon'=>'bi-arrow-up-circle-fill'],
    'ajustement'  => ['lib'=>'Ajustement',  'color'=>'#92400e','bg'=>'#fef3c7','icon'=>'bi-sliders'],
    'commande'    => ['lib'=>'Commande',    'color'=>'#1d4ed8','bg'=>'#dbeafe','icon'=>'bi-bag-fill'],
];

$filterProduit = (int)($_GET['produit_id'] ?? 0);
$filterType    = $_GET['type'] ?? '';
$filterDebut   = $_GET['date_debut'] ?? '';
$filterFin     = $_GET['date_fin']   ?? '';
?>

<style>
.mv-kpi{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;}
.mv-box{background:#fff;border-radius:16px;padding:18px 22px;border:1px solid #e5e7eb;display:flex;align-items:center;gap:14px;}
.mv-box-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.mv-box-val{font-size:1.5rem;font-weight:900;color:#111827;line-height:1;}
.mv-box-lbl{font-size:.7rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.05em;margin-top:3px;}

.mv-card{background:#fff;border-radius:18px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.04);}
.mv-head{padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;}
.mv-title{font-size:.92rem;font-weight:800;color:#111827;display:flex;align-items:center;gap:8px;}

.mv-table{width:100%;border-collapse:collapse;}
.mv-table th{font-size:.63rem;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;font-weight:700;padding:10px 16px;background:#fafafa;border-bottom:1px solid #f3f4f6;white-space:nowrap;}
.mv-table td{padding:13px 16px;border-bottom:1px solid #f9fafb;font-size:.83rem;vertical-align:middle;}
.mv-table tr:last-child td{border-bottom:none;}
.mv-table tbody tr:hover td{background:#fafaf9;}

.type-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.7rem;font-weight:700;}
.prod-photo{width:52px;height:52px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;}
.prod-photo-ph{width:52px;height:52px;border-radius:10px;background:#f5f3ee;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;}

.filter-bar{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
.f-select,.f-input{padding:7px 11px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.78rem;font-family:inherit;background:#fafafa;color:#374151;transition:border-color .15s;}
.f-select:focus,.f-input:focus{outline:none;border-color:var(--vert);background:#fff;}
.f-btn{padding:7px 16px;border-radius:9px;font-size:.78rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;}
.f-btn-primary{background:var(--vert);color:#fff;}
.f-btn-reset{background:#f3f4f6;color:#6b7280;}

/* Modal */
.modal-bg{display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10000;align-items:center;justify-content:center;}
.modal-bg.open{display:flex;}
.modal-box{background:#fff;border-radius:20px;padding:28px 32px;width:460px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);animation:modalIn .2s ease;}
@keyframes modalIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
.modal-title{font-size:1rem;font-weight:800;color:#111827;margin-bottom:20px;display:flex;align-items:center;gap:8px;}
.form-group{margin-bottom:16px;}
.form-label{font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;display:block;text-transform:uppercase;letter-spacing:.05em;}
.form-control{width:100%;padding:9px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.84rem;font-family:inherit;color:#111827;transition:border-color .15s;background:#fafafa;}
.form-control:focus{outline:none;border-color:var(--vert);background:#fff;}
</style>

<!-- KPI -->
<div class="mv-kpi">
  <div class="mv-box">
    <div class="mv-box-icon" style="background:#dcfce7;"><i class="bi bi-arrow-down-circle-fill" style="color:#166534;font-size:1.2rem;"></i></div>
    <div>
      <div class="mv-box-val"><?= number_format($statsTotal['total_entrees'],0,',',' ') ?> kg</div>
      <?php if ((float)$statsTotal['total_entrees_cartons'] > 0): ?>
      <div style="display:inline-flex;align-items:center;gap:4px;background:#dbeafe;color:#1d4ed8;border-radius:6px;padding:2px 7px;font-size:.68rem;font-weight:700;margin-top:4px;">
        <i class="bi bi-box-seam"></i> <?= (int)$statsTotal['total_entrees_cartons'] ?> carton<?= $statsTotal['total_entrees_cartons'] > 1 ? 's' : '' ?>
      </div>
      <?php endif; ?>
      <div class="mv-box-lbl">Total entrées</div>
    </div>
  </div>
  <div class="mv-box">
    <div class="mv-box-icon" style="background:#fee2e2;"><i class="bi bi-arrow-up-circle-fill" style="color:#991b1b;font-size:1.2rem;"></i></div>
    <div>
      <div class="mv-box-val"><?= number_format($statsTotal['total_sorties'],0,',',' ') ?> kg</div>
      <?php if ((float)$statsTotal['total_sorties_cartons'] > 0): ?>
      <div style="display:inline-flex;align-items:center;gap:4px;background:#fee2e2;color:#991b1b;border-radius:6px;padding:2px 7px;font-size:.68rem;font-weight:700;margin-top:4px;">
        <i class="bi bi-box-seam"></i> <?= (int)$statsTotal['total_sorties_cartons'] ?> carton<?= $statsTotal['total_sorties_cartons'] > 1 ? 's' : '' ?>
      </div>
      <?php endif; ?>
      <div class="mv-box-lbl">Total sorties</div>
    </div>
  </div>
  <div class="mv-box">
    <div class="mv-box-icon" style="background:#f3f4f6;"><i class="bi bi-arrow-left-right" style="color:#6b7280;font-size:1.1rem;"></i></div>
    <div>
      <div class="mv-box-val"><?= $statsTotal['nb'] ?></div>
      <div class="mv-box-lbl">Total mouvements</div>
    </div>
  </div>
</div>

<!-- Filtres + Table -->
<div class="mv-card">
  <div class="mv-head">
    <div class="mv-title">
      <i class="bi bi-arrow-left-right" style="color:var(--vert);"></i>
      Historique des mouvements
      <span style="background:#f3f4f6;color:#6b7280;font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:20px;"><?= count($mouvements) ?></span>
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
      <!-- Filtres -->
      <form method="GET" action="<?= BASE_URL ?>" class="filter-bar">
        <input type="hidden" name="page" value="admin_stock_mouvements">
        <select name="produit_id" class="f-select">
          <option value="">Tous les produits</option>
          <?php foreach($produits as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $filterProduit == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nom']) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="type" class="f-select">
          <option value="">Tous types</option>
          <?php foreach($typesMap as $k=>$t): ?>
          <option value="<?= $k ?>" <?= $filterType === $k ? 'selected' : '' ?>><?= $t['lib'] ?></option>
          <?php endforeach; ?>
        </select>
        <input type="date" name="date_debut" value="<?= htmlspecialchars($filterDebut) ?>" class="f-input" title="Date début">
        <input type="date" name="date_fin"   value="<?= htmlspecialchars($filterFin) ?>"   class="f-input" title="Date fin">
        <button type="submit" class="f-btn f-btn-primary"><i class="bi bi-funnel-fill"></i> Filtrer</button>
        <?php if($filterProduit || $filterType || $filterDebut || $filterFin): ?>
        <a href="<?= BASE_URL ?>?page=admin_stock_mouvements" class="f-btn f-btn-reset">Réinitialiser</a>
        <?php endif; ?>
      </form>
      <button onclick="document.getElementById('modal-ajout').classList.add('open')"
        style="padding:7px 16px;border-radius:9px;font-size:.78rem;font-weight:700;background:var(--vert);color:#fff;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;font-family:inherit;">
        <i class="bi bi-plus-lg"></i> Nouveau mouvement
      </button>
    </div>
  </div>

  <div style="overflow-x:auto;">
    <table class="mv-table">
      <thead>
        <tr>
          <th>Date</th>
          <th>Produit</th>
          <th>Type</th>
          <th>Quantité</th>
          <th>Motif</th>
          <th>Référence</th>
          <th>Par</th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($mouvements)): ?>
        <tr><td colspan="7" style="text-align:center;padding:48px;color:#9ca3af;">
          <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px;color:#e5e7eb;"></i>
          Aucun mouvement enregistré
        </td></tr>
        <?php else: ?>
        <?php foreach($mouvements as $mv):
          $t = $typesMap[$mv['type']] ?? ['lib'=>$mv['type'],'color'=>'#6b7280','bg'=>'#f3f4f6','icon'=>'bi-circle'];
          $imgSrc = !empty($mv['image_principale']) ? BASE_URL . $mv['image_principale'] : '';
          $qteSign = in_array($mv['type'], ['entree']) ? '+' : (in_array($mv['type'], ['sortie','commande']) ? '−' : '±');
          $qteColor = $mv['type'] === 'entree' ? '#166534' : ($mv['type'] === 'ajustement' ? '#92400e' : '#991b1b');
          $adminNom = $mv['admin_nom'] ?? '—';
          $d = new DateTime($mv['created_at']);
        ?>
        <tr>
          <td>
            <div style="font-size:.8rem;font-weight:600;color:#111827;"><?= $d->format('d/m/Y') ?></div>
            <div style="font-size:.68rem;color:#9ca3af;"><?= $d->format('H:i') ?></div>
          </td>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <?php if($imgSrc): ?>
              <img src="<?= htmlspecialchars($imgSrc) ?>" class="prod-photo" alt="">
              <?php else: ?>
              <div class="prod-photo-ph"><i class="bi bi-apple" style="color:#d1d5db;font-size:.8rem;"></i></div>
              <?php endif; ?>
              <span style="font-weight:700;color:#111827;font-size:.83rem;"><?= htmlspecialchars($mv['produit_nom'] ?? '—') ?></span>
            </div>
          </td>
          <td>
            <span class="type-badge" style="background:<?= $t['bg'] ?>;color:<?= $t['color'] ?>;">
              <i class="bi <?= $t['icon'] ?>"></i> <?= $t['lib'] ?>
            </span>
          </td>
          <td>
            <div style="display:flex;flex-direction:column;gap:4px;">
              <?php if((float)$mv['quantite_kg'] > 0): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:<?= $mv['type']==='entree' ? '#f0fdf4' : '#fef2f2' ?>;color:<?= $qteColor ?>;border:1px solid <?= $mv['type']==='entree' ? '#bbf7d0' : '#fecaca' ?>;border-radius:7px;padding:3px 9px;font-size:.78rem;font-weight:800;white-space:nowrap;">
                <i class="bi bi-rulers"></i> <?= $qteSign ?> <?= number_format($mv['quantite_kg'],1,',','') ?> kg
              </span>
              <?php endif; ?>
              <?php if((int)$mv['quantite_cartons'] > 0): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:<?= $mv['type']==='entree' ? '#eff6ff' : '#fef2f2' ?>;color:<?= $mv['type']==='entree' ? '#1d4ed8' : $qteColor ?>;border:1px solid <?= $mv['type']==='entree' ? '#bfdbfe' : '#fecaca' ?>;border-radius:7px;padding:3px 9px;font-size:.78rem;font-weight:800;white-space:nowrap;">
                <i class="bi bi-box-seam"></i> <?= $qteSign ?> <?= (int)$mv['quantite_cartons'] ?> carton<?= (int)$mv['quantite_cartons'] > 1 ? 's' : '' ?>
              </span>
              <?php endif; ?>
              <?php if((float)$mv['quantite_kg'] == 0 && (int)$mv['quantite_cartons'] == 0): ?>
              <span style="color:#d1d5db;">—</span>
              <?php endif; ?>
            </div>
          </td>
          <td style="color:#6b7280;font-size:.8rem;max-width:160px;">
            <?= $mv['motif'] ? htmlspecialchars($mv['motif']) : '<span style="color:#d1d5db;">—</span>' ?>
          </td>
          <td>
            <?php if($mv['reference']): ?>
            <span style="font-size:.7rem;font-family:monospace;color:#1a5c2a;font-weight:700;"><?= htmlspecialchars($mv['reference']) ?></span>
            <?php else: ?>
            <span style="color:#d1d5db;">—</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.78rem;color:#6b7280;"><?= htmlspecialchars($adminNom) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Modal ajout mouvement -->
<div class="modal-bg" id="modal-ajout" onclick="if(event.target===this)this.classList.remove('open')">
  <div class="modal-box">
    <div class="modal-title">
      <i class="bi bi-plus-circle-fill" style="color:var(--vert);"></i>
      Enregistrer un mouvement
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_stock_mouvement_ajouter">
      <div class="form-group">
        <label class="form-label">Produit</label>
        <select name="produit_id" class="form-control" required>
          <option value="">Sélectionner un produit…</option>
          <?php foreach($produits as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $filterProduit == $p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Type de mouvement</label>
        <select name="type" class="form-control" required>
          <option value="entree">Entrée — réapprovisionnement</option>
          <option value="sortie">Sortie — perte / retrait manuel</option>
          <option value="ajustement">Ajustement — correction de stock</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Unité</label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:4px;">
          <label style="cursor:pointer;"><input type="radio" name="unite" value="kg" checked style="display:none;" onchange="mvChangerUnite(this.value)">
            <div class="mv-unite-opt" data-val="kg" style="padding:8px 4px;border-radius:9px;border:2px solid var(--vert);background:#f0fdf4;text-align:center;font-size:.73rem;font-weight:700;color:#1a5c2a;">kg</div>
          </label>
          <label style="cursor:pointer;"><input type="radio" name="unite" value="carton" style="display:none;" onchange="mvChangerUnite(this.value)">
            <div class="mv-unite-opt" data-val="carton" style="padding:8px 4px;border-radius:9px;border:2px solid #e5e7eb;text-align:center;font-size:.73rem;font-weight:700;color:#6b7280;">Cartons</div>
          </label>
          <label style="cursor:pointer;"><input type="radio" name="unite" value="les_deux" style="display:none;" onchange="mvChangerUnite(this.value)">
            <div class="mv-unite-opt" data-val="les_deux" style="padding:8px 4px;border-radius:9px;border:2px solid #e5e7eb;text-align:center;font-size:.73rem;font-weight:700;color:#6b7280;">Les deux</div>
          </label>
        </div>
      </div>
      <div style="display:grid;gap:10px;margin-bottom:16px;" id="mv-qte-grid">
        <div id="mv-field-kg">
          <label class="form-label">Quantité (kg)</label>
          <input type="number" name="quantite_kg" step="0.1" min="0" class="form-control" placeholder="ex: 50">
        </div>
        <div id="mv-field-carton" style="display:none;">
          <label class="form-label">Quantité (cartons)</label>
          <input type="number" name="quantite_cartons" step="1" min="0" class="form-control" placeholder="ex: 5">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Motif <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
        <input type="text" name="motif" class="form-control" placeholder="ex: Livraison fournisseur, Produits abîmés…">
      </div>
      <div class="form-group">
        <label class="form-label">Référence <span style="color:#9ca3af;font-weight:400;">(optionnel)</span></label>
        <input type="text" name="reference" class="form-control" placeholder="ex: BL-2026-001">
      </div>
      <div style="display:flex;gap:10px;margin-top:22px;">
        <button type="submit" style="flex:1;padding:11px;background:var(--vert);color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:800;cursor:pointer;font-family:inherit;">
          Enregistrer
        </button>
        <button type="button" onclick="document.getElementById('modal-ajout').classList.remove('open')"
          style="padding:11px 20px;background:#f3f4f6;color:#374151;border:none;border-radius:10px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;">
          Annuler
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function mvChangerUnite(val) {
  // Update visual selection
  document.querySelectorAll('.mv-unite-opt').forEach(el => {
    if (el.dataset.val === val) {
      el.style.borderColor = 'var(--vert)';
      el.style.background  = '#f0fdf4';
      el.style.color       = '#1a5c2a';
    } else {
      el.style.borderColor = '#e5e7eb';
      el.style.background  = '#fff';
      el.style.color       = '#6b7280';
    }
  });
  // Show/hide quantity fields
  const kgField     = document.getElementById('mv-field-kg');
  const cartonField = document.getElementById('mv-field-carton');
  if (val === 'kg') {
    kgField.style.display     = '';
    cartonField.style.display = 'none';
  } else if (val === 'carton') {
    kgField.style.display     = 'none';
    cartonField.style.display = '';
  } else { // les_deux
    kgField.style.display     = '';
    cartonField.style.display = '';
  }
}
// Make unit option divs clickable
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.mv-unite-opt').forEach(div => {
    div.addEventListener('click', () => {
      const val = div.dataset.val;
      const radio = div.previousElementSibling;
      radio.checked = true;
      mvChangerUnite(val);
    });
  });
});
</script>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:28px;right:28px;background:linear-gradient(135deg,#1a5c2a,#2d8a42);color:#fff;padding:14px 20px;border-radius:14px;font-size:.84rem;font-weight:600;box-shadow:0 8px 30px rgba(26,92,42,.3);z-index:9999;display:flex;align-items:center;gap:10px;animation:slideIn .25s ease;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<style>@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}</style>
<script>setTimeout(()=>{const m=document.getElementById('flashMsg');if(m){m.style.transition='opacity .4s';m.style.opacity='0';setTimeout(()=>m.remove(),400);}},3500);</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:28px;right:28px;background:#dc2626;color:#fff;padding:14px 20px;border-radius:14px;font-size:.84rem;font-weight:600;box-shadow:0 8px 24px rgba(220,38,38,.3);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>{const m=document.getElementById('flashErr');if(m){m.style.transition='opacity .4s';m.style.opacity='0';setTimeout(()=>m.remove(),400);}},5000);</script>
<?php endif; ?>

<?php require_once __DIR__ . '/layout_end.php'; ?>
