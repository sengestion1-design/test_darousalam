<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Administration — Darou Salam Business Company</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
html, body { height:100%; overflow:hidden; font-family:'DM Sans',sans-serif; }

.admin-screen {
    display:flex;
    width:100vw; height:100vh;
}

/* LEFT — sombre premium */
.admin-left {
    flex:1;
    background:linear-gradient(160deg, #050d07 0%, #0f2d16 40%, #1a5c2a 100%);
    position:relative; overflow:hidden;
    display:flex; flex-direction:column;
    justify-content:space-between;
    padding:44px 60px 52px;
}
.admin-left::before {
    content:'';
    position:absolute; inset:0;
    background:
        radial-gradient(ellipse at 20% 50%, rgba(26,92,42,.4) 0%, transparent 60%),
        radial-gradient(ellipse at 80% 20%, rgba(212,160,23,.08) 0%, transparent 50%);
}
/* Grid décoratif */
.admin-left::after {
    content:'';
    position:absolute; inset:0;
    background-image:
        linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
    background-size:40px 40px;
}
.admin-left > * { position:relative; z-index:2; }

.admin-brand {
    display:flex; align-items:center; gap:12px; text-decoration:none;
}
.admin-brand img {
    width:48px; height:48px; border-radius:50%;
    object-fit:cover; border:2px solid #d4a017;
    box-shadow:0 0 0 4px rgba(212,160,23,.15);
}
.admin-brand .name {
    font-family:'Playfair Display',serif;
    font-weight:700; font-size:1.05rem; color:#fff; display:block;
}
.admin-brand .tag {
    font-size:.62rem; letter-spacing:.15em;
    text-transform:uppercase; color:#d4a017;
}

.admin-left-body { flex:1; display:flex; flex-direction:column; justify-content:center; padding:20px 0; }

.admin-role-badge {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(239,68,68,.12); border:1px solid rgba(239,68,68,.3);
    color:#fca5a5; border-radius:6px;
    padding:5px 14px; font-size:.72rem; font-weight:700;
    letter-spacing:.1em; text-transform:uppercase;
    margin-bottom:24px; width:fit-content;
}
.admin-left h2 {
    font-family:'Playfair Display',serif;
    font-size:2.5rem; font-weight:900; color:#fff;
    line-height:1.15; margin-bottom:14px;
}
.admin-left h2 span { color:#d4a017; }
.admin-left p.lead {
    color:rgba(255,255,255,.55);
    font-size:.9rem; line-height:1.7;
    max-width:400px; margin-bottom:36px;
}

.admin-features { display:flex; flex-direction:column; gap:16px; }
.admin-feature {
    display:flex; align-items:center; gap:14px;
    padding:14px 16px;
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.07);
    border-radius:12px;
}
.admin-feature-icon {
    width:40px; height:40px; border-radius:10px;
    display:flex; align-items:center; justify-content:center;
    font-size:1rem; flex-shrink:0;
}
.admin-feature strong { display:block; color:#fff; font-size:.86rem; margin-bottom:1px; }
.admin-feature span { font-size:.76rem; color:rgba(255,255,255,.45); }

.admin-footer-strip {
    display:flex; align-items:center; gap:16px;
    padding-top:24px;
    border-top:1px solid rgba(255,255,255,.08);
    font-size:.76rem; color:rgba(255,255,255,.35);
}
.admin-footer-strip i { color:rgba(255,255,255,.2); }

/* RIGHT — formulaire */
.admin-right {
    width:460px; flex-shrink:0;
    background:#fff;
    display:flex; flex-direction:column;
    justify-content:center;
    padding:0 52px;
    overflow-y:auto;
}
.admin-right-inner { max-width:360px; width:100%; margin:0 auto; }

/* Icône centrale */
.admin-icon {
    width:60px; height:60px; border-radius:16px;
    background:linear-gradient(135deg,#0f2d16,#1a5c2a);
    display:flex; align-items:center; justify-content:center;
    font-size:1.6rem; color:#fff;
    margin-bottom:22px;
    box-shadow:0 8px 24px rgba(15,45,22,.3);
}
.admin-title {
    font-family:'Playfair Display',serif;
    font-size:1.8rem; font-weight:900; color:#0f2d16;
    margin-bottom:4px;
}
.admin-subtitle { font-size:.84rem; color:#9ca3af; margin-bottom:30px; }

/* Alert */
.admin-alert {
    background:#fef2f2; border:1px solid #fecaca;
    color:#b91c1c; border-radius:10px;
    padding:11px 14px; font-size:.83rem;
    display:flex; align-items:center; gap:10px;
    margin-bottom:18px;
}

/* Champs */
.f-group { margin-bottom:18px; }
.f-label { font-size:.78rem; font-weight:700; color:#374151; display:block; margin-bottom:6px; letter-spacing:.03em; }
.f-wrap { position:relative; }
.f-icon { position:absolute; left:13px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.95rem; pointer-events:none; }
.f-wrap input {
    width:100%; padding:12px 14px 12px 40px;
    border:1.5px solid #e5e7eb; border-radius:10px;
    font-size:.88rem; font-family:'DM Sans',sans-serif;
    color:#0f2d16; background:#fafafa;
    transition:all .2s;
}
.f-wrap input:focus {
    outline:none; border-color:#1a5c2a;
    background:#fff; box-shadow:0 0 0 3px rgba(26,92,42,.08);
}
.f-end-btn {
    position:absolute; right:11px; top:50%; transform:translateY(-50%);
    background:none; border:none; color:#9ca3af; cursor:pointer; padding:0; font-size:.95rem;
}

/* Bouton connexion */
.btn-admin-login {
    width:100%; padding:13px;
    background:linear-gradient(135deg,#0f2d16,#1a5c2a);
    color:#fff; border:none; border-radius:12px;
    font-size:.93rem; font-weight:700;
    font-family:'DM Sans',sans-serif; cursor:pointer;
    display:flex; align-items:center; justify-content:center; gap:8px;
    letter-spacing:.03em; transition:all .25s;
}
.btn-admin-login:hover {
    background:linear-gradient(135deg,#1a5c2a,#2d8a42);
    transform:translateY(-1px);
    box-shadow:0 8px 24px rgba(15,45,22,.35);
}

/* Sécurité strip */
.security-strip {
    display:flex; gap:0; margin-top:20px;
    background:#f9fafb; border:1px solid #f3f4f6;
    border-radius:10px; overflow:hidden;
}
.security-item {
    flex:1; display:flex; flex-direction:column;
    align-items:center; justify-content:center; gap:3px;
    padding:10px 8px; font-size:.68rem; color:#9ca3af;
    text-align:center; border-right:1px solid #f3f4f6;
}
.security-item:last-child { border-right:none; }
.security-item i { font-size:.95rem; color:#1a5c2a; }

/* Lien retour */
.admin-back {
    display:inline-flex; align-items:center; gap:6px;
    color:#9ca3af; font-size:.78rem; text-decoration:none;
    margin-bottom:28px; transition:color .2s;
}
.admin-back:hover { color:#1a5c2a; }

@media (max-width:860px) {
    .admin-left { display:none; }
    .admin-right { width:100%; padding:40px 24px; }
}
</style>
</head>
<body>

<div class="admin-screen">

    <!-- LEFT -->
    <div class="admin-left">
        <a class="admin-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Logo">
            <div>
                <span class="name">Darou Salam</span>
                <span class="tag">Business Company</span>
            </div>
        </a>

        <div class="admin-left-body">
            <div class="admin-role-badge">
                <i class="bi bi-shield-fill-exclamation"></i>
                Accès restreint — Administration
            </div>
            <h2>Panneau<br><span>d'administration</span></h2>
            <p class="lead">
                Espace réservé aux administrateurs autorisés. Gérez les commandes, clients, produits et stocks de Darou Salam Business Company.
            </p>

            <div class="admin-features">
                <div class="admin-feature">
                    <div class="admin-feature-icon" style="background:rgba(59,130,246,.15);color:#60a5fa;">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div>
                        <strong>Tableau de bord temps réel</strong>
                        <span>Chiffre d'affaires, commandes, alertes stock</span>
                    </div>
                </div>
                <div class="admin-feature">
                    <div class="admin-feature-icon" style="background:rgba(249,115,22,.15);color:#fb923c;">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div>
                        <strong>Gestion des commandes</strong>
                        <span>Suivi, validation et livraisons</span>
                    </div>
                </div>
                <div class="admin-feature">
                    <div class="admin-feature-icon" style="background:rgba(212,160,23,.15);color:#d4a017;">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <strong>Gestion des stocks</strong>
                        <span>Alertes de réapprovisionnement automatiques</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-footer-strip">
            <i class="bi bi-lock-fill"></i>
            Connexion sécurisée · Session chiffrée
            <span style="margin-left:auto;">v2.0</span>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="admin-right">
        <div class="admin-right-inner">
            <a href="/darousalam/" class="admin-back">
                <i class="bi bi-arrow-left"></i> Retour au site
            </a>

            <div class="admin-icon">
                <i class="bi bi-shield-lock-fill"></i>
            </div>

            <h1 class="admin-title">Administration</h1>
            <p class="admin-subtitle">Connectez-vous avec vos identifiants administrateur</p>

            <?php if (!empty($error)): ?>
            <div class="admin-alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>?page=admin_login" novalidate>
                <div class="f-group">
                    <label class="f-label">Adresse email</label>
                    <div class="f-wrap">
                        <i class="bi bi-envelope f-icon"></i>
                        <input type="email" name="email"
                               value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                               placeholder="admin@darousalam.com" required autofocus>
                    </div>
                </div>

                <div class="f-group">
                    <label class="f-label">Mot de passe</label>
                    <div class="f-wrap" style="position:relative;">
                        <i class="bi bi-lock f-icon"></i>
                        <input type="password" name="password" id="apwd" placeholder="••••••••" required>
                        <button type="button" class="f-end-btn" onclick="togglePwd()" tabindex="-1">
                            <i class="bi bi-eye" id="aeye"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-admin-login">
                    <i class="bi bi-shield-check"></i>
                    Accéder au panneau admin
                </button>
            </form>

            <div class="security-strip">
                <div class="security-item">
                    <i class="bi bi-shield-lock-fill"></i>
                    SSL sécurisé
                </div>
                <div class="security-item">
                    <i class="bi bi-eye-slash-fill"></i>
                    Accès privé
                </div>
                <div class="security-item">
                    <i class="bi bi-clock-history"></i>
                    Session limitée
                </div>
            </div>

            <div style="margin-top:24px;padding:12px 14px;background:#fffbeb;border:1px solid #fde68a;border-radius:10px;font-size:.76rem;color:#92400e;line-height:1.6;">
                <i class="bi bi-info-circle-fill me-1"></i>
                <strong>Identifiants par défaut :</strong><br>
                Email : <code>admin@darousalam.com</code><br>
                Mot de passe : <code>senegal</code>
            </div>
        </div>
    </div>

</div>

<script>
function togglePwd() {
    const inp = document.getElementById('apwd');
    const icon = document.getElementById('aeye');
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}
</script>
</body>
</html>
