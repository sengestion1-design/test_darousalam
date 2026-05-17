<?php
$adminPage = 'admins';
$pageTitle = 'Administrateurs';
$topbarActions = '<button onclick="document.getElementById(\'modalAjoutAdmin\').style.display=\'flex\'" class="topbar-btn primary"><i class="bi bi-shield-plus"></i> Ajouter un admin</button>';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';

$roleLabels = [
    'super_admin'  => ['lib'=>'Super Admin',  'color'=>'#7c3aed','bg'=>'#faf5ff','icon'=>'bi-shield-fill-check'],
    'admin'        => ['lib'=>'Admin',        'color'=>'#0284c7','bg'=>'#eff6ff','icon'=>'bi-shield-fill'],
    'gestionnaire' => ['lib'=>'Gestionnaire', 'color'=>'#ea580c','bg'=>'#fff7ed','icon'=>'bi-person-gear'],
    'livreur'      => ['lib'=>'Livreur',      'color'=>'#16a34a','bg'=>'#f0fdf4','icon'=>'bi-truck-front-fill'],
];
?>

<!-- Stats -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#faf5ff;"><i class="bi bi-shield-fill-check" style="color:#7c3aed;"></i></div>
    <div class="stat-box-val"><?= count($admins) ?></div>
    <div class="stat-box-lbl">Total admins</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-person-check-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($admins, fn($a)=>$a['actif'])) ?></div>
    <div class="stat-box-lbl">Actifs</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#eff6ff;"><i class="bi bi-shield-fill" style="color:#0284c7;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($admins, fn($a)=>$a['role']==='super_admin')) ?></div>
    <div class="stat-box-lbl">Super Admins</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef2f2;"><i class="bi bi-person-slash" style="color:#dc2626;"></i></div>
    <div class="stat-box-val"><?= count(array_filter($admins, fn($a)=>!$a['actif'])) ?></div>
    <div class="stat-box-lbl">Inactifs</div>
  </div>
</div>

<!-- Table -->
<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-shield-lock" style="color:var(--vert);"></i> Liste des administrateurs</div>
    <div class="filter-bar">
      <div class="filter-wrap">
        <i class="bi bi-search"></i>
        <input type="search" id="searchAdmin" class="filter-input" placeholder="Nom, email…" oninput="filterTable('searchAdmin','adminTable')">
      </div>
    </div>
  </div>
  <div style="overflow-x:auto;">
    <table class="a-table" id="adminTable">
      <thead>
        <tr>
          <th>Administrateur</th>
          <th>Email</th>
          <th>Rôle</th>
          <th>Statut</th>
          <th>Créé le</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($admins as $a):
          $r = $roleLabels[$a['role']] ?? ['lib'=>$a['role'],'color'=>'#6b7280','bg'=>'#f9fafb','icon'=>'bi-person'];
          $initiale = strtoupper(substr($a['nom'], 0, 1));
        ?>
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:38px;height:38px;border-radius:50%;background:var(--vert);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;flex-shrink:0;">
                <?= $initiale ?>
              </div>
              <div style="font-weight:700;font-size:.85rem;color:#1c1917;"><?= htmlspecialchars($a['nom']) ?></div>
            </div>
          </td>
          <td style="font-size:.82rem;color:#6b7280;"><?= htmlspecialchars($a['email']) ?></td>
          <td>
            <span class="status-badge" style="color:<?= $r['color'] ?>;background:<?= $r['bg'] ?>;">
              <i class="bi <?= $r['icon'] ?>" style="font-size:.7rem;"></i> <?= $r['lib'] ?>
            </span>
          </td>
          <td>
            <?php if($a['actif']): ?>
            <span class="status-badge" style="color:#16a34a;background:#f0fdf4;">
              <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Actif
            </span>
            <?php else: ?>
            <span class="status-badge" style="color:#dc2626;background:#fef2f2;">
              <i class="bi bi-circle-fill" style="font-size:.4rem;"></i> Inactif
            </span>
            <?php endif; ?>
          </td>
          <td style="font-size:.78rem;color:#9ca3af;"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
          <td>
            <div style="display:flex;gap:6px;">
              <button onclick='openEditAdmin(<?= json_encode(["id"=>$a["id"],"nom"=>$a["nom"],"email"=>$a["email"],"role"=>$a["role"],"actif"=>$a["actif"]]) ?>)'
                style="padding:6px 12px;border-radius:8px;background:#fff8f0;color:#ea580c;border:1.5px solid #fed7aa;font-size:.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:4px;">
                <i class="bi bi-pencil-fill"></i> Modifier
              </button>
              <?php if($a['id'] != ($_SESSION['admin_id'] ?? 0)): ?>
              <a href="<?= BASE_URL ?>?page=admin_admin_toggle&id=<?= $a['id'] ?>"
                onclick="return confirm('<?= $a['actif'] ? 'Désactiver' : 'Activer' ?> cet admin ?')"
                style="padding:6px 12px;border-radius:8px;background:<?= $a['actif'] ? '#fef2f2' : '#f0fdf4' ?>;color:<?= $a['actif'] ? '#dc2626' : '#16a34a' ?>;border:1.5px solid <?= $a['actif'] ? '#fecaca' : '#bbf7d0' ?>;font-size:.75rem;font-weight:700;text-decoration:none;display:flex;align-items:center;gap:4px;">
                <i class="bi bi-<?= $a['actif'] ? 'slash-circle' : 'check-circle' ?>-fill"></i>
                <?= $a['actif'] ? 'Désactiver' : 'Activer' ?>
              </a>
              <?php endif; ?>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

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

<!-- MODAL AJOUTER ADMIN -->
<div id="modalAjoutAdmin" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-shield-plus" style="color:var(--vert);"></i> Nouvel administrateur
      </div>
      <button onclick="document.getElementById('modalAjoutAdmin').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_admin_ajouter" style="padding:24px 28px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom complet *</label>
          <input type="text" name="nom" required class="form-inp" placeholder="Ex : Aminata Diallo">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Email *</label>
          <input type="email" name="email" required class="form-inp" placeholder="admin@darousalam.com">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Rôle *</label>
          <select name="role" required class="form-inp">
            <option value="gestionnaire">Gestionnaire</option>
            <option value="admin">Admin</option>
            <option value="livreur">Livreur</option>
            <option value="super_admin">Super Admin</option>
          </select>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Mot de passe *</label>
          <input type="password" name="mot_de_passe" required class="form-inp" placeholder="Minimum 6 caractères" minlength="6">
        </div>

      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalAjoutAdmin').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">
          Annuler
        </button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Créer
        </button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL MODIFIER ADMIN -->
<div id="modalEditAdmin" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 28px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;background:#fff;z-index:1;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-pencil-fill" style="color:#ea580c;"></i> Modifier l'administrateur
      </div>
      <button onclick="document.getElementById('modalEditAdmin').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_admin_modifier" style="padding:24px 28px;">
      <input type="hidden" name="id" id="eadmin_id">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nom complet *</label>
          <input type="text" name="nom" id="eadmin_nom" required class="form-inp">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Email *</label>
          <input type="email" name="email" id="eadmin_email" required class="form-inp">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Rôle *</label>
          <select name="role" id="eadmin_role" required class="form-inp">
            <option value="gestionnaire">Gestionnaire</option>
            <option value="admin">Admin</option>
            <option value="livreur">Livreur</option>
            <option value="super_admin">Super Admin</option>
          </select>
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Nouveau mot de passe <span style="color:#9ca3af;font-weight:400;">(laisser vide pour ne pas changer)</span></label>
          <input type="password" name="mot_de_passe" class="form-inp" placeholder="Nouveau mot de passe…">
        </div>

        <div style="grid-column:1/-1;">
          <label class="form-lbl">Statut</label>
          <div style="display:flex;gap:20px;margin-top:6px;">
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" id="eadmin_actif1" value="1"> Actif
            </label>
            <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:.85rem;">
              <input type="radio" name="actif" id="eadmin_actif0" value="0"> Inactif
            </label>
          </div>
        </div>

      </div>
      <div style="display:flex;gap:12px;margin-top:24px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalEditAdmin').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">
          Annuler
        </button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:#ea580c;color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-check-lg"></i> Enregistrer
        </button>
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
document.getElementById('modalAjoutAdmin').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });
document.getElementById('modalEditAdmin').addEventListener('click', function(e){ if(e.target===this) this.style.display='none'; });

function openEditAdmin(a) {
  document.getElementById('eadmin_id').value    = a.id;
  document.getElementById('eadmin_nom').value   = a.nom;
  document.getElementById('eadmin_email').value = a.email;
  document.getElementById('eadmin_role').value  = a.role;
  document.getElementById('eadmin_actif1').checked = a.actif == 1;
  document.getElementById('eadmin_actif0').checked = a.actif == 0;
  document.getElementById('modalEditAdmin').style.display = 'flex';
}
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
