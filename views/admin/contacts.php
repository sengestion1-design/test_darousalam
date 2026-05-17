<?php
$adminPage = 'contacts';
$pageTitle = 'Messages de contact';
require_once __DIR__ . '/layout.php';
?>

<div class="stat-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#eff6ff;"><i class="bi bi-envelope-fill" style="color:#2563eb;"></i></div>
    <div class="stat-box-val"><?= count($messages) ?></div>
    <div class="stat-box-lbl">Total messages</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#fef3c7;"><i class="bi bi-envelope-open-fill" style="color:#d97706;"></i></div>
    <div class="stat-box-val"><?= $nbNonLus ?></div>
    <div class="stat-box-lbl">Non lus</div>
  </div>
  <div class="stat-box">
    <div class="stat-box-icon" style="background:#f0fdf4;"><i class="bi bi-check-circle-fill" style="color:#16a34a;"></i></div>
    <div class="stat-box-val"><?= count($messages) - $nbNonLus ?></div>
    <div class="stat-box-lbl">Lus</div>
  </div>
</div>

<div class="a-card">
  <div class="a-card-head">
    <div class="a-card-title"><i class="bi bi-chat-dots" style="color:var(--vert);"></i> Messages reçus</div>
    <span style="font-size:.75rem;color:#9ca3af;"><?= count($messages) ?> message(s)</span>
  </div>

  <?php if (empty($messages)): ?>
  <div style="padding:60px;text-align:center;color:#9ca3af;">
    <i class="bi bi-inbox" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
    Aucun message pour le moment
  </div>
  <?php else: ?>
  <div style="padding:8px 0;">
    <?php foreach ($messages as $msg): ?>
    <div style="padding:20px 24px;border-bottom:1px solid #f3f4f6;<?= !$msg['lu'] ? 'background:#fffbeb;' : '' ?>display:flex;gap:18px;align-items:flex-start;">
      <!-- Avatar -->
      <div style="width:42px;height:42px;border-radius:50%;background:<?= !$msg['lu'] ? '#fef3c7' : '#f3f4f6' ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;color:<?= !$msg['lu'] ? '#d97706' : '#9ca3af' ?>;flex-shrink:0;">
        <?= strtoupper(substr($msg['nom'], 0, 1)) ?>
      </div>
      <!-- Contenu -->
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;flex-wrap:wrap;">
          <span style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($msg['nom']) ?></span>
          <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="font-size:.78rem;color:#2563eb;text-decoration:none;"><?= htmlspecialchars($msg['email']) ?></a>
          <?php if (!$msg['lu']): ?>
          <span style="background:#fef3c7;color:#d97706;border:1px solid #fcd34d;border-radius:5px;padding:1px 8px;font-size:.68rem;font-weight:700;">NOUVEAU</span>
          <?php endif; ?>
          <span style="margin-left:auto;font-size:.72rem;color:#9ca3af;"><?= date('d/m/Y à H:i', strtotime($msg['created_at'])) ?></span>
        </div>
        <?php if (!empty($msg['sujet'])): ?>
        <div style="font-size:.8rem;font-weight:600;color:#374151;margin-bottom:6px;">
          <i class="bi bi-tag-fill" style="font-size:.7rem;color:var(--vert);"></i> <?= htmlspecialchars($msg['sujet']) ?>
        </div>
        <?php endif; ?>
        <!-- Message client -->
        <div style="font-size:.83rem;color:#4b5563;line-height:1.6;background:#fff;border:1px solid #f3f4f6;border-radius:10px;padding:10px 14px;">
          <?= nl2br(htmlspecialchars($msg['message'])) ?>
        </div>

        <!-- Fil des réponses -->
        <?php if (!empty($reponses[$msg['id']])): ?>
        <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px;">
          <?php foreach ($reponses[$msg['id']] as $rep): ?>
          <div style="display:flex;gap:10px;align-items:flex-start;padding-left:16px;">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1a5c2a,#2d8a42);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:800;flex-shrink:0;">
              DS
            </div>
            <div style="flex:1;min-width:0;">
              <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;">
                <span style="font-size:.78rem;font-weight:700;color:#1a5c2a;">Darou Salam</span>
                <?php if (!empty($rep['admin_nom'])): ?>
                <span style="font-size:.7rem;color:#9ca3af;">(<?= htmlspecialchars($rep['admin_nom']) ?>)</span>
                <?php endif; ?>
                <span style="font-size:.7rem;color:#9ca3af;margin-left:auto;"><?= date('d/m/Y à H:i', strtotime($rep['created_at'])) ?></span>
                <span style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;border-radius:5px;padding:1px 7px;font-size:.65rem;font-weight:700;display:flex;align-items:center;gap:3px;">
                  <i class="bi bi-send-fill" style="font-size:.55rem;"></i> Envoyé
                </span>
              </div>
              <?php
                $msgCourt = mb_strlen($rep['message']) > 300;
                $msgId = 'rep-' . $rep['id'];
              ?>
              <div id="<?= $msgId ?>" style="font-size:.82rem;color:#374151;line-height:1.6;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:10px 14px;max-height:120px;overflow:hidden;transition:max-height .3s;">
                <?= nl2br(htmlspecialchars($rep['message'])) ?>
              </div>
              <?php if ($msgCourt): ?>
              <button onclick="
                var el=document.getElementById('<?= $msgId ?>');
                var btn=this;
                if(el.style.maxHeight==='none'){el.style.maxHeight='120px';btn.textContent='Voir plus ▾';}
                else{el.style.maxHeight='none';btn.textContent='Voir moins ▴';}
              " style="background:none;border:none;color:#1a5c2a;font-size:.72rem;font-weight:700;cursor:pointer;margin-top:4px;padding:0;">Voir plus ▾</button>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
      <!-- Actions -->
      <div style="display:flex;flex-direction:column;gap:6px;flex-shrink:0;">
        <?php if (!$msg['lu']): ?>
        <a href="<?= BASE_URL ?>?page=admin_contacts&marquer_lu=<?= $msg['id'] ?>"
           class="btn-sm-action btn-view" title="Marquer comme lu" style="white-space:nowrap;">
          <i class="bi bi-check2"></i>
        </a>
        <?php endif; ?>
        <button onclick="openReponse(<?= $msg['id'] ?>, <?= htmlspecialchars(json_encode($msg['nom']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode($msg['email']), ENT_QUOTES) ?>, <?= htmlspecialchars(json_encode('Re: ' . ($msg['sujet'] ?? 'Votre message')), ENT_QUOTES) ?>)"
           class="btn-sm-action" style="background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;" title="Répondre par email">
          <i class="bi bi-reply-fill"></i>
        </button>
        <a href="<?= BASE_URL ?>?page=admin_contacts&supprimer=<?= $msg['id'] ?>"
           class="btn-sm-action btn-del" title="Supprimer"
           onclick="return confirm('Supprimer ce message ?')">
          <i class="bi bi-trash"></i>
        </a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php
$flashSuccess = $_SESSION['flash_success'] ?? '';
$flashError   = $_SESSION['flash_error']   ?? '';
unset($_SESSION['flash_success'], $_SESSION['flash_error']);
?>
<?php if ($flashSuccess): ?>
<div id="flashOk" style="position:fixed;bottom:24px;right:24px;background:#16a34a;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-check-circle-fill"></i> <?= htmlspecialchars($flashSuccess) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashOk')?.remove(),5000);</script>
<?php endif; ?>
<?php if ($flashError): ?>
<div id="flashErr" style="position:fixed;bottom:24px;right:24px;background:#dc2626;color:#fff;padding:14px 22px;border-radius:12px;font-size:.85rem;font-weight:600;box-shadow:0 4px 20px rgba(0,0,0,.15);z-index:9999;display:flex;align-items:center;gap:10px;">
  <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($flashError) ?>
</div>
<script>setTimeout(()=>document.getElementById('flashErr')?.remove(),6000);</script>
<?php endif; ?>

<!-- MODAL RÉPONSE -->
<div id="modalReponse" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:2000;align-items:center;justify-content:center;padding:20px;">
  <div style="background:#fff;border-radius:20px;width:100%;max-width:560px;box-shadow:0 20px 60px rgba(0,0,0,.2);">
    <div style="padding:20px 26px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;border-radius:20px 20px 0 0;">
      <div style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:800;color:#0f2d16;">
        <i class="bi bi-reply-fill" style="color:#2563eb;"></i> Répondre au message
      </div>
      <button onclick="document.getElementById('modalReponse').style.display='none'" style="background:none;border:none;font-size:1.4rem;cursor:pointer;color:#9ca3af;">&times;</button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=admin_contact_repondre" style="padding:22px 26px;">
      <input type="hidden" name="contact_id" id="reponseContactId">

      <!-- Destinataire (readonly) -->
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">À</label>
        <div id="reponseDestinataire" style="padding:9px 12px;background:#f9fafb;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;color:#6b7280;"></div>
      </div>

      <!-- Sujet -->
      <div style="margin-bottom:14px;">
        <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">Sujet</label>
        <input type="text" name="sujet" id="reponseSujet" required
               style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;box-sizing:border-box;">
      </div>

      <!-- Message -->
      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:.75rem;font-weight:700;color:#374151;margin-bottom:5px;text-transform:uppercase;letter-spacing:.05em;">Message *</label>
        <textarea name="message" id="reponseMessage" rows="7" required
                  style="width:100%;padding:9px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.85rem;resize:vertical;box-sizing:border-box;font-family:inherit;"
                  placeholder="Tapez votre réponse ici…"></textarea>
      </div>

      <div style="display:flex;gap:12px;justify-content:flex-end;">
        <button type="button" onclick="document.getElementById('modalReponse').style.display='none'"
          style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;color:#374151;font-size:.85rem;font-weight:600;cursor:pointer;">Annuler</button>
        <button type="submit"
          style="padding:10px 24px;border-radius:10px;border:none;background:#2563eb;color:#fff;font-size:.85rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:8px;">
          <i class="bi bi-send-fill"></i> Envoyer
        </button>
      </div>
    </form>
  </div>
</div>

<script>
function openReponse(id, nom, email, sujet) {
  document.getElementById('reponseContactId').value = id;
  document.getElementById('reponseDestinataire').textContent = nom + ' <' + email + '>';
  document.getElementById('reponseSujet').value = sujet;
  document.getElementById('reponseMessage').value = '';
  document.getElementById('modalReponse').style.display = 'flex';
  setTimeout(() => document.getElementById('reponseMessage').focus(), 100);
}
document.getElementById('modalReponse').addEventListener('click', e => {
  if (e.target === e.currentTarget) e.currentTarget.style.display = 'none';
});
</script>

<?php require_once __DIR__ . '/layout_end.php'; ?>
