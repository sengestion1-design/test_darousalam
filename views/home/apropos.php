<?php
$pageTitle  = 'À propos – Darou Salam Business Company';
$activePage = 'apropos';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
.apropos-hero {
    background: linear-gradient(135deg, #0f2d16 0%, #1a5c2a 60%, #0f2d16 100%);
    color: #fff;
    padding: 70px 0 50px;
    position: relative;
    overflow: hidden;
}
.apropos-hero::before {
    content: '';
    position: absolute;
    top: -100px; right: -100px;
    width: 400px; height: 400px;
    background: radial-gradient(circle, rgba(212,160,23,.15), transparent 70%);
    pointer-events: none;
}
.stat-pill {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(212,160,23,.3);
    border-radius: 14px;
    padding: 22px 28px;
    text-align: center;
    backdrop-filter: blur(6px);
}
.stat-pill .num {
    font-family: 'Playfair Display', serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #d4a017;
    line-height: 1;
}
.stat-pill .lbl {
    font-size: .78rem;
    color: rgba(255,255,255,.7);
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: .08em;
}
.section-badge {
    display: inline-block;
    background: rgba(212,160,23,.12);
    color: #d4a017;
    border: 1px solid rgba(212,160,23,.3);
    border-radius: 20px;
    font-size: .72rem;
    letter-spacing: .12em;
    text-transform: uppercase;
    padding: 4px 14px;
    margin-bottom: 12px;
    font-weight: 600;
}
.value-card {
    border: none;
    border-radius: 16px;
    padding: 32px 24px;
    text-align: center;
    height: 100%;
    transition: transform .25s, box-shadow .25s;
    background: #fff;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
}
.value-card:hover { transform: translateY(-6px); box-shadow: 0 12px 32px rgba(0,0,0,.1); }
.value-icon {
    width: 64px; height: 64px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    margin: 0 auto 18px;
}
.timeline-item {
    display: flex;
    gap: 20px;
    margin-bottom: 32px;
}
.timeline-dot {
    width: 14px; height: 14px;
    background: #d4a017;
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 5px;
    box-shadow: 0 0 0 4px rgba(212,160,23,.2);
}
.timeline-line {
    width: 2px;
    background: linear-gradient(to bottom, #d4a017, rgba(212,160,23,.1));
    margin-left: 6px;
    flex-shrink: 0;
}
.partner-logo {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 18px 28px;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    color: #1a5c2a;
    border: 2px solid transparent;
    transition: border-color .2s, background .2s;
    height: 70px;
}
.partner-logo:hover { border-color: #d4a017; background: #fff; }
</style>

<!-- HERO -->
<div class="apropos-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="section-badge">Notre histoire</span>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.8rem);font-weight:700;line-height:1.2;margin-bottom:18px;">
                    Darou Salam<br><span style="color:#d4a017;">Business Company</span>
                </h1>
                <p style="font-size:1.05rem;line-height:1.8;color:rgba(255,255,255,.82);margin-bottom:24px;">
                    Spécialiste de l'approvisionnement en <strong style="color:#fff;">fruits frais premium</strong> au Sénégal,
                    Darou Salam Business Company est le partenaire privilégié des grandes surfaces, hôtels et restaurateurs
                    de Dakar et de la sous-région depuis plusieurs années.
                </p>
                <p style="font-size:.95rem;line-height:1.8;color:rgba(255,255,255,.65);margin-bottom:32px;">
                    Basée à <strong style="color:#d4a017;">Dakar Médina, Rue 47×37</strong>, notre entreprise garantit une chaîne
                    d'approvisionnement maîtrisée de bout en bout — de la sélection à la livraison.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/darousalam/contact" class="btn btn-warning fw-600 px-4 py-2" style="border-radius:8px;color:#0f2d16;font-weight:600;">
                        <i class="bi bi-envelope-fill me-2"></i>Nous contacter
                    </a>
                    <a href="/darousalam/catalogue" class="btn btn-outline-light px-4 py-2" style="border-radius:8px;">
                        <i class="bi bi-grid me-2"></i>Voir le catalogue
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Mosaïque photos entrepôt / fruits -->
                <div style="display:grid;grid-template-columns:1fr 1fr;grid-template-rows:200px 180px;gap:10px;border-radius:18px;overflow:hidden;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-22-33-25.jpg" alt="Entrepôt Darou Salam"
                         style="width:100%;height:100%;object-fit:cover;grid-row:span 2;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-21-57-40.jpg" alt="Mangues"
                         style="width:100%;height:100%;object-fit:cover;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-22-41-06.jpg" alt="Avocats"
                         style="width:100%;height:100%;object-fit:cover;">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mt-4">
            <div class="col-6 col-md-3">
                <div class="stat-pill">
                    <div class="num">10+</div>
                    <div class="lbl">Années d'expérience</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-pill">
                    <div class="num">50+</div>
                    <div class="lbl">Clients fidèles</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-pill">
                    <div class="num">100T+</div>
                    <div class="lbl">Livrées / mois</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-pill">
                    <div class="num">20+</div>
                    <div class="lbl">Variétés de fruits</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MISSION & VALEURS -->
<div class="container py-5">
    <div class="text-center mb-5">
        <span class="section-badge">Nos engagements</span>
        <h2 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#0f2d16;">Ce qui nous distingue</h2>
    </div>
    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="value-card">
                <div class="value-icon" style="background:rgba(26,92,42,.1);">
                    <i class="bi bi-patch-check-fill" style="color:#1a5c2a;"></i>
                </div>
                <h6 style="font-weight:700;font-size:1rem;margin-bottom:10px;">Qualité Premium</h6>
                <p class="text-muted small" style="line-height:1.7;">Sélection rigoureuse de chaque lot. Nos fruits respectent les normes d'exportation européennes et les standards de nos partenaires hôteliers.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="value-card">
                <div class="value-icon" style="background:rgba(249,115,22,.1);">
                    <i class="bi bi-truck" style="color:#f97316;"></i>
                </div>
                <h6 style="font-weight:700;font-size:1rem;margin-bottom:10px;">Livraison Fiable</h6>
                <p class="text-muted small" style="line-height:1.7;">Flotte propre, horaires respectés. Nous livrons Auchan, EDK, les supermarchés et hôtels de Dakar dans les délais convenus.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="value-card">
                <div class="value-icon" style="background:rgba(212,160,23,.1);">
                    <i class="bi bi-box-seam" style="color:#d4a017;"></i>
                </div>
                <h6 style="font-weight:700;font-size:1rem;margin-bottom:10px;">Stock Permanent</h6>
                <p class="text-muted small" style="line-height:1.7;">Entrepôts stratégiques en sous-région. Disponibilité garantie toute l'année grâce à un réseau de fournisseurs diversifié.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="value-card">
                <div class="value-icon" style="background:rgba(14,165,233,.1);">
                    <i class="bi bi-people-fill" style="color:#0ea5e9;"></i>
                </div>
                <h6 style="font-weight:700;font-size:1rem;margin-bottom:10px;">Service Personnalisé</h6>
                <p class="text-muted small" style="line-height:1.7;">Un interlocuteur dédié pour chaque client professionnel. Commandes sur mesure, formats KG ou carton, selon votre besoin.</p>
            </div>
        </div>
    </div>
</div>

<!-- NOTRE HISTOIRE (timeline) -->
<div style="background:#f8fdf9;padding:60px 0;">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-5">
                <span class="section-badge">Parcours</span>
                <h2 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#0f2d16;margin-bottom:32px;">
                    Une croissance construite sur la confiance
                </h2>
                <div style="position:relative;">
                    <div style="position:absolute;left:6px;top:0;bottom:0;width:2px;background:linear-gradient(to bottom,#d4a017,rgba(212,160,23,.1));"></div>
                    <div class="timeline-item" style="padding-left:34px;position:relative;">
                        <div style="position:absolute;left:0;top:4px;width:14px;height:14px;background:#d4a017;border-radius:50%;box-shadow:0 0 0 4px rgba(212,160,23,.2);"></div>
                        <div>
                            <div style="font-weight:700;color:#0f2d16;margin-bottom:4px;">Création de l'entreprise</div>
                            <p class="text-muted small" style="line-height:1.6;">Darou Salam Business Company ouvre ses portes à Dakar Médina, spécialisée dans la distribution de fruits frais.</p>
                        </div>
                    </div>
                    <div class="timeline-item" style="padding-left:34px;position:relative;margin-top:24px;">
                        <div style="position:absolute;left:0;top:4px;width:14px;height:14px;background:#d4a017;border-radius:50%;box-shadow:0 0 0 4px rgba(212,160,23,.2);"></div>
                        <div>
                            <div style="font-weight:700;color:#0f2d16;margin-bottom:4px;">Partenariats grandes surfaces</div>
                            <p class="text-muted small" style="line-height:1.6;">Signature des premiers contrats avec Auchan Sénégal et EDK, consolidant notre position de fournisseur de référence.</p>
                        </div>
                    </div>
                    <div class="timeline-item" style="padding-left:34px;position:relative;margin-top:24px;">
                        <div style="position:absolute;left:0;top:4px;width:14px;height:14px;background:#d4a017;border-radius:50%;box-shadow:0 0 0 4px rgba(212,160,23,.2);"></div>
                        <div>
                            <div style="font-weight:700;color:#0f2d16;margin-bottom:4px;">Extension sous-régionale</div>
                            <p class="text-muted small" style="line-height:1.6;">Ouverture d'entrepôts en sous-région pour sécuriser les approvisionnements et réduire les délais de livraison.</p>
                        </div>
                    </div>
                    <div class="timeline-item" style="padding-left:34px;position:relative;margin-top:24px;">
                        <div style="position:absolute;left:0;top:4px;width:14px;height:14px;background:#1a5c2a;border-radius:50%;box-shadow:0 0 0 4px rgba(26,92,42,.2);"></div>
                        <div>
                            <div style="font-weight:700;color:#1a5c2a;margin-bottom:4px;">Boutique en ligne — 2024</div>
                            <p class="text-muted small" style="line-height:1.6;">Lancement de la plateforme e-commerce pour permettre aux particuliers et professionnels de commander en ligne.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-22-33-25.jpg" alt="Entrepôt"
                         style="width:100%;height:220px;object-fit:cover;border-radius:14px;grid-column:span 2;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-22-41-06.jpg" alt="Avocats"
                         style="width:100%;height:160px;object-fit:cover;border-radius:14px;">
                    <img src="/darousalam/photos/PHOTO-2026-05-14-22-33-13.jpg" alt="Oranges"
                         style="width:100%;height:160px;object-fit:cover;border-radius:14px;">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PARTENAIRES -->
<div class="container py-5">
    <div class="text-center mb-4">
        <span class="section-badge">Ils nous font confiance</span>
        <h2 style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#0f2d16;">Nos clients partenaires</h2>
    </div>
    <div class="row g-3 justify-content-center">
        <div class="col-6 col-md-3 col-lg-2">
            <div class="partner-logo"><i class="bi bi-building me-2 text-warning"></i>Auchan</div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="partner-logo"><i class="bi bi-shop me-2 text-warning"></i>EDK</div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="partner-logo"><i class="bi bi-cart3 me-2 text-warning"></i>Supermarchés</div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="partner-logo"><i class="bi bi-star me-2 text-warning"></i>Hôtels 4★</div>
        </div>
        <div class="col-6 col-md-3 col-lg-2">
            <div class="partner-logo"><i class="bi bi-cup-straw me-2 text-warning"></i>Restaurants</div>
        </div>
    </div>
</div>

<!-- CTA CONTACT -->
<div style="background:linear-gradient(135deg,#0f2d16,#1a5c2a);padding:60px 0;text-align:center;">
    <div class="container">
        <h2 style="font-family:'Playfair Display',serif;color:#fff;font-size:2rem;margin-bottom:14px;">
            Vous avez un projet d'approvisionnement ?
        </h2>
        <p style="color:rgba(255,255,255,.75);font-size:1.05rem;margin-bottom:28px;max-width:520px;margin-left:auto;margin-right:auto;">
            Contactez-nous pour un devis personnalisé. Livraison en gros, tarifs professionnels, contrats cadres.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/darousalam/contact" class="btn btn-warning px-5 py-2" style="border-radius:8px;font-weight:600;color:#0f2d16;">
                <i class="bi bi-envelope-fill me-2"></i>Nous écrire
            </a>
            <a href="https://wa.me/221774715353" target="_blank" class="btn px-5 py-2" style="border-radius:8px;background:#25D366;color:#fff;font-weight:600;">
                <i class="bi bi-whatsapp me-2"></i>WhatsApp
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
