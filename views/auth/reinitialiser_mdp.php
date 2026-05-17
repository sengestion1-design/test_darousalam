<?php
$pageTitle = 'Nouveau mot de passe — Darou Salam Business Company';
$_bodyClass = 'auth-page';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
html, body.auth-page { margin:0!important;padding:0!important;overflow:hidden!important;height:100%!important; }
body.auth-page #top-bar, body.auth-page #main-nav { display:none!important; }
.auth-screen { display:flex;position:fixed;top:0;left:0;right:0;bottom:0;width:100%;height:100%;overflow:hidden;z-index:9999; }
.auth-left {
    flex:1;background:linear-gradient(160deg,#0f2d16 0%,#1a5c2a 55%,#2d8a42 100%);
    position:relative;overflow:hidden;display:flex;flex-direction:column;
    justify-content:space-between;padding:40px 56px 48px;
}
.auth-left::before { content:'';position:absolute;inset:0;background:url('/darousalam/photos/PHOTO-2026-05-14-22-41-06.jpg') center/cover no-repeat;opacity:.35; }
.auth-left::after  { content:'';position:absolute;inset:0;background:linear-gradient(to bottom,rgba(15,45,22,.2) 0%,rgba(15,45,22,.6) 100%); }
.auth-left > * { position:relative;z-index:2; }
.auth-brand { display:flex;align-items:center;gap:12px;text-decoration:none; }
.auth-brand img { width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #d4a017; }
.auth-brand .name { font-family:'Playfair Display',serif;font-weight:700;font-size:1.05rem;color:#fff;display:block; }
.auth-brand .tag  { font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;color:#d4a017; }
.auth-left h2 { font-family:'Playfair Display',serif;font-size:2.3rem;font-weight:900;color:#fff;line-height:1.2;margin-bottom:16px; }
.auth-left p.lead { color:rgba(255,255,255,.7);font-size:.91rem;line-height:1.7;max-width:420px;margin-bottom:28px; }
.pwd-rules { display:flex;flex-direction:column;gap:10px; }
.pwd-rule { display:flex;align-items:center;gap:10px;font-size:.84rem;color:rgba(255,255,255,.7); }
.pwd-rule i { font-size:1rem;color:#d4a017;flex-shrink:0; }

.auth-right { width:520px;flex-shrink:0;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:0 52px;overflow-y:auto; }
.auth-right-inner { max-width:400px;width:100%;margin:0 auto; }
.auth-icon-circle { width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:2px solid #bbf7d0;display:flex;align-items:center;justify-content:center;font-size:1.8rem;color:#1a5c2a;margin-bottom:20px; }
.auth-title { font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:800;color:#0f2d16;margin-bottom:6px; }
.auth-desc { font-size:.86rem;color:#6b7280;margin-bottom:28px;line-height:1.6; }
.f-group { margin-bottom:16px; }
.f-wrap { position:relative; }
.f-icon { position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.95rem;pointer-events:none; }
.f-wrap input { width:100%;padding:12px 40px 12px 40px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.88rem;font-family:'DM Sans',sans-serif;color:#0f2d16;background:#fafafa;transition:all .2s; }
.f-wrap input:focus { outline:none;border-color:#1a5c2a;background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.09); }
.f-end-btn { position:absolute;right:11px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;padding:0;font-size:.95rem; }
.pwd-bars { display:flex;gap:3px;margin-top:5px; }
.pwd-bar  { flex:1;height:3px;border-radius:3px;background:#e5e7eb;transition:background .3s; }
.pwd-bar.weak { background:#ef4444; } .pwd-bar.medium { background:#f59e0b; } .pwd-bar.strong { background:#22c55e; }
.pwd-match-msg { font-size:.73rem;margin-top:4px;display:none; }
.btn-auth { width:100%;padding:13px;background:#1a5c2a;color:#fff;border:none;border-radius:12px;font-size:.93rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:all .25s; }
.btn-auth:hover { background:#2d8a42;transform:translateY(-1px);box-shadow:0 8px 24px rgba(26,92,42,.28); }
.auth-alert-error   { background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:12px;padding:14px 16px;font-size:.85rem;display:flex;align-items:center;gap:10px;margin-bottom:20px; }
.auth-alert-warning { background:#fffbeb;border:1px solid #fde68a;color:#92400e;border-radius:12px;padding:16px;font-size:.86rem;text-align:center;margin-bottom:20px; }
.auth-switch { text-align:center;margin-top:24px;font-size:.82rem;color:#9ca3af; }
.auth-switch a { color:#1a5c2a;font-weight:700;text-decoration:none; }
@media (max-width:900px) { .auth-left{display:none;} .auth-right{width:100%;padding:40px 24px;} }
</style>

<script>document.documentElement.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';document.body.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';</script>

<div class="auth-screen">
    <div class="auth-left">
        <a class="auth-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Logo">
            <div><span class="name">Darou Salam</span><span class="tag">Business Company</span></div>
        </a>
        <div style="flex:1;display:flex;flex-direction:column;justify-content:center;">
            <h2>Choisissez un<br>nouveau mot de passe</h2>
            <p class="lead">Pour sécuriser votre compte, choisissez un mot de passe fort que vous n'utilisez pas ailleurs.</p>
            <div class="pwd-rules">
                <div class="pwd-rule"><i class="bi bi-check-circle-fill"></i> Au moins 8 caractères</div>
                <div class="pwd-rule"><i class="bi bi-check-circle-fill"></i> Une lettre majuscule recommandée</div>
                <div class="pwd-rule"><i class="bi bi-check-circle-fill"></i> Un chiffre recommandé</div>
                <div class="pwd-rule"><i class="bi bi-check-circle-fill"></i> Ne pas réutiliser un ancien mot de passe</div>
            </div>
        </div>
        <div style="padding-top:20px;border-top:1px solid rgba(255,255,255,.12);font-size:.8rem;color:rgba(255,255,255,.5);">
            Ce lien expire dans 1 heure ·
            <a href="<?= BASE_URL ?>?page=mot_de_passe_oublie" style="color:#d4a017;">Faire une nouvelle demande</a>
        </div>
    </div>

    <div class="auth-right">
        <div class="auth-right-inner">
            <a href="<?= BASE_URL ?>?page=login" style="display:inline-flex;align-items:center;gap:6px;color:#9ca3af;font-size:.78rem;text-decoration:none;margin-bottom:28px;">
                <i class="bi bi-arrow-left"></i> Retour à la connexion
            </a>
            <div class="auth-icon-circle"><i class="bi bi-key-fill"></i></div>
            <h1 class="auth-title">Nouveau mot de passe</h1>

            <?php if (!$client): ?>
                <div class="auth-alert-warning">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size:1.5rem;display:block;margin-bottom:8px;color:#d97706;"></i>
                    <strong style="display:block;margin-bottom:4px;">Lien invalide ou expiré</strong>
                    <?= htmlspecialchars($error) ?>
                    <div style="margin-top:12px;">
                        <a href="<?= BASE_URL ?>?page=mot_de_passe_oublie" style="display:inline-flex;align-items:center;gap:6px;background:#1a5c2a;color:#fff;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:.84rem;font-weight:700;">
                            <i class="bi bi-arrow-clockwise"></i> Faire une nouvelle demande
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <p class="auth-desc">Compte : <strong><?= htmlspecialchars($client['email']) ?></strong></p>

                <?php if ($error): ?>
                <div class="auth-alert-error"><i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>?page=reinitialiser_mdp&token=<?= urlencode($token) ?>">
                    <div class="f-group">
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                            Nouveau mot de passe <span style="color:#ef4444">*</span>
                        </label>
                        <div class="f-wrap" style="position:relative;">
                            <i class="bi bi-lock f-icon"></i>
                            <input type="password" name="password" id="pwd" placeholder="Min. 8 caractères" required autofocus>
                            <button type="button" class="f-end-btn" onclick="togglePwd('pwd','eye1')" tabindex="-1"><i class="bi bi-eye" id="eye1"></i></button>
                        </div>
                        <div class="pwd-bars"><div class="pwd-bar" id="b1"></div><div class="pwd-bar" id="b2"></div><div class="pwd-bar" id="b3"></div><div class="pwd-bar" id="b4"></div></div>
                    </div>
                    <div class="f-group">
                        <label style="font-size:.8rem;font-weight:600;color:#374151;display:block;margin-bottom:6px;">
                            Confirmer <span style="color:#ef4444">*</span>
                        </label>
                        <div class="f-wrap" style="position:relative;">
                            <i class="bi bi-lock-fill f-icon"></i>
                            <input type="password" name="password_conf" id="pwd2" placeholder="Répéter le mot de passe" required>
                            <button type="button" class="f-end-btn" onclick="togglePwd('pwd2','eye2')" tabindex="-1"><i class="bi bi-eye" id="eye2"></i></button>
                        </div>
                        <div class="pwd-match-msg" id="matchMsg"></div>
                    </div>
                    <button type="submit" class="btn-auth">
                        <i class="bi bi-shield-check"></i>
                        Enregistrer le nouveau mot de passe
                    </button>
                </form>
            <?php endif; ?>

            <div class="auth-switch">
                <a href="<?= BASE_URL ?>?page=login">&larr; Retour à la connexion</a>
            </div>
        </div>
    </div>
</div>

<script>
function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (inp.type === 'password') { inp.type = 'text'; icon.className = 'bi bi-eye-slash'; }
    else { inp.type = 'password'; icon.className = 'bi bi-eye'; }
}

document.getElementById('pwd').addEventListener('input', function() {
    const val = this.value;
    const bars = ['b1','b2','b3','b4'].map(id => document.getElementById(id));
    bars.forEach(b => { b.className = 'pwd-bar'; });
    let s = 0;
    if (val.length >= 8) s++;
    if (/[A-Z]/.test(val)) s++;
    if (/[0-9]/.test(val)) s++;
    if (/[^A-Za-z0-9]/.test(val)) s++;
    const cls = s <= 1 ? 'weak' : s === 2 ? 'medium' : 'strong';
    for (let i = 0; i < s; i++) bars[i].classList.add(cls);
});

document.getElementById('pwd2').addEventListener('input', function() {
    const msg = document.getElementById('matchMsg');
    const v1 = document.getElementById('pwd').value;
    if (!this.value) { msg.style.display = 'none'; return; }
    msg.style.display = 'block';
    if (v1 === this.value) {
        msg.style.color = '#22c55e';
        msg.textContent = '✓ Mots de passe identiques';
    } else {
        msg.style.color = '#ef4444';
        msg.textContent = '✗ Mots de passe différents';
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
