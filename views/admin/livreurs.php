<?php
$adminPage = 'livreurs';
$pageTitle = 'Livreurs';
$topbarActions = '<button onclick="document.getElementById(\'modalAddLivreur\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-plus-lg"></i> Ajouter un livreur</button>';
require_once __DIR__ . '/layout.php';

$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>

<?php if ($flashSuccess): ?>
<div id="flashOk" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($flashSuccess) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashOk')?.remove(),4000);</script>
<?php endif; ?>
<?php if ($flashError): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($flashError) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashErr')?.remove(),5000);</script>
<?php endif; ?>

<?php
$nbTotal  = count($livreurs);
$nbActifs = count(array_filter($livreurs, fn($l) => $l['actif']));
$nbLivrees = array_sum(array_column($livreurs, 'nb_livrees'));
$tauxGlobal = array_sum(array_column($livreurs, 'nb_livraisons')) > 0
    ? round(array_sum(array_column($livreurs, 'nb_livrees')) / array_sum(array_column($livreurs, 'nb_livraisons')) * 100)
    : 0;
?>

<!-- KPIs -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#eff6ff;"><i class="bi bi-people-fill" style="color:#2563eb;"></i></div>
    <div class="stat-box-val"><?= $nbTotal ?></div>
    <div class="stat-box-lbl">Total livreurs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-person-check-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= $nbActifs ?></div>
    <div class="stat-box-lbl">Actifs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef3c7;"><i class="bi bi-truck-front-fill" style="color:#d97706;"></i></div>
    <div class="stat-box-val"><?= $nbLivrees ?></div>
    <div class="stat-box-lbl">Livraisons réussies</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fdf4ff;"><i class="bi bi-bar-chart-fill" style="color:#9333ea;"></i></div>
    <div class="stat-box-val"><?= $tauxGlobal ?>%</div>
    <div class="stat-box-lbl">Taux de succès</div>
  </div>
</div>

<!-- Tableau livreurs -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-people" style="color:var(--vert);"></i> Liste des livreurs</div>
    <div class="filter-wrap">
      <i class="bi bi-search"></i>
      <input type="search" id="searchLivreur" class="filter-input" placeholder="Rechercher…"
             oninput="filterTable('searchLivreur','livreurTable')">
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="livreurTable">
      <thead>
        <tr>
          <th>Livreur</th>
          <th>Téléphone</th>
          <th>Zone</th>
          <th>Livraisons</th>
          <th>Taux réussite</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($livreurs)): ?>
        <tr><td colspan="7" style="text-align:center;padding:48px;color:#9ca3af;">
          <i class="bi bi-person-x" style="font-size:2.2rem;display:block;margin-bottom:10px;"></i>
          Aucun livreur enregistré
        </td></tr>
        <?php else: foreach ($livreurs as $lr): ?>
        <?php
          $nb   = (int)$lr['nb_livraisons'];
          $ok   = (int)$lr['nb_livrees'];
          $taux = $nb > 0 ? round(($ok / $nb) * 100) : 0;
          $barColor = $taux >= 80 ? '#16a34a' : ($taux >= 50 ? '#d97706' : '#dc2626');
          $initials = strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $lr['nom']), 0, 2))));
        ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--vert),#2d8a42);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.82rem;font-weight:800;flex-shrink:0;">
                <?= htmlspecialchars($initials) ?>
              </div>
              <div>
                <div style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($lr['nom']) ?></div>
                <div style="font-size:.7rem;color:#9ca3af;">#<?= $lr['id'] ?></div>
              </div>
            </div>
          </td>
          <td>
            <a href="tel:<?= htmlspecialchars($lr['telephone']) ?>" style="font-size:.84rem;color:#374151;text-decoration:none;display:flex;align-items:center;gap:6px;">
              <i class="bi bi-telephone-fill" style="color:var(--vert);font-size:.75rem;"></i>
              <?= htmlspecialchars($lr['telephone']) ?>
            </a>
          </td>
          <td style="font-size:.83rem;color:#6b7280;">
            <?= !empty($lr['zone']) ? '<span style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:6px;padding:2px 10px;font-weight:600;color:#166534;font-size:.78rem;">'.htmlspecialchars($lr['zone']).'</span>' : '<span style="color:#d1d5db;">—</span>' ?>
          </td>
          <td>
            <span style="font-weight:800;font-size:.9rem;color:#1c1917;"><?= $nb ?></span>
            <?php if ($ok > 0): ?>
            <span style="font-size:.7rem;color:#16a34a;margin-left:4px;">(<?= $ok ?> ✓)</span>
            <?php endif; ?>
          </td>
          <td style="min-width:120px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="height:7px;flex:1;background:#f3f4f6;border-radius:4px;overflow:hidden;">
                <div style="width:<?= $taux ?>%;height:100%;background:<?= $barColor ?>;border-radius:4px;transition:width .4s;"></div>
              </div>
              <span style="font-size:.75rem;font-weight:800;color:<?= $barColor ?>;min-width:30px;"><?= $taux ?>%</span>
            </div>
          </td>
          <td>
            <?php if ($lr['actif']): ?>
            <span class="status-badge" style="background:#dcfce7;color:#15803d;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif</span>
            <?php else: ?>
            <span class="status-badge" style="background:#f3f4f6;color:#9ca3af;"><i class="bi bi-circle" style="font-size:.4rem;"></i> Inactif</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <a href="<?= BASE_URL ?>?page=admin_livreur_detail&id=<?= $lr['id'] ?>" class="btn-sm-action btn-view" title="Voir détail & paie">
                <i class="bi bi-eye"></i>
              </a>
              <button onclick="openEditLivreur(this)"
                data-id="<?= $lr['id'] ?>"
                data-nom="<?= htmlspecialchars($lr['nom'], ENT_QUOTES) ?>"
                data-tel="<?= htmlspecialchars($lr['telephone'], ENT_QUOTES) ?>"
                data-zone="<?= htmlspecialchars($lr['zone'] ?? '', ENT_QUOTES) ?>"
                data-actif="<?= (int)$lr['actif'] ?>"
                class="btn-sm-action btn-edit" title="Modifier">
                <i class="bi bi-pencil"></i>
              </button>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_livreur_supprimer" style="display:inline;"
                    onsubmit="return confirm('Supprimer « <?= htmlspecialchars(addslashes($lr['nom'])) ?> » ?');">
                <input type="hidden" name="id" value="<?= $lr['id'] ?>">
                <button type="submit" class="btn-sm-action btn-del" title="Supprimer"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL AJOUTER -->
<div id="modalAddLivreur" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 26px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-person-plus-fill" style="color:var(--vert);"></i> Ajouter un livreur
      </div>
      <button onclick="document.getElementById('modalAddLivreur').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_livreur_ajouter" style="padding:22px 26px;">
      <?php echo livreurFormFields('add'); ?>
      <div style="display:flex;gap:12px;margin-top:22px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAddLivreur').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER -->
<div id="modalEditLivreur" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 26px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-pencil" style="color:var(--vert);"></i> Modifier le livreur
      </div>
      <button onclick="document.getElementById('modalEditLivreur').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_livreur_modifier" style="padding:22px 26px;">
      <input type="hidden" name="id" id="editLivreurId">
      <?php echo livreurFormFields('edit'); ?>
      <div style="display:flex;gap:12px;margin-top:22px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEditLivreur').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<?php
function livreurFormFields(string $prefix): string {
  return '
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
    <div style="grid-column:1/-1;">
      <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">Nom complet *</label>
      <input type="text" name="nom" id="'.$prefix.'LivreurNom" required
             style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;box-sizing:border-box;"
             placeholder="Ex : Moussa Diallo">
    </div>
    <div>
      <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">Téléphone *</label>
      <input type="tel" name="telephone" id="'.$prefix.'LivreurTel" required
             style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;box-sizing:border-box;"
             placeholder="+221 77 000 00 00">
    </div>
    <div>
      <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">Zone</label>
      <input type="text" name="zone" id="'.$prefix.'LivreurZone"
             style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;box-sizing:border-box;"
             placeholder="Ex: Dakar, Plateau…">
    </div>
    <div style="grid-column:1/-1;">
      <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em;">Statut</label>
      <div style="display:flex;gap:16px;">
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
          <input type="radio" name="actif" id="'.$prefix.'LivreurActif1" value="1" checked> Actif
        </label>
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
          <input type="radio" name="actif" id="'.$prefix.'LivreurActif0" value="0"> Inactif
        </label>
      </div>
    </div>
  </div>';
}
?>

<script>
function openEditLivreur(btn) {
  document.getElementById('editLivreurId').value          = btn.dataset.id;
  document.getElementById('editLivreurNom').value         = btn.dataset.nom;
  document.getElementById('editLivreurTel').value         = btn.dataset.tel;
  document.getElementById('editLivreurZone').value        = btn.dataset.zone;
  document.getElementById('editLivreurActif1').checked    = btn.dataset.actif == '1';
  document.getElementById('editLivreurActif0').checked    = btn.dataset.actif == '0';
  document.getElementById('modalEditLivreur').style.display = 'flex';
}
function filterTable(inputId, tableId) {
  const q = document.getElementById(inputId).value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(r =>
    r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'
  );
}
document.getElementById('modalAddLivreur').addEventListener('click',  e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
document.getElementById('modalEditLivreur').addEventListener('click', e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
