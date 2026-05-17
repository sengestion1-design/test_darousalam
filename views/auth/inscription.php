<?php
$pageTitle = 'Créer un compte — Darou Salam Business Company';
$activePage = 'compte';
$_bodyClass = 'auth-page';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
html, body.auth-page {
    margin: 0 !important;
    padding: 0 !important;
    overflow: hidden !important;
    height: 100% !important;
}
body.auth-page #top-bar, body.auth-page #main-nav { display: none !important; }

.auth-screen {
    display: flex;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    width: 100%; height: 100%;
    overflow: hidden;
    z-index: 9999;
}

/* LEFT */
.auth-left {
    flex: 1;
    background: linear-gradient(160deg, #0f2d16 0%, #1a5c2a 55%, #2d8a42 100%);
    position: relative; overflow: hidden;
    display: flex; flex-direction: column;
    justify-content: space-between;
    padding: 40px 56px 48px;
}
.auth-left::before {
    content: ''; position: absolute; inset: 0;
    background: url('/darousalam/photos/PHOTO-2026-05-14-22-41-06.jpg') center/cover no-repeat;
    opacity: .35;
}
.auth-left::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(to bottom, rgba(15,45,22,.2) 0%, rgba(15,45,22,.6) 100%);
}
.auth-left > * { position: relative; z-index: 2; }

.auth-brand { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.auth-brand img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 2px solid #d4a017; }
.auth-brand .name { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.25rem; color: #fff; display: block; line-height: 1.2; }
.auth-brand .tag  { font-size: .72rem; letter-spacing: .14em; text-transform: uppercase; color: #d4a017; }

.auth-left-body { flex: 1; display: flex; flex-direction: column; justify-content: center; padding: 20px 0; }

.auth-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(212,160,23,.18); border: 1px solid rgba(212,160,23,.4);
    color: #d4a017; border-radius: 50px; padding: 6px 18px;
    font-size: .73rem; font-weight: 700; letter-spacing: .1em;
    text-transform: uppercase; margin-bottom: 22px; width: fit-content;
}
.auth-left h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2.4rem; font-weight: 900; color: #fff; line-height: 1.15; margin-bottom: 14px;
}
.auth-left p.lead { color: rgba(255,255,255,.7); font-size: .91rem; line-height: 1.7; max-width: 420px; margin-bottom: 28px; }

.auth-perks { display: flex; flex-direction: column; gap: 14px; margin-bottom: 28px; }
.auth-perk { display: flex; align-items: center; gap: 14px; color: rgba(255,255,255,.85); font-size: .86rem; }
.auth-perk-icon {
    width: 40px; height: 40px; border-radius: 11px;
    background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.12);
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; color: #d4a017; flex-shrink: 0;
}
.auth-perk-body strong { display: block; color: #fff; font-size: .86rem; margin-bottom: 1px; }
.auth-perk-body span  { font-size: .76rem; color: rgba(255,255,255,.58); }

/* Stats */
.auth-stats {
    display: flex; gap: 0;
    background: rgba(255,255,255,.07);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 14px; overflow: hidden;
    margin-bottom: 28px;
}
.auth-stat {
    flex: 1; text-align: center; padding: 14px 8px;
    border-right: 1px solid rgba(255,255,255,.1);
}
.auth-stat:last-child { border-right: none; }
.auth-stat strong { display: block; font-family: 'Playfair Display', serif; font-size: 1.35rem; font-weight: 900; color: #d4a017; }
.auth-stat span { font-size: .72rem; color: rgba(255,255,255,.6); }

.auth-trust { display: flex; align-items: center; gap: 16px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,.12); }
.trust-avatars { display: flex; }
.trust-avatars span { width: 34px; height: 34px; border-radius: 50%; background: #d4a017; border: 2px solid #1a5c2a; color: #0f2d16; font-size: .7rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin-left: -8px; }
.trust-avatars span:first-child { margin-left: 0; }
.trust-text { font-size: .78rem; color: rgba(255,255,255,.65); line-height: 1.5; }
.trust-text strong { color: #fff; }

/* RIGHT */
.auth-right {
    width: 540px; flex-shrink: 0;
    background: #fff;
    display: flex; flex-direction: column;
    justify-content: flex-start;
    padding: 0 48px;
    overflow-y: auto;
}
.auth-right-inner { max-width: 440px; width: 100%; margin: 0 auto; padding: 36px 0; }

.auth-back { display: inline-flex; align-items: center; gap: 7px; color: #374151; font-size: .8rem; font-weight: 600; text-decoration: none; margin-bottom: 24px; background: #f4f4f0; border: 1.5px solid #e5e7eb; border-radius: 30px; padding: 7px 16px 7px 12px; transition: all .2s; }
.auth-back i { width: 22px; height: 22px; background: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: .75rem; color: #1a5c2a; border: 1px solid #e5e7eb; flex-shrink: 0; }
.auth-back:hover { background: #f0fdf4; border-color: #bbf7d0; color: #1a5c2a; }
.auth-title { font-family: 'Playfair Display', serif; font-size: 1.75rem; font-weight: 800; color: #0f2d16; margin-bottom: 4px; }
.auth-desc { font-size: .84rem; color: #6b7280; margin-bottom: 22px; }
.auth-desc a { color: #1a5c2a; font-weight: 700; text-decoration: none; }

.auth-tabs { display: flex; background: #f0fdf4; border-radius: 14px; padding: 5px; margin-bottom: 22px; gap: 5px; border: 1.5px solid #bbf7d0; }
.auth-tab { flex: 1; text-align: center; padding: 10px 12px; border-radius: 10px; font-weight: 700; font-size: .84rem; text-decoration: none; color: #6b7280; transition: all .25s; display: flex; align-items: center; justify-content: center; gap: 6px; letter-spacing: .02em; }
.auth-tab:hover:not(.active) { background: rgba(26,92,42,.07); color: #1a5c2a; }
.auth-tab.active { background: #1a5c2a; color: #fff; box-shadow: 0 4px 14px rgba(26,92,42,.35); }

.auth-alert { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 10px; padding: 11px 14px; font-size: .82rem; display: flex; align-items: center; gap: 10px; margin-bottom: 16px; }

/* Type selector */
.type-selector { display: flex; gap: 10px; margin-bottom: 18px; }
.type-card {
    flex: 1; border: 1.5px solid #e5e7eb; border-radius: 12px;
    padding: 13px 12px; cursor: pointer; transition: all .2s;
    display: flex; align-items: center; gap: 10px;
    position: relative;
}
.type-card input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
.type-card-icon { width: 36px; height: 36px; border-radius: 9px; background: #f4f4f0; display: flex; align-items: center; justify-content: center; font-size: 1rem; color: #9ca3af; flex-shrink: 0; transition: all .2s; }
.type-card strong { display: block; font-size: .82rem; font-weight: 700; color: #374151; }
.type-card span { font-size: .72rem; color: #9ca3af; }
.type-card.selected, .type-card:hover { border-color: #1a5c2a; background: #f0fdf4; }
.type-card.selected .type-card-icon { background: #1a5c2a; color: #fff; }
.type-card.selected strong { color: #1a5c2a; }

/* Fields */
.f-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.f-group { margin-bottom: 12px; }
.f-label { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.f-label label { font-size: .78rem; font-weight: 600; color: #374151; }
.f-wrap { position: relative; }
.f-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: .9rem; pointer-events: none; }
.f-wrap input, .f-wrap select {
    width: 100%; padding: 10px 12px 10px 36px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: .84rem; font-family: 'DM Sans', sans-serif;
    color: #0f2d16; background: #fafafa;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.f-wrap input:focus, .f-wrap select:focus { outline: none; border-color: #1a5c2a; background: #fff; box-shadow: 0 0 0 3px rgba(26,92,42,.08); }
.f-end-btn { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #9ca3af; cursor: pointer; padding: 0; font-size: .9rem; }

/* Pro section */
.pro-box { background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px; padding: 16px; margin-bottom: 12px; }
.pro-box-title { font-size: .75rem; font-weight: 700; color: #1a5c2a; text-transform: uppercase; letter-spacing: .07em; margin-bottom: 12px; display: flex; align-items: center; gap: 6px; }

/* Password strength */
.pwd-bars { display: flex; gap: 3px; margin-top: 5px; }
.pwd-bar { flex: 1; height: 3px; border-radius: 3px; background: #e5e7eb; transition: background .3s; }
.pwd-bar.weak { background: #ef4444; }
.pwd-bar.medium { background: #f59e0b; }
.pwd-bar.strong { background: #22c55e; }
.pwd-match-msg { font-size: .73rem; margin-top: 4px; display: none; }

/* CGU */
.cgu-row { display: flex; gap: 9px; align-items: flex-start; margin: 14px 0; font-size: .78rem; color: #6b7280; }
.cgu-row input { width: 15px; height: 15px; accent-color: #1a5c2a; margin-top: 2px; flex-shrink: 0; cursor: pointer; }
.cgu-row a { color: #1a5c2a; font-weight: 600; }

/* Btn */
.btn-auth {
    width: 100%; padding: 13px; background: #1a5c2a; color: #fff;
    border: none; border-radius: 12px; font-size: .91rem; font-weight: 700;
    font-family: 'DM Sans', sans-serif; cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 8px;
    letter-spacing: .03em; transition: all .25s;
}
.btn-auth:hover { background: #2d8a42; transform: translateY(-1px); box-shadow: 0 8px 24px rgba(26,92,42,.28); }
.btn-auth:active { transform: translateY(0); }

.auth-switch { text-align: center; margin-top: 18px; font-size: .81rem; color: #9ca3af; }
.auth-switch a { color: #1a5c2a; font-weight: 700; text-decoration: none; }

@media (max-width: 900px) {
    body.auth-page { overflow: auto; }
    .auth-screen { flex-direction: column; height: auto; min-height: 100vh; }
    .auth-left { display: none; }
    .auth-right { width: 100%; padding: 36px 20px; }
    .f-row { grid-template-columns: 1fr; }
}
</style>

<script>document.documentElement.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';document.body.style.cssText='margin:0;padding:0;height:100%;overflow:hidden';</script>
<div class="auth-screen">

    <!-- LEFT -->
    <div class="auth-left">
        <a class="auth-brand" href="/darousalam/">
            <img src="/darousalam/logo.jpg" alt="Logo">
            <div>
                <span class="name">Darou Salam</span>
                <span class="tag">Business Company</span>
            </div>
        </a>

        <div class="auth-left-body">
            <div class="auth-badge"><i class="bi bi-person-plus-fill"></i> Inscription gratuite</div>
            <h2>Rejoignez notre<br>réseau de confiance</h2>
            <p class="lead">Des supermarchés aux hôtels 5 étoiles, nos clients professionnels bénéficient de tarifs privilégiés et d'une livraison express sur Dakar.</p>

            <div class="auth-perks">
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-percent"></i></div>
                    <div class="auth-perk-body">
                        <strong>Remises professionnelles</strong>
                        <span>Jusqu'à 15% sur les commandes en gros</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-truck"></i></div>
                    <div class="auth-perk-body">
                        <strong>Livraison prioritaire 24h</strong>
                        <span>Commandes préparées et livrées le lendemain</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <div class="auth-perk-body">
                        <strong>Historique & rapports</strong>
                        <span>Consultez vos achats et dépenses à tout moment</span>
                    </div>
                </div>
                <div class="auth-perk">
                    <div class="auth-perk-icon"><i class="bi bi-bell-fill"></i></div>
                    <div class="auth-perk-body">
                        <strong>Alertes arrivages</strong>
                        <span>Notifié dès l'arrivée de nouveaux fruits</span>
                    </div>
                </div>
            </div>

            <div class="auth-stats">
                <div class="auth-stat"><strong>200+</strong><span>Clients actifs</span></div>
                <div class="auth-stat"><strong>15+</strong><span>Variétés</span></div>
                <div class="auth-stat"><strong>24h</strong><span>Livraison</span></div>
                <div class="auth-stat"><strong>7j/7</strong><span>Disponible</span></div>
            </div>
        </div>

        <div class="auth-trust">
            <div class="trust-avatars">
                <span>AM</span><span>FD</span><span>KS</span><span>+</span>
            </div>
            <div class="trust-text">
                <strong>Supermarchés, hôtels & restaurateurs</strong><br>
                font confiance à Darou Salam
            </div>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="auth-right">
        <div class="auth-right-inner">
            <a href="/darousalam/" class="auth-back"><i class="bi bi-arrow-left"></i> Retour à l'accueil</a>

            <h1 class="auth-title">Créer un compte</h1>
            <p class="auth-desc">Déjà client ? <a href="<?= BASE_URL ?>?page=login">Se connecter</a></p>

            <div class="auth-tabs">
                <a href="<?= BASE_URL ?>?page=login" class="auth-tab">
                    <i class="bi bi-box-arrow-in-right"></i> Se connecter
                </a>
                <a href="<?= BASE_URL ?>?page=inscription" class="auth-tab active">
                    <i class="bi bi-person-plus-fill"></i> Créer un compte
                </a>
            </div>

            <?php if (!empty($error)): ?>
            <div class="auth-alert"><i class="bi bi-exclamation-circle-fill"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>?page=inscription" novalidate>

                <!-- Type de compte -->
                <div class="type-selector" id="typeSelector">
                    <label class="type-card <?= (($data['type_client'] ?? 'particulier') === 'particulier') ? 'selected' : '' ?>">
                        <input type="radio" name="type_client" value="particulier" <?= (($data['type_client'] ?? 'particulier') === 'particulier') ? 'checked' : '' ?>>
                        <div class="type-card-icon"><i class="bi bi-person-fill"></i></div>
                        <div><strong>Particulier</strong><span>Usage personnel</span></div>
                    </label>
                    <label class="type-card <?= (($data['type_client'] ?? '') === 'professionnel') ? 'selected' : '' ?>">
                        <input type="radio" name="type_client" value="professionnel" <?= (($data['type_client'] ?? '') === 'professionnel') ? 'checked' : '' ?>>
                        <div class="type-card-icon"><i class="bi bi-briefcase-fill"></i></div>
                        <div><strong>Professionnel</strong><span>Entreprise, hôtel…</span></div>
                    </label>
                </div>

                <!-- Prénom / Nom -->
                <div class="f-row">
                    <div class="f-group">
                        <div class="f-label"><label>Prénom <span style="color:#ef4444">*</span></label></div>
                        <div class="f-wrap">
                            <i class="bi bi-person f-icon"></i>
                            <input type="text" name="prenom" value="<?= htmlspecialchars($data['prenom'] ?? '') ?>" placeholder="Votre prénom" required>
                        </div>
                    </div>
                    <div class="f-group">
                        <div class="f-label"><label>Nom <span style="color:#ef4444">*</span></label></div>
                        <div class="f-wrap">
                            <i class="bi bi-person f-icon"></i>
                            <input type="text" name="nom" value="<?= htmlspecialchars($data['nom'] ?? '') ?>" placeholder="Votre nom" required>
                        </div>
                    </div>
                </div>

                <!-- Email / Téléphone -->
                <div class="f-row">
                    <div class="f-group">
                        <div class="f-label"><label>Email <span style="color:#ef4444">*</span></label></div>
                        <div class="f-wrap">
                            <i class="bi bi-envelope f-icon"></i>
                            <input type="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" placeholder="exemple@email.com" required>
                        </div>
                    </div>
                    <div class="f-group">
                        <div class="f-label"><label>Téléphone</label></div>
                        <div class="f-wrap">
                            <i class="bi bi-telephone f-icon"></i>
                            <input type="tel" name="telephone" value="<?= htmlspecialchars($data['telephone'] ?? '') ?>" placeholder="7X XXX XX XX">
                        </div>
                    </div>
                </div>

                <!-- Professionnel -->
                <div id="champs-pro" style="display:<?= (($data['type_client'] ?? '') === 'professionnel') ? 'block' : 'none' ?>;">
                    <div class="pro-box">
                        <div class="pro-box-title"><i class="bi bi-briefcase-fill"></i> Informations professionnelles</div>
                        <div class="f-group">
                            <div class="f-label"><label>Nom de l'entreprise <span style="color:#ef4444">*</span></label></div>
                            <div class="f-wrap">
                                <i class="bi bi-shop f-icon"></i>
                                <input type="text" name="nom_entreprise" value="<?= htmlspecialchars($data['nom_entreprise'] ?? '') ?>" placeholder="Ma Société SARL">
                            </div>
                        </div>
                        <div class="f-group" style="margin-bottom:0;">
                            <div class="f-label"><label>Secteur d'activité</label></div>
                            <div class="f-wrap">
                                <i class="bi bi-tags f-icon"></i>
                                <select name="secteur">
                                    <option value="">Choisir un secteur</option>
                                    <option value="supermarche">Supermarché / Grande surface</option>
                                    <option value="hotel">Hôtel / Resort</option>
                                    <option value="restaurant">Restaurant / Traiteur</option>
                                    <option value="grossiste">Grossiste / Distributeur</option>
                                    <option value="autre">Autre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mots de passe -->
                <div class="f-row">
                    <div class="f-group">
                        <div class="f-label"><label>Mot de passe <span style="color:#ef4444">*</span></label></div>
                        <div class="f-wrap" style="position:relative;">
                            <i class="bi bi-lock f-icon"></i>
                            <input type="password" name="password" id="pwd" placeholder="Min. 8 caractères" required oninput="checkStrength(this.value)">
                            <button type="button" class="f-end-btn" onclick="togglePwd('pwd','eye1')" tabindex="-1"><i class="bi bi-eye" id="eye1"></i></button>
                        </div>
                        <div class="pwd-bars"><div class="pwd-bar" id="b1"></div><div class="pwd-bar" id="b2"></div><div class="pwd-bar" id="b3"></div><div class="pwd-bar" id="b4"></div></div>
                    </div>
                    <div class="f-group">
                        <div class="f-label"><label>Confirmer <span style="color:#ef4444">*</span></label></div>
                        <div class="f-wrap" style="position:relative;">
                            <i class="bi bi-lock-fill f-icon"></i>
                            <input type="password" name="password_conf" id="pwd2" placeholder="Répéter" required oninput="checkMatch()">
                            <button type="button" class="f-end-btn" onclick="togglePwd('pwd2','eye2')" tabindex="-1"><i class="bi bi-eye" id="eye2"></i></button>
                        </div>
                        <div class="pwd-match-msg" id="matchMsg"></div>
                    </div>
                </div>

                <!-- CGU -->
                <div class="cgu-row">
                    <input type="checkbox" id="cgu" name="cgu" required>
                    <label for="cgu">J'accepte les <a href="#">Conditions d'utilisation</a> et la <a href="#">Politique de confidentialité</a></label>
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-person-check-fill"></i>
                    Créer mon compte gratuitement
                </button>
            </form>

            <div class="auth-switch">
                Déjà client ? <a href="<?= BASE_URL ?>?page=login">Se connecter &rarr;</a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('#typeSelector .type-card').forEach(card => {
    card.addEventListener('click', () => {
        document.querySelectorAll('#typeSelector .type-card').forEach(c => c.classList.remove('selected'));
        card.classList.add('selected');
        document.getElementById('champs-pro').style.display =
            card.querySelector('input').value === 'professionnel' ? 'block' : 'none';
    });
});

function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

function checkStrength(val) {
    const bars = ['b1','b2','b3','b4'].map(id => document.getElementById(id));
    bars.forEach(b => b.className = 'pwd-bar');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const cls = score <= 1 ? 'weak' : score === 2 ? 'medium' : 'strong';
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
}

function checkMatch() {
    const msg = document.getElementById('matchMsg');
    const v1 = document.getElementById('pwd').value;
    const v2 = document.getElementById('pwd2').value;
    if (!v2) { msg.style.display = 'none'; return; }
    msg.style.display = 'block';
    if (v1 === v2) {
        msg.style.color = '#22c55e';
        msg.innerHTML = '<i class="bi bi-check-circle-fill"></i> Identiques';
    } else {
        msg.style.color = '#ef4444';
        msg.innerHTML = '<i class="bi bi-x-circle-fill"></i> Différents';
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
