<?php
$pageTitle = 'Connexion — Darou Salam Business Company';
$activePage = 'compte';

// Inject custom body class before header
$_bodyClass = 'auth-page';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
/* ===== Reset for auth page ===== */
html, body.auth-page {
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
    height: 100% !important;
}
body.auth-page #top-bar,
body.auth-page #main-nav {
    display: none !important;
}

/* ===== AUTH FULL SCREEN ===== */
.auth-screen {
    display: flex;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    z-index: 9999;
}

/* LEFT PANEL */
.auth-left {
    flex: 1;
    background: linear-gradient(160deg, #0f2d16 0%, #1a5c2a 55%, #2d8a42 100%);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 40px 56px 48px;
}
.auth-left::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('/darousalam/photos/PHOTO-2026-05-14-21-57-40.jpg') center/cover no-repeat;
    opacity: .35;
}
.auth-left::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, rgba(15,45,22,.2) 0%, rgba(15,45,22,.6) 100%);
}
.auth-left > * { position: relative; z-index: 2; }

/* Logo top-left */
.auth-brand {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
}
.auth-brand img {
    width: 80px; height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #d4a017;
}
.auth-brand-text .name {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    font-size: 1.25rem;
    color: #fff;
    display: block;
    line-height: 1.2;
}
.auth-brand-text .tag {
    font-size: .72rem;
    letter-spacing: .14em;
    text-transform: uppercase;
    color: #d4a017;
}

/* Main content */
.auth-left-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 20px 0; }

.auth-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212,160,23,.18);
    border: 1px solid rgba(212,160,23,.4);
    color: #d4a017;
    border-radius: 50px;
    padding: 6px 18px;
    font-size: .73rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    margin-bottom: 22px;
    width: fit-content;
}
.auth-left h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.6rem;
    font-weight: 900;
    color: #fff;
    line-height: 1.15;
    margin-bottom: 16px;
}
.auth-left p.lead {
    color: rgba(255,255,255,.7);
    font-size: .93rem;
    line-height: 1.7;
    max-width: 420px;
    margin-bottom: 32px;
}
.auth-perks {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-bottom: 36px;
}
.auth-perk {
    display: flex;
    align-items: center;
    gap: 14px;
    color: rgba(255,255,255,.85);
    font-size: .87rem;
}
.auth-perk-icon {
    width: 40px; height: 40px;
    border-radius: 11px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.05rem;
    color: #d4a017;
    flex-shrink: 0;
}
.auth-perk-body strong { display: block; color: #fff; font-size: .87rem; margin-bottom: 1px; }
.auth-perk-body span  { font-size: .78rem; color: rgba(255,255,255,.6); }

/* Trust */
.auth-trust {
    display: flex;
    align-items: center;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid rgba(255,255,255,.12);
}
.trust-avatars { display: flex; }
.trust-avatars span {
    width: 36px; height: 36px;
    border-radius: 50%;
    background: #d4a017;
    border: 2px solid #1a5c2a;
    color: #0f2d16;
    font-size: .72rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    margin-left: -8px;
}
.trust-avatars span:first-child { margin-left: 0; }
.trust-text { font-size: .8rem; color: rgba(255,255,255,.65); line-height: 1.5; }
.trust-text strong { color: #fff; }

/* RIGHT PANEL */
.auth-right {
    width: 520px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 0 52px;
    overflow-y: auto;
}
.auth-right-inner { max-width: 380px; width: 100%; margin: 0 auto; }

/* Back link */
.auth-back {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    color: #374151;
    font-size: .8rem;
    font-weight: 600;
    text-decoration: none;
    margin-bottom: 32px;
    background: #f4f4f0;
    border: 1.5px solid #e5e7eb;
    border-radius: 30px;
    padding: 7px 16px 7px 12px;
    transition: all .2s;
}
.auth-back i {
    width: 22px; height: 22px;
    background: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: .75rem;
    color: #1a5c2a;
    border: 1px solid #e5e7eb;
    flex-shrink: 0;
}
.auth-back:hover {
    background: #f0fdf4;
    border-color: #bbf7d0;
    color: #1a5c2a;
}

/* Title */
.auth-title {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    color: #0f2d16;
    margin-bottom: 4px;
}
.auth-desc { font-size: .86rem; color: #6b7280; margin-bottom: 28px; }
.auth-desc a { color: #1a5c2a; font-weight: 700; text-decoration: none; }

/* Tabs */
.auth-tabs {
    display: flex;
    background: #f0fdf4;
    border-radius: 14px;
    padding: 5px;
    margin-bottom: 28px;
    gap: 5px;
    border: 1.5px solid #bbf7d0;
}
.auth-tab {
    flex: 1; text-align: center;
    padding: 10px 12px;
    border-radius: 10px;
    font-weight: 700; font-size: .84rem;
    text-decoration: none; color: #6b7280;
    transition: all .25s;
    display: flex; align-items: center; justify-content: center; gap: 6px;
    letter-spacing: .02em;
}
.auth-tab:hover:not(.active) {
    background: rgba(26,92,42,.07);
    color: #1a5c2a;
}
.auth-tab.active {
    background: #1a5c2a;
    color: #fff;
    box-shadow: 0 4px 14px rgba(26,92,42,.35);
}

/* Alert */
.auth-alert {
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    border-radius: 10px;
    padding: 11px 14px;
    font-size: .83rem;
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 18px;
}

/* Fields */
.f-group { margin-bottom: 16px; }
.f-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 6px;
}
.f-label label { font-size: .8rem; font-weight: 600; color: #374151; }
.f-label a { font-size: .76rem; color: #1a5c2a; font-weight: 600; text-decoration: none; }
.f-label a:hover { text-decoration: underline; }
.f-wrap { position: relative; }
.f-icon {
    position: absolute; left: 13px; top: 50%;
    transform: translateY(-50%);
    color: #9ca3af; font-size: .95rem;
    pointer-events: none;
}
.f-wrap input {
    width: 100%;
    padding: 12px 40px 12px 40px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: .88rem;
    font-family: 'DM Sans', sans-serif;
    color: #0f2d16;
    background: #fafafa;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.f-wrap input:focus {
    outline: none;
    border-color: #1a5c2a;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(26,92,42,.09);
}
.f-end-btn {
    position: absolute; right: 11px; top: 50%;
    transform: translateY(-50%);
    background: none; border: none;
    color: #9ca3af; cursor: pointer; padding: 0;
    font-size: .95rem;
    transition: color .2s;
}
.f-end-btn:hover { color: #374151; }

/* Remember */
.f-check {
    display: flex; align-items: center; gap: 8px;
    margin-bottom: 20px;
}
.f-check input { width: 15px; height: 15px; accent-color: #1a5c2a; cursor: pointer; }
.f-check label { font-size: .81rem; color: #6b7280; cursor: pointer; }

/* Btn */
.btn-auth {
    width: 100%;
    padding: 13px;
    background: #1a5c2a;
    color: #fff;
    border: none;
    border-radius: 12px;
    font-size: .93rem;
    font-weight: 700;
    font-family: 'DM Sans', sans-serif;
    cursor: pointer;
    display: flex; align-items: center;
    justify-content: center; gap: 8px;
    letter-spacing: .03em;
    transition: all .25s;
}
.btn-auth:hover {
    background: #2d8a42;
    transform: translateY(-1px);
    box-shadow: 0 8px 24px rgba(26,92,42,.28);
}
.btn-auth:active { transform: translateY(0); }

/* Divider */
.auth-divider {
    display: flex; align-items: center; gap: 12px;
    margin: 20px 0; color: #9ca3af;
}
.auth-divider::before, .auth-divider::after {
    content: ''; flex: 1; height: 1px; background: #e5e7eb;
}
.auth-divider span { font-size: .78rem; white-space: nowrap; }

/* Quick actions */
.auth-quick { display: flex; gap: 10px; }
.btn-quick {
    flex: 1;
    display: flex; align-items: center;
    justify-content: center; gap: 7px;
    padding: 10px;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    font-size: .8rem; font-weight: 600;
    color: #374151; text-decoration: none;
    background: #fff;
    transition: all .2s; cursor: pointer;
}
.btn-quick:hover { border-color: #1a5c2a; background: #f0fdf4; color: #1a5c2a; }
.btn-quick.wa:hover { border-color: #25d366; background: #f0fdf4; color: #15803d; }

/* Security strip */
.auth-security {
    display: flex; gap: 0;
    background: #f9fafb;
    border: 1px solid #f3f4f6;
    border-radius: 10px;
    margin-top: 16px;
    overflow: hidden;
}
.auth-security-item {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    gap: 4px;
    padding: 10px 8px;
    font-size: .7rem; color: #6b7280;
    text-align: center;
    border-right: 1px solid #f3f4f6;
}
.auth-security-item:last-child { border-right: none; }
.auth-security-item i { font-size: 1rem; color: #1a5c2a; }

/* Switch */
.auth-switch {
    text-align: center;
    margin-top: 22px;
    font-size: .82rem;
    color: #9ca3af;
}
.auth-switch a { color: #1a5c2a; font-weight: 700; text-decoration: none; }
.auth-switch a:hover { text-decoration: underline; }

@media (max-width: 900px) {
    body.auth-page { overflow: auto; }
    .auth-screen { flex-direction: column; height: auto; min-height: 100vh; }
    .auth-left { display: none; }
    .auth-right { width: 100%; padding: 40px 24px; }
}
</style>

<script>document.documentElement.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';document.body.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';</script>
<div class="auth-screen">

    <!-- ===== LEFT ===== -->
    <div class="auth-left">
        <!-- Brand -->
        <a class="auth-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Logo">
            <div class="auth-brand-text">
                <span class="name">Darou Salam</span>
                <span class="tag">Business Company</span>
            </div>
        </a>

        <!-- Body -->
        <div class="auth-left-body">
            <div class="auth-badge">
                <i class="bi bi-shield-check"></i>
                Espace client sécurisé
            </div>
            <h2>Bienvenue chez<br>Darou Salam</h2>
            <p class="lead">Accédez à votre espace personnel pour gérer vos commandes, suivre vos livraisons et profiter de nos offres exclusives.</p>

            <div class="auth-perks">
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="auth-perk-body">
                        <strong>Suivi en temps réel</strong>
                        <span>Commandes et livraisons suivies à chaque étape</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-tag-fill"></i></div>
                    <div class="auth-perk-body">
                        <strong>Tarifs préférentiels</strong>
                        <span>Prix grossiste réservés aux clients enregistrés</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-heart-fill"></i></div>
                    <div class="auth-perk-body">
                        <strong>Liste de favoris</strong>
                        <span>Retrouvez rapidement vos fruits préférés</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-headset"></i></div>
                    <div class="auth-perk-body">
                        <strong>Support dédié 7j/7</strong>
                        <span>Une équipe disponible pour vous aider</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Trust -->
        <div class="auth-trust">
            <div class="trust-avatars">
                <span>AM</span><span>FD</span><span>KS</span><span>+</span>
            </div>
            <div class="trust-text">
                <strong>+200 clients</strong> nous font confiance<br>
                Supermarchés, hôtels, restaurateurs
            </div>
        </div>
    </div>

    <!-- ===== RIGHT ===== -->
    <div class="auth-right">
        <div class="auth-right-inner">
            <a href="/darousalam/" class="auth-back">
                <i class="bi bi-arrow-left"></i> Retour à l'accueil
            </a>

            <h1 class="auth-title">Connexion</h1>
            <p class="auth-desc">Pas encore de compte ? <a href="<?= BASE_URL ?>?page=inscription">Créer un compte gratuitement</a></p>

            <div class="auth-tabs">
                <a href="<?= BASE_URL ?>?page=login" class="auth-tab active">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </a>
                <a href="<?= BASE_URL ?>?page=inscription" class="auth-tab">
                    <i class="bi bi-person-plus-fill"></i> Créer un compte
                </a>
            </div>

            <?php if ($error): ?>
            <div class="auth-alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>?page=login" novalidate>
                <!-- Email -->
                <div class="f-group">
                    <div class="f-label"><label for="email">Adresse email</label></div>
                    <div class="f-wrap">
                        <i class="bi bi-envelope f-icon"></i>
                        <input type="email" id="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="exemple@email.com" required autofocus>
                    </div>
                </div>

                <!-- Mot de passe -->
                <div class="f-group">
                    <div class="f-label">
                        <label for="password">Mot de passe</label>
                        <a href="<?= BASE_URL ?>?page=mot_de_passe_oublie">Mot de passe oublié ?</a>
                    </div>
                    <div class="f-wrap">
                        <i class="bi bi-lock f-icon"></i>
                        <input type="password" id="password" name="password" placeholder="••••••••" required>
                        <button type="button" class="f-end-btn" onclick="togglePwd()" tabindex="-1">
                            <i class="bi bi-eye" id="eye-icon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember -->
                <div class="f-check">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Rester connecté pendant 30 jours</label>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Se connecter à mon espace
                </button>
            </form>

            <div class="auth-divider"><span>ou accéder rapidement via</span></div>

            <div class="auth-quick">
                <a href="https://wa.me/221774715353?text=Bonjour%2C%20je%20voudrais%20commander" target="_blank" class="btn-quick wa">
                    <i class="bi bi-whatsapp" style="color:#25d366;font-size:1rem;"></i>
                    WhatsApp
                </a>
                <a href="tel:+221774715353" class="btn-quick">
                    <i class="bi bi-telephone-fill" style="color:#1a5c2a;font-size:.95rem;"></i>
                    Nous appeler
                </a>
                <a href="<?= BASE_URL ?>?page=catalogue" class="btn-quick">
                    <i class="bi bi-grid-fill" style="color:#f97316;font-size:.95rem;"></i>
                    Catalogue
                </a>
            </div>

            <div class="auth-security">
                <div class="auth-security-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    Connexion SSL
                </div>
                <div class="auth-security-item">
                    <i class="bi bi-eye-slash-fill"></i>
                    Données privées
                </div>
                <div class="auth-security-item">
                    <i class="bi bi-patch-check-fill"></i>
                    Compte vérifié
                </div>
            </div>

            <div class="auth-switch">
                Nouveau client ? <a href="<?= BASE_URL ?>?page=inscription">Créer mon compte &rarr;</a>
            </div>
        </div>
    </div>

</div>

<script>
function togglePwd() {
    const inp = document.getElementById('password');
    const icon = document.getElementById('eye-icon');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
