<?php require_once __DIR__ . '/layout.php'; ?>

<?php
// Flash messages
$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error'] ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);

// Helpers statut livraison
function statutLivraisonBadge(string $statut): string {
    return match($statut) {
        'en_attente' => '<span class="status-badge" style="background:#fef3c7;color:#92400e;"><i class="bi bi-clock"></i> En attente</span>',
        'assignee'   => '<span class="status-badge" style="background:#eff6ff;color:#1d4ed8;"><i class="bi bi-person-check"></i> Assignée</span>',
        'en_cours'   => '<span class="status-badge" style="background:#f0fdf4;color:#166534;"><i class="bi bi-truck"></i> En cours</span>',
        'livree'     => '<span class="status-badge" style="background:#dcfce7;color:#15803d;"><i class="bi bi-check-circle-fill"></i> Livrée</span>',
        'echec'      => '<span class="status-badge" style="background:#fef2f2;color:#b91c1c;"><i class="bi bi-x-circle-fill"></i> Échec</span>',
        default      => '<span class="status-badge" style="background:#f3f4f6;color:#6b7280;">' . htmlspecialchars($statut) . '</span>',
    };
}
function adresseLivraison(mixed $json): string {
    if (!$json) return '<span style="color:#9ca3af;">—</span>';
    $a = is_array($json) ? $json : (json_decode($json, true) ?: []);
    $parts = array_filter([
        $a['adresse'] ?? $a['rue'] ?? '',
        $a['ville'] ?? '',
        $a['code_postal'] ?? $a['cp'] ?? '',
    ]);
    return $parts ? htmlspecialchars(implode(', ', $parts)) : '<span style="color:#9ca3af;">—</span>';
}
?>

<style>
.liv-select{border:1.5px solid #e5e7eb;border-radius:8px;padding:5px 8px;font-size:.78rem;font-family:var(--font);background:#fafafa;cursor:pointer;max-width:160px;}
.liv-select:focus{outline:none;border-color:var(--vert);}
.livreur-avatar{width:34px;height:34px;border-radius:50%;background:var(--vert);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.8rem;font-weight:700;flex-shrink:0;}
.kpi-row{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
</style>

<?php if ($flashSuccess): ?>
<div class="alert alert-success d-flex align-items-center gap-2 mb-3" style="border-radius:12px;font-size:.85rem;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($flashSuccess) ?>
</div>
<?php endif; ?>
<?php if ($flashError): ?>
<div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius:12px;font-size:.85rem;">
  <i class="bi bi-exclamation-triangle-fill"></i> <?= htmlspecialchars($flashError) ?>
</div>
<?php endif; ?>

<!-- KPIs -->
<div class="kpi-row">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-clock-history"></i></div>
    <div class="stat-box-val"><?= $kpiAttente ?></div>
    <div class="stat-box-lbl">En attente / Assignées</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-truck"></i></div>
    <div class="stat-box-val"><?= $kpiEnCours ?></div>
    <div class="stat-box-lbl">En cours de livraison</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-check2-all"></i></div>
    <div class="stat-box-val"><?= $kpiLivreesAujourdhui ?></div>
    <div class="stat-box-lbl">Livrées aujourd'hui</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;color:#15803d;"><i class="bi bi-bar-chart-fill"></i></div>
    <div class="stat-box-val"><?= $tauxSucces ?>%</div>
    <div class="stat-box-lbl">Taux de succès</div>
  </div>
</div>

<!-- Tableau livraisons -->
<div class="a-card mb-4">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-truck text-success"></i> Liste des livraisons</div>
    <span style="font-size:.75rem;color:#9ca3af;"><?= count($livraisons) ?> livraison(s)</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Client</th>
          <th>Adresse</th>
          <th>Livreur</th>
          <th>Statut</th>
          <th>Date prévue</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($livraisons)): ?>
        <tr><td colspan="7" style="text-align:center;padding:36px;color:#9ca3af;">
          <i class="bi bi-inbox" style="font-size:1.8rem;display:block;margin-bottom:8px;"></i>
          Aucune livraison pour le moment
        </td></tr>
        <?php else: foreach ($livraisons as $liv): ?>
        <tr>
          <td>
            <span style="font-weight:700;font-size:.82rem;color:#1a5c2a;"><?= htmlspecialchars($liv['reference']) ?></span>
            <div style="font-size:.68rem;color:#9ca3af;">#<?= $liv['id'] ?></div>
          </td>
          <td>
            <?php if ($liv['client_nom']): ?>
              <div style="font-size:.84rem;font-weight:600;"><?= htmlspecialchars($liv['client_prenom'] . ' ' . $liv['client_nom']) ?></div>
            <?php else: ?>
              <span style="color:#9ca3af;">—</span>
            <?php endif; ?>
          </td>
          <td style="max-width:200px;font-size:.78rem;color:#374151;">
            <?= adresseLivraison($liv['adresse_livraison']) ?>
          </td>
          <td>
            <!-- Assign livreur inline -->
            <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_assigner" style="display:inline;">
              <input type="hidden" name="livraison_id" value="<?= $liv['id'] ?>">
              <select name="livreur_id" class="liv-select" onchange="this.form.submit()" title="Assigner un livreur">
                <option value="0" <?= !$liv['livreur_id'] ? 'selected' : '' ?>>— Choisir —</option>
                <?php foreach ($livreursActifs as $lr): ?>
                <option value="<?= $lr['id'] ?>" <?= $liv['livreur_id'] == $lr['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($lr['nom']) ?>
                </option>
                <?php endforeach; ?>
              </select>
            </form>
          </td>
          <td>
            <!-- Changer statut inline -->
            <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_statut" style="display:inline;">
              <input type="hidden" name="livraison_id" value="<?= $liv['id'] ?>">
              <select name="statut" class="liv-select" onchange="this.form.submit()"
                      style="<?= match($liv['statut']) {
                        'en_attente' => 'border-color:#d97706;color:#92400e;',
                        'assignee'   => 'border-color:#2563eb;color:#1d4ed8;',
                        'en_cours'   => 'border-color:#16a34a;color:#166534;',
                        'livree'     => 'border-color:#15803d;color:#15803d;',
                        'echec'      => 'border-color:#dc2626;color:#b91c1c;',
                        default      => ''
                      } ?>">
                <option value="en_attente" <?= $liv['statut']==='en_attente'?'selected':'' ?>>En attente</option>
                <option value="assignee"   <?= $liv['statut']==='assignee'?'selected':'' ?>>Assignée</option>
                <option value="en_cours"   <?= $liv['statut']==='en_cours'?'selected':'' ?>>En cours</option>
                <option value="livree"     <?= $liv['statut']==='livree'?'selected':'' ?>>Livrée</option>
                <option value="echec"      <?= $liv['statut']==='echec'?'selected':'' ?>>Échec</option>
              </select>
            </form>
          </td>
          <td style="font-size:.8rem;color:#374151;">
            <?= $liv['date_prevue'] ? date('d/m/Y', strtotime($liv['date_prevue'])) : '<span style="color:#9ca3af;">—</span>' ?>
            <?php if ($liv['date_livree']): ?>
              <div style="font-size:.7rem;color:#16a34a;margin-top:2px;">
                <i class="bi bi-check-circle-fill"></i> <?= date('d/m/Y H:i', strtotime($liv['date_livree'])) ?>
              </div>
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
    </table>
  </div>
</div>

<!-- Lien vers la page dédiée livreurs -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-people" style="color:var(--vert);"></i> Livreurs</div>
    <a href="<?= BASE_URL ?>?page=admin_livreurs" class="topbar-btn primary">
      <i class="bi bi-arrow-right-circle"></i> Gérer les livreurs
    </a>
  </div>
  <div style="padding:20px 22px;display:flex;align-items:center;gap:16px;color:#6b7280;font-size:.85rem;">
    <i class="bi bi-info-circle" style="font-size:1.2rem;color:#2563eb;flex-shrink:0;"></i>
    La gestion des livreurs (ajout, modification, statistiques) a été déplacée dans une page dédiée.
    <a href="<?= BASE_URL ?>?page=admin_livreurs" style="margin-left:auto;padding:8px 16px;background:var(--vert);color:#fff;border-radius:8px;text-decoration:none;font-size:.82rem;font-weight:700;white-space:nowrap;">
      <i class="bi bi-arrow-right-circle"></i> Voir les livreurs
    </a>
  </div>
</div>

<?php require_once __DIR__ . '/layout_end.php'; ?>
