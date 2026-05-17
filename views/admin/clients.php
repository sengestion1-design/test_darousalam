<?php
$adminPage = 'clients';
$pageTitle = 'Clients';
$topbarActions = '<button onclick="document.getElementById(\'modalAjoutClient\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-person-plus-fill"></i> Ajouter un utilisateur</button>';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';

$totalClients  = count($clients);
$actifs        = count(array_filter($clients, fn($c)=>$c['statut']==='actif'));
$pros          = count(array_filter($clients, fn($c)=>$c['type']==='professionnel'));
$totalCA       = array_sum(array_column($clients,'total_depense'));
?>

<div class="stat-grid">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#e0f2fe;"><i class="bi bi-people-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= $totalClients ?></div>
    <div class="stat-box-lbl">Total clients</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-person-check-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= $actifs ?></div>
    <div class="stat-box-lbl">Comptes actifs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#faf5ff;"><i class="bi bi-briefcase-fill" style="color:#7c3aed;"></i></div>
    <div class="stat-box-val"><?= $pros ?></div>
    <div class="stat-box-lbl">Professionnels</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fff7ed;"><i class="bi bi-cash-stack" style="color:#ea580c;"></i></div>
    <div class="stat-box-val" style="font-size:1.1rem;"><?= number_format($totalCA,0,',',' ') ?></div>
    <div class="stat-box-lbl">FCFA total dépensé</div>
  </div>
</div>

<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-people" style="color:var(--vert);"></i> Liste des clients</div>
    <div class="filter-bar">
      <div class="filter-wrap">
        <i class="bi bi-search"></i>
        <input type="search" id="searchClient" class="filter-input" placeholder="Nom, email…" oninput="filterTable('searchClient','clientTable')">
      </div>
      <select class="filter-select" onchange="filterType(this.value)">
        <option value="">Tous types</option>
        <option value="particulier">Particuliers</option>
        <option value="professionnel">Professionnels</option>
      </select>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="clientTable">
      <thead>
        <tr>
          <th>Client</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Type</th>
          <th>Commandes</th>
          <th>Total dépensé</th>
          <th>Statut</th>
          <th>Inscription</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($clients as $c):
          $statutColor = $c['statut']==='actif' ? '#16a34a' : '#dc2626';
          $statutBg    = $c['statut']==='actif' ? '#f0fdf4' : '#fef2f2';
          $typeColor   = $c['type']==='professionnel' ? '#7c3aed' : '#0284c7';
          $typeBg      = $c['type']==='professionnel' ? '#faf5ff' : '#eff6ff';
          $initiales   = strtoupper(substr($c['prenom']??'?',0,1).substr($c['nom']??'',0,1));
        ?>
        <tr data-type="<?= $c['type'] ?>">
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:36px;height:36px;border-radius:50%;background:var(--vert);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.78rem;font-weight:700;flex-shrink:0;"><?= $initiales ?></div>
              <div>
                <div style="font-weight:700;font-size:.85rem;"><?= htmlspecialchars(($c['prenom']??'').' '.($c['nom']??'')) ?></div>
                <?php if(!empty($c['entreprise'])): ?>
                <div style="font-size:.72rem;color:#9ca3af;"><i class="bi bi-briefcase"></i> <?= htmlspecialchars($c['entreprise']) ?></div>
                <?php endif; ?>
                <?php if(!empty($c['ville'])): ?>
                <div style="font-size:.72rem;color:#9ca3af;"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($c['ville']) ?></div>
                <?php endif; ?>
              </div>
            </div>
          </td>
          <td style="font-size:.82rem;color:#6b7280;"><?= htmlspecialchars($c['email']) ?></td>
          <td style="font-size:.82rem;"><?= htmlspecialchars($c['telephone']??'—') ?></td>
          <td>
            <span class="status-badge" style="color:<?= $typeColor ?>;background:<?= $typeBg ?>;">
              <i class="bi bi-<?= $c['type']==='professionnel'?'briefcase-fill':'person-fill' ?>" style="font-size:.65rem;"></i>
              <?= ucfirst($c['type']) ?>
            </span>
          </td>
          <td style="font-weight:700;text-align:center;"><?= $c['nb_commandes'] ?></td>
          <td style="font-weight:700;color:var(--orange);"><?= number_format($c['total_depense'],0,',',' ') ?> FCFA</td>
          <td>
            <span class="status-badge" style="color:<?= $statutColor ?>;background:<?= $statutBg ?>;">
              <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
              <?= ucfirst($c['statut']) ?>
            </span>
          </td>
          <td style="font-size:.78rem;color:#9ca3af;"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
          <td>
            <?php if ((int)$c['nb_commandes'] === 0): ?>
            <form method="POST" action="<?= BASE_URL ?>?page=admin_client_supprimer" style="display:inline;"
                  onsubmit="return confirm('Supprimer le client « <?= htmlspecialchars(addslashes(($c['prenom']??'').' '.($c['nom']??''))) ?> » ?');">
              <input type="hidden" name="id" value="<?= $c['id'] ?>">
              <button type="submit" class="btn-sm-action btn-del" title="Supprimer"><i class="bi bi-trash"></i></button>
            </form>
            <?php else: ?>
            <span style="font-size:.7rem;color:#d1d5db;" title="Client avec commandes">—</span>
            <?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function filterType(type) {
  document.querySelectorAll('#clientTable tbody tr').forEach(row => {
    row.style.display = (!type || row.dataset.type === type) ? '' : 'none';
  });
}
</script>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<script>setTimeout(()=>{const m=document.getElementById('flashMsg');if(m)m.remove();},4000);</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>{const m=document.getElementById('flashErr');if(m)m.remove();},5000);</script>
<?php endif; ?>

<!-- MODAL AJOUTER UTILISATEUR -->
<div id="modalAjoutClient" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:660px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-person-plus-fill" style="color:var(--vert);"></i> Ajouter un utilisateur
      </div>
      <button onclick="document.getElementById('modalAjoutClient').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_client_ajouter" style="padding:24px 28px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <div>
          <label class="form-lbl">Civilité</label>
          <select name="civilite" class="form-inp">
            <option value="M.">M.</option>
            <option value="Mme">Mme</option>
            <option value="Mlle">Mlle</option>
            <option value="Dr">Dr</option>
          </select>
        </div>

        <div>
          <label class="form-lbl">Type</label>
          <select name="type" class="form-inp">
            <option value="particulier">Particulier</option>
            <option value="professionnel">Professionnel</option>
          </select>
        </div>

        <div>
          <label class="form-lbl">Prénom *</label>
          <input type="text" name="prenom" required class="form-inp" placeholder="Ex : Moussa">
        </div>

        <div>
          <label class="form-lbl">Nom *</label>
          <input type="text" name="nom" required class="form-inp" placeholder="Ex : Diallo">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Email *</label>
          <input type="email" name="email" required class="form-inp" placeholder="email@exemple.com">
        </div>

        <div>
          <label class="form-lbl">Téléphone</label>
          <input type="tel" name="telephone" class="form-inp" placeholder="77 000 00 00">
        </div>

        <div>
          <label class="form-lbl">Ville</label>
          <input type="text" name="ville" class="form-inp" placeholder="Ex : Dakar" value="Dakar">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Entreprise (si professionnel)</label>
          <input type="text" name="entreprise" class="form-inp" placeholder="Nom de l'entreprise">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Adresse</label>
          <input type="text" name="adresse" class="form-inp" placeholder="Ex : Rue 47×37, Médina">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Mot de passe *</label>
          <input type="password" name="mot_de_passe" required class="form-inp" placeholder="Minimum 6 caractères" minlength="6">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:20px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="statut" value="actif" checked> Actif
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="statut" value="suspendu"> Suspendu
            </label>
          </div>
        </div>

      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjoutClient').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">
          Annuler
        </button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Créer l'utilisateur
        </button>
      </div>
    </form>
  </div>
</div>

<script>
document.getElementById('modalAjoutClient').addEventListener('click', function(e) {
  if (e.target === this) this.style.display = 'none';
});
<?php if (($_GET['add'] ?? '') === '1'): ?>
document.getElementById('modalAjoutClient').style.display = 'flex';
<?php endif; ?>
</script>

<style>
.form-lbl{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;}
.form-inp{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;font-family:var(--font);background:#fafafa;transition:all .2s;}
.form-inp:focus{outline:none;border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.08);}
</style>

<?php require_once __DIR__ . '/layout_end.php'; ?>
