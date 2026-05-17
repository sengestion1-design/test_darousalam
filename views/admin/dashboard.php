<?php
$adminPage = 'dashboard';
$pageTitle = 'Tableau de bord';
require_once __DIR__ . '/layout.php';

$deltaCmd = $statsToday['nb_commandes'] - $statsHier['nb_commandes'];
$deltaCa  = $statsHier['ca'] > 0 ? round((($statsToday['ca'] - $statsHier['ca']) / $statsHier['ca']) * 100) : 0;
$deltaCli = $clientsToday['nb'] - $clientsHier['nb'];

$statutsMap = [
    'en_attente'     => ['lib'=>'En attente',    'color'=>'#1d4ed8','bg'=>'#dbeafe'],
    'confirmee'      => ['lib'=>'Confirmée',     'color'=>'#0e7490','bg'=>'#cffafe'],
    'en_preparation' => ['lib'=>'Préparation',   'color'=>'#92400e','bg'=>'#fef3c7'],
    'en_livraison'   => ['lib'=>'En livraison',  'color'=>'#6d28d9','bg'=>'#ede9fe'],
    'livree'         => ['lib'=>'Livrée',        'color'=>'#166534','bg'=>'#dcfce7'],
    'annulee'        => ['lib'=>'Annulée',       'color'=>'#991b1b','bg'=>'#fee2e2'],
];
?>

<style>
.db-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.db-kpi{background:#fff;border-radius:18px;padding:20px 22px;border:1px solid #e5e7eb;position:relative;overflow:hidden;transition:transform .15s,box-shadow .15s;}
.db-kpi:hover{transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.07);}
.db-kpi-bar{position:absolute;top:0;left:0;right:0;height:3px;border-radius:18px 18px 0 0;}
.db-kpi-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;margin-bottom:12px;}
.db-kpi-val{font-size:1.65rem;font-weight:900;color:#111827;line-height:1;margin-bottom:4px;}
.db-kpi-lbl{font-size:.7rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}
.db-kpi-delta{font-size:.72rem;font-weight:700;margin-top:6px;display:flex;align-items:center;gap:3px;}
.delta-up{color:#16a34a;} .delta-dn{color:#ef4444;} .delta-neu{color:#9ca3af;}

.db-card{background:#fff;border-radius:18px;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:20px;box-shadow:0 1px 6px rgba(0,0,0,.04);}
.db-card-head{padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;}
.db-card-title{font-size:.9rem;font-weight:800;color:#111827;display:flex;align-items:center;gap:8px;}

.rc-table{width:100%;border-collapse:collapse;}
.rc-table th{font-size:.63rem;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;font-weight:700;padding:10px 16px;background:#fafafa;border-bottom:1px solid #f3f4f6;white-space:nowrap;}
.rc-table td{padding:12px 16px;border-bottom:1px solid #f9fafb;font-size:.83rem;vertical-align:middle;}
.rc-table tr:last-child td{border-bottom:none;}
.rc-table tbody tr:hover td{background:#fafaf9;}

.stat-pill{display:inline-flex;align-items:center;padding:4px 10px;border-radius:8px;font-size:.7rem;font-weight:700;}

.stock-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid #f9fafb;}
.stock-row:last-child{border-bottom:none;}
.stock-photo{width:40px;height:40px;border-radius:10px;object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;}
.stock-photo-placeholder{width:40px;height:40px;border-radius:10px;background:#f5f3ee;border:1px solid #e5e7eb;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;}
.stock-bar-bg{flex:1;height:5px;background:#f3f4f6;border-radius:3px;overflow:hidden;}
.stock-bar-fill{height:100%;border-radius:3px;transition:width .4s;}

@media(max-width:1100px){.db-grid{grid-template-columns:1fr 1fr;}}
</style>

<!-- KPI -->
<div class="db-grid">
  <div class="db-kpi">
    <div class="db-kpi-bar" style="background:linear-gradient(90deg,#1a5c2a,#2d8a42);"></div>
    <div class="db-kpi-icon" style="background:#f0fdf4;"><i class="bi bi-bag-check-fill" style="color:#1a5c2a;"></i></div>
    <div class="db-kpi-val"><?= $statsToday['nb_commandes'] ?></div>
    <div class="db-kpi-lbl">Commandes aujourd'hui</div>
    <div class="db-kpi-delta <?= $deltaCmd >= 0 ? 'delta-up' : 'delta-dn' ?>">
      <i class="bi bi-arrow-<?= $deltaCmd >= 0 ? 'up' : 'down' ?>-short"></i>
      <?= ($deltaCmd >= 0 ? '+' : '') . $deltaCmd ?> vs hier
    </div>
  </div>
  <div class="db-kpi">
    <div class="db-kpi-bar" style="background:linear-gradient(90deg,#ea580c,#f97316);"></div>
    <div class="db-kpi-icon" style="background:#fff7ed;"><i class="bi bi-cash-stack" style="color:#ea580c;"></i></div>
    <div class="db-kpi-val" style="font-size:1.15rem;"><?= number_format($statsToday['ca'],0,',',' ') ?></div>
    <div class="db-kpi-lbl">FCFA chiffre d'affaires</div>
    <div class="db-kpi-delta <?= $deltaCa >= 0 ? 'delta-up' : 'delta-dn' ?>">
      <i class="bi bi-arrow-<?= $deltaCa >= 0 ? 'up' : 'down' ?>-short"></i>
      <?= ($deltaCa >= 0 ? '+' : '') . $deltaCa ?>% vs hier
    </div>
  </div>
  <div class="db-kpi">
    <div class="db-kpi-bar" style="background:linear-gradient(90deg,#1d4ed8,#3b82f6);"></div>
    <div class="db-kpi-icon" style="background:#eff6ff;"><i class="bi bi-person-plus-fill" style="color:#1d4ed8;"></i></div>
    <div class="db-kpi-val"><?= $clientsToday['nb'] ?></div>
    <div class="db-kpi-lbl">Nouveaux clients</div>
    <div class="db-kpi-delta <?= $deltaCli >= 0 ? 'delta-up' : 'delta-dn' ?>">
      <i class="bi bi-arrow-<?= $deltaCli >= 0 ? 'up' : 'down' ?>-short"></i>
      <?= ($deltaCli >= 0 ? '+' : '') . $deltaCli ?> vs hier
    </div>
  </div>
  <div class="db-kpi">
    <div class="db-kpi-bar" style="background:linear-gradient(90deg,#dc2626,#ef4444);"></div>
    <div class="db-kpi-icon" style="background:#fef2f2;"><i class="bi bi-exclamation-triangle-fill" style="color:#dc2626;"></i></div>
    <div class="db-kpi-val"><?= $nbStockAlertes ?></div>
    <div class="db-kpi-lbl">Alertes stock</div>
    <?php if($nbStockAlertes > 0): ?>
    <div class="db-kpi-delta delta-dn"><i class="bi bi-exclamation-circle"></i> Réapprovisionnement requis</div>
    <?php else: ?>
    <div class="db-kpi-delta delta-up"><i class="bi bi-check-circle"></i> Stocks suffisants</div>
    <?php endif; ?>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

  <!-- Colonne gauche -->
  <div>
    <!-- Graphique -->
    <div class="db-card">
      <div class="db-card-head">
        <div class="db-card-title"><i class="bi bi-bar-chart-fill" style="color:var(--vert);"></i> Commandes — 7 derniers jours</div>
      </div>
      <div style="padding:20px;">
        <canvas id="ordersChart" height="130"></canvas>
      </div>
    </div>

    <!-- Commandes récentes -->
    <div class="db-card">
      <div class="db-card-head">
        <div class="db-card-title"><i class="bi bi-clock-history" style="color:var(--orange);"></i> Commandes récentes</div>
        <a href="<?= BASE_URL ?>?page=admin_commandes" style="font-size:.76rem;color:var(--vert);text-decoration:none;font-weight:700;display:flex;align-items:center;gap:4px;">
          Voir tout <i class="bi bi-arrow-right"></i>
        </a>
      </div>
      <div style="overflow-x:auto;">
        <table class="rc-table">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Client</th>
              <th>Montant</th>
              <th>Statut</th>
              <th>Date</th>
            </tr>
          </thead>
          <tbody>
            <?php if(empty($commandesRecentes)): ?>
            <tr><td colspan="5" style="text-align:center;padding:32px;color:#9ca3af;font-size:.84rem;">Aucune commande</td></tr>
            <?php else: ?>
            <?php foreach($commandesRecentes as $cmd):
              $st = $statutsMap[$cmd['statut']] ?? ['lib'=>$cmd['statut'],'color'=>'#6b7280','bg'=>'#f3f4f6'];
              $nom = trim(($cmd['client_prenom']??'').' '.($cmd['client_nom']??'')) ?: 'Client';
            ?>
            <tr>
              <td>
                <a href="<?= BASE_URL ?>?page=admin_commande_detail&id=<?= $cmd['id'] ?>"
                   style="font-weight:800;color:var(--vert);text-decoration:none;font-size:.8rem;font-family:monospace;">
                  <?= htmlspecialchars($cmd['reference']) ?>
                </a>
              </td>
              <td style="font-weight:600;"><?= htmlspecialchars($nom) ?></td>
              <td style="font-weight:800;color:var(--orange);"><?= number_format($cmd['total'],0,',',' ') ?> <span style="font-size:.65rem;font-weight:600;color:#fdba74;">FCFA</span></td>
              <td>
                <span class="stat-pill" style="background:<?= $st['bg'] ?>;color:<?= $st['color'] ?>;"><?= $st['lib'] ?></span>
              </td>
              <td style="font-size:.75rem;color:#9ca3af;"><?= date('d/m H:i', strtotime($cmd['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Colonne droite -->
  <div>
    <!-- État du stock avec vraies photos -->
    <div class="db-card">
      <div class="db-card-head">
        <div class="db-card-title"><i class="bi bi-boxes" style="color:var(--vert);"></i> État du stock</div>
        <a href="<?= BASE_URL ?>?page=admin_stocks" style="font-size:.74rem;color:var(--orange);text-decoration:none;font-weight:700;">Gérer</a>
      </div>
      <?php if(empty($stockProduits)): ?>
      <div style="padding:24px;text-align:center;color:#9ca3af;font-size:.82rem;">Aucun produit</div>
      <?php else: ?>
      <?php foreach($stockProduits as $p):
        $seuil = (float)($p['unite_min_kg'] ?? 10);
        $stock = (float)$p['stock_kg'];
        $pct   = $seuil > 0 ? min(100, round(($stock / ($seuil * 3)) * 100)) : 50;
        $low   = $stock <= $seuil;
        $mid   = !$low && $stock <= $seuil * 2;
        $color = $low ? '#ef4444' : ($mid ? '#f97316' : '#22c55e');
        $imgSrc = !empty($p['image_principale']) ? BASE_URL . $p['image_principale'] : '';
      ?>
      <div class="stock-row">
        <?php if($imgSrc): ?>
        <img src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($p['nom']) ?>" class="stock-photo">
        <?php else: ?>
        <div class="stock-photo-placeholder"><i class="bi bi-apple" style="color:#d1d5db;"></i></div>
        <?php endif; ?>
        <div style="flex:1;min-width:0;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:5px;">
            <span style="font-size:.82rem;font-weight:700;color:#111827;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:120px;"><?= htmlspecialchars($p['nom']) ?></span>
            <span style="font-size:.75rem;font-weight:800;color:<?= $color ?>;white-space:nowrap;margin-left:6px;">
              <?= number_format($stock,0,',',' ') ?> kg<?php if($low): ?> <i class="bi bi-exclamation-triangle-fill" style="font-size:.65rem;"></i><?php endif; ?>
            </span>
          </div>
          <div class="stock-bar-bg"><div class="stock-bar-fill" style="width:<?= $pct ?>%;background:<?= $color ?>;"></div></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Actions rapides -->
    <div class="db-card">
      <div class="db-card-head">
        <div class="db-card-title"><i class="bi bi-lightning-fill" style="color:var(--orange);"></i> Actions rapides</div>
      </div>
      <div style="padding:10px 12px;">
        <a href="<?= BASE_URL ?>?page=admin_commandes" style="display:flex;align-items:center;gap:12px;padding:11px 10px;border-radius:11px;text-decoration:none;transition:background .12s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background=''">
          <div style="width:38px;height:38px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-inbox-fill" style="color:#2563eb;font-size:.95rem;"></i></div>
          <div><div style="font-size:.82rem;font-weight:700;color:#111827;">Commandes en attente</div><div style="font-size:.7rem;color:#9ca3af;margin-top:1px;"><?= $enAttente['nb'] ?> à traiter</div></div>
          <i class="bi bi-chevron-right" style="color:#d1d5db;font-size:.72rem;margin-left:auto;"></i>
        </a>
        <a href="<?= BASE_URL ?>?page=admin_stocks" style="display:flex;align-items:center;gap:12px;padding:11px 10px;border-radius:11px;text-decoration:none;transition:background .12s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background=''">
          <div style="width:38px;height:38px;border-radius:10px;background:#fef2f2;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-exclamation-triangle-fill" style="color:#ef4444;font-size:.95rem;"></i></div>
          <div><div style="font-size:.82rem;font-weight:700;color:#111827;">Alertes stock</div><div style="font-size:.7rem;color:#9ca3af;margin-top:1px;"><?= $nbStockAlertes ?> produit<?= $nbStockAlertes > 1 ? 's' : '' ?> à recharger</div></div>
          <i class="bi bi-chevron-right" style="color:#d1d5db;font-size:.72rem;margin-left:auto;"></i>
        </a>
        <a href="<?= BASE_URL ?>?page=admin_produit_ajouter" style="display:flex;align-items:center;gap:12px;padding:11px 10px;border-radius:11px;text-decoration:none;transition:background .12s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background=''">
          <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-plus-circle-fill" style="color:#1a5c2a;font-size:.95rem;"></i></div>
          <div><div style="font-size:.82rem;font-weight:700;color:#111827;">Ajouter un produit</div><div style="font-size:.7rem;color:#9ca3af;margin-top:1px;">Catalogue</div></div>
          <i class="bi bi-chevron-right" style="color:#d1d5db;font-size:.72rem;margin-left:auto;"></i>
        </a>
        <a href="<?= BASE_URL ?>?page=admin_clients" style="display:flex;align-items:center;gap:12px;padding:11px 10px;border-radius:11px;text-decoration:none;transition:background .12s;" onmouseover="this.style.background='#fafafa'" onmouseout="this.style.background=''">
          <div style="width:38px;height:38px;border-radius:10px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="bi bi-people-fill" style="color:#6b7280;font-size:.95rem;"></i></div>
          <div><div style="font-size:.82rem;font-weight:700;color:#111827;">Clients</div><div style="font-size:.7rem;color:#9ca3af;margin-top:1px;">Gérer les comptes</div></div>
          <i class="bi bi-chevron-right" style="color:#d1d5db;font-size:.72rem;margin-left:auto;"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('ordersChart').getContext('2d'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($joursSemaine) ?>,
    datasets: [{
      label: 'Commandes',
      data: <?= json_encode($semaine) ?>,
      backgroundColor: 'rgba(26,92,42,.12)',
      borderColor: '#1a5c2a',
      borderWidth: 2,
      borderRadius: 8,
      borderSkipped: false,
      hoverBackgroundColor: 'rgba(249,115,22,.18)',
      hoverBorderColor: '#f97316',
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: { callbacks: { label: c => ' ' + c.raw + ' commande' + (c.raw > 1 ? 's' : '') } }
    },
    scales: {
      y: { beginAtZero: true, grid: { color: '#f9fafb' }, ticks: { font: { size: 11 }, stepSize: 1 } },
      x: { grid: { display: false }, ticks: { font: { size: 11 } } }
    }
  }
});
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
