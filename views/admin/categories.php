<?php
$adminPage = 'categories';
$pageTitle = 'Catégories';
$topbarActions = '<button onclick="document.getElementById(\'modalAjoutCat\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-plus-lg"></i> Ajouter une catégorie</button>';
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

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#e0f2fe;"><i class="bi bi-grid-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= count($categories) ?></div>
    <div class="stat-box-lbl">Total catégories</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($categories, fn($c)=>$c['actif'])) ?></div>
    <div class="stat-box-lbl">Catégories actives</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#faf5ff;"><i class="bi bi-box-seam-fill" style="color:#7c3aed;"></i></div>
    <div class="stat-box-val"><?= array_sum(array_column($categories, 'nb_produits')) ?></div>
    <div class="stat-box-lbl">Total produits</div>
  </div>
</div>

<!-- Tableau -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-grid" style="color:var(--vert);"></i> Liste des catégories</div>
    <div class="filter-wrap">
      <i class="bi bi-search"></i>
      <input type="search" id="searchCat" class="filter-input" placeholder="Rechercher…" oninput="filterTable('searchCat','catTable')">
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="catTable">
      <thead>
        <tr>
          <th>Catégorie</th>
          <th>Slug</th>
          <th>Couleur</th>
          <th>Ordre</th>
          <th>Produits</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($categories as $cat): ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:12px;">
              <?php if (!empty($cat['image'])): ?>
              <img src="/darousalam/<?= htmlspecialchars($cat['image']) ?>" style="width:64px;height:64px;border-radius:14px;object-fit:cover;border:2px solid <?= htmlspecialchars($cat['couleur']) ?>44;flex-shrink:0;" onerror="this.style.display='none'">
              <?php else: ?>
              <div style="width:64px;height:64px;border-radius:14px;background:<?= htmlspecialchars($cat['couleur']) ?>22;border:2px solid <?= htmlspecialchars($cat['couleur']) ?>44;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-<?= htmlspecialchars($cat['icone'] ?? 'grid') ?>" style="color:<?= htmlspecialchars($cat['couleur']) ?>;font-size:1.4rem;"></i>
              </div>
              <?php endif; ?>
              <div>
                <div style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($cat['nom']) ?></div>
                <?php if($cat['description']): ?>
                <div style="font-size:.72rem;color:#9ca3af;max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($cat['description']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td style="font-size:.78rem;color:#9ca3af;font-family:monospace;"><?= htmlspecialchars($cat['slug']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="width:20px;height:20px;border-radius:6px;background:<?= htmlspecialchars($cat['couleur']) ?>;border:1px solid #e5e7eb;"></div>
              <span style="font-size:.78rem;color:#6b7280;font-family:monospace;"><?= htmlspecialchars($cat['couleur']) ?></span>
            </div>
          </td>
          <td style="font-weight:700;text-align:center;"><?= (int)$cat['ordre'] ?></td>
          <td style="font-weight:700;text-align:center;"><?= (int)$cat['nb_produits'] ?></td>
          <td>
            <?php if($cat['actif']): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif</span>
            <?php else: ?>
            <span class="status-badge" style="color:#9ca3af;background:#f9fafb;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactif</span>
            <?php endif; ?>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button onclick="openEditCat(<?= htmlspecialchars(json_encode($cat)) ?>)" class="btn-sm-action btn-edit" title="Modifier">
                <i class="bi bi-pencil"></i>
              </button>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_categorie_supprimer" style="display:inline;"
                    onsubmit="return confirm('Supprimer la catégorie « <?= htmlspecialchars(addslashes($cat['nom'])) ?> » ?');">
                <input type="hidden" name="id" value="<?= $cat['id'] ?>">
                <button type="submit" class="btn-sm-action btn-del" title="Supprimer"><i class="bi bi-trash"></i></button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- MODAL AJOUT -->
<div id="modalAjoutCat" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;"><i class="bi bi-plus-circle" style="color:var(--vert);"></i> Nouvelle catégorie</div>
      <button onclick="document.getElementById('modalAjoutCat').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_categorie_ajouter" enctype="multipart/form-data" style="padding:24px 28px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom *</label>
          <input type="text" name="nom" required class="form-inp" placeholder="Ex: Fruits Exotiques">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Description</label>
          <textarea name="description" rows="2" class="form-inp" placeholder="Description courte…" style="resize:vertical;"></textarea>
        </div>
        <div>
          <label class="form-lbl">Couleur</label>
          <div style="display:flex;align-items:center;gap:8px;">
            <input type="color" name="couleur" value="#1a5c2a" style="width:44px;height:36px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;padding:2px;">
            <span style="font-size:.78rem;color:#9ca3af;">Choisir une couleur</span>
          </div>
        </div>
        <div>
          <label class="form-lbl">Ordre d'affichage</label>
          <input type="number" name="ordre" value="<?= count($categories)+1 ?>" min="0" class="form-inp">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Photo de la catégorie</label>
          <input type="file" name="photo" accept="image/*" class="form-inp" style="padding:6px;" onchange="previewCatPhoto(this,'ajoutCatPreview')">
          <div id="ajoutCatPreview" style="margin-top:8px;display:none;">
            <img style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
          </div>
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" value="1" checked> Actif</label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" value="0"> Inactif</label>
          </div>
        </div>
      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjoutCat').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;"><i class="bi bi-check-lg"></i> Créer</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER -->
<div id="modalEditCat" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;"><i class="bi bi-pencil" style="color:var(--vert);"></i> Modifier la catégorie</div>
      <button onclick="document.getElementById('modalEditCat').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_categorie_modifier" enctype="multipart/form-data" style="padding:24px 28px;">
      <input type="hidden" name="id" id="editCatId">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom *</label>
          <input type="text" name="nom" id="editCatNom" required class="form-inp">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Description</label>
          <textarea name="description" id="editCatDesc" rows="2" class="form-inp" style="resize:vertical;"></textarea>
        </div>
        <div>
          <label class="form-lbl">Couleur</label>
          <div style="display:flex;align-items:center;gap:8px;">
            <input type="color" name="couleur" id="editCatCouleur" style="width:44px;height:36px;border:1.5px solid #e5e7eb;border-radius:8px;cursor:pointer;padding:2px;">
          </div>
        </div>
        <div>
          <label class="form-lbl">Ordre</label>
          <input type="number" name="ordre" id="editCatOrdre" min="0" class="form-inp">
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Photo de la catégorie</label>
          <div id="editCatCurrentPhoto" style="margin-bottom:8px;display:none;">
            <img id="editCatCurrentPhotoImg" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">Photo actuelle — uploader une nouvelle pour remplacer</div>
          </div>
          <input type="file" name="photo" id="editCatPhoto" accept="image/*" class="form-inp" style="padding:6px;" onchange="previewCatPhoto(this,'editCatPreview')">
          <div id="editCatPreview" style="margin-top:8px;display:none;">
            <img style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
          </div>
        </div>
        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" id="editActif1" value="1"> Actif</label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;"><input type="radio" name="actif" id="editActif0" value="0"> Inactif</label>
          </div>
        </div>
      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEditCat').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
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
function openEditCat(cat) {
  document.getElementById('editCatId').value      = cat.id;
  document.getElementById('editCatNom').value     = cat.nom;
  document.getElementById('editCatDesc').value    = cat.description || '';
  document.getElementById('editCatCouleur').value = cat.couleur || '#1a5c2a';
  document.getElementById('editCatOrdre').value   = cat.ordre;
  document.getElementById('editActif1').checked   = cat.actif == 1;
  document.getElementById('editActif0').checked   = cat.actif == 0;
  // Photo actuelle
  const photoDiv = document.getElementById('editCatCurrentPhoto');
  const photoImg = document.getElementById('editCatCurrentPhotoImg');
  if (cat.image) {
    photoImg.src = '/darousalam/' + cat.image;
    photoDiv.style.display = 'block';
  } else {
    photoDiv.style.display = 'none';
  }
  document.getElementById('editCatPreview').style.display = 'none';
  document.getElementById('editCatPhoto').value = '';
  document.getElementById('modalEditCat').style.display = 'flex';
}
function previewCatPhoto(input, previewId) {
  const preview = document.getElementById(previewId);
  const img = preview.querySelector('img');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; preview.style.display = 'block'; };
    reader.readAsDataURL(input.files[0]);
  }
}
document.getElementById('modalAjoutCat').addEventListener('click', e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
document.getElementById('modalEditCat').addEventListener('click',  e => { if(e.target===e.currentTarget) e.currentTarget.style.display='none'; });
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
