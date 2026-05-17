<?php
$pageTitle  = 'Contact – Darou Salam Business Company';
$activePage = 'contact';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
/* ── Hero contact ── */
.contact-hero {
    background: linear-gradient(135deg, #0f2d16 0%, #1a5c2a 60%, #0f2d16 100%);
    color: #fff;
    padding: 60px 0 50px;
    position: relative;
    overflow: hidden;
}
.contact-hero::before {
    content:'';
    position:absolute;top:-80px;right:-80px;
    width:360px;height:360px;
    background:radial-gradient(circle,rgba(212,160,23,.14),transparent 70%);
    pointer-events:none;
}

/* ── Cartes coordonnées ── */
.coord-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px 24px;
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    display: flex;
    gap: 18px;
    align-items: flex-start;
    transition: transform .22s, box-shadow .22s;
    height: 100%;
}
.coord-card:hover { transform: translateY(-4px); box-shadow: 0 10px 30px rgba(0,0,0,.12); }
.coord-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}

/* ── Formulaire ── */
.contact-form-wrap {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 6px 32px rgba(0,0,0,.08);
    padding: 40px;
}
.contact-form-wrap .form-control,
.contact-form-wrap .form-select {
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: .93rem;
    transition: border-color .2s, box-shadow .2s;
}
.contact-form-wrap .form-control:focus {
    border-color: #1a5c2a;
    box-shadow: 0 0 0 3px rgba(26,92,42,.1);
}
.contact-form-wrap label { font-weight: 600; font-size: .88rem; color: #374151; margin-bottom: 6px; }
.btn-send {
    background: linear-gradient(135deg, #1a5c2a, #2d8a42);
    color: #fff; border: none;
    border-radius: 12px; padding: 14px 36px;
    font-size: 1rem; font-weight: 600;
    width: 100%; cursor: pointer;
    transition: transform .2s, box-shadow .2s;
}
.btn-send:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(26,92,42,.35); }

/* ── FAQ ── */
.faq-item {
    border: 1.5px solid #e8f5ec;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 12px;
    transition: border-color .2s;
}
.faq-item:hover { border-color: #1a5c2a; }
.faq-question {
    padding: 18px 22px;
    font-weight: 600; font-size: .95rem;
    cursor: pointer;
    display: flex; justify-content: space-between; align-items: center;
    background: #f8fdf9; color: #0f2d16;
    list-style: none;
}
.faq-question::after { content: '+'; font-size: 1.3rem; color: #1a5c2a; transition: transform .2s; }
.faq-item[open] .faq-question::after { transform: rotate(45deg); }
.faq-answer { padding: 0 22px 18px; font-size: .9rem; color: #4b5563; line-height: 1.7; }

/* ── Map placeholder ── */
.map-wrap {
    border-radius: 18px;
    overflow: hidden;
    height: 280px;
    background: linear-gradient(135deg, #e8f5ec, #d1f0db);
    display: flex; align-items: center; justify-content: center;
    border: 2px dashed #a7d9b3;
    position: relative;
}

/* ── Section badge ── */
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
    margin-bottom: 10px;
    font-weight: 600;
}

/* ── Horaires ── */
.horaire-row {
    display: flex; justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #f0f0f0;
    font-size: .9rem;
}
.horaire-row:last-child { border-bottom: none; }
.horaire-row .jour { color: #374151; font-weight: 500; }
.horaire-row .heure { color: #1a5c2a; font-weight: 700; }
.horaire-row .ferme { color: #ef4444; font-weight: 600; }
</style>

<!-- ══ HERO ══ -->
<div class="contact-hero">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="section-badge">Contactez-nous</span>
                <h1 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.6rem);font-weight:700;line-height:1.2;margin-bottom:14px;">
                    Parlons de votre<br><span style="color:#d4a017;">approvisionnement</span>
                </h1>
                <p style="font-size:1rem;color:rgba(255,255,255,.78);line-height:1.8;max-width:520px;">
                    Notre équipe est disponible du lundi au samedi pour répondre à toutes vos demandes — commandes en gros, devis professionnels, ou simples questions.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="row g-3">
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(212,160,23,.25);border-radius:14px;padding:20px;text-align:center;">
                            <div style="width:48px;height:48px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;backdrop-filter:blur(4px);">
                                <i class="bi bi-telephone-fill" style="font-size:1.3rem;color:#d4a017;"></i>
                            </div>
                            <div style="font-weight:700;color:#fff;font-size:.9rem;">Appel direct</div>
                            <div style="font-size:.78rem;color:rgba(255,255,255,.6);">Lun–Sam 7h–19h</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(212,160,23,.25);border-radius:14px;padding:20px;text-align:center;">
                            <div style="width:48px;height:48px;background:rgba(37,211,102,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;backdrop-filter:blur(4px);">
                                <i class="bi bi-whatsapp" style="font-size:1.3rem;color:#25D366;"></i>
                            </div>
                            <div style="font-weight:700;color:#fff;font-size:.9rem;">WhatsApp</div>
                            <div style="font-size:.78rem;color:rgba(255,255,255,.6);">Réponse rapide</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(212,160,23,.25);border-radius:14px;padding:20px;text-align:center;">
                            <div style="width:48px;height:48px;background:rgba(212,160,23,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;backdrop-filter:blur(4px);">
                                <i class="bi bi-geo-alt-fill" style="font-size:1.3rem;color:#d4a017;"></i>
                            </div>
                            <div style="font-weight:700;color:#fff;font-size:.9rem;">Sur place</div>
                            <div style="font-size:.78rem;color:rgba(255,255,255,.6);">Médina, Rue 47×37</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div style="background:rgba(255,255,255,.08);border:1px solid rgba(212,160,23,.25);border-radius:14px;padding:20px;text-align:center;">
                            <div style="width:48px;height:48px;background:rgba(249,115,22,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;backdrop-filter:blur(4px);">
                                <i class="bi bi-truck" style="font-size:1.3rem;color:#f97316;"></i>
                            </div>
                            <div style="font-weight:700;color:#fff;font-size:.9rem;">Livraison</div>
                            <div style="font-size:.78rem;color:rgba(255,255,255,.6);">Dakar & sous-région</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ CARTES COORDONNÉES ══ -->
<div style="background:#f8fdf9;padding:50px 0;">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="coord-card">
                    <div class="coord-icon" style="background:#e8f5ec;"><i class="bi bi-geo-alt-fill" style="color:#1a5c2a;"></i></div>
                    <div>
                        <div style="font-weight:700;font-size:.92rem;color:#0f2d16;margin-bottom:4px;">Adresse</div>
                        <div style="font-size:.85rem;color:#4b5563;line-height:1.6;">Dakar Médina<br>Rue 47×37<br>Sénégal</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="coord-card">
                    <div class="coord-icon" style="background:#fff3cd;"><i class="bi bi-telephone-fill" style="color:#d4a017;"></i></div>
                    <div>
                        <div style="font-weight:700;font-size:.92rem;color:#0f2d16;margin-bottom:4px;">Téléphone</div>
                        <a href="tel:+221774715353" style="display:block;font-size:.85rem;color:#1a5c2a;font-weight:600;text-decoration:none;">+221 77 471 53 53</a>
                        <a href="tel:+221701035050" style="display:block;font-size:.85rem;color:#1a5c2a;font-weight:600;text-decoration:none;margin-top:4px;">+221 70 103 50 50</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="coord-card">
                    <div class="coord-icon" style="background:#dcfce7;"><i class="bi bi-whatsapp" style="color:#25D366;"></i></div>
                    <div>
                        <div style="font-weight:700;font-size:.92rem;color:#0f2d16;margin-bottom:4px;">WhatsApp</div>
                        <div style="font-size:.85rem;color:#4b5563;margin-bottom:8px;">Commander ou poser une question</div>
                        <a href="https://wa.me/221774715353?text=Bonjour%20Darou%20Salam%2C%20je%20souhaite%20un%20renseignement." target="_blank"
                           style="background:#25D366;color:#fff;font-size:.78rem;font-weight:600;padding:5px 12px;border-radius:6px;text-decoration:none;">
                            Écrire maintenant
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="coord-card">
                    <div class="coord-icon" style="background:#ffe4e1;"><i class="bi bi-clock-fill" style="color:#ef4444;"></i></div>
                    <div>
                        <div style="font-weight:700;font-size:.92rem;color:#0f2d16;margin-bottom:4px;">Horaires</div>
                        <div style="font-size:.85rem;color:#4b5563;line-height:1.6;">Lun – Ven : <strong style="color:#1a5c2a;">07h – 19h</strong><br>Samedi : <strong style="color:#1a5c2a;">07h – 17h</strong><br>Dimanche : <strong style="color:#ef4444;">Fermé</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ FORMULAIRE + INFOS ══ -->
<div class="container py-5">
    <div class="row g-5 align-items-start">

        <!-- Formulaire -->
        <div class="col-lg-7">
            <div class="contact-form-wrap">
                <span class="section-badge">Formulaire</span>
                <h2 style="font-family:'Playfair Display',serif;font-size:1.7rem;font-weight:700;color:#0f2d16;margin-bottom:6px;">
                    Envoyez-nous un message
                </h2>
                <p style="font-size:.88rem;color:#6b7280;margin-bottom:28px;"><i class="bi bi-hourglass-split me-1" style="color:#1a5c2a;"></i> Nous vous répondons dans les 24h ouvrées.</p>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2 mb-4" style="border-radius:12px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" style="border-radius:12px;">
                        <i class="bi bi-exclamation-circle-fill"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/darousalam/contact" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label>Votre nom <span style="color:#ef4444;">*</span></label>
                            <input type="text" class="form-control" name="nom" placeholder="Ex : Amadou Diallo"
                                   value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Email <span style="color:#ef4444;">*</span></label>
                            <input type="email" class="form-control" name="email" placeholder="votre@email.com"
                                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label>Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" placeholder="+221 7X XXX XX XX"
                                   value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label>Type de demande</label>
                            <select class="form-control" name="type_demande" style="border:1.5px solid #e2e8f0;border-radius:10px;padding:12px 16px;">
                                <option value="">Sélectionner...</option>
                                <option value="commande" <?= (($_POST['type_demande'] ?? '') === 'commande') ? 'selected' : '' ?>>Commande en gros</option>
                                <option value="devis" <?= (($_POST['type_demande'] ?? '') === 'devis') ? 'selected' : '' ?>>Demande de devis</option>
                                <option value="partenariat" <?= (($_POST['type_demande'] ?? '') === 'partenariat') ? 'selected' : '' ?>>Partenariat</option>
                                <option value="information" <?= (($_POST['type_demande'] ?? '') === 'information') ? 'selected' : '' ?>>Information produit</option>
                                <option value="autre" <?= (($_POST['type_demande'] ?? '') === 'autre') ? 'selected' : '' ?>>Autre</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label>Sujet</label>
                            <input type="text" class="form-control" name="sujet" placeholder="Ex : Commande mensuelle de mangues"
                                   value="<?= htmlspecialchars($_POST['sujet'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label>Message <span style="color:#ef4444;">*</span></label>
                            <textarea class="form-control" name="message" rows="5" required
                                      placeholder="Décrivez votre besoin : fruits souhaités, quantités, fréquence de livraison..."><?= htmlspecialchars($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <div class="col-12">
                            <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 16px;font-size:.82rem;color:#166534;display:flex;gap:10px;align-items:center;">
                                <i class="bi bi-shield-lock-fill"></i>
                                Vos données sont confidentielles et ne seront jamais partagées avec des tiers.
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <button type="submit" class="btn-send">
                            <i class="bi bi-send-fill me-2"></i>Envoyer le message
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Colonne droite -->
        <div class="col-lg-5">

            <!-- Horaires détaillés -->
            <div style="background:#fff;border-radius:18px;box-shadow:0 4px 20px rgba(0,0,0,.07);padding:28px;margin-bottom:24px;">
                <h5 style="font-family:'Playfair Display',serif;font-weight:700;color:#0f2d16;margin-bottom:20px;">
                    <i class="bi bi-clock me-2" style="color:#d4a017;"></i>Horaires d'ouverture
                </h5>
                <div class="horaire-row"><span class="jour">Lundi</span><span class="heure">07h00 – 19h00</span></div>
                <div class="horaire-row"><span class="jour">Mardi</span><span class="heure">07h00 – 19h00</span></div>
                <div class="horaire-row"><span class="jour">Mercredi</span><span class="heure">07h00 – 19h00</span></div>
                <div class="horaire-row"><span class="jour">Jeudi</span><span class="heure">07h00 – 19h00</span></div>
                <div class="horaire-row"><span class="jour">Vendredi</span><span class="heure">07h00 – 19h00</span></div>
                <div class="horaire-row"><span class="jour">Samedi</span><span class="heure">07h00 – 17h00</span></div>
                <div class="horaire-row"><span class="jour">Dimanche</span><span class="ferme">Fermé</span></div>
            </div>

            <!-- Contact rapide WhatsApp -->
            <a href="https://wa.me/221774715353?text=Bonjour%20Darou%20Salam%20Business%20Company%2C%20je%20souhaite%20un%20renseignement."
               target="_blank"
               style="display:block;background:linear-gradient(135deg,#25D366,#128C7E);color:#fff;border-radius:18px;padding:24px;text-decoration:none;margin-bottom:24px;transition:transform .2s;"
               onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="display:flex;align-items:center;gap:16px;">
                    <div style="width:52px;height:52px;background:rgba(255,255,255,.2);border-radius:14px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-whatsapp" style="font-size:1.6rem;color:#fff;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:1rem;margin-bottom:4px;">Commander via WhatsApp</div>
                        <div style="font-size:.83rem;opacity:.85;">Réponse garantie en moins d'1h en heures ouvrées</div>
                    </div>
                    <i class="bi bi-arrow-right-circle-fill ms-auto fs-4"></i>
                </div>
            </a>

            <!-- Carte localisation placeholder -->
            <div class="map-wrap">
                <div style="text-align:center;">
                    <div style="font-size:3rem;margin-bottom:12px;">📍</div>
                    <div style="font-weight:700;color:#0f2d16;font-size:1rem;">Dakar Médina</div>
                    <div style="color:#4b5563;font-size:.85rem;margin:4px 0 14px;">Rue 47×37, Sénégal</div>
                    <a href="https://maps.google.com/?q=Dakar+Medina+Rue+47+37+Senegal" target="_blank"
                       style="background:#1a5c2a;color:#fff;padding:8px 20px;border-radius:8px;text-decoration:none;font-size:.82rem;font-weight:600;">
                        <i class="bi bi-map me-1"></i>Voir sur Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ FAQ ══ -->
<div style="background:#f8fdf9;padding:60px 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span class="section-badge">FAQ</span>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:700;color:#0f2d16;">Questions fréquentes</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-box-seam me-2" style="color:#1a5c2a;"></i>Quelles sont les quantités minimales de commande ?</summary>
                    <div class="faq-answer">Pour les particuliers, la commande minimum est de 1 kg ou 1 carton selon le produit. Pour les professionnels (hôtels, supermarchés), nous proposons des contrats adaptés à vos volumes. Contactez-nous pour un devis personnalisé.</div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-truck me-2" style="color:#1a5c2a;"></i>Livrez-vous en dehors de Dakar ?</summary>
                    <div class="faq-answer">Oui ! Nous livrons dans toute la région de Dakar et dans la sous-région sénégalaise. Des frais de livraison supplémentaires peuvent s'appliquer selon la distance. Contactez-nous pour connaître les tarifs selon votre localité.</div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-credit-card me-2" style="color:#1a5c2a;"></i>Quels modes de paiement acceptez-vous ?</summary>
                    <div class="faq-answer">Nous acceptons : Wave, Orange Money, paiement à la livraison (espèces), et virement bancaire pour les commandes professionnelles. Le paiement en ligne est disponible sur notre boutique.</div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-lightning-charge me-2" style="color:#1a5c2a;"></i>Comment fonctionne la livraison ?</summary>
                    <div class="faq-answer">Après validation de votre commande, notre équipe prépare votre colis et vous contacte pour confirmer le créneau de livraison. Les livraisons à Dakar se font généralement sous 24h à 48h.</div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-briefcase me-2" style="color:#1a5c2a;"></i>Proposez-vous des contrats pour les professionnels ?</summary>
                    <div class="faq-answer">Absolument. Nous travaillons avec Auchan, EDK, des hôtels et des restaurateurs sur la base de contrats cadres avec des tarifs négociés, des livraisons régulières et un service dédié. Contactez-nous pour discuter de vos besoins.</div>
                </details>
                <details class="faq-item">
                    <summary class="faq-question"><i class="bi bi-currency-exchange me-2" style="color:#1a5c2a;"></i>Les prix sont-ils en FCFA ?</summary>
                    <div class="faq-answer">Oui, tous les prix affichés sur notre site sont en Francs CFA (FCFA). Les prix professionnels et les devis pour commandes en gros sont communiqués sur demande.</div>
                </details>
            </div>
        </div>
    </div>
</div>

<!-- ══ CTA FINAL ══ -->
<div style="background:linear-gradient(135deg,#0f2d16,#1a5c2a);padding:50px 0;text-align:center;">
    <div class="container">
        <h2 style="font-family:'Playfair Display',serif;color:#fff;font-size:1.8rem;margin-bottom:12px;">
            Prêt à passer commande ?
        </h2>
        <p style="color:rgba(255,255,255,.72);margin-bottom:28px;max-width:480px;margin-left:auto;margin-right:auto;">
            Parcourez notre catalogue de fruits frais premium ou appelez-nous directement.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/darousalam/catalogue" class="btn btn-warning px-5 py-2" style="border-radius:8px;font-weight:700;color:#0f2d16;">
                <i class="bi bi-grid-fill me-2"></i>Voir le catalogue
            </a>
            <a href="tel:+221774715353" class="btn btn-outline-light px-5 py-2" style="border-radius:8px;font-weight:600;">
                <i class="bi bi-telephone-fill me-2"></i>Appeler maintenant
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
