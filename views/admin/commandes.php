<?php
$adminPage = 'commandes';
$pageTitle = 'Commandes';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';

$statutsMap = [
    'en_attente'     => ['lib'=>'En attente',    'color'=>'#1d4ed8','bg'=>'#dbeafe','dot'=>'#3b82f6'],
    'confirmee'      => ['lib'=>'Confirmée',     'color'=>'#0e7490','bg'=>'#cffafe','dot'=>'#06b6d4'],
    'en_preparation' => ['lib'=>'Préparation',   'color'=>'#92400e','bg'=>'#fef3c7','dot'=>'#f59e0b'],
    'en_livraison'   => ['lib'=>'En livraison',  'color'=>'#6d28d9','bg'=>'#ede9fe','dot'=>'#8b5cf6'],
    'livree'         => ['lib'=>'Livrée',        'color'=>'#166534','bg'=>'#dcfce7','dot'=>'#22c55e'],
    'annulee'        => ['lib'=>'Annulée',       'color'=>'#991b1b','bg'=>'#fee2e2','dot'=>'#ef4444'],
];

$paiementsMap = [
    'cash'         => ['lib'=>'Livraison',    'icon'=>'bi-cash-coin',    'color'=>'#374151','bg'=>'#f3f4f6'],
    'wave'         => ['lib'=>'Wave',         'icon'=>'bi-phone',        'color'=>'#1d4ed8','bg'=>'#dbeafe'],
    'orange_money' => ['lib'=>'Orange Money', 'icon'=>'bi-phone-fill',   'color'=>'#c2410c','bg'=>'#ffedd5'],
];

$totalCommandes = count($commandes);
$totalCA        = array_sum(array_column($commandes, 'total'));
$enAttente      = count(array_filter($commandes, fn($c)=>$c['statut']==='en_attente'));
$livrees        = count(array_filter($commandes, fn($c)=>$c['statut']==='livree'));
$enCours        = count(array_filter($commandes, fn($c)=>in_array($c['statut'],['confirmee','en_preparation','en_livraison'])));
?>

<style>
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;}
.kpi{background:#fff;border-radius:18px;padding:22px 24px;border:1px solid #e5e7eb;position:relative;overflow:hidden;transition:transform .15s,box-shadow .15s;}
.kpi:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.07);}
.kpi-accent{position:absolute;top:0;left:0;right:0;height:3px;border-radius:18px 18px 0 0;}
.kpi-icon{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;margin-bottom:14px;}
.kpi-val{font-size:1.7rem;font-weight:900;color:#111827;line-height:1;margin-bottom:5px;}
.kpi-lbl{font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}

.cmd-card{background:#fff;border-radius:18px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.04);}
.cmd-card-head{padding:18px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f3f4f6;}
.cmd-card-title{font-size:.95rem;font-weight:800;color:#111827;display:flex;align-items:center;gap:8px;}

.filter-pill{display:flex;align-items:center;gap:6px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;padding:7px 13px;font-size:.8rem;font-family:inherit;color:#374151;cursor:pointer;transition:all .15s;}
.filter-pill:focus{outline:none;border-color:var(--vert);box-shadow:0 0 0 3px rgba(26,92,42,.07);}
.search-pill{display:flex;align-items:center;gap:8px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;padding:7px 13px;transition:all .15s;}
.search-pill:focus-within{border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.07);}
.search-pill input{border:none;background:transparent;font-size:.8rem;font-family:inherit;color:#374151;outline:none;width:180px;}
.search-pill i{color:#9ca3af;font-size:.85rem;}

.cmd-table{width:100%;border-collapse:collapse;}
.cmd-table th{font-size:.63rem;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;font-weight:700;padding:11px 16px;background:#fafafa;border-bottom:1px solid #f3f4f6;white-space:nowrap;}
.cmd-table td{padding:14px 16px;border-bottom:1px solid #f9fafb;vertical-align:middle;}
.cmd-table tr:last-child td{border-bottom:none;}
.cmd-table tbody tr{transition:background .12s;}
.cmd-table tbody tr:hover td{background:#fafaf9;}

.ref-badge{font-weight:800;color:var(--vert);font-size:.82rem;font-family:'DM Mono',monospace,sans-serif;letter-spacing:.02em;}
.ref-id{font-size:.67rem;color:#cbd5e1;margin-top:2px;}

.client-name{font-weight:700;font-size:.85rem;color:#111827;}
.client-email{font-size:.72rem;color:#9ca3af;margin-top:2px;}
.client-ville{font-size:.68rem;color:#cbd5e1;margin-top:2px;display:flex;align-items:center;gap:3px;}

.articles-chip{display:inline-flex;align-items:center;justify-content:center;min-width:26px;height:26px;padding:0 6px;background:#f3f4f6;color:#374151;border-radius:8px;font-size:.75rem;font-weight:800;}
.unite-badge{display:inline-flex;align-items:center;gap:4px;padding:3px 8px;border-radius:7px;font-size:.7rem;font-weight:700;white-space:nowrap;}
.unite-badge-kg{background:#dcfce7;color:#166534;border:1px solid #bbf7d0;}
.unite-badge-carton{background:#dbeafe;color:#1d4ed8;border:1px solid #bfdbfe;}

.amount{font-weight:900;color:#f97316;white-space:nowrap;font-size:.9rem;}
.amount-sub{font-size:.65rem;color:#d1d5db;margin-top:2px;}

.pay-chip{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.7rem;font-weight:700;}

.statut-dot{width:7px;height:7px;border-radius:50%;display:inline-block;margin-right:5px;flex-shrink:0;}
.statut-select{padding:5px 10px 5px 8px;border-radius:8px;font-size:.72rem;font-weight:700;border:1.5px solid transparent;cursor:pointer;font-family:inherit;transition:all .15s;display:inline-flex;align-items:center;appearance:none;-webkit-appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6'%3E%3Cpath d='M0 0l5 6 5-6z' fill='%239ca3af'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 7px center;padding-right:22px;}
.statut-select:focus{outline:none;}

.btn-voir{display:inline-flex;align-items:center;gap:5px;padding:6px 14px;border-radius:9px;font-size:.75rem;font-weight:700;background:#f0fdf4;color:#166534;text-decoration:none;transition:all .15s;border:1px solid #bbf7d0;}
.btn-voir:hover{background:#166534;color:#fff;border-color:#166534;}

.date-main{font-size:.78rem;color:#6b7280;white-space:nowrap;}
.date-sub{font-size:.67rem;color:#d1d5db;margin-top:2px;}

.empty-state{text-align:center;padding:60px 40px;color:#9ca3af;}
.empty-state i{font-size:2.5rem;display:block;margin-bottom:12px;color:#e5e7eb;}
.empty-state p{font-size:.88rem;}

@media(max-width:1100px){.kpi-grid{grid-template-columns:1fr 1fr;}}
</style>

<!-- KPI -->
<div class="kpi-grid">
  <div class="kpi">
    <div class="kpi-accent" style="background:linear-gradient(90deg,#1a5c2a,#2d8a42);"></div>
    <div class="kpi-icon" style="background:#f0fdf4;"><i class="bi bi-bag-check-fill" style="color:#1a5c2a;"></i></div>
    <div class="kpi-val"><?= $totalCommandes ?></div>
    <div class="kpi-lbl">Total commandes</div>
  </div>
  <div class="kpi">
    <div class="kpi-accent" style="background:linear-gradient(90deg,#ea580c,#f97316);"></div>
    <div class="kpi-icon" style="background:#fff7ed;"><i class="bi bi-cash-stack" style="color:#ea580c;"></i></div>
    <div class="kpi-val" style="font-size:1.25rem;"><?= number_format($totalCA,0,',',' ') ?></div>
    <div class="kpi-lbl">FCFA chiffre d'affaires</div>
  </div>
  <div class="kpi">
    <div class="kpi-accent" style="background:linear-gradient(90deg,#1d4ed8,#3b82f6);"></div>
    <div class="kpi-icon" style="background:#eff6ff;"><i class="bi bi-hourglass-split" style="color:#1d4ed8;"></i></div>
    <div class="kpi-val"><?= $enAttente ?></div>
    <div class="kpi-lbl">En attente</div>
  </div>
  <div class="kpi">
    <div class="kpi-accent" style="background:linear-gradient(90deg,#166534,#22c55e);"></div>
    <div class="kpi-icon" style="background:#dcfce7;"><i class="bi bi-check-circle-fill" style="color:#166534;"></i></div>
    <div class="kpi-val"><?= $livrees ?></div>
    <div class="kpi-lbl">Livrées</div>
  </div>
</div>

<!-- Table -->
<div class="cmd-card">
  <div class="cmd-card-head">
    <div class="cmd-card-title">
      <i class="bi bi-receipt" style="color:var(--vert);font-size:1rem;"></i>
      Liste des commandes
      <span style="background:#f3f4f6;color:#6b7280;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;"><?= $totalCommandes ?></span>
    </div>
    <div style="display:flex;gap:10px;align-items:center;">
      <div class="search-pill">
        <i class="bi bi-search"></i>
        <input type="search" id="searchCmd" placeholder="Référence, client…" oninput="filterTable(this.value)">
      </div>
      <select class="filter-pill" id="filterStatut" onchange="filterStatut(this.value)">
        <option value="">Tous statuts</option>
        <?php foreach($statutsMap as $k=>$s): ?>
        <option value="<?= $k ?>"><?= $s['lib'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <div style="overflow-x:auto;">
    <table class="cmd-table" id="cmdTable">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Client</th>
          <th style="text-align:center;">Articles</th>
          <th>Montant</th>
          <th>Paiement</th>
          <th>Livreur</th>
          <th>Statut</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(empty($commandes)): ?>
        <tr><td colspan="8">
          <div class="empty-state">
            <i class="bi bi-bag-x"></i>
            <p>Aucune commande pour le moment</p>
          </div>
        </td></tr>
        <?php else: ?>
        <?php foreach($commandes as $cmd):
          $statut  = $statutsMap[$cmd['statut']] ?? ['lib'=>ucfirst($cmd['statut']),'color'=>'#6b7280','bg'=>'#f3f4f6','dot'=>'#9ca3af'];
          $paie    = $paiementsMap[$cmd['mode_paiement']] ?? ['lib'=>$cmd['mode_paiement'],'icon'=>'bi-credit-card','color'=>'#374151','bg'=>'#f3f4f6'];
          $adresse = json_decode($cmd['adresse_livraison'] ?? '{}', true);
          $clientNom = trim(($cmd['client_prenom']??'') . ' ' . ($cmd['client_nom']??''));
          if(!$clientNom) $clientNom = 'Client #' . $cmd['client_id'];
          $dateObj = new DateTime($cmd['created_at']);
        ?>
        <tr data-statut="<?= $cmd['statut'] ?>" data-search="<?= strtolower(htmlspecialchars($cmd['reference'].$clientNom.($cmd['client_email']??''))) ?>">
          <td>
            <div class="ref-badge"><?= htmlspecialchars($cmd['reference']) ?></div>
            <div class="ref-id">#<?= $cmd['id'] ?></div>
          </td>
          <td>
            <div class="client-name"><?= htmlspecialchars($clientNom) ?></div>
            <div class="client-email"><?= htmlspecialchars($cmd['client_email']??'') ?></div>
            <?php if(!empty($adresse['ville'])): ?>
            <div class="client-ville"><i class="bi bi-geo-alt-fill" style="font-size:.6rem;color:#cbd5e1;"></i><?= htmlspecialchars($adresse['ville']) ?></div>
            <?php endif; ?>
          </td>
          <td style="text-align:center;">
            <div style="display:flex;flex-direction:column;align-items:center;gap:5px;">
              <?php if ((float)($cmd['total_kg'] ?? 0) > 0): ?>
              <span class="unite-badge unite-badge-kg">
                <i class="bi bi-rulers"></i> <?= number_format((float)$cmd['total_kg'], 0) ?> kg
              </span>
              <?php endif; ?>
              <?php if ((float)($cmd['total_cartons'] ?? 0) > 0): ?>
              <span class="unite-badge unite-badge-carton">
                <i class="bi bi-box-seam"></i> <?= (int)$cmd['total_cartons'] ?> carton<?= $cmd['total_cartons'] > 1 ? 's' : '' ?>
              </span>
              <?php endif; ?>
              <?php if ((float)($cmd['total_kg'] ?? 0) == 0 && (float)($cmd['total_cartons'] ?? 0) == 0): ?>
              <span class="articles-chip"><?= (int)($cmd['nb_articles'] ?? 0) ?></span>
              <?php endif; ?>
            </div>
          </td>
          <td>
            <div class="amount"><?= number_format($cmd['sous_total'],0,',',' ') ?> <span style="font-size:.65rem;font-weight:600;color:#fdba74;">FCFA</span></div>
            <?php if (!empty($cmd['remise']) && (float)$cmd['remise'] > 0): ?>
            <div style="font-size:.7rem;color:#16a34a;font-weight:600;display:flex;align-items:center;gap:4px;margin-top:2px;">
              <i class="bi bi-scissors"></i> -<?= number_format((float)$cmd['remise'],0,',',' ') ?> FCFA
              <?php if (!empty($cmd['code_promo'])): ?>
              <span style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:4px;padding:0 5px;font-family:monospace;font-size:.65rem;color:#15803d;letter-spacing:1px;"><?= htmlspecialchars($cmd['code_promo']) ?></span>
              <?php endif; ?>
            </div>
            <?php endif; ?>
            <?php if(!empty($cmd['frais_livraison']) && $cmd['frais_livraison']>0): ?>
            <div style="font-size:.72rem;color:#60a5fa;font-weight:600;margin-top:2px;">+ <?= number_format($cmd['frais_livraison'],0,',',' ') ?> livraison</div>
            <?php endif;?>
            <div style="font-size:.72rem;font-weight:800;color:#f97316;margin-top:3px;">= <?= number_format($cmd['total'],0,',',' ') ?> FCFA</div>
          </td>
          <td>
            <span class="pay-chip" style="background:<?= $paie['bg'] ?>;color:<?= $paie['color'] ?>;">
              <i class="bi <?= $paie['icon'] ?>"></i> <?= $paie['lib'] ?>
            </span>
          </td>
          <td>
            <?php
              $lvStatutMap = [
                'en_attente' => ['lib'=>'En attente','bg'=>'#f3f4f6','color'=>'#6b7280','icon'=>'bi-clock'],
                'assignee'   => ['lib'=>'Assignée',  'bg'=>'#fef9c3','color'=>'#92400e','icon'=>'bi-person-check-fill'],
                'en_cours'   => ['lib'=>'En cours',  'bg'=>'#dbeafe','color'=>'#1d4ed8','icon'=>'bi-bicycle'],
                'livree'     => ['lib'=>'Livrée',    'bg'=>'#dcfce7','color'=>'#15803d','icon'=>'bi-check-circle-fill'],
                'echec'      => ['lib'=>'Échec',     'bg'=>'#fee2e2','color'=>'#b91c1c','icon'=>'bi-x-circle-fill'],
              ];
            ?>
            <?php if (!empty($cmd['livreur_nom'])): ?>
              <?php $lvS = $lvStatutMap[$cmd['livraison_statut'] ?? ''] ?? $lvStatutMap['en_attente']; ?>
              <div style="display:flex;flex-direction:column;gap:4px;">
                <span style="font-size:.8rem;font-weight:700;color:#1c1917;">
                  <i class="bi bi-person-fill" style="color:#6b7280;"></i>
                  <?= htmlspecialchars($cmd['livreur_nom']) ?>
                </span>
                <?php if (!empty($cmd['livreur_tel'])): ?>
                <span style="font-size:.68rem;color:#9ca3af;"><?= htmlspecialchars($cmd['livreur_tel']) ?></span>
                <?php endif; ?>
                <!-- Statut livraison inline -->
                <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_statut" style="display:flex;gap:4px;align-items:center;margin-top:2px;">
                  <input type="hidden" name="livraison_id" value="<?= $cmd['livraison_id'] ?>">
                  <select name="statut" onchange="this.form.submit()"
                    style="font-size:.68rem;border:1px solid <?= $lvS['bg'] ?>;border-radius:5px;padding:2px 5px;background:<?= $lvS['bg'] ?>;color:<?= $lvS['color'] ?>;font-weight:600;cursor:pointer;">
                    <?php foreach ($lvStatutMap as $k => $s): ?>
                    <option value="<?= $k ?>" <?= ($cmd['livraison_statut']??'')===$k?'selected':'' ?>><?= $s['lib'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </form>
              </div>
            <?php else: ?>
              <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_assigner" style="display:flex;flex-direction:column;gap:4px;">
                <input type="hidden" name="commande_id" value="<?= $cmd['id'] ?>">
                <input type="hidden" name="retour" value="admin_commandes">
                <select name="livreur_id" style="font-size:.75rem;border:1px solid #e5e7eb;border-radius:6px;padding:3px 6px;color:#374151;width:100%;max-width:130px;">
                  <option value="">— Assigner —</option>
                  <?php foreach ($livreurs as $lr): ?>
                  <option value="<?= $lr['id'] ?>"><?= htmlspecialchars($lr['nom']) ?></option>
                  <?php endforeach; ?>
                </select>
                <button type="submit" style="font-size:.7rem;background:var(--vert);color:#fff;border:none;border-radius:5px;padding:3px 8px;cursor:pointer;width:fit-content;">
                  <i class="bi bi-send"></i> Assigner
                </button>
              </form>
            <?php endif; ?>
          </td>
          <td>
            <form method="POST" action="<?= BASE_URL ?>?page=admin_commande_statut" style="display:inline;">
              <input type="hidden" name="id" value="<?= $cmd['id'] ?>">
              <select name="statut" onchange="this.form.submit()" class="statut-select"
                style="background:<?= $statut['bg'] ?>;color:<?= $statut['color'] ?>;border-color:<?= $statut['bg'] ?>;">
                <?php foreach($statutsMap as $k=>$s): ?>
                <option value="<?= $k ?>" <?= $cmd['statut']===$k?'selected':'' ?>><?= $s['lib'] ?></option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td>
            <div class="date-main"><?= $dateObj->format('d/m/Y') ?></div>
            <div class="date-sub"><?= $dateObj->format('H:i') ?></div>
          </td>
          <td>
            <a href="<?= BASE_URL ?>?page=admin_commande_detail&id=<?= $cmd['id'] ?>" class="btn-voir">
              <i class="bi bi-eye"></i> Voir
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function filterTable(val) {
  val = val.toLowerCase();
  document.querySelectorAll('#cmdTable tbody tr[data-search]').forEach(row => {
    const matchSearch = !val || row.dataset.search.includes(val);
    const matchStatut = !currentStatut || row.dataset.statut === currentStatut;
    row.style.display = (matchSearch && matchStatut) ? '' : 'none';
  });
}
let currentStatut = '';
function filterStatut(val) {
  currentStatut = val;
  filterTable(document.getElementById('searchCmd').value);
}
</script>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:28px;right:28px;background:linear-gradient(135deg,#1a5c2a,#2d8a42);color:#fff;padding:14px 20px;border-radius:14px;font-size:.84rem;font-weight:600;box-shadow:0 8px 30px rgba(26,92,42,.3);z-index:9999;display:flex;align-items:center;gap:10px;animation:slideIn .25s ease;">
  <i class="bi bi-check-circle-fill" style="font-size:1rem;"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<style>@keyframes slideIn{from{opacity:0;transform:translateY(12px);}to{opacity:1;transform:translateY(0);}}</style>
<script>setTimeout(()=>{const m=document.getElementById('flashMsg');if(m){m.style.transition='opacity .4s';m.style.opacity='0';setTimeout(()=>m.remove(),400);}},3500);</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:28px;right:28px;background:linear-gradient(135deg,#dc2626,#ef4444);color:#fff;padding:14px 20px;border-radius:14px;font-size:.84rem;font-weight:600;box-shadow:0 8px 30px rgba(220,38,38,.3);z-index:9999;display:flex;align-items:center;gap:10px;animation:slideIn .25s ease;">
  <i class="bi bi-exclamation-circle-fill" style="font-size:1rem;"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>{const m=document.getElementById('flashErr');if(m){m.style.transition='opacity .4s';m.style.opacity='0';setTimeout(()=>m.remove(),400);}},5000);</script>
<?php endif; ?>

<?php require_once __DIR__ . '/layout_end.php'; ?>
