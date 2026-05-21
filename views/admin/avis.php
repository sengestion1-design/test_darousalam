<?php
$adminPage = 'avis';
$pageTitle = 'Avis clients';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error']   ?? '';

$statutsMap = [
    'en_attente' => ['lib' => 'En attente', 'color' => '#1d4ed8', 'bg' => '#dbeafe'],
    'approuve'   => ['lib' => 'Approuvé',   'color' => '#166534', 'bg' => '#dcfce7'],
    'rejete'     => ['lib' => 'Rejeté',     'color' => '#991b1b', 'bg' => '#fee2e2'],
];

$filtre      = $_GET['statut'] ?? '';
$total       = count($avis);
$nbAttente   = count(array_filter($avis, fn($a) => $a['statut'] === 'en_attente'));
$nbApprouves = count(array_filter($avis, fn($a) => $a['statut'] === 'approuve'));
$nbRejetes   = count(array_filter($avis, fn($a) => $a['statut'] === 'rejete'));
$moyenneNote = $nbApprouves > 0
    ? round(array_sum(array_column(array_filter($avis, fn($a) => $a['statut'] === 'approuve'), 'note')) / $nbApprouves, 1)
    : 0;
?>

<style>
.kpi-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;}
.kpi{background:#fff;border-radius:18px;padding:22px 24px;border:1px solid #e5e7eb;position:relative;overflow:hidden;transition:transform .15s,box-shadow .15s;}
.kpi:hover{transform:translateY(-2px);box-shadow:0 8px 30px rgba(0,0,0,.07);}
.kpi-accent{position:absolute;top:0;left:0;right:0;height:3px;border-radius:18px 18px 0 0;}
.kpi-icon{width:46px;height:46px;border-radius:13px;display:flex;align-items:center;justify-content:center;font-size:1.15rem;margin-bottom:14px;}
.kpi-val{font-size:1.7rem;font-weight:900;color:#111827;line-height:1;margin-bottom:5px;}
.kpi-lbl{font-size:.72rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.06em;}

.a-card{background:#fff;border-radius:18px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,.04);}
.a-card-head{padding:18px 24px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #f3f4f6;}
.a-card-title{font-size:.95rem;font-weight:800;color:#111827;display:flex;align-items:center;gap:8px;}

.a-table{width:100%;border-collapse:collapse;}
.a-table th{font-size:.63rem;text-transform:uppercase;letter-spacing:.1em;color:#9ca3af;font-weight:700;padding:11px 16px;background:#fafafa;border-bottom:1px solid #f3f4f6;white-space:nowrap;}
.a-table td{padding:13px 16px;border-bottom:1px solid #f9fafb;vertical-align:middle;}
.a-table tr:last-child td{border-bottom:none;}
.a-table tbody tr{transition:background .12s;}
.a-table tbody tr:hover td{background:#fafaf9;}

.filter-pill{display:inline-flex;align-items:center;gap:6px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:10px;padding:6px 14px;font-size:.8rem;font-family:inherit;color:#374151;cursor:pointer;text-decoration:none;transition:all .15s;font-weight:600;}
.filter-pill:hover{border-color:var(--vert);color:var(--vert);}
.filter-pill.active{background:#f0fdf4;border-color:var(--vert);color:var(--vert);}

.statut-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:8px;font-size:.72rem;font-weight:700;}

.avis-stars{color:#f59e0b;font-size:.88rem;}

.btn-action{display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:9px;font-size:.74rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:all .15s;text-decoration:none;}
.btn-ok{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
.btn-ok:hover{background:#166534;color:#fff;}
.btn-ko{background:#fff7ed;color:#c2410c;border:1px solid #fed7aa;}
.btn-ko:hover{background:#c2410c;color:#fff;}
.btn-del{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;}
.btn-del:hover{background:#991b1b;color:#fff;}

.comment-cell{max-width:320px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.83rem;color:#374151;font-style:italic;}
.empty-state{text-align:center;padding:60px 40px;color:#9ca3af;}
.empty-state i{font-size:2.5rem;display:block;margin-bottom:12px;color:#e5e7eb;}
.empty-state p{font-size:.88rem;}
@media(max-width:1100px){.kpi-grid{grid-template-columns:1fr 1fr;}}
</style>

<?php if ($successMsg): ?>
<div style="background:#dcfce7;color:#166534;border:1px solid #bbf7d0;border-radius:12px;padding:14px 18px;margin-bottom:18px;font-size:.88rem;font-weight:600;">
    <?= htmlspecialchars($successMsg, ENT_QUOTES, 'UTF-8') ?>
</div>
<?php endif; ?>
<?php if ($errorMsg): ?>
<div style="background:#fee2e2;color:#991b1b;border:1px solid #fecaca;border-radius:12px;padding:14px 18px;margin-bottom:18px;font-size:.88rem;font-weight:600;">
    <?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?>
</div>
<?php endif; ?>

<!-- KPI -->
<div class="kpi-grid">
    <div class="kpi">
        <div class="kpi-accent" style="background:linear-gradient(90deg,#1a5c2a,#2d8a42);"></div>
        <div class="kpi-icon" style="background:#f0fdf4;"><i class="bi bi-chat-square-quote-fill" style="color:#1a5c2a;"></i></div>
        <div class="kpi-val"><?= $total ?></div>
        <div class="kpi-lbl">Total avis</div>
    </div>
    <div class="kpi">
        <div class="kpi-accent" style="background:linear-gradient(90deg,#1d4ed8,#3b82f6);"></div>
        <div class="kpi-icon" style="background:#eff6ff;"><i class="bi bi-hourglass-split" style="color:#1d4ed8;"></i></div>
        <div class="kpi-val"><?= $nbAttente ?></div>
        <div class="kpi-lbl">En attente</div>
    </div>
    <div class="kpi">
        <div class="kpi-accent" style="background:linear-gradient(90deg,#166534,#22c55e);"></div>
        <div class="kpi-icon" style="background:#dcfce7;"><i class="bi bi-check-circle-fill" style="color:#166534;"></i></div>
        <div class="kpi-val"><?= $nbApprouves ?></div>
        <div class="kpi-lbl">Approuvés</div>
    </div>
    <div class="kpi">
        <div class="kpi-accent" style="background:linear-gradient(90deg,#f59e0b,#fbbf24);"></div>
        <div class="kpi-icon" style="background:#fffbeb;"><i class="bi bi-star-fill" style="color:#f59e0b;"></i></div>
        <div class="kpi-val"><?= $moyenneNote > 0 ? number_format($moyenneNote, 1) : '—' ?></div>
        <div class="kpi-lbl">Note moyenne</div>
    </div>
</div>

<!-- Table -->
<div class="a-card">
    <div class="a-card-head">
        <div class="a-card-title">
            <i class="bi bi-star-half" style="color:var(--vert);font-size:1rem;"></i>
            Avis clients
            <span style="background:#f3f4f6;color:#6b7280;font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:20px;"><?= $total ?></span>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <a href="?page=adm_avis" class="filter-pill <?= $filtre === '' ? 'active' : '' ?>">Tous (<?= $total ?>)</a>
            <a href="?page=adm_avis&statut=en_attente" class="filter-pill <?= $filtre === 'en_attente' ? 'active' : '' ?>">
                En attente (<?= $nbAttente ?>)
            </a>
            <a href="?page=adm_avis&statut=approuve" class="filter-pill <?= $filtre === 'approuve' ? 'active' : '' ?>">
                Approuvés (<?= $nbApprouves ?>)
            </a>
            <a href="?page=adm_avis&statut=rejete" class="filter-pill <?= $filtre === 'rejete' ? 'active' : '' ?>">
                Rejetés (<?= $nbRejetes ?>)
            </a>
        </div>
    </div>

    <?php if (empty($avis)): ?>
        <div class="empty-state">
            <i class="bi bi-chat-square-quote"></i>
            <p>Aucun avis trouvé.</p>
        </div>
    <?php else: ?>
    <div style="overflow-x:auto;">
        <table class="a-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Note</th>
                    <th>Client</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($avis as $av): ?>
                <tr>
                    <td style="font-size:.75rem;color:#9ca3af;font-weight:700;">#<?= $av['id'] ?></td>
                    <td>
                        <div class="avis-stars">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="bi bi-star<?= $s <= (int)$av['note'] ? '-fill' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <div style="font-size:.7rem;color:#6b7280;margin-top:2px;"><?= (int)$av['note'] ?>/5</div>
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:.85rem;color:#111827;"><?= htmlspecialchars($av['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                        <?php if ($av['client_id']): ?>
                            <div style="font-size:.7rem;color:#9ca3af;">Client #<?= $av['client_id'] ?></div>
                        <?php else: ?>
                            <div style="font-size:.7rem;color:#d1d5db;">Visiteur</div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="comment-cell" title="<?= htmlspecialchars($av['commentaire'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($av['commentaire'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    </td>
                    <td>
                        <?php $s = $statutsMap[$av['statut']] ?? $statutsMap['en_attente']; ?>
                        <span class="statut-badge" style="color:<?= $s['color'] ?>;background:<?= $s['bg'] ?>;">
                            <?= $s['lib'] ?>
                        </span>
                    </td>
                    <td style="font-size:.78rem;color:#6b7280;white-space:nowrap;">
                        <?= date('d/m/Y', strtotime($av['created_at'])) ?><br>
                        <span style="font-size:.68rem;color:#d1d5db;"><?= date('H:i', strtotime($av['created_at'])) ?></span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <?php if ($av['statut'] !== 'approuve'): ?>
                            <form method="post" action="?page=adm_avis_ok" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int)$av['id'] ?>">
                                <button type="submit" class="btn-action btn-ok" title="Approuver">
                                    <i class="bi bi-check-lg"></i> Approuver
                                </button>
                            </form>
                            <?php endif; ?>
                            <?php if ($av['statut'] !== 'rejete'): ?>
                            <form method="post" action="?page=adm_avis_ko" style="display:inline;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int)$av['id'] ?>">
                                <button type="submit" class="btn-action btn-ko" title="Rejeter">
                                    <i class="bi bi-x-lg"></i> Rejeter
                                </button>
                            </form>
                            <?php endif; ?>
                            <form method="post" action="?page=adm_avis_del" style="display:inline;"
                                  onsubmit="return confirm('Supprimer cet avis définitivement ?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_admin'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="id" value="<?= (int)$av['id'] ?>">
                                <button type="submit" class="btn-action btn-del" title="Supprimer">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
