<?php
$adminPage = 'produits';
$pageTitle = 'Produits';
$topbarActions = '<button onclick="document.getElementById(\'modalAjout\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-plus-lg"></i> Ajouter un produit</button>';
require_once __DIR__ . '/layout.php';

$statuts_map = [
    1 => ['lib'=>'Actif',   'color'=>'#16a34a','bg'=>'#f0fdf4'],
    0 => ['lib'=>'Inactif', 'color'=>'#9ca3af','bg'=>'#f9fafb'],
];

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';
?>

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#e0f2fe;"><i class="bi bi-box-seam-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= count($produits) ?></div>
    <div class="stat-box-lbl">Total produits</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($produits, fn($p)=>$p['actif'])) ?></div>
    <div class="stat-box-lbl">Produits actifs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef2f2;"><i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($produits, fn($p)=>$p['stock_kg']<20 || (($p['prix_carton']??0)>0 && ($p['stock_cartons']??0)<3))) ?></div>
    <div class="stat-box-lbl">Stock faible</div>
  </div>
</div>

<!-- Tableau produits -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-box-seam" style="color:var(--vert);"></i> Liste des produits</div>
    <div class="filter-bar">
      <div class="filter-wrap">
        <i class="bi bi-search"></i>
        <input type="search" id="searchProd" class="filter-input" placeholder="Rechercher…" oninput="filterTable('searchProd','prodTable')">
      </div>
      <select class="filter-select" onchange="filterCat(this.value)">
        <option value="">Toutes catégories</option>
        <?php foreach ($categories as $cat): ?>
        <option value="<?= htmlspecialchars($cat['nom']) ?>"><?= htmlspecialchars($cat['nom']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="prodTable">
      <thead>
        <tr>
          <th>Photo</th>
          <th>Produit</th>
          <th>Catégorie</th>
          <th>Prix</th>
          <th>Stock</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produits as $p):
          $st = $statuts_map[$p['actif']];
          $stockKgAlert = $p['stock_kg'] < 20;
          $hasCarton = !empty($p['prix_carton']) && $p['prix_carton'] > 0;
          $stockCartonAlert = $hasCarton && ($p['stock_cartons'] ?? 0) < 3;
        ?>
        <tr data-cat="<?= htmlspecialchars($p['categorie_nom']??'') ?>">
          <td>
            <?php if($p['image_principale']): ?>
            <?php
              $imgParts = explode('/', $p['image_principale'], 2);
              $imgUrl = '/darousalam/' . $imgParts[0] . '/' . rawurlencode($imgParts[1] ?? '');
            ?>
            <div style="position:relative;display:inline-block;">
              <img src="<?= htmlspecialchars($imgUrl) ?>"
                   style="width:60px;height:60px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;cursor:zoom-in;display:block;"
                   onmouseover="this.nextElementSibling.style.display='block'"
                   onmouseout="this.nextElementSibling.style.display='none'"
                   onerror="this.style.display='none'">
              <div style="display:none;position:absolute;left:68px;top:0;z-index:999;width:200px;height:200px;border-radius:14px;overflow:hidden;box-shadow:0 8px 30px rgba(0,0,0,.2);border:2px solid #fff;pointer-events:none;">
                <img src="<?= htmlspecialchars($imgUrl) ?>" style="width:100%;height:100%;object-fit:cover;">
              </div>
            </div>
            <?php else: ?>
            <div style="width:60px;height:60px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
              <i class="bi bi-image" style="color:#9ca3af;font-size:1.2rem;"></i>
            </div>
            <?php endif; ?>
          </td>
          <td>
            <div style="font-weight:700;font-size:.85rem;color:#1c1917;"><?= htmlspecialchars($p['nom']) ?></div>
            <?php if($p['origine']): ?>
            <div style="font-size:.72rem;color:#9ca3af;"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($p['origine']) ?></div>
            <?php endif; ?>
          </td>
          <td style="font-size:.82rem;color:#6b7280;"><?= htmlspecialchars($p['categorie_nom']??'—') ?></td>
          <td>
            <div style="display:flex;flex-direction:column;gap:4px;">
              <?php if ($p['prix_kg'] > 0): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;border-radius:7px;padding:3px 8px;font-size:.72rem;font-weight:700;white-space:nowrap;">
                <i class="bi bi-rulers"></i> <?= number_format($p['prix_kg'],0,',',' ') ?> FCFA / kg
              </span>
              <?php else: ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:#f9fafb;color:#9ca3af;border:1px solid #e5e7eb;border-radius:7px;padding:3px 8px;font-size:.72rem;font-weight:600;white-space:nowrap;">
                <i class="bi bi-rulers"></i> — / kg
              </span>
              <?php endif; ?>
              <?php if ($hasCarton): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;border-radius:7px;padding:3px 8px;font-size:.72rem;font-weight:700;white-space:nowrap;">
                <i class="bi bi-box-seam"></i> <?= number_format($p['prix_carton'],0,',',' ') ?> FCFA / carton
                <?php if (!empty($p['poids_carton_kg'])): ?>
                <span style="font-weight:500;color:#3b82f6;">· <?= number_format((float)$p['poids_carton_kg'],0) ?> kg</span>
                <?php endif; ?>
              </span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <div style="display:flex;flex-direction:column;gap:4px;">
              <span style="display:inline-flex;align-items:center;gap:4px;background:<?= $stockKgAlert ? '#fef2f2' : '#f9fafb' ?>;color:<?= $stockKgAlert ? '#dc2626' : '#374151' ?>;border:1px solid <?= $stockKgAlert ? '#fecaca' : '#e5e7eb' ?>;border-radius:7px;padding:3px 8px;font-size:.72rem;font-weight:700;white-space:nowrap;">
                <i class="bi bi-rulers"></i> <?= number_format($p['stock_kg'],0,',',' ') ?> kg
                <?php if ($stockKgAlert): ?><i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;" title="Stock faible"></i><?php endif; ?>
              </span>
              <?php if ($hasCarton): ?>
              <span style="display:inline-flex;align-items:center;gap:4px;background:<?= $stockCartonAlert ? '#fef2f2' : '#eff6ff' ?>;color:<?= $stockCartonAlert ? '#dc2626' : '#1d4ed8' ?>;border:1px solid <?= $stockCartonAlert ? '#fecaca' : '#bfdbfe' ?>;border-radius:7px;padding:3px 8px;font-size:.72rem;font-weight:700;white-space:nowrap;">
                <i class="bi bi-box-seam"></i> <?= (int)($p['stock_cartons'] ?? 0) ?> carton<?= ($p['stock_cartons'] ?? 0) > 1 ? 's' : '' ?>
                <?php if ($stockCartonAlert): ?><i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;" title="Stock carton faible"></i><?php endif; ?>
              </span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <span class="status-badge" style="color:<?= $st['color'] ?>;background:<?= $st['bg'] ?>;">
              <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> <?= $st['lib'] ?>
            </span>
          </td>
          <td>
            <div style="display:flex;gap:4px;">
              <button onclick='openEditProd(<?= json_encode([
                "id"=>$p["id"],"nom"=>$p["nom"],"categorie_id"=>$p["categorie_id"],
                "origine"=>$p["origine"],"prix_kg"=>$p["prix_kg"],"prix_carton"=>$p["prix_carton"],
                "stock_kg"=>$p["stock_kg"],"stock_cartons"=>$p["stock_cartons"],
                "poids_carton_kg"=>$p["poids_carton_kg"],"unite_min_kg"=>$p["unite_min_kg"],
                "description"=>$p["description"],"badge"=>$p["badge"],"saison"=>$p["saison"],
                "actif"=>$p["actif"],"image_principale"=>$p["image_principale"]
              ]) ?>)' class="btn-sm-action" style="background:#fff8f0;color:#ea580c;border:1.5px solid #fed7aa;" title="Modifier">
                <i class="bi bi-pencil-fill"></i>
              </button>
              <a href="<?= BASE_URL ?>?page=produit&id=<?= $p['id'] ?>" target="_blank" class="btn-sm-action btn-view" title="Voir">
                <i class="bi bi-eye"></i>
              </a>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_produit_supprimer" style="display:inline;"
                    onsubmit="return confirm('Supprimer « <?= htmlspecialchars(addslashes($p['nom'])) ?> » ? Cette action est irréversible.');">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
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

<script>
document.getElementById('searchProd') && document.getElementById('searchProd').addEventListener('input', function() {
  filterTable('searchProd', 'prodTable');
});
function filterCat(cat) {
  document.querySelectorAll('#prodTable tbody tr').forEach(row => {
    row.style.display = (!cat || row.dataset.cat === cat) ? '' : 'none';
  });
}
</script>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<!-- Alerte également en haut de page -->
<div id="topAlert" style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:14px;padding:14px 20px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-size:.88rem;font-weight:600;color:#16a34a;">
  <i class="bi bi-check-circle-fill" style="font-size:1.1rem;"></i>
  <?= htmlspecialchars($successMsg) ?>
  <button onclick="document.getElementById('topAlert').remove()" style="margin-left:auto;background:none;border:none;color:#16a34a;cursor:pointer;font-size:1rem;"><i class="bi bi-x-lg"></i></button>
</div>
<script>
  // Scroll vers le dernier produit ajouté (le dernier du tbody)
  window.addEventListener('load', () => {
    const rows = document.querySelectorAll('#prodTable tbody tr');
    if (rows.length) {
      rows[rows.length - 1].scrollIntoView({behavior:'smooth', block:'center'});
      rows[rows.length - 1].style.background = '#f0fdf4';
      rows[rows.length - 1].style.transition = 'background 2s';
      setTimeout(() => rows[rows.length - 1].style.background = '', 2500);
    }
  });
  setTimeout(()=>{ const m=document.getElementById('flashMsg'); if(m) m.remove(); }, 4000);
</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashErr').remove(), 5000);</script>
<?php endif; ?>

<!-- MODAL AJOUT PRODUIT -->
<div id="modalAjout" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:800;color:#0f2d16;"><i class="bi bi-plus-circle" style="color:var(--vert);"></i> Ajouter un produit</div>
      <button onclick="document.getElementById('modalAjout').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;line-height:1;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_produit_ajouter" enctype="multipart/form-data" style="padding:24px 28px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom du produit *</label>
          <input type="text" name="nom" required class="form-inp" placeholder="Ex: Mangues Vertes Locales">
        </div>

        <div>
          <label class="form-lbl">Catégorie *</label>
          <select name="categorie_id" required class="form-inp">
            <option value="">-- Choisir --</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="form-lbl">Origine</label>
          <input type="text" name="origine" class="form-inp" placeholder="Ex: Sénégal, Maroc…">
        </div>

        <!-- Section KG -->
        <div style="grid-column:1/-1;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:16px 18px;">
          <div style="font-size:.72rem;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <i class="bi bi-rulers"></i> Vente au kg
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label class="form-lbl">Prix / kg (FCFA) *</label>
              <input type="number" name="prix_kg" required min="0" class="form-inp" placeholder="1200">
            </div>
            <div>
              <label class="form-lbl">Stock (kg) *</label>
              <input type="number" name="stock_kg" required min="0" step="0.01" class="form-inp" placeholder="100">
            </div>
            <div>
              <label class="form-lbl">Commande min (kg)</label>
              <input type="number" name="unite_min_kg" min="0" step="0.01" value="1" class="form-inp">
            </div>
          </div>
        </div>

        <!-- Section CARTON -->
        <div style="grid-column:1/-1;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:16px 18px;">
          <div style="font-size:.72rem;font-weight:800;color:#1d4ed8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <i class="bi bi-box-seam"></i> Vente au carton <span style="font-weight:500;color:#60a5fa;">(optionnel)</span>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <div>
              <label class="form-lbl">Prix / carton (FCFA)</label>
              <input type="number" name="prix_carton" min="0" class="form-inp" placeholder="13000">
            </div>
            <div>
              <label class="form-lbl">Stock (cartons)</label>
              <input type="number" name="stock_cartons" min="0" class="form-inp" placeholder="10">
            </div>
            <div>
              <label class="form-lbl">Poids carton (kg)</label>
              <input type="number" name="poids_carton_kg" min="0" step="0.01" class="form-inp" placeholder="13">
            </div>
          </div>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Description</label>
          <textarea name="description" rows="3" class="form-inp" placeholder="Description du produit…" style="resize:vertical;"></textarea>
        </div>

        <div>
          <label class="form-lbl">Badge</label>
          <input type="text" name="badge" class="form-inp" placeholder="Ex: Nouveau, Promo…">
        </div>

        <div>
          <label class="form-lbl">Saison</label>
          <input type="text" name="saison" class="form-inp" placeholder="Ex: Mai–Août">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Photo principale</label>
          <input type="file" name="photo" accept="image/*" class="form-inp" style="padding:6px;">
          <div id="photoPreview" style="margin-top:10px;display:none;">
            <img id="photoPreviewImg" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
          </div>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" value="1" checked> Actif
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" value="0"> Inactif
            </label>
          </div>
        </div>

      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjout').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;"><i class="bi bi-check-lg"></i> Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER PRODUIT -->
<div id="modalEdit" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:700px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:22px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:800;color:#0f2d16;"><i class="bi bi-pencil-fill" style="color:#ea580c;"></i> Modifier le produit</div>
      <button onclick="document.getElementById('modalEdit').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;line-height:1;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_produit_modifier" enctype="multipart/form-data" style="padding:24px 28px;">
      <input type="hidden" name="id" id="edit_id">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom du produit *</label>
          <input type="text" name="nom" id="edit_nom" required class="form-inp">
        </div>

        <div>
          <label class="form-lbl">Catégorie *</label>
          <select name="categorie_id" id="edit_categorie_id" required class="form-inp">
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div>
          <label class="form-lbl">Origine</label>
          <input type="text" name="origine" id="edit_origine" class="form-inp">
        </div>

        <!-- Section KG -->
        <div style="grid-column:1/-1;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:16px 18px;">
          <div style="font-size:.72rem;font-weight:800;color:#166534;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <i class="bi bi-rulers"></i> Vente au kg
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label class="form-lbl">Prix / kg (FCFA) *</label>
              <input type="number" name="prix_kg" id="edit_prix_kg" required min="0" class="form-inp">
            </div>
            <div>
              <label class="form-lbl">Stock (kg)</label>
              <input type="number" name="stock_kg" id="edit_stock_kg" min="0" step="0.01" class="form-inp">
            </div>
            <div>
              <label class="form-lbl">Commande min (kg)</label>
              <input type="number" name="unite_min_kg" id="edit_unite_min_kg" min="0" step="0.01" class="form-inp">
            </div>
          </div>
        </div>

        <!-- Section CARTON -->
        <div style="grid-column:1/-1;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:16px 18px;">
          <div style="font-size:.72rem;font-weight:800;color:#1d4ed8;text-transform:uppercase;letter-spacing:.07em;margin-bottom:12px;display:flex;align-items:center;gap:6px;">
            <i class="bi bi-box-seam"></i> Vente au carton <span style="font-weight:500;color:#60a5fa;">(optionnel)</span>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;">
            <div>
              <label class="form-lbl">Prix / carton (FCFA)</label>
              <input type="number" name="prix_carton" id="edit_prix_carton" min="0" class="form-inp">
            </div>
            <div>
              <label class="form-lbl">Stock (cartons)</label>
              <input type="number" name="stock_cartons" id="edit_stock_cartons" min="0" class="form-inp">
            </div>
            <div>
              <label class="form-lbl">Poids carton (kg)</label>
              <input type="number" name="poids_carton_kg" id="edit_poids_carton_kg" min="0" step="0.01" class="form-inp">
            </div>
          </div>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Description</label>
          <textarea name="description" id="edit_description" rows="3" class="form-inp" style="resize:vertical;"></textarea>
        </div>

        <div>
          <label class="form-lbl">Badge</label>
          <input type="text" name="badge" id="edit_badge" class="form-inp">
        </div>

        <div>
          <label class="form-lbl">Saison</label>
          <input type="text" name="saison" id="edit_saison" class="form-inp">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Photo principale</label>
          <div id="edit_current_photo" style="margin-bottom:8px;display:none;">
            <img id="edit_current_photo_img" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">Photo actuelle — uploader une nouvelle pour remplacer</div>
          </div>
          <input type="file" name="photo" id="edit_photo" accept="image/*" class="form-inp" style="padding:6px;">
          <div id="editPhotoPreview" style="margin-top:8px;display:none;">
            <img id="editPhotoPreviewImg" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;">
          </div>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:16px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" id="edit_actif_1" value="1"> Actif
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" id="edit_actif_0" value="0"> Inactif
            </label>
          </div>
        </div>

      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEdit').style.display='none'" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:#ea580c;color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;"><i class="bi bi-check-lg"></i> Enregistrer</button>
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
// Preview photo ajout
document.querySelector('#modalAjout input[name="photo"]').addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('photoPreviewImg').src = e.target.result;
      document.getElementById('photoPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});

// Preview photo edit
document.getElementById('edit_photo').addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      document.getElementById('editPhotoPreviewImg').src = e.target.result;
      document.getElementById('editPhotoPreview').style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
});

// Fermer modals au clic extérieur
document.getElementById('modalAjout').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
document.getElementById('modalEdit').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});

// Ouvrir modal édition et pré-remplir
function openEditProd(p) {
  document.getElementById('edit_id').value           = p.id;
  document.getElementById('edit_nom').value          = p.nom || '';
  document.getElementById('edit_categorie_id').value = p.categorie_id || '';
  document.getElementById('edit_origine').value      = p.origine || '';
  document.getElementById('edit_prix_kg').value      = p.prix_kg || '';
  document.getElementById('edit_prix_carton').value  = p.prix_carton || '';
  document.getElementById('edit_stock_kg').value     = p.stock_kg || '';
  document.getElementById('edit_stock_cartons').value= p.stock_cartons || '';
  document.getElementById('edit_poids_carton_kg').value = p.poids_carton_kg || '';
  document.getElementById('edit_unite_min_kg').value = p.unite_min_kg || '';
  document.getElementById('edit_description').value  = p.description || '';
  document.getElementById('edit_badge').value        = p.badge || '';
  document.getElementById('edit_saison').value       = p.saison || '';
  document.getElementById('edit_actif_1').checked    = p.actif == 1;
  document.getElementById('edit_actif_0').checked    = p.actif == 0;

  // Photo actuelle
  const cpDiv = document.getElementById('edit_current_photo');
  const cpImg = document.getElementById('edit_current_photo_img');
  if (p.image_principale) {
    const parts = p.image_principale.split('/');
    const encoded = parts.slice(0,-1).join('/') + '/' + encodeURIComponent(parts[parts.length-1]);
    cpImg.src = '/darousalam/' + encoded;
    cpDiv.style.display = 'block';
  } else {
    cpDiv.style.display = 'none';
  }
  document.getElementById('editPhotoPreview').style.display = 'none';

  document.getElementById('modalEdit').style.display = 'flex';
}
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
