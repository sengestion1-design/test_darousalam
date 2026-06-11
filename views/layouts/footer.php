<!-- ===== FOOTER ===== -->
<footer style="
    background: linear-gradient(160deg, #0f2d16 0%, #1a5c2a 60%, #0f2d16 100%);
    color: rgba(255,255,255,.82);
    font-family: 'DM Sans', sans-serif;
    padding-top: 10px;
    position: relative;
    overflow: hidden;
">
    <!-- Decorative -->
    <div style="position:absolute;top:-80px;right:-80px;width:340px;height:340px;background:radial-gradient(circle,rgba(212,160,23,.12),transparent 70%);pointer-events:none;"></div>
    <div style="position:absolute;bottom:0;left:-60px;width:280px;height:280px;background:radial-gradient(circle,rgba(249,115,22,.08),transparent 70%);pointer-events:none;"></div>

    <div class="container position-relative">
        <div class="row g-3">

            <!-- Col 1 : Brand -->
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <img src="logo.jpg" alt="Logo" style="width:44px;height:44px;object-fit:cover;border-radius:50%;border:2px solid #d4a017;box-shadow:0 0 0 3px rgba(212,160,23,.2);">
                    <div>
                        <div style="font-family:'Playfair Display',serif;font-weight:700;font-size:1.1rem;color:#fff;">Darou Salam</div>
                        <div style="font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:#d4a017;">Business Company</div>
                    </div>
                </div>
                <p style="font-size:.88rem;line-height:1.6;margin-bottom:12px;max-width:260px;color:rgba(255,255,255,.8);">
                    Votre partenaire de confiance pour l'approvisionnement en fruits frais premium au Sénégal. Qualité garantie, livraison fiable.
                </p>
                <!-- Socials — flex en ligne -->
                <div style="display:flex;flex-wrap:nowrap;gap:8px;align-items:center;">
                    <a href="https://wa.me/221774715353" target="_blank" class="footer-social" style="background:#25D366;" title="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=61590522353210" target="_blank" class="footer-social" style="background:#1877F2;" title="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/darousalamcontact1/" target="_blank" class="footer-social" style="background:linear-gradient(45deg,#f09433,#e6683c,#dc2743,#cc2366,#bc1888);" title="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="https://www.tiktok.com/@darou.salam.business" target="_blank" class="footer-social" style="background:#010101;" title="TikTok">
                        <i class="bi bi-tiktok"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2 : Liens rapides -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading">Navigation</h6>
                <ul class="footer-links">
                    <li><a href="">Accueil</a></li>
                    <li><a href="catalogue">Catalogue</a></li>
                    <li><a href="apropos">À propos</a></li>
                    <li><a href="contact">Contact</a></li>
                    <li><a href="compte">Mon compte</a></li>
                    <li><a href="panier">Mon panier</a></li>
                </ul>
            </div>

            <!-- Col 3 : Produits -->
            <div class="col-lg-2 col-md-6 col-6">
                <h6 class="footer-heading">Nos Fruits</h6>
                <ul class="footer-links">
                    <li><a href="catalogue?cat=mangues">Mangues</a></li>
                    <li><a href="catalogue?cat=avocats">Avocats</a></li>
                    <li><a href="catalogue?cat=kiwis">Kiwis</a></li>
                    <li><a href="catalogue?cat=oranges">Oranges</a></li>
                    <li><a href="catalogue?cat=pommes">Pommes</a></li>
                    <li><a href="catalogue?cat=melon">Melons & Pastèques</a></li>
                </ul>
            </div>

            <!-- Col 4 : Contact -->
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-heading">Nous Contacter</h6>
                <ul class="footer-links" style="list-style:none;padding:0;">
                    <li class="mb-2">
                        <i class="bi bi-geo-alt-fill me-2" style="color:#d4a017;font-size:.9rem;"></i>
                        <span style="font-size:.88rem;">Dakar Médina, Rue 47×37</span>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-telephone-fill me-2" style="color:#d4a017;font-size:.9rem;"></i>
                        <a href="tel:+221774715353" style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;">+221 77 471 53 53</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-phone-fill me-2" style="color:#d4a017;font-size:.9rem;"></i>
                        <a href="tel:+221701035050" style="color:rgba(255,255,255,.85);text-decoration:none;font-size:.88rem;">+221 70 103 50 50</a>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-clock-fill me-2" style="color:#d4a017;font-size:.9rem;"></i>
                        <span style="font-size:.88rem;">Lun – Sam : 07h00 – 19h00</span>
                    </li>
                </ul>
                <!-- Newsletter mini -->
                <div style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:12px;">
                    <p style="font-size:.85rem;margin-bottom:8px;color:#fff;">Recevez nos offres et actualités :</p>
                    <form id="newsletter-footer-form" class="d-flex gap-2" style="flex-wrap:nowrap;" onsubmit="newsletterFooterSubmit(event)">
                        <input type="email" id="newsletter-footer-email" name="email" placeholder="votre@email.com" required style="flex:1;min-width:0;width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);border-radius:8px;color:#fff;font-size:.82rem;padding:8px 12px;outline:none;" onfocus="this.style.borderColor='#d4a017'" onblur="this.style.borderColor='rgba(255,255,255,.2)'">
                        <button type="submit" id="newsletter-footer-btn" style="background:#f97316;border:none;border-radius:8px;color:#fff;padding:8px 14px;font-size:.82rem;cursor:pointer;white-space:nowrap;flex-shrink:0;transition:background .2s;" onmouseover="this.style.background='#ea6400'" onmouseout="this.style.background='#f97316'">
                            <i class="bi bi-send-fill" id="newsletter-footer-icon"></i>
                        </button>
                    </form>
                    <div id="newsletter-footer-msg" style="display:none;margin-top:8px;font-size:.82rem;border-radius:6px;padding:6px 10px;"></div>
                </div>
<script>
function newsletterFooterSubmit(e) {
    e.preventDefault();
    var btn   = document.getElementById('newsletter-footer-btn');
    var icon  = document.getElementById('newsletter-footer-icon');
    var email = document.getElementById('newsletter-footer-email').value;
    var msg   = document.getElementById('newsletter-footer-msg');
    btn.disabled = true;
    icon.className = 'bi bi-hourglass-split';
    var fd = new FormData();
    fd.append('email', email);
    fetch('<?= BASE_URL ?>?page=newsletter_subscribe', {method: 'POST', body: fd})
        .then(function(r) { return r.json(); })
        .then(function(data) {
            msg.style.display = 'block';
            if (data.success) {
                msg.style.background = 'rgba(34,197,94,.18)';
                msg.style.color = '#86efac';
                msg.style.border = '1px solid rgba(34,197,94,.3)';
                document.getElementById('newsletter-footer-email').value = '';
            } else {
                msg.style.background = 'rgba(239,68,68,.18)';
                msg.style.color = '#fca5a5';
                msg.style.border = '1px solid rgba(239,68,68,.3)';
            }
            msg.textContent = data.message;
            btn.disabled = false;
            icon.className = 'bi bi-send-fill';
        })
        .catch(function() {
            msg.style.display = 'block';
            msg.style.background = 'rgba(239,68,68,.18)';
            msg.style.color = '#fca5a5';
            msg.style.border = '1px solid rgba(239,68,68,.3)';
            msg.textContent = 'Erreur réseau. Veuillez réessayer.';
            btn.disabled = false;
            icon.className = 'bi bi-send-fill';
        });
}
</script>
            </div>
        </div>

        <!-- Divider -->
        <hr style="border-color:rgba(255,255,255,.12);margin:16px 0 10px;">

        <!-- Bottom bar — padding compact -->
        <div class="row align-items-center" style="padding:12px 0;">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                <p style="font-size:.78rem;margin:0;color:rgba(255,255,255,.45);">
                    &copy; <?= date('Y') ?> Darou Salam Business Company. Tous droits réservés.
                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span style="font-size:.78rem;color:rgba(255,255,255,.45);">
                    <a href="<?= BASE_URL ?>?page=mentions_legales" style="color:rgba(255,255,255,.45);text-decoration:none;">Mentions légales</a>
                    &nbsp;·&nbsp;
                    <a href="<?= BASE_URL ?>?page=politique_confidentialite" style="color:rgba(255,255,255,.45);text-decoration:none;">Confidentialité</a>
                    &nbsp;·&nbsp;
                    <a href="<?= BASE_URL ?>?page=cgv" style="color:rgba(255,255,255,.45);text-decoration:none;">CGV</a>
                    &nbsp;·&nbsp;
                    <a href="<?= BASE_URL ?>?page=faq" style="color:rgba(255,255,255,.45);text-decoration:none;">FAQ</a>
                </span>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<a href="https://wa.me/221774715353?text=Bonjour%20Darou%20Salam%20Business%20Company%2C%20je%20souhaite%20commander%20des%20fruits."
   target="_blank"
   id="whatsapp-float"
   title="Commander via WhatsApp"
   style="
    position:fixed;
    bottom:28px; right:24px;
    z-index:9999;
    background:#25D366;
    color:#fff;
    width:56px; height:56px;
    border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:1.6rem;
    box-shadow:0 6px 24px rgba(37,211,102,.45);
    text-decoration:none;
    transition:transform .25s, box-shadow .25s;
    animation: waBounce 2.5s ease-in-out infinite;
">
    <i class="bi bi-whatsapp"></i>
</a>

<!-- Back to Top -->
<button id="back-top" onclick="window.scrollTo({top:0,behavior:'smooth'})" title="Retour en haut" style="
    position:fixed;
    bottom:92px; right:26px;
    z-index:9998;
    background:#1a5c2a;
    color:#fff;
    width:42px; height:42px;
    border-radius:50%;
    border:none;
    font-size:1rem;
    box-shadow:0 4px 16px rgba(0,0,0,.2);
    cursor:pointer;
    transition:all .25s;
    opacity:0;
    pointer-events:none;
">
    <i class="bi bi-chevron-up"></i>
</button>

<style>
.footer-heading {
    font-family: 'Playfair Display', serif;
    font-weight: 700;
    color: #fff;
    font-size: 1rem;
    margin-bottom: 10px;
    position: relative;
    padding-bottom: 8px;
}
.footer-heading::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0;
    width: 28px; height: 2px;
    background: #d4a017;
    border-radius: 2px;
}
.footer-links {
    list-style: none;
    padding: 0;
}
.footer-links li { margin-bottom: 6px; }
.footer-links a {
    color: rgba(255,255,255,.75);
    text-decoration: none;
    font-size: .88rem;
    line-height: 1.7;
    transition: color .2s, padding-left .2s;
    display: inline-block;
}
.footer-links a::before {
    content: '→ ';
    opacity: 0;
    transition: opacity .2s;
}
.footer-links a:hover {
    color: #d4a017;
    padding-left: 4px;
}
.footer-links a:hover::before { opacity: 1; }

.footer-social {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
    flex-shrink: 0;
}
.footer-social:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,.3); color: #fff; }

/* Newsletter input mobile */
@media (max-width: 575px) {
    .footer-newsletter-form { flex-direction: column !important; }
    .footer-newsletter-form input { width: 100% !important; }
}

@keyframes waBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}
</style>

<script>
// Back to top visibility
window.addEventListener('scroll', () => {
    const btn = document.getElementById('back-top');
    if (btn) {
        if (window.scrollY > 400) { btn.style.opacity='1'; btn.style.pointerEvents='auto'; }
        else { btn.style.opacity='0'; btn.style.pointerEvents='none'; }
    }
}, { passive: true });
</script>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
