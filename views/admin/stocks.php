<?php
$adminPage = 'stocks';
$pageTitle = 'Stocks';
require_once __DIR__ . '/layout.php';

$totalProduits  = count($produits);
$stockCritique  = count(array_filter($produits, fn($p) => $p['stock_kg'] < 20));
$stockFaible    = count(array_filter($produits, fn($p) => $p['stock_kg'] >= 20 && $p['stock_kg'] < 50));
$stockOk        = count(array_filter($produits, fn($p) => $p['stock_kg'] >= 50));
$totalStockKg   = array_sum(array_column($produits, 'stock_kg'));
?>

<div class="stat-grid">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#e0f2fe;"><i class="bi bi-box-seam-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= $totalProduits ?></div>
    <div class="stat-box-lbl">Total produits</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef2f2;"><i class="bi bi-exclamation-octagon-fill" style="color:#dc2626;"></i></div>
    <div class="stat-box-val"><?= $stockCritique ?></div>
    <div class="stat-box-lbl">Stock critique (&lt;20 kg)</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fff7ed;"><i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i></div>
    <div class="stat-box-val"><?= $stockFaible ?></div>
    <div class="stat-box-lbl">Stock faible (20–50 kg)</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= number_format($totalStockKg, 0, ',', ' ') ?> kg</div>
    <div class="stat-box-lbl">Stock total disponible</div>
  </div>
</div>

<!-- Alertes critiques -->
<?php $critiques = array_filter($produits, fn($p) => $p['stock_kg'] < 20); ?>
<?php if (!empty($critiques)): ?>
<div class="a-card" style="border-color:#fecaca;">
  <div class="a-card-head" style="background:#fef2f2;">
    <div class="a-card-title" style="color:#dc2626;">
      <i class="bi bi-exclamation-octagon-fill"></i> Alertes stock critique
    </div>
    <span style="font-size:.75rem;color:#dc2626;font-weight:600;"><?= count($critiques) ?> produit(s) à réapprovisionner</span>
  </div>
  <div style="display:flex;flex-wrap:wrap;gap:10px;padding:16px;">
    <?php foreach ($critiques as $p): ?>
    <div style="display:flex;align-items:center;gap:10px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:10px 14px;">
      <?php if($p['image_principale']): ?>
      <?php $imgParts2=explode('/',$p['image_principale'],2); $imgUrl2='/darousalam/'.$imgParts2[0].'/'.rawurlencode($imgParts2[1]??''); ?>
      <img src="<?= htmlspecialchars($imgUrl2) ?>"
           style="width:48px;height:48px;border-radius:10px;object-fit:cover;"
           onerror="this.style.display='none'">
      <?php else: ?>
      <div style="width:48px;height:48px;border-radius:10px;background:#fee2e2;display:flex;align-items:center;justify-content:center;">
        <i class="bi bi-box-seam" style="color:#dc2626;font-size:.85rem;"></i>
      </div>
      <?php endif; ?>
      <div>
        <div style="font-weight:700;font-size:.82rem;color:#1c1917;"><?= htmlspecialchars($p['nom']) ?></div>
        <div style="font-size:.72rem;color:#dc2626;font-weight:600;">
          <i class="bi bi-exclamation-circle"></i> <?= number_format($p['stock_kg'], 1, ',', ' ') ?> kg restants
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Filtres + onglets -->
<div class="a-card">
  <div class="a-card-head" style="flex-wrap:wrap;gap:12px;">
    <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
      <!-- Onglets vue -->
      <div style="display:flex;background:#f3f4f6;border-radius:10px;padding:3px;gap:2px;" id="vue-tabs">
        <button onclick="basculerVue('kg')" id="tab-kg"
          style="padding:6px 16px;border-radius:8px;border:none;font-size:.78rem;font-weight:700;cursor:pointer;font-family:inherit;background:var(--vert);color:#fff;transition:all .15s;">
          <i class="bi bi-speedometer2"></i> Par kg
        </button>
        <button onclick="basculerVue('carton')" id="tab-carton"
          style="padding:6px 16px;border-radius:8px;border:none;font-size:.78rem;font-weight:700;cursor:pointer;font-family:inherit;background:transparent;color:#6b7280;transition:all .15s;">
          <i class="bi bi-box-seam"></i> Par carton
        </button>
      </div>
      <div class="filter-wrap">
        <i class="bi bi-search"></i>
        <input type="search" id="searchStock" class="filter-input" placeholder="Rechercher un produit…" oninput="filterStockTable()">
      </div>
      <select class="filter-select" id="sel-niveau" onchange="filterStockTable()">
        <option value="">Tous niveaux</option>
        <option value="critique">Critique</option>
        <option value="faible">Faible</option>
        <option value="ok">OK</option>
      </select>
      <select class="filter-select" id="sel-cat" onchange="filterStockTable()">
        <option value="">Toutes catégories</option>
        <?php
          $cats = array_unique(array_filter(array_column($produits, 'categorie_nom')));
          sort($cats);
          foreach ($cats as $cat):
        ?>
        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <a href="<?= BASE_URL ?>?page=admin_produit_ajouter"
       style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:var(--vert);color:#fff;border-radius:9px;font-size:.8rem;font-weight:700;text-decoration:none;transition:background .15s;"
       onmouseover="this.style.background='var(--vert-l)'" onmouseout="this.style.background='var(--vert)'">
      <i class="bi bi-plus-lg"></i> Ajouter un article
    </a>
  </div>

  <!-- === VUE KG === -->
  <div id="vue-kg" style="overflow-x:auto;">
    <table class="a-table" id="stockTableKg">
      <thead>
        <tr>
          <th>Produit</th>
          <th>Catégorie</th>
          <th style="text-align:center;">Stock (kg)</th>
          <th>Progression</th>
          <th style="text-align:center;">Niveau</th>
          <th style="text-align:center;">Prix/kg</th>
          <th style="text-align:center;">Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produits as $p):
          if ($p['stock_kg'] < 20) {
            $niveau = 'critique';
            $niveauColor = '#dc2626'; $niveauBg = '#fef2f2'; $niveauLib = 'Critique';
            $barColor = '#dc2626'; $barPct = max(2, ($p['stock_kg'] / 20) * 33);
          } elseif ($p['stock_kg'] < 50) {
            $niveau = 'faible';
            $niveauColor = '#f59e0b'; $niveauBg = '#fffbeb'; $niveauLib = 'Faible';
            $barColor = '#f59e0b'; $barPct = 33 + (($p['stock_kg'] - 20) / 30) * 34;
          } else {
            $niveau = 'ok';
            $niveauColor = '#16a34a'; $niveauBg = '#f0fdf4'; $niveauLib = 'OK';
            $barColor = '#16a34a'; $barPct = min(100, 67 + (($p['stock_kg'] - 50) / 150) * 33);
          }
        ?>
        <tr data-niveau="<?= $niveau ?>" data-cat="<?= htmlspecialchars($p['categorie_nom']??'') ?>" data-nom="<?= htmlspecialchars(strtolower($p['nom'])) ?>">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <?php if($p['image_principale']): ?>
              <?php $imgParts3=explode('/',$p['image_principale'],2); $imgUrl3='/darousalam/'.$imgParts3[0].'/'.rawurlencode($imgParts3[1]??''); ?>
              <img src="<?= htmlspecialchars($imgUrl3) ?>" style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;" onerror="this.style.display='none'">
              <?php else: ?>
              <div style="width:48px;height:48px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-image" style="color:#9ca3af;font-size:.9rem;"></i>
              </div>
              <?php endif; ?>
              <div>
                <div style="font-weight:700;font-size:.85rem;color:#1c1917;"><?= htmlspecialchars($p['nom']) ?></div>
                <?php if($p['origine']): ?>
                <div style="font-size:.72rem;color:#9ca3af;"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($p['origine']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td style="font-size:.82rem;color:#6b7280;"><?= htmlspecialchars($p['categorie_nom']??'—') ?></td>
          <td style="text-align:center;">
            <span style="font-weight:900;font-size:1.05rem;color:<?= $niveauColor ?>;"><?= number_format($p['stock_kg'], 1, ',', ' ') ?></span>
            <span style="font-size:.72rem;color:#9ca3af;font-weight:600;"> kg</span>
          </td>
          <td style="width:140px;">
            <div style="background:#f3f4f6;border-radius:20px;height:7px;overflow:hidden;width:110px;">
              <div style="height:100%;width:<?= round($barPct) ?>%;background:<?= $barColor ?>;border-radius:20px;"></div>
            </div>
            <div style="font-size:.68rem;color:#9ca3af;margin-top:2px;"><?= round($barPct) ?>%</div>
          </td>
          <td style="text-align:center;">
            <span class="status-badge" style="color:<?= $niveauColor ?>;background:<?= $niveauBg ?>;">
              <?php if($niveau==='critique'): ?><i class="bi bi-exclamation-octagon-fill" style="font-size:.7rem;"></i>
              <?php elseif($niveau==='faible'): ?><i class="bi bi-exclamation-triangle-fill" style="font-size:.7rem;"></i>
              <?php else: ?><i class="bi bi-check-circle-fill" style="font-size:.7rem;"></i><?php endif; ?>
              <?= $niveauLib ?>
            </span>
          </td>
          <td style="text-align:center;font-weight:700;color:var(--vert);"><?= number_format($p['prix_kg'], 0, ',', ' ') ?> FCFA</td>
          <td style="text-align:center;">
            <?php if($p['actif']): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif</span>
            <?php else: ?>
            <span class="status-badge" style="color:#9ca3af;background:#f9fafb;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactif</span>
            <?php endif; ?>
          </td>
          <td><?= actionsBtns($p, BASE_URL) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- === VUE CARTONS === -->
  <div id="vue-carton" style="display:none;overflow-x:auto;">
    <table class="a-table" id="stockTableCarton">
      <thead>
        <tr>
          <th>Produit</th>
          <th>Catégorie</th>
          <th style="text-align:center;">Stock (cartons)</th>
          <th style="text-align:center;">Poids / carton</th>
          <th style="text-align:center;">Équiv. kg</th>
          <th style="text-align:center;">Niveau</th>
          <th style="text-align:center;">Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produits as $p):
          $cartons = (int)$p['stock_cartons'];
          $poidsCarton = (float)($p['poids_carton_kg'] ?? 0);
          $equivKg = $cartons * $poidsCarton;
          // Niveau basé sur cartons
          if ($cartons === 0) {
            $nc='critique'; $ncColor='#dc2626'; $ncBg='#fef2f2'; $ncLib='Vide';
          } elseif ($cartons < 5) {
            $nc='faible'; $ncColor='#f59e0b'; $ncBg='#fffbeb'; $ncLib='Faible';
          } else {
            $nc='ok'; $ncColor='#16a34a'; $ncBg='#f0fdf4'; $ncLib='OK';
          }
        ?>
        <tr data-niveau="<?= $nc ?>" data-cat="<?= htmlspecialchars($p['categorie_nom']??'') ?>" data-nom="<?= htmlspecialchars(strtolower($p['nom'])) ?>">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <?php if($p['image_principale']): ?>
              <?php $imgParts4=explode('/',$p['image_principale'],2); $imgUrl4='/darousalam/'.$imgParts4[0].'/'.rawurlencode($imgParts4[1]??''); ?>
              <img src="<?= htmlspecialchars($imgUrl4) ?>" style="width:48px;height:48px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;" onerror="this.style.display='none'">
              <?php else: ?>
              <div style="width:48px;height:48px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-image" style="color:#9ca3af;font-size:.9rem;"></i>
              </div>
              <?php endif; ?>
              <div>
                <div style="font-weight:700;font-size:.85rem;color:#1c1917;"><?= htmlspecialchars($p['nom']) ?></div>
                <?php if($p['origine']): ?>
                <div style="font-size:.72rem;color:#9ca3af;"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($p['origine']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td style="font-size:.82rem;color:#6b7280;"><?= htmlspecialchars($p['categorie_nom']??'—') ?></td>
          <td style="text-align:center;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:10px;padding:6px 14px;">
              <i class="bi bi-box-seam-fill" style="color:#0284c7;font-size:.9rem;"></i>
              <span style="font-weight:900;font-size:1.1rem;color:#0c4a6e;"><?= $cartons ?></span>
              <span style="font-size:.72rem;color:#0284c7;font-weight:600;">cartons</span>
            </div>
          </td>
          <td style="text-align:center;font-size:.82rem;color:#6b7280;">
            <?php if($poidsCarton > 0): ?>
              <span style="background:#f3f4f6;border-radius:7px;padding:4px 10px;font-weight:600;"><?= number_format($poidsCarton,1,',',' ') ?> kg/carton</span>
            <?php else: ?>
              <span style="color:#d1d5db;">—</span>
            <?php endif; ?>
          </td>
          <td style="text-align:center;">
            <?php if($equivKg > 0): ?>
              <span style="font-weight:700;font-size:.88rem;color:#374151;"><?= number_format($equivKg,1,',',' ') ?> kg</span>
            <?php else: ?>
              <span style="color:#d1d5db;font-size:.82rem;">—</span>
            <?php endif; ?>
          </td>
          <td style="text-align:center;">
            <span class="status-badge" style="color:<?= $ncColor ?>;background:<?= $ncBg ?>;">
              <?php if($nc==='critique'): ?><i class="bi bi-exclamation-octagon-fill" style="font-size:.7rem;"></i>
              <?php elseif($nc==='faible'): ?><i class="bi bi-exclamation-triangle-fill" style="font-size:.7rem;"></i>
              <?php else: ?><i class="bi bi-check-circle-fill" style="font-size:.7rem;"></i><?php endif; ?>
              <?= $ncLib ?>
            </span>
          </td>
          <td style="text-align:center;">
            <?php if($p['actif']): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif</span>
            <?php else: ?>
            <span class="status-badge" style="color:#9ca3af;background:#f9fafb;"><i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactif</span>
            <?php endif; ?>
          </td>
          <td><?= actionsBtns($p, BASE_URL) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<?php
function actionsBtns(array $p, string $BASE_URL): string {
  $id = $p['id'];
  $nom = htmlspecialchars(json_encode($p['nom']), ENT_QUOTES);
  $stock = (float)$p['stock_kg'];
  return <<<HTML
<div style="display:flex;align-items:center;gap:5px;">
  <a href="{$BASE_URL}?page=admin_produit_modifier&id={$id}"
     style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#f0fdf4;color:#16a34a;border-radius:8px;font-size:.73rem;font-weight:700;text-decoration:none;border:1px solid #bbf7d0;white-space:nowrap;"
     onmouseover="this.style.background='#16a34a';this.style.color='#fff'" onmouseout="this.style.background='#f0fdf4';this.style.color='#16a34a'">
    <i class="bi bi-pencil-fill"></i> Modifier
  </a>
  <button onclick="ouvrirMouvement({$id}, {$nom}, {$stock})"
     style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#fff7ed;color:#ea580c;border-radius:8px;font-size:.73rem;font-weight:700;border:1px solid #fed7aa;cursor:pointer;font-family:inherit;white-space:nowrap;"
     onmouseover="this.style.background='#ea580c';this.style.color='#fff'" onmouseout="this.style.background='#fff7ed';this.style.color='#ea580c'">
    <i class="bi bi-plus-slash-minus"></i> Mouvement
  </button>
  <a href="{$BASE_URL}?page=admin_stock_mouvements&produit_id={$id}"
     style="display:inline-flex;align-items:center;gap:4px;padding:5px 10px;background:#f3f4f6;color:#6b7280;border-radius:8px;font-size:.73rem;font-weight:700;text-decoration:none;border:1px solid #e5e7eb;white-space:nowrap;"
     onmouseover="this.style.background='#374151';this.style.color='#fff'" onmouseout="this.style.background='#f3f4f6';this.style.color='#6b7280'">
    <i class="bi bi-clock-history"></i> Historique
  </a>
</div>
HTML;
}
?>

<!-- Modal mouvement -->
<div id="modal-mouvement" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.45);z-index:10000;align-items:center;justify-content:center;" onclick="if(event.target===this)fermerModal()">
  <div style="background:#fff;border-radius:20px;padding:28px 32px;width:440px;max-width:95vw;box-shadow:0 20px 60px rgba(0,0,0,.2);animation:modalIn .2s ease;">
    <div style="font-size:1rem;font-weight:800;color:#111827;margin-bottom:6px;display:flex;align-items:center;gap:8px;">
      <i class="bi bi-plus-slash-minus" style="color:var(--vert);"></i>
      <span id="modal-titre">Mouvement de stock</span>
    </div>
    <div id="modal-stock-actuel" style="font-size:.76rem;color:#9ca3af;margin-bottom:20px;"></div>

    <form method="POST" action="<?= BASE_URL ?>?page=admin_stock_mouvement_ajouter">
      <input type="hidden" name="produit_id" id="modal-produit-id">

      <div style="margin-bottom:14px;">
        <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Type de mouvement</label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;" id="type-btns">
          <label style="cursor:pointer;">
            <input type="radio" name="type" value="entree" style="display:none;" onchange="selectType(this)">
            <div class="type-opt" data-val="entree" style="padding:10px 6px;border-radius:10px;border:2px solid #e5e7eb;text-align:center;transition:all .15s;">
              <i class="bi bi-arrow-down-circle-fill" style="color:#16a34a;font-size:1.1rem;display:block;margin-bottom:4px;"></i>
              <div style="font-size:.72rem;font-weight:700;color:#111827;">Entrée</div>
              <div style="font-size:.62rem;color:#9ca3af;">Réapprovisionnement</div>
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="type" value="sortie" style="display:none;" onchange="selectType(this)">
            <div class="type-opt" data-val="sortie" style="padding:10px 6px;border-radius:10px;border:2px solid #e5e7eb;text-align:center;transition:all .15s;">
              <i class="bi bi-arrow-up-circle-fill" style="color:#dc2626;font-size:1.1rem;display:block;margin-bottom:4px;"></i>
              <div style="font-size:.72rem;font-weight:700;color:#111827;">Sortie</div>
              <div style="font-size:.62rem;color:#9ca3af;">Perte / retrait</div>
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="type" value="ajustement" style="display:none;" onchange="selectType(this)">
            <div class="type-opt" data-val="ajustement" style="padding:10px 6px;border-radius:10px;border:2px solid #e5e7eb;text-align:center;transition:all .15s;">
              <i class="bi bi-sliders" style="color:#f59e0b;font-size:1.1rem;display:block;margin-bottom:4px;"></i>
              <div style="font-size:.72rem;font-weight:700;color:#111827;">Ajustement</div>
              <div style="font-size:.62rem;color:#9ca3af;">Correction stock</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Unité -->
      <div style="margin-bottom:14px;">
        <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Unité</label>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
          <label style="cursor:pointer;">
            <input type="radio" name="unite" value="kg" checked style="display:none;" onchange="changerUnite('kg')">
            <div class="unite-opt" data-val="kg" style="padding:8px 6px;border-radius:10px;border:2px solid var(--vert);background:#f0fdf4;text-align:center;font-size:.75rem;font-weight:700;color:#1a5c2a;transition:all .15s;cursor:pointer;">
              Kilogrammes (kg)
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="unite" value="carton" style="display:none;" onchange="changerUnite('carton')">
            <div class="unite-opt" data-val="carton" style="padding:8px 6px;border-radius:10px;border:2px solid #e5e7eb;text-align:center;font-size:.75rem;font-weight:700;color:#6b7280;transition:all .15s;cursor:pointer;">
              Cartons
            </div>
          </label>
          <label style="cursor:pointer;">
            <input type="radio" name="unite" value="les_deux" style="display:none;" onchange="changerUnite('les_deux')">
            <div class="unite-opt" data-val="les_deux" style="padding:8px 6px;border-radius:10px;border:2px solid #e5e7eb;text-align:center;font-size:.75rem;font-weight:700;color:#6b7280;transition:all .15s;cursor:pointer;">
              Les deux
            </div>
          </label>
        </div>
      </div>

      <!-- Champs quantité -->
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">
        <div id="field-kg">
          <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Quantité (kg)</label>
          <input type="number" name="quantite_kg" id="modal-qte" step="0.1" min="0"
            style="width:100%;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.88rem;font-family:inherit;color:#111827;background:#fafafa;"
            onfocus="this.style.borderColor='var(--vert)'" onblur="this.style.borderColor='#e5e7eb'"
            placeholder="ex: 50">
        </div>
        <div id="field-carton" style="display:none;">
          <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Quantité (cartons)</label>
          <input type="number" name="quantite_cartons" id="modal-cartons" step="1" min="0"
            style="width:100%;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.88rem;font-family:inherit;color:#111827;background:#fafafa;"
            onfocus="this.style.borderColor='var(--vert)'" onblur="this.style.borderColor='#e5e7eb'"
            placeholder="ex: 5">
        </div>
      </div>

      <div style="margin-bottom:14px;">
        <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Motif <span style="color:#9ca3af;font-weight:400;text-transform:none;">(optionnel)</span></label>
        <input type="text" name="motif"
          style="width:100%;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.84rem;font-family:inherit;color:#111827;background:#fafafa;transition:border-color .15s;"
          onfocus="this.style.borderColor='var(--vert)'" onblur="this.style.borderColor='#e5e7eb'"
          placeholder="ex: Livraison fournisseur, Produits abîmés…">
      </div>

      <div style="margin-bottom:22px;">
        <label style="font-size:.72rem;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;display:block;margin-bottom:6px;">Référence <span style="color:#9ca3af;font-weight:400;text-transform:none;">(optionnel)</span></label>
        <input type="text" name="reference"
          style="width:100%;padding:10px 13px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.84rem;font-family:inherit;color:#111827;background:#fafafa;transition:border-color .15s;"
          onfocus="this.style.borderColor='var(--vert)'" onblur="this.style.borderColor='#e5e7eb'"
          placeholder="ex: BL-2026-001">
      </div>

      <div style="display:flex;gap:10px;">
        <button type="submit" id="modal-submit-btn"
          style="flex:1;padding:12px;background:var(--vert);color:#fff;border:none;border-radius:10px;font-size:.88rem;font-weight:800;cursor:pointer;font-family:inherit;transition:background .15s;"
          onmouseover="this.style.background='var(--vert-l)'" onmouseout="this.style.background='var(--vert)'">
          Enregistrer
        </button>
        <button type="button" onclick="fermerModal()"
          style="padding:12px 20px;background:#f3f4f6;color:#374151;border:none;border-radius:10px;font-size:.88rem;font-weight:700;cursor:pointer;font-family:inherit;">
          Annuler
        </button>
      </div>
    </form>
  </div>
</div>

<style>
@keyframes modalIn{from{opacity:0;transform:scale(.96)}to{opacity:1;transform:scale(1)}}
.type-opt.selected{border-color:var(--vert)!important;background:#f0fdf4;}
</style>

<script>
let vueActive = 'kg';

function basculerVue(vue) {
  vueActive = vue;
  document.getElementById('vue-kg').style.display     = vue === 'kg'     ? 'block' : 'none';
  document.getElementById('vue-carton').style.display = vue === 'carton' ? 'block' : 'none';
  const tabKg     = document.getElementById('tab-kg');
  const tabCarton = document.getElementById('tab-carton');
  tabKg.style.background     = vue === 'kg'     ? 'var(--vert)' : 'transparent';
  tabKg.style.color          = vue === 'kg'     ? '#fff'        : '#6b7280';
  tabCarton.style.background = vue === 'carton' ? 'var(--vert)' : 'transparent';
  tabCarton.style.color      = vue === 'carton' ? '#fff'        : '#6b7280';
  filterStockTable();
}

function filterStockTable() {
  const search  = (document.getElementById('searchStock').value || '').toLowerCase();
  const niveau  = document.getElementById('sel-niveau').value;
  const cat     = document.getElementById('sel-cat').value;
  const tableId = vueActive === 'kg' ? 'stockTableKg' : 'stockTableCarton';
  document.querySelectorAll('#' + tableId + ' tbody tr').forEach(row => {
    const matchNom    = !search  || (row.dataset.nom || '').includes(search);
    const matchNiveau = !niveau  || row.dataset.niveau === niveau;
    const matchCat    = !cat     || row.dataset.cat    === cat;
    row.style.display = (matchNom && matchNiveau && matchCat) ? '' : 'none';
  });
}

function ouvrirMouvement(produitId, nomProduit, stockActuel) {
  document.getElementById('modal-produit-id').value = produitId;
  document.getElementById('modal-titre').textContent = nomProduit;
  document.getElementById('modal-stock-actuel').textContent = 'Stock actuel : ' + stockActuel.toLocaleString('fr-FR') + ' kg';
  document.getElementById('modal-qte').value = '';
  document.getElementById('modal-cartons').value = '';
  // Reset type
  document.querySelectorAll('.type-opt').forEach(el => el.classList.remove('selected'));
  document.querySelectorAll('#type-btns input[type=radio]').forEach(el => el.checked = false);
  // Reset unité à kg
  changerUnite('kg');
  document.querySelector('input[name=unite][value=kg]').checked = true;
  const modal = document.getElementById('modal-mouvement');
  modal.style.display = 'flex';
}

function changerUnite(val) {
  // Mettre à jour les boutons unité
  document.querySelectorAll('.unite-opt').forEach(el => {
    const selected = el.dataset.val === val;
    el.style.borderColor   = selected ? 'var(--vert)' : '#e5e7eb';
    el.style.background    = selected ? '#f0fdf4' : '#fff';
    el.style.color         = selected ? '#1a5c2a' : '#6b7280';
  });
  // Afficher/masquer les champs
  const kg      = document.getElementById('field-kg');
  const carton  = document.getElementById('field-carton');
  const gridEl  = kg.parentElement;
  if (val === 'kg') {
    kg.style.display = 'block'; carton.style.display = 'none';
    gridEl.style.gridTemplateColumns = '1fr';
  } else if (val === 'carton') {
    kg.style.display = 'none'; carton.style.display = 'block';
    gridEl.style.gridTemplateColumns = '1fr';
  } else {
    kg.style.display = 'block'; carton.style.display = 'block';
    gridEl.style.gridTemplateColumns = '1fr 1fr';
  }
}

function fermerModal() {
  document.getElementById('modal-mouvement').style.display = 'none';
}

function selectType(radio) {
  document.querySelectorAll('.type-opt').forEach(el => el.classList.remove('selected'));
  document.querySelector('.type-opt[data-val="' + radio.value + '"]').classList.add('selected');
}
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
