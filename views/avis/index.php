<?php
$pageTitle  = $pageTitle ?? 'Avis clients — Darou Salam Business Company';
$activePage = 'avis';
require_once __DIR__ . '/../layouts/header.php';

// Nom pre-rempli si client connecte
$nomDefault = '';
if (!empty($_SESSION['client'])) {
    $nomDefault = trim(($_SESSION['client']['prenom'] ?? '') . ' ' . ($_SESSION['client']['nom'] ?? ''));
}
?>

<style>
:root {
    --vert:   #1a5c2a;
    --vert-l: #236b34;
    --orange: #f97316;
    --creme:  #fdf8ee;
    --sombre: #0f2d16;
}

.avis-hero {
    background: linear-gradient(135deg, var(--sombre) 0%, var(--vert) 100%);
    padding: 70px 0 50px;
    text-align: center;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.avis-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: url('/photos/PHOTO-2026-05-14-21-57-41.jpg') center/cover no-repeat;
    opacity: .12;
}
.avis-hero-content { position: relative; z-index: 1; }
.avis-hero h1 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: clamp(2rem, 4vw, 3rem);
    font-weight: 800;
    margin-bottom: 12px;
}
.avis-hero p { font-size: 1.05rem; opacity: .85; max-width: 500px; margin: 0 auto; }

.avis-stats-bar {
    background: #fff;
    border-bottom: 1px solid #e5e7eb;
    padding: 18px 0;
}
.stats-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: .9rem;
    font-weight: 700;
    color: #374151;
}
.stats-pill .stars-display { color: #f59e0b; font-size: 1rem; }
.stats-pill .avg { font-size: 1.4rem; color: var(--vert); font-weight: 900; }

.avis-form-section {
    background: var(--creme);
    padding: 60px 0;
}
.avis-form-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 4px 30px rgba(0,0,0,.08);
    padding: 40px;
    max-width: 640px;
    margin: 0 auto;
}
.avis-form-card h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.6rem;
    color: var(--vert);
    margin-bottom: 6px;
}
.avis-form-card .sub { font-size: .9rem; color: #6b7280; margin-bottom: 28px; }

.star-rating { display: flex; flex-direction: row-reverse; gap: 4px; justify-content: flex-end; margin-bottom: 6px; }
.star-rating input { display: none; }
.star-rating label {
    font-size: 2rem;
    color: #d1d5db;
    cursor: pointer;
    transition: color .15s, transform .1s;
    user-select: none;
}
.star-rating label:hover,
.star-rating label:hover ~ label,
.star-rating input:checked ~ label {
    color: #f59e0b;
}
.star-rating label:hover { transform: scale(1.15); }
.star-hint { font-size: .78rem; color: #9ca3af; margin-bottom: 20px; min-height: 18px; }

.form-label-custom {
    font-size: .82rem;
    font-weight: 700;
    color: #374151;
    letter-spacing: .04em;
    text-transform: uppercase;
    margin-bottom: 6px;
    display: block;
}
.form-control-custom {
    width: 100%;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: .95rem;
    font-family: inherit;
    color: #111827;
    background: #fafafa;
    transition: border-color .15s, box-shadow .15s;
    box-sizing: border-box;
}
.form-control-custom:focus {
    outline: none;
    border-color: var(--vert);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(26,92,42,.1);
}
.char-count { font-size: .75rem; color: #9ca3af; text-align: right; margin-top: 4px; }
.char-count.ok { color: #16a34a; }

.btn-submit-avis {
    width: 100%;
    background: var(--vert);
    color: #fff;
    border: none;
    border-radius: 14px;
    padding: 14px 24px;
    font-size: 1rem;
    font-weight: 800;
    font-family: inherit;
    cursor: pointer;
    transition: background .2s, transform .1s;
    margin-top: 8px;
}
.btn-submit-avis:hover { background: var(--vert-l); transform: translateY(-1px); }
.btn-submit-avis:active { transform: translateY(0); }
.btn-submit-avis:disabled { opacity: .6; cursor: not-allowed; transform: none; }

.alert-custom {
    border-radius: 12px;
    padding: 14px 18px;
    font-size: .9rem;
    margin-bottom: 20px;
    display: none;
}
.alert-success-custom { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
.alert-error-custom   { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }

.avis-list-section { padding: 60px 0; background: #fff; }
.avis-list-section h2 {
    font-family: 'Playfair Display', Georgia, serif;
    font-size: 1.8rem;
    color: var(--vert);
    text-align: center;
    margin-bottom: 8px;
}
.avis-list-section .section-sub {
    text-align: center;
    color: #6b7280;
    font-size: .95rem;
    margin-bottom: 40px;
}

.avis-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 24px; }

.avis-card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 18px;
    padding: 24px;
    transition: box-shadow .2s, transform .15s;
    position: relative;
}
.avis-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,.08); transform: translateY(-2px); }
.avis-card::before {
    content: '\201C';
    position: absolute;
    top: 16px;
    right: 20px;
    font-size: 3rem;
    color: #e5e7eb;
    font-family: Georgia, serif;
    line-height: 1;
}

.avis-stars { color: #f59e0b; font-size: 1rem; margin-bottom: 10px; }
.avis-comment { color: #374151; font-size: .92rem; line-height: 1.6; margin-bottom: 16px; font-style: italic; }
.avis-author { display: flex; align-items: center; gap: 10px; }
.avis-avatar {
    width: 38px; height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--vert), var(--vert-l));
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .9rem;
    flex-shrink: 0;
}
.avis-name { font-weight: 700; font-size: .88rem; color: #111827; }
.avis-date { font-size: .72rem; color: #9ca3af; margin-top: 1px; }

.empty-avis {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}
.empty-avis i { font-size: 3rem; color: #e5e7eb; margin-bottom: 16px; display: block; }
.empty-avis p { font-size: .95rem; }

@media (max-width: 767px) {
    .avis-form-card { padding: 24px 18px; }
    .avis-grid { grid-template-columns: 1fr; }
}
</style>

<!-- Hero -->
<section class="avis-hero">
    <div class="container avis-hero-content">
        <h1><i class="bi bi-star-fill me-2" style="color:#f59e0b;"></i> Avis de nos clients</h1>
        <p>Decouvrez ce que pensent ceux qui font confiance a Darou Salam Business Company.</p>
    </div>
</section>

<?php
$nbAvis  = count($avis);
$moyenne = 0;
if ($nbAvis > 0) {
    $moyenne = round(array_sum(array_column($avis, 'note')) / $nbAvis, 1);
}
?>

<?php if ($nbAvis > 0): ?>
<div class="avis-stats-bar">
    <div class="container d-flex flex-wrap align-items-center justify-content-center gap-4">
        <div class="stats-pill">
            <span class="avg"><?= number_format($moyenne, 1) ?></span>
            <span class="stars-display">
                <?php for ($s = 1; $s <= 5; $s++): ?>
                    <i class="bi bi-star<?= $s <= round($moyenne) ? '-fill' : '' ?>"></i>
                <?php endfor; ?>
            </span>
        </div>
        <div class="stats-pill">
            <i class="bi bi-chat-square-quote-fill" style="color:var(--vert);"></i>
            <span><?= $nbAvis ?> avis publiés</span>
        </div>
        <div class="stats-pill">
            <i class="bi bi-shield-check-fill" style="color:#16a34a;"></i>
            <span>Avis vérifiés par notre équipe</span>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Formulaire -->
<section class="avis-form-section">
    <div class="container">
        <div class="avis-form-card">
            <h2>Laissez votre avis</h2>
            <p class="sub">Votre expérience aide d'autres clients à nous faire confiance.</p>

            <div class="alert-custom alert-success-custom" id="alertSuccess" role="alert"></div>
            <div class="alert-custom alert-error-custom"   id="alertError"   role="alert"></div>

            <form id="avisForm" novalidate>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_avis'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <!-- Note étoiles -->
                <div class="mb-3">
                    <label class="form-label-custom">Votre note <span style="color:#ef4444;">*</span></label>
                    <div class="star-rating" id="starRating">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="note" id="star<?= $i ?>" value="<?= $i ?>">
                            <label for="star<?= $i ?>" title="<?= $i ?> étoile<?= $i > 1 ? 's' : '' ?>">&#9733;</label>
                        <?php endfor; ?>
                    </div>
                    <div class="star-hint" id="starHint">Cliquez pour noter</div>
                </div>

                <!-- Nom -->
                <div class="mb-3">
                    <label class="form-label-custom" for="avisNom">Votre nom <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="avisNom" name="nom" class="form-control-custom"
                           placeholder="Ex : Amadou Diallo"
                           value="<?= htmlspecialchars($nomDefault, ENT_QUOTES, 'UTF-8') ?>"
                           maxlength="100" required>
                </div>

                <!-- Commentaire -->
                <div class="mb-4">
                    <label class="form-label-custom" for="avisCommentaire">Votre commentaire <span style="color:#ef4444;">*</span></label>
                    <textarea id="avisCommentaire" name="commentaire" class="form-control-custom"
                              rows="4" maxlength="1000"
                              placeholder="Partagez votre expérience (min. 20 caractères)…" required></textarea>
                    <div class="char-count" id="charCount">0 / 1000</div>
                </div>

                <button type="submit" class="btn-submit-avis" id="btnSubmit">
                    Envoyer mon avis
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Liste des avis approuvés -->
<section class="avis-list-section">
    <div class="container">
        <h2>Ce que disent nos clients</h2>
        <p class="section-sub">Tous nos avis sont vérifiés avant publication.</p>

        <?php if (empty($avis)): ?>
            <div class="empty-avis">
                <i class="bi bi-chat-square-heart"></i>
                <p>Aucun avis publié pour l'instant.<br>Soyez le premier à partager votre expérience !</p>
            </div>
        <?php else: ?>
            <div class="avis-grid">
                <?php foreach ($avis as $av): ?>
                    <div class="avis-card">
                        <div class="avis-stars">
                            <?php for ($s = 1; $s <= 5; $s++): ?>
                                <i class="bi bi-star<?= $s <= (int)$av['note'] ? '-fill' : '' ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="avis-comment"><?= nl2br(htmlspecialchars($av['commentaire'], ENT_QUOTES, 'UTF-8')) ?></p>
                        <div class="avis-author">
                            <div class="avis-avatar"><?= mb_strtoupper(mb_substr($av['nom'], 0, 1)) ?></div>
                            <div>
                                <div class="avis-name"><?= htmlspecialchars($av['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="avis-date"><?= date('d/m/Y', strtotime($av['created_at'])) ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
(function() {
    var hints = ['','Tres insatisfait','Insatisfait','Moyen','Satisfait','Tres satisfait !'];
    var radios = document.querySelectorAll('#starRating input[type=radio]');
    var hintEl = document.getElementById('starHint');
    radios.forEach(function(r) {
        r.addEventListener('change', function() {
            hintEl.textContent = hints[parseInt(r.value)] || '';
        });
    });

    var ta      = document.getElementById('avisCommentaire');
    var counter = document.getElementById('charCount');
    ta.addEventListener('input', function() {
        var n = this.value.length;
        counter.textContent = n + ' / 1000';
        counter.classList.toggle('ok', n >= 20);
    });

    var form    = document.getElementById('avisForm');
    var btnSub  = document.getElementById('btnSubmit');
    var alertOk = document.getElementById('alertSuccess');
    var alertEr = document.getElementById('alertError');

    function showAlert(type, msg) {
        alertOk.style.display = 'none';
        alertEr.style.display = 'none';
        if (type === 'success') {
            alertOk.textContent   = msg;
            alertOk.style.display = 'block';
        } else {
            alertEr.textContent   = msg;
            alertEr.style.display = 'block';
        }
        window.scrollTo({ top: alertOk.getBoundingClientRect().top + window.scrollY - 120, behavior: 'smooth' });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        btnSub.disabled     = true;
        btnSub.textContent  = 'Envoi en cours…';

        var fd = new FormData(form);
        fetch('<?= BASE_URL ?>?page=avis_soumettre', {
            method:  'POST',
            body:    fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(res) {
            if (res.success) {
                showAlert('success', res.message);
                form.reset();
                hintEl.textContent  = 'Cliquez pour noter';
                counter.textContent = '0 / 1000';
                counter.classList.remove('ok');
            } else {
                showAlert('error', res.message);
            }
        })
        .catch(function() {
            showAlert('error', 'Erreur réseau. Veuillez réessayer.');
        })
        .finally(function() {
            btnSub.disabled    = false;
            btnSub.textContent = 'Envoyer mon avis';
        });
    });
})();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
