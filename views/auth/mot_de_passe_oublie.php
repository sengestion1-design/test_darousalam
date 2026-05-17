<?php
$pageTitle = 'Mot de passe oublié — Darou Salam Business Company';
$_bodyClass = 'auth-page';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
html, body.auth-page { margin:0!important; padding:0!important; overflow:hidden!important; height:100%!important; }
body.auth-page #top-bar, body.auth-page #main-nav { display:none!important; }

.auth-screen {
    display: flex;
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    width:100%; height:100%;
    overflow:hidden; z-index:9999;
}
.auth-left {
    flex:1;
    background: linear-gradient(160deg,#0f2d16 0%,#1a5c2a 55%,#2d8a42 100%);
    position:relative; overflow:hidden;
    display:flex; flex-direction:column;
    justify-content:space-between; padding:40px 56px 48px;
}
.auth-left::before {
    content:''; position:absolute; inset:0;
    background:url('/darousalam/photos/PHOTO-2026-05-14-21-57-40.jpg') center/cover no-repeat;
    opacity:.35;
}
.auth-left::after {
    content:''; position:absolute; inset:0;
    background:linear-gradient(to bottom,rgba(15,45,22,.2) 0%,rgba(15,45,22,.6) 100%);
}
.auth-left > * { position:relative; z-index:2; }
.auth-brand { display:flex; align-items:center; gap:12px; text-decoration:none; }
.auth-brand img { width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #d4a017; }
.auth-brand .name { font-family:'Playfair Display',serif;font-weight:700;font-size:1.05rem;color:#fff;display:block;line-height:1.2; }
.auth-brand .tag  { font-size:.65rem;letter-spacing:.14em;text-transform:uppercase;color:#d4a017; }
.auth-left-body { flex:1;display:flex;flex-direction:column;justify-content:center; }
.auth-left h2 { font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:900;color:#fff;line-height:1.15;margin-bottom:16px; }
.auth-left p.lead { color:rgba(255,255,255,.7);font-size:.92rem;line-height:1.7;max-width:420px;margin-bottom:28px; }
.auth-steps { display:flex;flex-direction:column;gap:16px; }
.auth-step { display:flex;align-items:center;gap:14px;color:rgba(255,255,255,.85);font-size:.87rem; }
.step-num { width:32px;height:32px;border-radius:50%;background:rgba(212,160,23,.25);border:1.5px solid rgba(212,160,23,.5);color:#d4a017;font-weight:800;font-size:.88rem;display:flex;align-items:center;justify-content:center;flex-shrink:0; }
.step-body strong { display:block;color:#fff;font-size:.87rem; }
.step-body span { font-size:.78rem;color:rgba(255,255,255,.58); }

/* RIGHT */
.auth-right { width:520px;flex-shrink:0;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:0 52px;overflow-y:auto; }
.auth-right-inner { max-width:400px;width:100%;margin:0 auto; }
.auth-back { display:inline-flex;align-items:center;gap:6px;color:#9ca3af;font-size:.78rem;text-decoration:none;margin-bottom:28px;transition:color .2s; }
.auth-back:hover { color:#1a5c2a; }

.auth-icon-circle {
    width:64px;height:64px;border-radius:50%;
    background:linear-gradient(135deg,#f0fdf4,#dcfce7);
    border:2px solid #bbf7d0;
    display:flex;align-items:center;justify-content:center;
    font-size:1.8rem;color:#1a5c2a;
    margin-bottom:20px;
}
.auth-title { font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:800;color:#0f2d16;margin-bottom:6px; }
.auth-desc { font-size:.86rem;color:#6b7280;margin-bottom:28px;line-height:1.6; }

.f-group { margin-bottom:18px; }
.f-label label { display:block;font-size:.8rem;font-weight:600;color:#374151;margin-bottom:6px; }
.f-wrap { position:relative; }
.f-icon { position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.95rem;pointer-events:none; }
.f-wrap input { width:100%;padding:12px 14px 12px 40px;border:1.5px solid #e5e7eb;border-radius:10px;font-size:.88rem;font-family:'DM Sans',sans-serif;color:#0f2d16;background:#fafafa;transition:all .2s; }
.f-wrap input:focus { outline:none;border-color:#1a5c2a;background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.09); }

.btn-auth { width:100%;padding:13px;background:#1a5c2a;color:#fff;border:none;border-radius:12px;font-size:.93rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;letter-spacing:.03em;transition:all .25s; }
.btn-auth:hover { background:#2d8a42;transform:translateY(-1px);box-shadow:0 8px 24px rgba(26,92,42,.28); }

.auth-alert-success { background:#f0fdf4;border:1px solid #bbf7d0;color:#15803d;border-radius:12px;padding:14px 16px;font-size:.86rem;display:flex;align-items:flex-start;gap:10px;margin-bottom:20px; }
.auth-alert-error   { background:#fef2f2;border:1px solid #fecaca;color:#b91c1c;border-radius:12px;padding:14px 16px;font-size:.86rm;display:flex;align-items:center;gap:10px;margin-bottom:20px; }
.auth-alert-success i, .auth-alert-error i { font-size:1.1rem;flex-shrink:0;margin-top:1px; }

/* Lien de dev visible */
.dev-link { background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:12px 14px;margin-top:12px;font-size:.8rem;color:#92400e;word-break:break-all; }
.dev-link strong { display:block;margin-bottom:4px;color:#78350f; }
.dev-link a { color:#1a5c2a;font-weight:700; }

.auth-switch { text-align:center;margin-top:24px;font-size:.82rem;color:#9ca3af; }
.auth-switch a { color:#1a5c2a;font-weight:700;text-decoration:none; }

@media (max-width:900px) {
    .auth-left { display:none; }
    .auth-right { width:100%;padding:40px 24px; }
}
</style>

<script>document.documentElement.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';document.body.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';</script>

<div class="auth-screen">

    <!-- LEFT -->
    <div class="auth-left">
        <a class="auth-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Logo">
            <div><span class="name">Darou Salam</span><span class="tag">Business Company</span></div>
        </a>
        <div class="auth-left-body">
            <h2>Réinitialisez<br>votre mot de passe</h2>
            <p class="lead">Pas d'inquiétude, ça arrive à tout le monde. Suivez ces 3 étapes simples pour récupérer l'accès à votre compte.</p>
            <div class="auth-steps">
                <div class="auth-step">
                    <div class="step-num">1</div>
                    <div class="step-body"><strong>Saisissez votre email</strong><span>L'adresse utilisée lors de votre inscription</span></div>
                </div>
                <div class="auth-step">
                    <div class="step-num">2</div>
                    <div class="step-body"><strong>Consultez votre boîte mail</strong><span>Un lien sécurisé vous sera envoyé (valable 1h)</span></div>
                </div>
                <div class="auth-step">
                    <div class="step-num">3</div>
                    <div class="step-body"><strong>Choisissez un nouveau mot de passe</strong><span>Et reconnectez-vous normalement</span></div>
                </div>
            </div>
        </div>
        <div style="padding-top:20px;border-top:1px solid rgba(255,255,255,.12);font-size:.8rem;color:rgba(255,255,255,.5);">
            Besoin d'aide ? <a href="<?= BASE_URL ?>?page=contact" style="color:#d4a017;">Contactez-nous</a>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-right-inner">
            <a href="<?= BASE_URL ?>?page=login" class="auth-back"><i class="bi bi-arrow-left"></i> Retour à la connexion</a>

            <div class="auth-icon-circle"><i class="bi bi-shield-lock"></i></div>
            <h1 class="auth-title">Mot de passe oublié</h1>
            <p class="auth-desc">Saisissez votre adresse email et nous vous enverrons un lien pour réinitialiser votre mot de passe.</p>

            <?php if ($success): ?>
                <div class="auth-alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    <div>
                        <strong style="display:block;margin-bottom:2px;">Email envoyé !</strong>
                        <?= htmlspecialchars($success) ?>
                    </div>
                </div>
                <?php if ($resetLink): ?>
                <div class="dev-link">
                    <strong><i class="bi bi-info-circle"></i> Lien de réinitialisation (mode local) :</strong>
                    <a href="<?= htmlspecialchars($resetLink) ?>"><?= htmlspecialchars($resetLink) ?></a>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="auth-alert-error">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!$success): ?>
            <form method="POST" action="<?= BASE_URL ?>?page=mot_de_passe_oublie">
                <div class="f-group">
                    <div class="f-label"><label for="email">Adresse email</label></div>
                    <div class="f-wrap">
                        <i class="bi bi-envelope f-icon"></i>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="exemple@email.com" required autofocus>
                    </div>
                </div>
                <button type="submit" class="btn-auth">
                    <i class="bi bi-send-fill"></i>
                    Envoyer le lien de réinitialisation
                </button>
            </form>
            <?php else: ?>
            <a href="<?= BASE_URL ?>?page=mot_de_passe_oublie" class="btn-auth" style="text-decoration:none;">
                <i class="bi bi-arrow-clockwise"></i> Faire une nouvelle demande
            </a>
            <?php endif; ?>

            <div class="auth-switch">
                Vous vous souvenez ? <a href="<?= BASE_URL ?>?page=login">Se connecter &rarr;</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
