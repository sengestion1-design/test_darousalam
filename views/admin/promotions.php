<?php
$adminPage   = 'promotions';
$pageTitle   = 'Codes promo';
$topbarActions = '<button onclick="document.getElementById(\'modalAjoutPromo\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-plus-lg"></i> Nouveau code</button>';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';
?>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashMsg').remove(), 4000);</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashErr').remove(), 5000);</script>
<?php endif; ?>

<!-- KPIs -->
<div class="stat-grid">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef9ec;"><i class="bi bi-tag-fill" style="color:#d4a017;"></i></div>
    <div class="stat-box-val"><?= $kpi['total'] ?></div>
    <div class="stat-box-lbl">Total codes promo</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= $kpi['actifs'] ?></div>
    <div class="stat-box-lbl">Codes actifs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef2f2;"><i class="bi bi-clock-history" style="color:#dc2626;"></i></div>
    <div class="stat-box-val"><?= $kpi['expires'] ?></div>
    <div class="stat-box-lbl">Codes expirés</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#eff6ff;"><i class="bi bi-bar-chart-fill" style="color:#2563eb;"></i></div>
    <div class="stat-box-val"><?= $kpi['utilisations'] ?></div>
    <div class="stat-box-lbl">Total utilisations</div>
  </div>
</div>

<!-- Tableau -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-tag" style="color:var(--vert);"></i> Liste des codes promo</div>
    <div class="filter-wrap">
      <i class="bi bi-search"></i>
      <input type="search" id="searchPromo" class="filter-input" placeholder="Rechercher un code…" oninput="filterTable('searchPromo','promoTable')">
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="promoTable">
      <thead>
        <tr>
          <th>Code</th>
          <th>Type</th>
          <th>Valeur</th>
          <th>Min commande</th>
          <th>Utilisations</th>
          <th>Période</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($promotions)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#9ca3af;">Aucun code promo pour l'instant.</td></tr>
        <?php endif; ?>
        <?php foreach ($promotions as $p): ?>
        <?php
          $today = date('Y-m-d');
          if (!$p['actif']) {
              $statut = ['label'=>'Inactif','color'=>'#9ca3af','bg'=>'#f9fafb'];
          } elseif ($p['date_fin'] && $p['date_fin'] < $today) {
              $statut = ['label'=>'Expiré','color'=>'#dc2626','bg'=>'#fef2f2'];
          } elseif ($p['usage_max'] !== null && $p['usage_count'] >= $p['usage_max']) {
              $statut = ['label'=>'Épuisé','color'=>'#f97316','bg'=>'#fff7ed'];
          } else {
              $statut = ['label'=>'Actif','color'=>'#16a34a','bg'=>'#f0fdf4'];
          }
        ?>
        <tr>
          <td>
            <span style="font-family:monospace;font-size:.9rem;font-weight:800;background:#f5f5f0;border:1px solid #e5e7eb;padding:4px 10px;border-radius:7px;color:#0f2d16;letter-spacing:.08em;">
              <?= htmlspecialchars($p['code']) ?>
            </span>
          </td>
          <td>
            <?php if ($p['type']==='pourcentage'): ?>
            <span class="status-badge" style="color:#7c3aed;background:#faf5ff;"><i class="bi bi-percent"></i> Pourcentage</span>
            <?php else: ?>
            <span class="status-badge" style="color:#0284c7;background:#e0f2fe;"><i class="bi bi-currency-exchange"></i> Fixe FCFA</span>
            <?php endif; ?>
          </td>
          <td style="font-weight:800;font-size:.95rem;color:#0f2d16;">
            <?= number_format($p['valeur'], 0, ',', ' ') ?><?= $p['type']==='pourcentage' ? '%' : ' FCFA' ?>
          </td>
          <td style="font-size:.83rem;color:#6b7280;">
            <?= $p['min_commande'] > 0 ? number_format($p['min_commande'],0,',',' ').' FCFA' : '<span style="color:#d1d5db;">—</span>' ?>
          </td>
          <td style="font-weight:700;text-align:center;">
            <?= (int)$p['usage_count'] ?>
            <?= $p['usage_max'] !== null ? '<span style="color:#9ca3af;font-weight:400;"> / '.(int)$p['usage_max'].'</span>' : '<span style="color:#9ca3af;font-weight:400;"> / ∞</span>' ?>
          </td>
          <td style="font-size:.78rem;color:#6b7280;white-space:nowrap;">
            <?php
              $debut = $p['date_debut'] ? date('d/m/Y', strtotime($p['date_debut'])) : null;
              $fin   = $p['date_fin']   ? date('d/m/Y', strtotime($p['date_fin']))   : null;
              if ($debut && $fin) echo $debut.' → '.$fin;
              elseif ($debut) echo 'Dès '.$debut;
              elseif ($fin)   echo 'Jusqu\'au '.$fin;
              else echo '<span style="color:#d1d5db;">Sans limite</span>';
            ?>
          </td>
          <td>
            <span class="status-badge" style="color:<?= $statut['color'] ?>;background:<?= $statut['bg'] ?>;">
              <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> <?= $statut['label'] ?>
            </span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button onclick="openEditPromo(<?= htmlspecialchars(json_encode($p)) ?>)" class="btn-sm-action btn-edit" title="Modifier">
                <i class="bi bi-pencil"></i>
              </button>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_promotion_supprimer" onsubmit="return confirm('Supprimer le code « <?= htmlspecialchars(addslashes($p['code'])) ?> » ?');" style="display:inline;">
                <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                <button type="submit" class="btn-sm-action btn-del" title="Supprimer"><i class="bi bi-trash3"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL CRÉER -->
<div id="modalAjoutPromo" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;"><i class="bi bi-tag-fill" style="color:var(--or);"></i> Nouveau code promo</div>
      <button onclick="document.getElementById('modalAjoutPromo').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_promotion_ajouter" style="padding:24px 28px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <!-- Code -->
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Code promo *</label>
          <div style="display:flex;gap:8px;">
            <input type="text" name="code" id="ajoutCode" required class="form-inp" placeholder="Ex: PROMO20" style="text-transform:uppercase;font-family:monospace;font-weight:700;letter-spacing:.08em;">
            <button type="button" onclick="genererCode('ajoutCode')" style="padding:9px 14px;border-radius:9px;border:1.5px solid #e5e7eb;background:#f9fafb;color:#374151;font-size:.8rem;font-weight:600;cursor:pointer;white-space:nowrap;flex-shrink:0;">
              <i class="bi bi-shuffle"></i> Générer
            </button>
          </div>
        </div>

        <!-- Type -->
        <div>
          <label class="form-lbl">Type de réduction *</label>
          <select name="type" class="form-inp">
            <option value="pourcentage">Pourcentage (%)</option>
            <option value="fixe">Montant fixe (FCFA)</option>
          </select>
        </div>

        <!-- Valeur -->
        <div>
          <label class="form-lbl">Valeur *</label>
          <input type="number" name="valeur" required class="form-inp" min="0" step="0.01" placeholder="Ex: 15">
        </div>

        <!-- Min commande -->
        <div>
          <label class="form-lbl">Commande minimum (FCFA)</label>
          <input type="number" name="min_commande" class="form-inp" min="0" step="1" value="0" placeholder="0 = pas de minimum">
        </div>

        <!-- Usage max -->
        <div>
          <label class="form-lbl">Utilisations max</label>
          <input type="number" name="usage_max" class="form-inp" min="1" step="1" placeholder="Vide = illimité">
        </div>

        <!-- Date début -->
        <div>
          <label class="form-lbl">Date de début</label>
          <input type="date" name="date_debut" class="form-inp">
        </div>

        <!-- Date fin -->
        <div>
          <label class="form-lbl">Date de fin</label>
          <input type="date" name="date_fin" class="form-inp">
        </div>

        <!-- Statut -->
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" value="1" checked> Actif</label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" value="0"> Inactif</label>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjoutPromo').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;"><i class="bi bi-check-lg"></i> Créer le code</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER -->
<div id="modalEditPromo" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:560px;max-height:92vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;"><i class="bi bi-pencil" style="color:var(--vert);"></i> Modifier le code promo</div>
      <button onclick="document.getElementById('modalEditPromo').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_promotion_modifier" style="padding:24px 28px;">
      <input type="hidden" name="id" id="editPromoId">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <!-- Code -->
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Code promo *</label>
          <div style="display:flex;gap:8px;">
            <input type="text" name="code" id="editPromoCode" required class="form-inp" style="text-transform:uppercase;font-family:monospace;font-weight:700;letter-spacing:.08em;">
            <button type="button" onclick="genererCode('editPromoCode')" style="padding:9px 14px;border-radius:9px;border:1.5px solid #e5e7eb;background:#f9fafb;color:#374151;font-size:.8rem;font-weight:600;cursor:pointer;white-space:nowrap;flex-shrink:0;">
              <i class="bi bi-shuffle"></i> Générer
            </button>
          </div>
        </div>

        <!-- Type -->
        <div>
          <label class="form-lbl">Type de réduction *</label>
          <select name="type" id="editPromoType" class="form-inp">
            <option value="pourcentage">Pourcentage (%)</option>
            <option value="fixe">Montant fixe (FCFA)</option>
          </select>
        </div>

        <!-- Valeur -->
        <div>
          <label class="form-lbl">Valeur *</label>
          <input type="number" name="valeur" id="editPromoValeur" required class="form-inp" min="0" step="0.01">
        </div>

        <!-- Min commande -->
        <div>
          <label class="form-lbl">Commande minimum (FCFA)</label>
          <input type="number" name="min_commande" id="editPromoMinCmd" class="form-inp" min="0" step="1">
        </div>

        <!-- Usage max -->
        <div>
          <label class="form-lbl">Utilisations max</label>
          <input type="number" name="usage_max" id="editPromoUsageMax" class="form-inp" min="1" step="1" placeholder="Vide = illimité">
        </div>

        <!-- Date début -->
        <div>
          <label class="form-lbl">Date de début</label>
          <input type="date" name="date_debut" id="editPromoDebut" class="form-inp">
        </div>

        <!-- Date fin -->
        <div>
          <label class="form-lbl">Date de fin</label>
          <input type="date" name="date_fin" id="editPromoFin" class="form-inp">
        </div>

        <!-- Statut -->
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" id="editPromoActif1" value="1"> Actif</label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" id="editPromoActif0" value="0"> Inactif</label>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEditPromo').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;"><i class="bi bi-check-lg"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<style>
.form-lbl{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;}
.form-inp{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;font-family:var(--font);background:#fafafa;transition:all .2s;}
.form-inp:focus{outline:none;border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.08);}
</style>

<script>
// Génération aléatoire
function genererCode(inputId) {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
  let code = '';
  for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
  document.getElementById(inputId).value = code;
}

// Pré-remplissage modal modifier
function openEditPromo(p) {
  document.getElementById('editPromoId').value       = p.id;
  document.getElementById('editPromoCode').value     = p.code;
  document.getElementById('editPromoType').value     = p.type;
  document.getElementById('editPromoValeur').value   = p.valeur;
  document.getElementById('editPromoMinCmd').value   = p.min_commande;
  document.getElementById('editPromoUsageMax').value = p.usage_max ?? '';
  document.getElementById('editPromoDebut').value    = p.date_debut ?? '';
  document.getElementById('editPromoFin').value      = p.date_fin ?? '';
  document.getElementById('editPromoActif1').checked = p.actif == 1;
  document.getElementById('editPromoActif0').checked = p.actif == 0;
  document.getElementById('modalEditPromo').style.display = 'flex';
}

// Fermer modals en cliquant hors
document.getElementById('modalAjoutPromo').addEventListener('click', e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
document.getElementById('modalEditPromo').addEventListener('click',  e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });

// Filtre tableau
function filterTable(inputId, tableId) {
  const q = document.getElementById(inputId).value.toLowerCase();
  const rows = document.querySelectorAll('#'+tableId+' tbody tr');
  rows.forEach(tr => {
    tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
}
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
