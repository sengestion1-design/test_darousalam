<?php
$adminPage = 'livreurs';
$pageTitle = 'Livreur — ' . htmlspecialchars($livreur['nom']);
$topbarActions = '<a href="'.BASE_URL.'?page=admin_livreurs" class="topbar-btn" style="background:#f3f4f6;color:#374151;"><i class="bi bi-arrow-left"></i> Retour</a>';
require_once __DIR__ . '/layout.php';

$nbTotal   = count($livraisons);
$nbLivrees = 0; $nbEchec = 0; $totalFrais = 0;
foreach ($livraisons as $l) {
    if ($l['statut'] === 'livree') { $nbLivrees++; $totalFrais += (float)$l['frais_livraison']; }
    if ($l['statut'] === 'echec')  { $nbEchec++; }
}
$taux = $nbTotal > 0 ? round($nbLivrees / $nbTotal * 100) : 0;

// Filtre période — basé sur created_at si date_livree est NULL
$mois = $_GET['mois'] ?? date('Y-m');
$livraisonsFiltrees = [];
$fraisMois = 0; $nbMois = 0;
foreach ($livraisons as $l) {
    $date = !empty($l['date_livree']) ? $l['date_livree'] : ($l['created_at'] ?? '');
    if ($date && str_starts_with($date, $mois)) {
        $livraisonsFiltrees[] = $l;
        if ($l['statut'] === 'livree') {
            $fraisMois += (float)$l['frais_livraison'];
            $nbMois++;
        }
    }
}
?>

<style>
.lv-hero{background:linear-gradient(135deg,#050d07,#0f2d16,#1a5c2a);border-radius:20px;padding:32px;margin-bottom:24px;display:flex;align-items:center;gap:24px;}
.lv-avatar{width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.6rem;font-weight:900;color:#fff;flex-shrink:0;border:3px solid rgba(255,255,255,.25);}
.lv-name{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:900;color:#fff;margin:0 0 4px;}
.lv-meta{font-size:.82rem;color:rgba(255,255,255,.6);display:flex;gap:16px;flex-wrap:wrap;}
.lv-meta span{display:flex;align-items:center;gap:5px;}
.paie-box{background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:2px solid #86efac;border-radius:16px;padding:22px 26px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;}
.paie-label{font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#166534;margin-bottom:4px;}
.paie-val{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:#15803d;}
.paie-sub{font-size:.75rem;color:#16a34a;margin-top:2px;}
.mois-select{border:1.5px solid #bbf7d0;border-radius:9px;padding:8px 12px;font-size:.84rem;background:#fff;color:#166534;font-weight:600;cursor:pointer;}
</style>

<!-- Hero livreur -->
<div class="lv-hero">
  <div class="lv-avatar">
    <?= strtoupper(implode('', array_map(fn($w) => $w[0], array_slice(explode(' ', $livreur['nom']), 0, 2)))) ?>
  </div>
  <div style="flex:1;">
    <div class="lv-name"><?= htmlspecialchars($livreur['nom']) ?></div>
    <div class="lv-meta">
      <span><i class="bi bi-telephone-fill"></i> <?= htmlspecialchars($livreur['telephone']) ?></span>
      <?php if ($livreur['zone']): ?>
      <span><i class="bi bi-geo-alt-fill"></i> <?= htmlspecialchars($livreur['zone']) ?></span>
      <?php endif; ?>
      <span><i class="bi bi-calendar3"></i> Depuis le <?= date('d/m/Y', strtotime($livreur['created_at'])) ?></span>
    </div>
  </div>
  <div style="text-align:right;">
    <?php if ($livreur['actif']): ?>
    <span style="background:rgba(74,222,128,.2);border:1px solid rgba(74,222,128,.4);border-radius:20px;padding:6px 16px;font-size:.8rem;font-weight:700;color:#4ade80;">● Actif</span>
    <?php else: ?>
    <span style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:20px;padding:6px 16px;font-size:.8rem;font-weight:700;color:rgba(255,255,255,.5);">○ Inactif</span>
    <?php endif; ?>
  </div>
</div>

<!-- KPIs globaux -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px;">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#eff6ff;"><i class="bi bi-truck-front-fill" style="color:#2563eb;"></i></div>
    <div class="stat-box-val"><?= $nbTotal ?></div>
    <div class="stat-box-lbl">Total livraisons</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check2-all" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= $nbLivrees ?></div>
    <div class="stat-box-lbl">Livrées</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef2f2;"><i class="bi bi-x-circle-fill" style="color:#dc2626;"></i></div>
    <div class="stat-box-val"><?= $nbEchec ?></div>
    <div class="stat-box-lbl">Échecs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fdf4ff;"><i class="bi bi-bar-chart-fill" style="color:#9333ea;"></i></div>
    <div class="stat-box-val"><?= $taux ?>%</div>
    <div class="stat-box-lbl">Taux de succès</div>
  </div>
</div>

<!-- Paie du mois -->
<div class="paie-box">
  <div>
    <div class="paie-label"><i class="bi bi-cash-coin"></i> Paie du mois — frais de livraison collectés</div>
    <div class="paie-val"><?= number_format($fraisMois, 0, ',', ' ') ?> <span style="font-size:1rem;font-weight:600;">FCFA</span></div>
    <div class="paie-sub">
      <?= $nbMois ?> livraison<?= $nbMois > 1 ? 's' : '' ?> réussie<?= $nbMois > 1 ? 's' : '' ?> ce mois
      <?php if ($nbMois > 0 && $fraisMois == 0): ?>
      <span style="margin-left:8px;background:#fef3c7;color:#92400e;border-radius:6px;padding:2px 8px;font-size:.7rem;font-weight:700;">
        <i class="bi bi-info-circle"></i> Livraisons gratuites (pas de frais facturés)
      </span>
      <?php endif; ?>
    </div>
    <?php
      // Montant en attente (assignées non encore livrées)
      $fraisEnAttente = 0;
      foreach ($livraisons as $l) {
          if (in_array($l['statut'], ['assignee','en_cours']) && (float)$l['frais_livraison'] > 0) {
              $fraisEnAttente += (float)$l['frais_livraison'];
          }
      }
    ?>
    <?php if ($fraisEnAttente > 0): ?>
    <div style="margin-top:8px;font-size:.78rem;color:#d97706;font-weight:600;">
      <i class="bi bi-clock-history"></i> <?= number_format($fraisEnAttente, 0, ',', ' ') ?> FCFA en attente (livraisons non encore effectuées)
    </div>
    <?php endif; ?>
  </div>
  <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
    <form method="GET" action="">
      <input type="hidden" name="page" value="admin_livreur_detail">
      <input type="hidden" name="id" value="<?= $livreur['id'] ?>">
      <input type="month" name="mois" value="<?= htmlspecialchars($mois) ?>" class="mois-select" onchange="this.form.submit()">
    </form>
    <div style="font-size:.75rem;color:#166534;">Total toutes périodes : <strong><?= number_format($totalFrais, 0, ',', ' ') ?> FCFA</strong></div>
  </div>
</div>

<!-- Tableau livraisons -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-list-ul" style="color:var(--vert);"></i> Livraisons de <?= htmlspecialchars($livreur['nom']) ?></div>
    <span style="font-size:.75rem;color:#9ca3af;"><?= count($livraisonsFiltrees) ?> ce mois / <?= $nbTotal ?> total</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Client</th>
          <th>Adresse</th>
          <th>Frais livraison</th>
          <th>Statut</th>
          <th>Date prévue</th>
          <th>Date livrée</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($livraisonsFiltrees)): ?>
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#9ca3af;">
          <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
          Aucune livraison ce mois
        </td></tr>
        <?php else: foreach ($livraisonsFiltrees as $liv): ?>
        <?php
          $adresseArr = json_decode($liv['adresse_livraison'] ?? '{}', true) ?: [];
          $adresse    = implode(', ', array_filter([$adresseArr['adresse'] ?? '', $adresseArr['ville'] ?? '']));
          $statutColors = [
            'en_attente' => ['#fef3c7','#92400e'],
            'assignee'   => ['#eff6ff','#1d4ed8'],
            'en_cours'   => ['#f0fdf4','#166534'],
            'livree'     => ['#dcfce7','#15803d'],
            'echec'      => ['#fef2f2','#b91c1c'],
          ];
          $sc = $statutColors[$liv['statut']] ?? ['#f3f4f6','#6b7280'];
          $statutLabels = ['en_attente'=>'En attente','assignee'=>'Assignée','en_cours'=>'En cours','livree'=>'Livrée','echec'=>'Échec'];
        ?>
        <tr>
          <td>
            <span style="font-weight:700;font-size:.82rem;color:#1a5c2a;"><?= htmlspecialchars($liv['reference']) ?></span>
            <div style="font-size:.68rem;color:#9ca3af;">#<?= $liv['commande_id'] ?></div>
          </td>
          <td>
            <div style="font-size:.84rem;font-weight:600;"><?= htmlspecialchars(trim(($liv['client_prenom'] ?? '') . ' ' . ($liv['client_nom'] ?? ''))) ?: '<span style="color:#9ca3af;">—</span>' ?></div>
            <?php if (!empty($liv['client_telephone'])): ?>
            <div style="font-size:.7rem;color:#9ca3af;"><?= htmlspecialchars($liv['client_telephone']) ?></div>
            <?php endif; ?>
          </td>
          <td style="font-size:.78rem;color:#374151;max-width:180px;"><?= $adresse ?: '<span style="color:#d1d5db;">—</span>' ?></td>
          <td>
            <?php if ((float)$liv['frais_livraison'] > 0): ?>
            <span style="font-weight:800;font-size:.88rem;color:#1a5c2a;"><?= number_format((float)$liv['frais_livraison'], 0, ',', ' ') ?> FCFA</span>
            <?php else: ?>
            <span style="color:#16a34a;font-size:.82rem;font-weight:600;"><i class="bi bi-gift-fill"></i> Gratuite</span>
            <?php endif; ?>
          </td>
          <td>
            <span class="status-badge" style="background:<?= $sc[0] ?>;color:<?= $sc[1] ?>;">
              <?= $statutLabels[$liv['statut']] ?? $liv['statut'] ?>
            </span>
          </td>
          <td style="font-size:.8rem;color:#374151;">
            <?= $liv['date_prevue'] ? date('d/m/Y', strtotime($liv['date_prevue'])) : '<span style="color:#d1d5db;">—</span>' ?>
          </td>
          <td style="font-size:.8rem;">
            <?php if ($liv['date_livree']): ?>
            <span style="color:#16a34a;font-weight:600;"><?= date('d/m/Y', strtotime($liv['date_livree'])) ?></span>
            <?php else: ?>
            <span style="color:#d1d5db;">—</span>
            <?php endif; ?>
          </td>
          <td>
            <a href="<?= BASE_URL ?>?page=admin_commande_detail&id=<?= $liv['commande_id'] ?>"
               class="btn-sm-action btn-view" title="Voir la commande">
              <i class="bi bi-eye"></i>
            </a>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
      <?php if (!empty($livraisonsFiltrees) && $fraisMois > 0): ?>
      <tfoot>
        <tr style="background:#f0fdf4;">
          <td colspan="3" style="text-align:right;font-weight:700;font-size:.85rem;padding:12px 16px;color:#166534;">Total frais ce mois</td>
          <td style="font-weight:900;font-size:.95rem;color:#15803d;padding:12px 16px;"><?= number_format($fraisMois, 0, ',', ' ') ?> FCFA</td>
          <td colspan="4"></td>
        </tr>
      </tfoot>
      <?php endif; ?>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/layout_end.php'; ?>
