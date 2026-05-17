<?php
$adminPage  = 'zones_livraison';
$pageTitle  = 'Zones de livraison';
$topbarActions = '<button onclick="document.getElementById(\'modalAjoutZone\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-plus-lg"></i> Ajouter une zone</button>';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error']   ?? '';
?>

<?php if ($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashMsg').remove(), 4000);</script>
<?php endif; ?>
<?php if ($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashErr').remove(), 5000);</script>
<?php endif; ?>

<?php
$nbTotal   = count($zones);
$nbActives = count(array_filter($zones, fn($z) => $z['actif']));
$nbGratuit = count(array_filter($zones, fn($z) => !is_null($z['frais_gratuit_si'])));
?>

<!-- KPIs -->
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px;">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#e0f2fe;"><i class="bi bi-geo-alt-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= $nbTotal ?></div>
    <div class="stat-box-lbl">Total zones</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= $nbActives ?></div>
    <div class="stat-box-lbl">Zones actives</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fff7ed;"><i class="bi bi-gift-fill" style="color:#f97316;"></i></div>
    <div class="stat-box-val"><?= $nbGratuit ?></div>
    <div class="stat-box-lbl">Avec seuil gratuit</div>
  </div>
</div>

<!-- Explication logique -->
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:20px;font-size:.83rem;color:#15803d;display:flex;gap:12px;align-items:flex-start;">
  <i class="bi bi-info-circle-fill" style="font-size:1.1rem;flex-shrink:0;margin-top:1px;"></i>
  <div>
    <strong>Logique de livraison :</strong> chaque zone a des <b>frais fixes</b> appliqués aux petites commandes.
    Si le client dépasse le <b>seuil de gratuité</b>, la livraison devient automatiquement gratuite.
    Le <b>minimum de commande</b> bloque la validation si le panier est trop petit.
  </div>
</div>

<!-- Tableau -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-geo-alt" style="color:var(--vert);"></i> Zones de livraison</div>
    <div class="filter-wrap">
      <i class="bi bi-search"></i>
      <input type="search" id="searchZone" class="filter-input" placeholder="Rechercher…" oninput="filterTable('searchZone','zoneTable')">
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="zoneTable">
      <thead>
        <tr>
          <th>Zone</th>
          <th>Description</th>
          <th>Frais</th>
          <th>Gratuit si ≥</th>
          <th>Min. commande</th>
          <th>Délai</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($zones)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#9ca3af;">
          <i class="bi bi-geo-alt" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
          Aucune zone configurée
        </td></tr>
        <?php else: ?>
        <?php foreach ($zones as $z): ?>
        <tr>
          <td><div style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($z['nom']) ?></div></td>
          <td style="color:#6b7280;font-size:.82rem;max-width:200px;">
            <?= !empty($z['description']) ? htmlspecialchars($z['description']) : '<span style="color:#d1d5db;">—</span>' ?>
          </td>
          <td>
            <?php if ((float)$z['frais'] == 0): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;"><i class="bi bi-gift-fill" style="font-size:.65rem;"></i> Gratuit</span>
            <?php else: ?>
            <span class="status-badge" style="color:#f97316;background:#fff7ed;"><?= number_format((float)$z['frais'], 0, ',', ' ') ?> FCFA</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if (!is_null($z['frais_gratuit_si'])): ?>
            <span style="font-size:.82rem;font-weight:700;color:#16a34a;">
              <i class="bi bi-arrow-up-circle-fill"></i> <?= number_format((int)$z['frais_gratuit_si'], 0, ',', ' ') ?> FCFA
            </span>
            <?php else: ?>
            <span style="color:#d1d5db;font-size:.82rem;">Jamais gratuit</span>
            <?php endif; ?>
          </td>
          <td>
            <?php if ((int)$z['min_commande'] > 0): ?>
            <span style="font-size:.82rem;font-weight:600;color:#7c3aed;"><?= number_format((int)$z['min_commande'], 0, ',', ' ') ?> FCFA</span>
            <?php else: ?>
            <span style="color:#d1d5db;font-size:.82rem;">Aucun</span>
            <?php endif; ?>
          </td>
          <td style="font-size:.84rem;"><span style="font-weight:600;"><?= (int)$z['delai_jours'] ?></span> <span style="color:#9ca3af;font-size:.78rem;">j</span></td>
          <td>
            <?php if ($z['actif']): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif</span>
            <?php else: ?>
            <span class="status-badge" style="color:#9ca3af;background:#f9fafb;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactif</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button onclick="openEditZone(this)" data-zone="<?= htmlspecialchars(json_encode($z), ENT_QUOTES) ?>" class="btn-sm-action btn-edit" title="Modifier">
                <i class="bi bi-pencil"></i>
              </button>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_zone_supprimer" style="display:inline;"
                    onsubmit="return confirm('Supprimer « <?= htmlspecialchars(addslashes($z['nom'])) ?> » ?');">
                <input type="hidden" name="id" value="<?= (int)$z['id'] ?>">
                <button type="submit" class="btn-sm-action btn-del" title="Supprimer"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
// Formulaire commun aux deux modals
$formFields = function(string $prefix, array $z = []) { ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
    <div style="grid-column:1/-1;">
      <label class="form-lbl">Nom de la zone *</label>
      <input type="text" name="nom" id="<?= $prefix ?>Nom" required class="form-inp" placeholder="Ex: Dakar Centre, Thiès…" value="<?= htmlspecialchars($z['nom'] ?? '') ?>">
    </div>
    <div style="grid-column:1/-1;">
      <label class="form-lbl">Description <span style="font-weight:400;color:#9ca3af;">(quartiers couverts)</span></label>
      <textarea name="description" id="<?= $prefix ?>Desc" rows="2" class="form-inp" style="resize:vertical;" placeholder="Ex: Plateau, Médina, HLM…"><?= htmlspecialchars($z['description'] ?? '') ?></textarea>
    </div>
    <div>
      <label class="form-lbl">Frais de livraison (FCFA)</label>
      <input type="number" name="frais" id="<?= $prefix ?>Frais" min="0" step="100" class="form-inp" placeholder="0 = toujours gratuit" value="<?= (int)($z['frais'] ?? 0) ?>">
      <div style="font-size:.72rem;color:#9ca3af;margin-top:3px;">0 = livraison toujours gratuite</div>
    </div>
    <div>
      <label class="form-lbl">Délai de livraison (jours)</label>
      <input type="number" name="delai_jours" id="<?= $prefix ?>Delai" min="1" max="30" class="form-inp" value="<?= (int)($z['delai_jours'] ?? 1) ?>">
    </div>
    <div>
      <label class="form-lbl">Gratuit si commande ≥ (FCFA)</label>
      <input type="number" name="frais_gratuit_si" id="<?= $prefix ?>FGS" min="0" step="1000" class="form-inp"
             placeholder="Laisser vide = jamais gratuit"
             value="<?= !empty($z['frais_gratuit_si']) ? (int)$z['frais_gratuit_si'] : '' ?>">
      <div style="font-size:.72rem;color:#9ca3af;margin-top:3px;">Livraison offerte au-delà de ce montant</div>
    </div>
    <div>
      <label class="form-lbl">Commande minimum (FCFA)</label>
      <input type="number" name="min_commande" id="<?= $prefix ?>Min" min="0" step="1000" class="form-inp"
             placeholder="0 = pas de minimum"
             value="<?= (int)($z['min_commande'] ?? 0) ?>">
      <div style="font-size:.72rem;color:#9ca3af;margin-top:3px;">Bloque les commandes en dessous</div>
    </div>
    <div style="grid-column:1/-1;">
      <label class="form-lbl">Statut</label>
      <div style="display:flex;gap:16px;margin-top:6px;">
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
          <input type="radio" name="actif" id="<?= $prefix ?>Actif1" value="1" <?= ($z['actif'] ?? 1) == 1 ? 'checked' : '' ?>> Actif
        </label>
        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
          <input type="radio" name="actif" id="<?= $prefix ?>Actif0" value="0" <?= ($z['actif'] ?? 1) == 0 ? 'checked' : '' ?>> Inactif
        </label>
      </div>
    </div>
  </div>
<?php }; ?>

<!-- MODAL AJOUT -->
<div id="modalAjoutZone" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:540px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 26px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-plus-circle" style="color:var(--vert);"></i> Nouvelle zone de livraison
      </div>
      <button onclick="document.getElementById('modalAjoutZone').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_zone_ajouter" style="padding:22px 26px;">
      <?php $formFields('add'); ?>
      <div style="display:flex;gap:12px;margin-top:22px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjoutZone').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Créer la zone
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER -->
<div id="modalEditZone" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:540px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 26px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-pencil" style="color:var(--vert);"></i> Modifier la zone
      </div>
      <button onclick="document.getElementById('modalEditZone').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_zone_modifier" style="padding:22px 26px;">
      <input type="hidden" name="id" id="editZoneId">
      <?php $formFields('editZone'); ?>
      <div style="display:flex;gap:12px;margin-top:22px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEditZone').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Enregistrer
        </button>
      </div>
    </form>
  </div>
</div>

<style>
.form-lbl{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;}
.form-inp{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;font-family:var(--font);background:#fafafa;transition:all .2s;box-sizing:border-box;}
.form-inp:focus{outline:none;border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.08);}
</style>

<script>
function openEditZone(btn) {
  const z = JSON.parse(btn.dataset.zone);
  document.getElementById('editZoneId').value      = z.id;
  document.getElementById('editZoneNom').value     = z.nom;
  document.getElementById('editZoneDesc').value    = z.description || '';
  document.getElementById('editZoneFrais').value   = parseFloat(z.frais) || 0;
  document.getElementById('editZoneDelai').value   = z.delai_jours;
  document.getElementById('editZoneFGS').value     = z.frais_gratuit_si || '';
  document.getElementById('editZoneMin').value     = z.min_commande || 0;
  document.getElementById('editZoneActif1').checked = z.actif == 1;
  document.getElementById('editZoneActif0').checked = z.actif == 0;
  document.getElementById('modalEditZone').style.display = 'flex';
}
function filterTable(inputId, tableId) {
  const q = document.getElementById(inputId).value.toLowerCase();
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(r =>
    r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none'
  );
}
document.getElementById('modalAjoutZone').addEventListener('click', e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
document.getElementById('modalEditZone').addEventListener('click',  e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
