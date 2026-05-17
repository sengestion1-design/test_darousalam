<?php require_once __DIR__ . '/layout.php'; ?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

<style>
/* ── Période tabs ── */
.periode-tabs{display:flex;gap:6px;flex-wrap:wrap;}
.periode-tab{
  padding:7px 16px;border-radius:9px;font-size:.78rem;font-weight:600;
  text-decoration:none;border:1.5px solid #e5e7eb;color:#6b7280;
  background:#fff;transition:all .18s;
}
.periode-tab:hover{border-color:var(--vert);color:var(--vert);}
.periode-tab.active{background:var(--vert);color:#fff;border-color:var(--vert);}

/* ── KPI cards ── */
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.kpi-card{
  background:#fff;border-radius:16px;border:1px solid #e5e7eb;
  box-shadow:0 1px 8px rgba(0,0,0,.04);padding:22px 20px;
  display:flex;flex-direction:column;gap:6px;
}
.kpi-icon{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;margin-bottom:6px;}
.kpi-val{font-size:1.55rem;font-weight:900;color:#0f2d16;line-height:1.1;}
.kpi-lbl{font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}
.kpi-sub{font-size:.73rem;color:#6b7280;margin-top:2px;}

/* ── Chart containers ── */
.chart-wrap{position:relative;width:100%;padding:20px;}
.chart-wrap canvas{max-height:280px;}
.chart-wrap-donut canvas{max-height:260px;}

/* ── Grilles ── */
.two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;}
.three-col{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:20px;}

/* ── Top rank badge ── */
.rank-badge{
  width:26px;height:26px;border-radius:8px;display:flex;align-items:center;
  justify-content:center;font-size:.72rem;font-weight:800;flex-shrink:0;
}
.rank-1{background:#fef9c3;color:#854d0e;}
.rank-2{background:#f1f5f9;color:#475569;}
.rank-3{background:#fef2e8;color:#9a3412;}
.rank-other{background:#f9fafb;color:#9ca3af;}

/* ── Progress bar ── */
.prog-bar{height:6px;border-radius:4px;background:#f3f4f6;overflow:hidden;margin-top:4px;}
.prog-fill{height:100%;border-radius:4px;transition:width .6s ease;}

/* ── Unité repartition ── */
.unite-card{
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  padding:28px 20px;background:#fff;border-radius:16px;border:1px solid #e5e7eb;
  box-shadow:0 1px 8px rgba(0,0,0,.04);text-align:center;
}
.unite-icon{font-size:2.4rem;margin-bottom:10px;}
.unite-val{font-size:2rem;font-weight:900;line-height:1;}
.unite-lbl{font-size:.72rem;color:#9ca3af;margin-top:4px;text-transform:uppercase;letter-spacing:.07em;}
.unite-ca{font-size:.88rem;font-weight:700;margin-top:8px;}

@media(max-width:992px){
  .kpi-grid{grid-template-columns:1fr 1fr;}
  .two-col,.three-col{grid-template-columns:1fr;}
}
@media(max-width:576px){
  .kpi-grid{grid-template-columns:1fr;}
}
</style>

<?php
// ── Helpers locaux ────────────────────────────────────────────────────────────
function fcfa(float $v): string {
    return number_format($v, 0, ',', ' ') . ' FCFA';
}
function pct(float $val, float $total): float {
    return $total > 0 ? round($val / $total * 100, 1) : 0;
}

$periode   = $periode ?? 'mois';
$periodeLabels = [
    'semaine'   => 'Cette semaine',
    'mois'      => 'Ce mois',
    'trimestre' => '3 derniers mois',
    'annee'     => 'Cette année',
];

// Couleurs statuts
$statutColors = [
    'en_attente'  => ['bg'=>'#fff7ed','color'=>'#f97316','label'=>'En attente'],
    'confirmee'   => ['bg'=>'#eff6ff','color'=>'#2563eb','label'=>'Confirmée'],
    'en_cours'    => ['bg'=>'#f0fdf4','color'=>'#16a34a','label'=>'En cours'],
    'livree'      => ['bg'=>'#f0fdf4','color'=>'#15803d','label'=>'Livrée'],
    'annulee'     => ['bg'=>'#fef2f2','color'=>'#dc2626','label'=>'Annulée'],
];

// Données graphiques JSON
$labelsJson   = json_encode($moisLabels);
$caJson       = json_encode($moisCA);
$nbJson       = json_encode($moisNb);

$statutLabels = array_map(fn($r) => $statutColors[$r['statut']]['label'] ?? ucfirst($r['statut']), $parStatut);
$statutNb     = array_column($parStatut, 'nb');
$statutColHex = array_map(fn($r) => $statutColors[$r['statut']]['color'] ?? '#9ca3af', $parStatut);

$statutLabelsJson  = json_encode($statutLabels);
$statutNbJson      = json_encode($statutNb);
$statutColorsJson  = json_encode($statutColHex);

// Max CA pour barres
$maxCA = max(array_column($topProduits ?: [['ca_total'=>1]], 'ca_total') ?: [1]);
$maxDep = max(array_column($topClients ?: [['total_depense'=>1]], 'total_depense') ?: [1]);
?>

<!-- ── HEADER ───────────────────────────────────────────────────────────────── -->
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;flex-wrap:wrap;gap:12px;">
  <div>
    <h2 style="font-family:'Playfair Display',serif;font-size:1.45rem;font-weight:800;color:#0f2d16;margin:0;">
      Rapports & Statistiques
    </h2>
    <p style="font-size:.78rem;color:#9ca3af;margin-top:3px;">
      Données filtrées · <strong style="color:#1a5c2a;"><?= $periodeLabels[$periode] ?></strong>
    </p>
  </div>
  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
    <div class="periode-tabs">
      <?php foreach ($periodeLabels as $key => $label): ?>
        <a href="?page=admin_rapports&periode=<?= $key ?>"
           class="periode-tab <?= $periode === $key ? 'active' : '' ?>">
          <?= $label ?>
        </a>
      <?php endforeach; ?>
    </div>
    <a href="<?= BASE_URL ?>?page=admin_export_commandes&periode=<?= $periode ?>"
       style="display:flex;align-items:center;gap:6px;padding:8px 14px;background:#f0fdf4;color:#166534;border:1.5px solid #bbf7d0;border-radius:9px;font-size:.78rem;font-weight:700;text-decoration:none;white-space:nowrap;"
       title="Exporter les commandes en CSV (Excel)">
      <i class="bi bi-file-earmark-spreadsheet-fill"></i> Exporter CSV
    </a>
  </div>
</div>

<!-- ── KPIs ─────────────────────────────────────────────────────────────────── -->
<div class="kpi-grid">
  <div class="kpi-card">
    <div class="kpi-icon" style="background:#f0fdf4;">
      <i class="bi bi-currency-exchange" style="color:#1a5c2a;"></i>
    </div>
    <div class="kpi-val"><?= fcfa((float)$kpiTotal['ca']) ?></div>
    <div class="kpi-lbl">Chiffre d'affaires total</div>
    <div class="kpi-sub"><?= number_format((int)$kpiTotal['nb'], 0, ',', ' ') ?> commandes au total</div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon" style="background:#fff7ed;">
      <i class="bi bi-calendar-month" style="color:#f97316;"></i>
    </div>
    <div class="kpi-val" style="color:#f97316;"><?= fcfa((float)$kpiMois['ca']) ?></div>
    <div class="kpi-lbl">CA — <?= $periodeLabels[$periode] ?></div>
    <div class="kpi-sub"><?= number_format((int)$kpiMois['nb'], 0, ',', ' ') ?> commande(s) sur la période</div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon" style="background:#eff6ff;">
      <i class="bi bi-bag-check" style="color:#2563eb;"></i>
    </div>
    <div class="kpi-val" style="color:#2563eb;"><?= number_format((int)$kpiMois['nb'], 0, ',', ' ') ?></div>
    <div class="kpi-lbl">Commandes (période)</div>
    <div class="kpi-sub">Sur <?= $periodeLabels[$periode] ?></div>
  </div>

  <div class="kpi-card">
    <div class="kpi-icon" style="background:#fdf4ff;">
      <i class="bi bi-basket2" style="color:#9333ea;"></i>
    </div>
    <div class="kpi-val" style="color:#9333ea;"><?= fcfa((float)$panierMoyen) ?></div>
    <div class="kpi-lbl">Panier moyen</div>
    <div class="kpi-sub">Toutes périodes confondues</div>
  </div>
</div>

<!-- ── GRAPHIQUE CA PAR MOIS ─────────────────────────────────────────────────── -->
<div class="a-card" style="margin-bottom:20px;">
  <div class="a-card-head">
    <div class="a-card-title">
      <i class="bi bi-graph-up-arrow" style="color:#1a5c2a;"></i>
      Chiffre d'affaires — 12 derniers mois
    </div>
    <span style="font-size:.72rem;color:#9ca3af;">Commandes hors annulées</span>
  </div>
  <div class="chart-wrap">
    <canvas id="chartCA"></canvas>
  </div>
</div>

<!-- ── 2 COL : Statuts + Unités ──────────────────────────────────────────────── -->
<div class="two-col">
  <!-- Répartition statuts -->
  <div class="a-card" style="margin-bottom:0;">
    <div class="a-card-head">
      <div class="a-card-title">
        <i class="bi bi-pie-chart" style="color:#f97316;"></i>
        Répartition par statut
      </div>
    </div>
    <div class="chart-wrap chart-wrap-donut" style="display:flex;align-items:center;gap:24px;flex-wrap:wrap;">
      <div style="flex:1;min-width:180px;">
        <canvas id="chartStatuts"></canvas>
      </div>
      <div style="flex:1;min-width:140px;">
        <?php foreach ($parStatut as $i => $row):
          $sc = $statutColors[$row['statut']] ?? ['bg'=>'#f9fafb','color'=>'#9ca3af','label'=>ucfirst($row['statut'])];
        ?>
        <div style="display:flex;align-items:center;justify-content:space-between;padding:7px 0;border-bottom:1px solid #f9fafb;">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:10px;height:10px;border-radius:3px;background:<?= $sc['color'] ?>;display:inline-block;flex-shrink:0;"></span>
            <span style="font-size:.78rem;color:#374151;"><?= $sc['label'] ?></span>
          </div>
          <span style="font-size:.82rem;font-weight:700;color:#1c1917;"><?= $row['nb'] ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Répartition kg / cartons -->
  <div class="a-card" style="margin-bottom:0;">
    <div class="a-card-head">
      <div class="a-card-title">
        <i class="bi bi-box2-heart" style="color:#9333ea;"></i>
        Kg vs Cartons vendus
      </div>
      <span style="font-size:.72rem;color:#9ca3af;"><?= $periodeLabels[$periode] ?></span>
    </div>
    <div style="padding:20px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <?php
        $totalKg      = (float)($repartitionUnites['total_kg'] ?? 0);
        $totalCartons = (float)($repartitionUnites['total_cartons'] ?? 0);
        $caKg         = (float)($repartitionUnites['ca_kg'] ?? 0);
        $caCartons    = (float)($repartitionUnites['ca_cartons'] ?? 0);
        $totalQte     = $totalKg + $totalCartons ?: 1;
      ?>
      <div class="unite-card" style="background:#f0fdf4;border-color:#bbf7d0;">
        <div class="unite-icon">⚖️</div>
        <div class="unite-val" style="color:#15803d;"><?= number_format($totalKg, 0, ',', ' ') ?></div>
        <div class="unite-lbl">kg vendus</div>
        <div class="unite-ca" style="color:#1a5c2a;"><?= fcfa($caKg) ?></div>
        <div style="font-size:.68rem;color:#86efac;margin-top:6px;"><?= pct($totalKg, $totalQte) ?>% du volume</div>
      </div>
      <div class="unite-card" style="background:#fff7ed;border-color:#fed7aa;">
        <div class="unite-icon">📦</div>
        <div class="unite-val" style="color:#c2410c;"><?= number_format($totalCartons, 0, ',', ' ') ?></div>
        <div class="unite-lbl">cartons vendus</div>
        <div class="unite-ca" style="color:#f97316;"><?= fcfa($caCartons) ?></div>
        <div style="font-size:.68rem;color:#fdba74;margin-top:6px;"><?= pct($totalCartons, $totalQte) ?>% du volume</div>
      </div>
    </div>
    <!-- Mini graphique barres côte à côte -->
    <div style="padding:0 20px 20px;">
      <canvas id="chartUnites" style="max-height:130px;"></canvas>
    </div>
  </div>
</div>

<div style="margin-bottom:20px;"></div>

<!-- ── TOP PRODUITS + TOP CLIENTS ─────────────────────────────────────────────── -->
<div class="two-col">
  <!-- Top 5 produits -->
  <div class="a-card" style="margin-bottom:0;">
    <div class="a-card-head">
      <div class="a-card-title">
        <i class="bi bi-trophy" style="color:#d4a017;"></i>
        Top 5 produits
      </div>
      <span style="font-size:.72rem;color:#9ca3af;"><?= $periodeLabels[$periode] ?></span>
    </div>
    <div style="padding:8px 0;">
      <?php if (empty($topProduits)): ?>
        <div style="padding:32px;text-align:center;color:#9ca3af;font-size:.83rem;">
          <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
          Aucune donnée
        </div>
      <?php else: ?>
        <?php foreach ($topProduits as $i => $p):
          $pct = pct((float)$p['ca_total'], (float)$maxCA);
          $colors = ['#d4a017','#9ca3af','#c2410c','#1a5c2a','#7c3aed'];
          $rankClasses = ['rank-1','rank-2','rank-3','rank-other','rank-other'];
        ?>
        <div style="padding:12px 20px;border-bottom:1px solid #f9fafb;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div class="rank-badge <?= $rankClasses[$i] ?>"><?= $i+1 ?></div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:.84rem;font-weight:700;color:#1c1917;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                <?= htmlspecialchars($p['nom_produit']) ?>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-top:3px;">
                <span style="font-size:.7rem;color:#9ca3af;"><?= number_format((float)$p['qte_totale'], 1) ?> unités · <?= (int)$p['nb_commandes'] ?> cmd</span>
                <span style="font-size:.8rem;font-weight:800;color:#f97316;"><?= fcfa((float)$p['ca_total']) ?></span>
              </div>
              <div class="prog-bar" style="margin-top:6px;">
                <div class="prog-fill" style="width:<?= $pct ?>%;background:<?= $colors[$i] ?? '#1a5c2a' ?>;"></div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Top 5 clients -->
  <div class="a-card" style="margin-bottom:0;">
    <div class="a-card-head">
      <div class="a-card-title">
        <i class="bi bi-people-fill" style="color:#2563eb;"></i>
        Top 5 clients
      </div>
      <span style="font-size:.72rem;color:#9ca3af;"><?= $periodeLabels[$periode] ?></span>
    </div>
    <div style="padding:8px 0;">
      <?php if (empty($topClients)): ?>
        <div style="padding:32px;text-align:center;color:#9ca3af;font-size:.83rem;">
          <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
          Aucune donnée
        </div>
      <?php else: ?>
        <?php foreach ($topClients as $i => $cl):
          $pct = pct((float)$cl['total_depense'], (float)$maxDep);
          $initiale = strtoupper(substr($cl['prenom'] ?? $cl['nom'] ?? 'C', 0, 1));
          $bgColors = ['#fef9c3','#f1f5f9','#fef2e8','#f0fdf4','#fdf4ff'];
          $txtColors = ['#854d0e','#475569','#9a3412','#15803d','#7e22ce'];
        ?>
        <div style="padding:12px 20px;border-bottom:1px solid #f9fafb;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:34px;height:34px;border-radius:10px;background:<?= $bgColors[$i] ?? '#f9fafb' ?>;color:<?= $txtColors[$i] ?? '#9ca3af' ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.82rem;flex-shrink:0;">
              <?= $initiale ?>
            </div>
            <div style="flex:1;min-width:0;">
              <div style="font-size:.84rem;font-weight:700;color:#1c1917;">
                <?= htmlspecialchars($cl['prenom'] . ' ' . $cl['nom']) ?>
              </div>
              <div style="display:flex;justify-content:space-between;align-items:center;margin-top:3px;">
                <span style="font-size:.7rem;color:#9ca3af;"><?= (int)$cl['nb_commandes'] ?> commande(s)</span>
                <span style="font-size:.8rem;font-weight:800;color:#1a5c2a;"><?= fcfa((float)$cl['total_depense']) ?></span>
              </div>
              <div class="prog-bar" style="margin-top:6px;">
                <div class="prog-fill" style="width:<?= $pct ?>%;background:#1a5c2a;"></div>
              </div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</div>

<div style="margin-bottom:20px;"></div>

<!-- ── TABLEAU DÉTAILLÉ PRODUITS ────────────────────────────────────────────── -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title">
      <i class="bi bi-table" style="color:#1a5c2a;"></i>
      Détail des ventes par produit
    </div>
    <span style="font-size:.72rem;color:#9ca3af;"><?= $periodeLabels[$periode] ?></span>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Produit</th>
          <th>Quantité vendue</th>
          <th>Nb commandes</th>
          <th>CA généré</th>
          <th>Part du CA</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $caTotal = array_sum(array_column($topProduits, 'ca_total')) ?: 1;
          if (empty($topProduits)):
        ?>
        <tr><td colspan="6" style="text-align:center;padding:28px;color:#9ca3af;">Aucune donnée pour cette période</td></tr>
        <?php else: foreach ($topProduits as $i => $p): ?>
        <tr>
          <td>
            <div class="rank-badge <?= ['rank-1','rank-2','rank-3','rank-other','rank-other'][$i] ?>" style="margin:0;">
              <?= $i+1 ?>
            </div>
          </td>
          <td style="font-weight:600;"><?= htmlspecialchars($p['nom_produit']) ?></td>
          <td><?= number_format((float)$p['qte_totale'], 1) ?> unités</td>
          <td><?= (int)$p['nb_commandes'] ?></td>
          <td style="font-weight:700;color:#f97316;"><?= fcfa((float)$p['ca_total']) ?></td>
          <td>
            <div style="display:flex;align-items:center;gap:8px;">
              <div class="prog-bar" style="flex:1;margin:0;">
                <div class="prog-fill" style="width:<?= pct((float)$p['ca_total'], $caTotal) ?>%;background:#1a5c2a;"></div>
              </div>
              <span style="font-size:.72rem;color:#6b7280;white-space:nowrap;"><?= pct((float)$p['ca_total'], $caTotal) ?>%</span>
            </div>
          </td>
        </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ── SCRIPTS CHART.JS ──────────────────────────────────────────────────────── -->
<script>
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.color = '#9ca3af';

// ── 1. CA par mois (bar + line combo) ────────────────────────────────────────
(function() {
  const ctx = document.getElementById('chartCA');
  if (!ctx) return;

  const labels = <?= $labelsJson ?>;
  const caData  = <?= $caJson ?>;
  const nbData  = <?= $nbJson ?>;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels,
      datasets: [
        {
          label: 'CA (FCFA)',
          data: caData,
          backgroundColor: 'rgba(26,92,42,.15)',
          borderColor: '#1a5c2a',
          borderWidth: 2,
          borderRadius: 6,
          borderSkipped: false,
          yAxisID: 'yCA',
          order: 2,
        },
        {
          label: 'Nb commandes',
          data: nbData,
          type: 'line',
          borderColor: '#f97316',
          backgroundColor: 'rgba(249,115,22,.12)',
          borderWidth: 2.5,
          pointRadius: 5,
          pointBackgroundColor: '#f97316',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          tension: 0.4,
          fill: true,
          yAxisID: 'yNb',
          order: 1,
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: {
          position: 'top',
          labels: { usePointStyle: true, padding: 16, font: { size: 12, weight: '600' } }
        },
        tooltip: {
          callbacks: {
            label: (ctx) => {
              if (ctx.datasetIndex === 0)
                return ' CA : ' + parseInt(ctx.raw).toLocaleString('fr-FR') + ' FCFA';
              return ' Commandes : ' + ctx.raw;
            }
          }
        }
      },
      scales: {
        yCA: {
          type: 'linear', position: 'left',
          grid: { color: 'rgba(0,0,0,.05)' },
          ticks: {
            callback: v => v >= 1000000
              ? (v/1000000).toFixed(1) + 'M'
              : v >= 1000 ? (v/1000).toFixed(0) + 'k' : v
          }
        },
        yNb: {
          type: 'linear', position: 'right',
          grid: { display: false },
          ticks: { stepSize: 1 }
        },
        x: { grid: { display: false } }
      }
    }
  });
})();

// ── 2. Doughnut statuts ───────────────────────────────────────────────────────
(function() {
  const ctx = document.getElementById('chartStatuts');
  if (!ctx) return;

  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: <?= $statutLabelsJson ?>,
      datasets: [{
        data: <?= $statutNbJson ?>,
        backgroundColor: <?= $statutColorsJson ?>,
        borderWidth: 3,
        borderColor: '#fff',
        hoverOffset: 8,
      }]
    },
    options: {
      responsive: true,
      cutout: '68%',
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => ' ' + ctx.label + ' : ' + ctx.raw + ' cmd'
          }
        }
      }
    }
  });
})();

// ── 3. Kg vs Cartons (bar horizontal) ────────────────────────────────────────
(function() {
  const ctx = document.getElementById('chartUnites');
  if (!ctx) return;

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Kg', 'Cartons'],
      datasets: [{
        data: [<?= (float)($repartitionUnites['total_kg'] ?? 0) ?>, <?= (float)($repartitionUnites['total_cartons'] ?? 0) ?>],
        backgroundColor: ['rgba(26,92,42,.8)', 'rgba(249,115,22,.8)'],
        borderColor: ['#1a5c2a', '#f97316'],
        borderWidth: 2,
        borderRadius: 6,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: (ctx) => ' ' + ctx.raw.toLocaleString('fr-FR') + (ctx.dataIndex === 0 ? ' kg' : ' cartons')
          }
        }
      },
      scales: {
        x: { grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 11 } } },
        y: { grid: { display: false }, ticks: { font: { size: 12, weight: '700' } } }
      }
    }
  });
})();
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
