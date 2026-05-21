<?php
$adminPage = 'settings';
$pageTitle = 'Paramètres';
require_once __DIR__ . '/layout.php';

$successMsg = $_GET['success'] ?? '';
$errorMsg   = $_GET['error'] ?? '';

$cfg = [];
foreach ($settings as $s) { $cfg[$s['cle']] = $s['valeur']; }
?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">

  <!-- Email / SMTP -->
  <div>
    <form method="POST" action="<?= BASE_URL ?>?page=adm_cfg_save">
      <input type="hidden" name="groupe" value="email">
      <div class="a-card" style="margin-bottom:20px;">
        <div class="a-card-head">
          <div class="a-card-title"><i class="bi bi-envelope-fill" style="color:var(--vert);"></i> Configuration Email (SMTP Gmail)</div>
        </div>
        <div style="padding:22px 24px;display:flex;flex-direction:column;gap:16px;">

          <div>
            <label class="form-lbl">Adresse Gmail expéditeur *</label>
            <input type="email" name="smtp_username" class="form-inp"
              value="<?= htmlspecialchars($cfg['smtp_username']??'') ?>"
              placeholder="votre.email@gmail.com">
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;"><i class="bi bi-info-circle"></i> Adresse qui apparaît comme expéditeur</div>
          </div>

          <div>
            <label class="form-lbl">Mot de passe d'application Gmail *</label>
            <div style="position:relative;">
              <input type="password" name="smtp_password" id="smtpPwd" class="form-inp"
                value="<?= htmlspecialchars($cfg['smtp_password']??'') ?>"
                placeholder="xxxx xxxx xxxx xxxx" style="padding-right:44px;">
              <button type="button" onclick="togglePwd()" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#9ca3af;font-size:1rem;">
                <i class="bi bi-eye" id="eyeIcon"></i>
              </button>
            </div>
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">
              <i class="bi bi-shield-lock"></i> Pas votre vrai mot de passe Gmail —
              <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color:var(--vert);font-weight:600;">créer un mot de passe d'application</a>
            </div>
          </div>

          <div>
            <label class="form-lbl">Nom affiché dans les emails</label>
            <input type="text" name="smtp_from_name" class="form-inp"
              value="<?= htmlspecialchars($cfg['smtp_from_name']??'Darou Salam Business Company') ?>"
              placeholder="Darou Salam Business Company">
          </div>

          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <label class="form-lbl">Serveur SMTP</label>
              <input type="text" name="smtp_host" class="form-inp"
                value="<?= htmlspecialchars($cfg['smtp_host']??'smtp.gmail.com') ?>"
                placeholder="smtp.gmail.com">
            </div>
            <div>
              <label class="form-lbl">Port</label>
              <input type="number" name="smtp_port" class="form-inp"
                value="<?= htmlspecialchars($cfg['smtp_port']??'587') ?>"
                placeholder="587">
            </div>
          </div>

          <!-- Test email -->
          <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:14px 16px;">
            <div style="font-size:.78rem;font-weight:700;color:#166534;margin-bottom:8px;"><i class="bi bi-send-check"></i> Tester la configuration</div>
            <div style="display:flex;gap:10px;align-items:center;">
              <input type="email" id="testEmail" class="form-inp" style="flex:1;" placeholder="adresse de test">
              <button type="button" onclick="testerEmail()" style="padding:9px 16px;border-radius:9px;border:none;background:var(--vert);color:#fff;font-size:.8rem;font-weight:700;cursor:pointer;white-space:nowrap;display:flex;align-items:center;gap:6px;">
                <i class="bi bi-send"></i> Envoyer
              </button>
            </div>
            <div id="testResult" style="margin-top:8px;font-size:.76rem;display:none;padding:8px 10px;border-radius:8px;"></div>
          </div>

        </div>
        <div style="padding:0 24px 22px;display:flex;justify-content:flex-end;">
          <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-lg"></i> Sauvegarder configuration email
          </button>
        </div>
      </div>
    </form>

    <!-- Guide -->
    <div class="a-card">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-question-circle" style="color:var(--vert);"></i> Comment obtenir le mot de passe ?</div>
      </div>
      <div style="padding:18px 24px;">
        <?php $steps = [
          '1' => 'Allez sur <strong>myaccount.google.com</strong>',
          '2' => 'Activez la <strong>Validation en 2 étapes</strong>',
          '3' => 'Cherchez <strong>"Mots de passe des applications"</strong>',
          '4' => 'Créez une app nommée <strong>Darou Salam</strong>',
          '5' => 'Copiez le mot de passe <strong>16 caractères</strong> généré',
        ];
        foreach ($steps as $n => $t): ?>
        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px;">
          <div style="width:26px;height:26px;border-radius:50%;background:var(--vert);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.72rem;font-weight:800;flex-shrink:0;"><?= $n ?></div>
          <div style="font-size:.83rem;color:#374151;padding-top:4px;"><?= $t ?></div>
        </div>
        <?php endforeach; ?>
        <a href="https://myaccount.google.com/apppasswords" target="_blank"
          style="display:inline-flex;align-items:center;gap:6px;margin-top:6px;padding:9px 16px;border-radius:9px;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;font-size:.8rem;font-weight:700;text-decoration:none;">
          <i class="bi bi-box-arrow-up-right"></i> Ouvrir Google — Mots de passe d'application
        </a>
      </div>
    </div>
  </div>

  <!-- Infos site + Livraison -->
  <div>
    <form method="POST" action="<?= BASE_URL ?>?page=adm_cfg_save">
      <input type="hidden" name="groupe" value="general">
      <div class="a-card" style="margin-bottom:20px;">
        <div class="a-card-head">
          <div class="a-card-title"><i class="bi bi-shop" style="color:var(--vert);"></i> Informations du site</div>
        </div>
        <div style="padding:22px 24px;display:flex;flex-direction:column;gap:16px;">
          <div>
            <label class="form-lbl">Nom du site</label>
            <input type="text" name="site_nom" class="form-inp"
              value="<?= htmlspecialchars($cfg['site_nom']??'') ?>"
              placeholder="Darou Salam Business Company">
          </div>
          <div>
            <label class="form-lbl">Téléphone</label>
            <input type="text" name="site_telephone" class="form-inp"
              value="<?= htmlspecialchars($cfg['site_telephone']??'') ?>"
              placeholder="+221 77 471 53 53">
          </div>
          <div>
            <label class="form-lbl">Numéro WhatsApp</label>
            <input type="text" name="site_whatsapp" class="form-inp"
              value="<?= htmlspecialchars($cfg['site_whatsapp']??'') ?>"
              placeholder="+221774715353">
          </div>
          <div>
            <label class="form-lbl">Adresse</label>
            <input type="text" name="site_adresse" class="form-inp"
              value="<?= htmlspecialchars($cfg['site_adresse']??'') ?>"
              placeholder="Dakar Médina, Rue 47×37">
          </div>
        </div>
        <div style="padding:0 24px 22px;display:flex;justify-content:flex-end;">
          <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-lg"></i> Sauvegarder
          </button>
        </div>
      </div>
    </form>

    <form method="POST" action="<?= BASE_URL ?>?page=adm_cfg_save">
      <input type="hidden" name="groupe" value="livraison">
      <div class="a-card">
        <div class="a-card-head">
          <div class="a-card-title"><i class="bi bi-truck" style="color:var(--vert);"></i> Livraison</div>
        </div>
        <div style="padding:22px 24px;display:flex;flex-direction:column;gap:16px;">
          <div>
            <label class="form-lbl">Frais de livraison (FCFA)</label>
            <input type="number" name="frais_livraison" class="form-inp"
              value="<?= htmlspecialchars($cfg['frais_livraison']??'0') ?>"
              placeholder="0" min="0">
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">Mettre 0 pour livraison gratuite</div>
          </div>
          <div>
            <label class="form-lbl">Livraison gratuite à partir de (FCFA)</label>
            <input type="number" name="livraison_gratuite_min" class="form-inp"
              value="<?= htmlspecialchars($cfg['livraison_gratuite_min']??'0') ?>"
              placeholder="0" min="0">
            <div style="font-size:.72rem;color:#9ca3af;margin-top:4px;">Mettre 0 pour désactiver</div>
          </div>
        </div>
        <div style="padding:0 24px 22px;display:flex;justify-content:flex-end;">
          <button type="submit" style="padding:10px 24px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
            <i class="bi bi-check-lg"></i> Sauvegarder
          </button>
        </div>
      </div>
    </form>
  </div>

</div>

<!-- QR CODES PAIEMENT -->
<div style="margin-top:24px;">
  <form method="POST" action="<?= BASE_URL ?>?page=adm_qr_save" enctype="multipart/form-data">
    <div class="a-card">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-qr-code" style="color:var(--vert);"></i> QR Codes de paiement</div>
      </div>
      <div style="padding:22px 24px;">
        <div style="font-size:.8rem;color:#6b7280;margin-bottom:20px;">
          <i class="bi bi-info-circle"></i> Ces QR codes s'affichent aux clients lors du checkout quand ils choisissent Wave ou Orange Money.
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">

          <!-- Wave QR -->
          <div>
            <label class="form-lbl" style="color:#0057ff;"><i class="bi bi-qr-code-scan"></i> QR Code Wave</label>
            <?php $qrWave = $cfg['qr_wave'] ?? ''; ?>
            <?php if ($qrWave): ?>
            <div style="margin-bottom:12px;text-align:center;">
              <img src="<?= BASE_URL ?>public/uploads/qrcodes/<?= htmlspecialchars($qrWave) ?>"
                   style="width:160px;height:160px;border:3px solid #0057ff;border-radius:16px;object-fit:contain;padding:8px;background:#fff;">
              <div style="font-size:.72rem;color:#0057ff;margin-top:6px;font-weight:600;">QR Code actuel</div>
            </div>
            <?php else: ?>
            <div style="width:160px;height:160px;border:2px dashed #bfdbfe;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;color:#93c5fd;margin-bottom:12px;">
              <i class="bi bi-qr-code" style="font-size:2rem;"></i>
              <span style="font-size:.72rem;">Aucun QR code</span>
            </div>
            <?php endif; ?>
            <input type="file" name="qr_wave" accept="image/png,image/jpeg,image/webp"
              style="width:100%;padding:8px;border:1.5px dashed #bfdbfe;border-radius:9px;font-size:.82rem;cursor:pointer;background:#eff6ff;">
            <div style="font-size:.7rem;color:#9ca3af;margin-top:4px;">PNG ou JPG recommandé · max 2 Mo</div>
          </div>

          <!-- Orange Money QR -->
          <div>
            <label class="form-lbl" style="color:#ff6600;"><i class="bi bi-qr-code-scan"></i> QR Code Orange Money</label>
            <?php $qrOm = $cfg['qr_orange_money'] ?? ''; ?>
            <?php if ($qrOm): ?>
            <div style="margin-bottom:12px;text-align:center;">
              <img src="<?= BASE_URL ?>public/uploads/qrcodes/<?= htmlspecialchars($qrOm) ?>"
                   style="width:160px;height:160px;border:3px solid #ff6600;border-radius:16px;object-fit:contain;padding:8px;background:#fff;">
              <div style="font-size:.72rem;color:#ff6600;margin-top:6px;font-weight:600;">QR Code actuel</div>
            </div>
            <?php else: ?>
            <div style="width:160px;height:160px;border:2px dashed #fed7aa;border-radius:16px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;color:#fdba74;margin-bottom:12px;">
              <i class="bi bi-qr-code" style="font-size:2rem;"></i>
              <span style="font-size:.72rem;">Aucun QR code</span>
            </div>
            <?php endif; ?>
            <input type="file" name="qr_orange_money" accept="image/png,image/jpeg,image/webp"
              style="width:100%;padding:8px;border:1.5px dashed #fed7aa;border-radius:9px;font-size:.82rem;cursor:pointer;background:#fff7ed;">
            <div style="font-size:.7rem;color:#9ca3af;margin-top:4px;">PNG ou JPG recommandé · max 2 Mo</div>
          </div>

        </div>
        <button type="submit" style="margin-top:20px;background:var(--vert);color:#fff;border:none;border-radius:10px;padding:11px 24px;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-cloud-upload-fill"></i> Enregistrer les QR codes
        </button>
      </div>
    </div>
  </form>
</div>

<?php if($successMsg): ?>
<div id="flashMsg" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($successMsg) ?>
</div>
<script>setTimeout(()=>{ const m=document.getElementById('flashMsg'); if(m)m.remove(); },4000);</script>
<?php endif; ?>
<?php if($errorMsg): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($errorMsg) ?>
</div>
<script>setTimeout(()=>{ const m=document.getElementById('flashErr'); if(m)m.remove(); },5000);</script>
<?php endif; ?>

<script>
function togglePwd() {
  var i = document.getElementById('smtpPwd');
  var e = document.getElementById('eyeIcon');
  if (i.type === 'password') { i.type = 'text'; e.className = 'bi bi-eye-slash'; }
  else { i.type = 'password'; e.className = 'bi bi-eye'; }
}

function testerEmail() {
  var email = document.getElementById('testEmail').value.trim();
  var res   = document.getElementById('testResult');
  if (!email) {
    res.style.display = 'block';
    res.style.background = '#fef2f2';
    res.style.color = '#dc2626';
    res.textContent = 'Saisissez une adresse email.';
    return;
  }
  res.style.display = 'block';
  res.style.background = '#f9fafb';
  res.style.color = '#9ca3af';
  res.textContent = 'Envoi en cours…';

  fetch('<?= BASE_URL ?>?page=adm_cfg_mail', {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'email=' + encodeURIComponent(email)
  })
  .then(function(r){ return r.json(); })
  .then(function(d){
    res.style.background = d.success ? '#f0fdf4' : '#fef2f2';
    res.style.color = d.success ? '#16a34a' : '#dc2626';
    res.textContent = d.message;
  })
  .catch(function(){
    res.style.background = '#fef2f2';
    res.style.color = '#dc2626';
    res.textContent = 'Erreur réseau.';
  });
}
</script>

<style>
.form-lbl{display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;}
.form-inp{width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;font-family:var(--font);background:#fafafa;transition:all .2s;}
.form-inp:focus{outline:none;border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.08);}
</style>

<?php require_once __DIR__ . '/layout_end.php'; ?>
