<?php
$pageTitle  = 'Darou Salam Business Company — Fruits Premium au Sénégal';
$activePage = 'home';
require_once __DIR__ . '/../layouts/header.php';
?>

<style>
/* ============================================================
   GLOBAL STYLES
   ============================================================ */
html { scroll-behavior: smooth; }
body { overflow-x: hidden; }

:root {
    --vert:   #1a5c2a;
    --vert-l: #236b34;
    --orange: #f97316;
    --or:     #d4a017;
    --creme:  #fdf8ee;
    --sombre: #0f2d16;
}

/* ============================================================
   HERO
   ============================================================ */
.hero-section {
    min-height: auto;
    padding: 10px 0 5px;
    position: relative;
    display: flex;
    align-items: center;
    overflow: visible;
    background: var(--sombre);
}
.hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 80% at 70% 50%, rgba(26,92,42,.6) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 20% 80%, rgba(249,115,22,.18) 0%, transparent 60%),
        url('/darousalam/photos/PHOTO-2026-05-14-21-57-41.jpg') center/cover no-repeat;
    filter: brightness(.62) saturate(1.2);
    transform-origin: center;
    animation: heroZoom 18s ease-in-out infinite alternate;
}
@keyframes heroZoom {
    from { transform: scale(1); }
    to   { transform: scale(1.06); }
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, rgba(15,45,22,.92) 0%, rgba(15,45,22,.55) 50%, rgba(15,45,22,.2) 100%);
}
.hero-orb {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
}
.hero-orb-1 {
    width: 500px; height: 500px;
    background: radial-gradient(circle, rgba(212,160,23,.2), transparent 70%);
    top: -120px; right: -80px;
}
.hero-orb-2 {
    width: 300px; height: 300px;
    background: radial-gradient(circle, rgba(249,115,22,.15), transparent 70%);
    bottom: -60px; left: 30%;
}

.hero-content { position: relative; z-index: 2; }

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212,160,23,.15);
    border: 1px solid rgba(212,160,23,.4);
    border-radius: 50px;
    padding: 6px 16px;
    font-size: .78rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #d4a017;
    margin-bottom: 20px;
    backdrop-filter: blur(8px);
    animation: fadeUp .7s ease both;
}
.hero-badge .dot {
    width: 7px; height: 7px;
    background: #25D366;
    border-radius: 50%;
    animation: pulse 1.5s ease-in-out infinite;
}
@keyframes pulse {
    0%, 100% { opacity:1; transform:scale(1); }
    50%       { opacity:.5; transform:scale(.7); }
}

.hero-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 3.2rem);
    font-weight: 900;
    line-height: 1.08;
    color: #fff;
    margin-bottom: 22px;
    animation: fadeUp .7s .12s ease both;
}
.hero-title em { font-style: italic; color: #d4a017; }
.hero-title .line-vert { display: block; color: #6dde8a; }

.hero-sub {
    font-size: 1.05rem;
    color: rgba(255,255,255,.75);
    max-width: 500px;
    line-height: 1.7;
    margin-bottom: 36px;
    animation: fadeUp .7s .22s ease both;
}

.hero-ctas {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    animation: fadeUp .7s .32s ease both;
}
.btn-cta-primary {
    background: linear-gradient(135deg, #f97316, #ea580c);
    color: #fff;
    border: none;
    border-radius: 50px;
    padding: 14px 34px;
    font-weight: 600;
    font-size: .95rem;
    letter-spacing: .04em;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 8px 32px rgba(249,115,22,.4);
    transition: transform .2s, box-shadow .2s;
    font-family: 'DM Sans', sans-serif;
}
.btn-cta-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 40px rgba(249,115,22,.55);
    color: #fff;
}
.btn-cta-secondary {
    background: rgba(255,255,255,.1);
    color: #fff;
    border: 1px solid rgba(255,255,255,.35);
    border-radius: 50px;
    padding: 14px 30px;
    font-weight: 500;
    font-size: .95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: blur(8px);
    transition: all .2s;
    font-family: 'DM Sans', sans-serif;
}
.btn-cta-secondary:hover { background: rgba(255,255,255,.18); color: #fff; }

.hero-stats {
    display: flex;
    flex-wrap: nowrap;
    gap: 0;
    margin-top: 44px;
    padding-bottom: 24px;
    animation: fadeUp .7s .45s ease both;
    align-items: center;
}
.hstat {
    text-align: center;
    flex: 1;
    padding: 0 20px;
    position: relative;
}
.hstat:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0; top: 50%;
    transform: translateY(-50%);
    width: 1px; height: 36px;
    background: rgba(255,255,255,.2);
}
.hstat-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.7rem;
    font-weight: 900;
    color: #fff;
    line-height: 1;
}
.hstat-num span { color: #d4a017; }
.hstat-label { font-size: .68rem; color: rgba(255,255,255,.55); text-transform: uppercase; letter-spacing: .08em; margin-top: 4px; }

.hero-mosaic { position: relative; height: 420px; animation: fadeUp .7s .18s ease both; }
.mosaic-img {
    position: absolute;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,.5);
}
.mosaic-img img { width:100%; height:100%; object-fit:cover; display:block; transition:transform .5s ease; }
.mosaic-img:hover img { transform: scale(1.05); }
.mosaic-1 { top:0; left:0; width:58%; height:62%; }
.mosaic-2 { top:0; right:0; width:38%; height:42%; }
.mosaic-3 { bottom:0; left:0; width:38%; height:40%; }
.mosaic-4 { bottom:0; right:0; width:58%; height:54%; }
.mosaic-badge {
    position: absolute;
    bottom: -14px; left: 50%;
    transform: translateX(-50%);
    background: #d4a017;
    color: var(--sombre);
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    border-radius: 50px;
    padding: 5px 14px;
    white-space: nowrap;
    z-index: 2;
}

.scroll-indicator {
    position: absolute;
    bottom: 30px; left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    color: rgba(255,255,255,.5);
    font-size: .72rem;
    letter-spacing: .1em;
    text-transform: uppercase;
    animation: fadeIn 1s 1s both;
}
.scroll-line {
    width: 1px; height: 50px;
    background: linear-gradient(to bottom, rgba(255,255,255,.5), transparent);
    animation: scrollLine 1.8s ease-in-out infinite;
}
@keyframes scrollLine {
    0%   { transform:scaleY(0); transform-origin:top; }
    50%  { transform:scaleY(1); transform-origin:top; }
    51%  { transform:scaleY(1); transform-origin:bottom; }
    100% { transform:scaleY(0); transform-origin:bottom; }
}

@keyframes fadeUp {
    from { opacity:0; transform:translateY(28px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes fadeIn { from{opacity:0;} to{opacity:1;} }

/* ============================================================
   TICKER
   ============================================================ */
.ticker-wrap {
    background: linear-gradient(90deg, #f97316, #ea580c);
    overflow: hidden;
    padding: 13px 0;
    position: relative;
    z-index: 10;
}
.ticker-track {
    display: flex;
    white-space: nowrap;
    animation: ticker 30s linear infinite;
}
.ticker-track:hover { animation-play-state: paused; }
.ticker-item {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    color: #fff;
    font-weight: 600;
    font-size: .88rem;
    letter-spacing: .04em;
    padding: 0 40px;
}
.ticker-item i {
    font-size: 1rem;
    color: rgba(255,255,255,.9);
    background: rgba(255,255,255,.15);
    width: 26px; height: 26px;
    border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.ticker-sep { color: rgba(255,255,255,.35); font-size: .9rem; margin-left: 10px; }
@keyframes ticker {
    from { transform:translateX(0); }
    to   { transform:translateX(-50%); }
}

/* ============================================================
   SECTION GENERICS
   ============================================================ */
.section-label {
    display: inline-block;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .18em;
    text-transform: uppercase;
    color: #f97316;
    margin-bottom: 10px;
}
.section-title {
    font-family: 'Playfair Display', serif;
    font-size: clamp(1.8rem, 4vw, 2.8rem);
    font-weight: 900;
    line-height: 1.15;
    color: #0f2d16;
    margin-bottom: 14px;
}
.section-title em { font-style: italic; color: #1a5c2a; }
.section-sub { font-size: .95rem; color: #555; max-width: 520px; margin: 0 auto; line-height: 1.7; }

/* ============================================================
   FRUITS VEDETTES
   ============================================================ */
.fruits-section { background: var(--creme); padding: 96px 0; }
.fruit-card {
    background: #fff;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 4px 24px rgba(0,0,0,.07);
    transition: transform .3s ease, box-shadow .3s ease;
    position: relative;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.fruit-card:hover { transform: translateY(-10px) scale(1.015); box-shadow: 0 24px 64px rgba(26,92,42,.18); }
.fruit-card-img { height: 220px; overflow: hidden; position: relative; flex-shrink: 0; }
.fruit-card-img img { width:100%; height:100%; object-fit:cover; object-position:center; transition:transform .5s ease; }
.fruit-card:hover .fruit-card-img img { transform: scale(1.08); }
.fruit-badge-card {
    position: absolute;
    top: 14px; left: 14px;
    background: #f97316;
    color: #fff;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    border-radius: 50px;
    padding: 4px 12px;
    z-index: 2;
}
.fruit-badge-card.saison { background: #1a5c2a; }
.fruit-badge-card.promo  { background: #dc2626; }
.fruit-card-body { padding: 20px 22px 24px; display:flex; flex-direction:column; flex:1; }
.fruit-name { font-family:'Playfair Display',serif; font-size:1.18rem; font-weight:700; color:#0f2d16; margin-bottom:4px; }
.fruit-origine { font-size:.78rem; color:#888; margin-bottom:12px; }
.fruit-prices { display:flex; align-items:baseline; gap:12px; margin-bottom:16px; flex-wrap:wrap; }
.btn-add-cart { margin-top: auto; }
.price-kg { font-size:1.35rem; font-weight:700; color:#1a5c2a; font-family:'Playfair Display',serif; }
.price-carton { font-size:.8rem; color:#888; background:#f4f4f0; padding:2px 10px; border-radius:50px; }
.btn-add-cart {
    width:100%;
    background: linear-gradient(135deg, #1a5c2a, #236b34);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 11px;
    font-weight: 600;
    font-size: .88rem;
    cursor: pointer;
    transition: all .25s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: 'DM Sans', sans-serif;
}
.btn-add-cart:hover { background:linear-gradient(135deg,#f97316,#ea580c); transform:translateY(-1px); box-shadow:0 6px 20px rgba(249,115,22,.35); }

/* ============================================================
   CATEGORIES
   ============================================================ */
.categories-section { padding: 80px 0; background: #fff; }
.cat-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
}
@media (max-width:1199px) { .cat-grid { grid-template-columns: repeat(4,1fr); } }
@media (max-width:767px)  { .cat-grid { grid-template-columns: repeat(2,1fr); } }

.cat-card {
    border-radius: 20px;
    overflow: hidden;
    text-align: center;
    cursor: pointer;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    position: relative;
    transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.cat-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 48px rgba(0,0,0,.15);
}
.cat-card:hover .cat-arrow { opacity: 1; transform: translateX(0); }
.cat-card:hover .cat-img { transform: scale(1.08); }

.cat-img-wrap {
    width: 100%;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    position: relative;
}
.cat-img {
    width: 100%; height: 100%;
    object-fit: cover;
    transition: transform .4s ease;
}
.cat-img-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.45) 0%, transparent 55%);
}
.cat-body {
    padding: 14px 14px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    flex: 1;
}
.cat-name  {
    font-weight: 700;
    font-size: .95rem;
    color: #0f2d16;
    display: block;
    margin-bottom: 3px;
}
.cat-count {
    font-size: .72rem;
    color: #888;
    display: block;
    margin-bottom: 10px;
}
.cat-arrow {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .06em;
    text-transform: uppercase;
    color: #1a5c2a;
    opacity: 0;
    transform: translateX(-6px);
    transition: opacity .25s, transform .25s;
}
.cat-arrow i { font-size: .8rem; }

/* ============================================================
   POURQUOI — corrige cards coupees + icones
   ============================================================ */
.why-section {
    padding: 30px 0 50px;
    background: linear-gradient(160deg, var(--sombre) 0%, #1a5c2a 100%);
    position: relative;
    overflow: visible;
}
.why-section::before {
    content:'';
    position:absolute; inset:0;
    background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    pointer-events: none;
}
.why-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    align-items: stretch;
}
.why-col {
    flex: 1 1 calc(25% - 20px);
    min-width: 220px;
    max-width: 300px;
    display: flex;
}
@media (max-width: 991px) { .why-col { flex: 1 1 calc(50% - 20px); } }
@media (max-width: 575px) { .why-col { flex: 1 1 100%; max-width: 100%; } }

.why-card {
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.14);
    border-radius: 20px;
    padding: 24px;
    text-align: center;
    backdrop-filter: blur(12px);
    transition: all .3s;
    width: 100%;
    position: relative;
    overflow: hidden;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.why-card::after {
    content:'';
    position:absolute; bottom:0; left:0; right:0;
    height:3px;
    background: linear-gradient(90deg,#f97316,#d4a017);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .3s;
}
.why-card:hover { background:rgba(255,255,255,.13); transform:translateY(-5px); border-color:rgba(212,160,23,.4); }
.why-card:hover::after { transform:scaleX(1); }

/* Icone fond clair sur fond sombre */
.why-icon {
    width: 64px;
    height: 64px;
    border-radius: 18px;
    background: rgba(255,255,255,0.12);
    border: 1px solid rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 18px;
    flex-shrink: 0;
    backdrop-filter: blur(6px);
}
.why-icon i { font-size: 1.8rem; }

.why-title { font-family:'Playfair Display',serif; font-weight:700; font-size:1.05rem; color:#fff; margin-bottom:8px; }
.why-text  { font-size:.84rem; color:rgba(255,255,255,.65); line-height:1.7; margin:0; }

/* ============================================================
   CLIENTS
   ============================================================ */
.clients-section { padding: 80px 0; background: var(--creme); }
.client-logo-wrap {
    background: #fff;
    border-radius: 16px;
    padding: 24px 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 16px rgba(0,0,0,.06);
    transition: all .25s;
    height: 90px;
}
.client-logo-wrap:hover { box-shadow:0 8px 32px rgba(26,92,42,.15); transform:translateY(-3px); }
.client-name { font-family:'Playfair Display',serif; font-weight:900; font-size:1.5rem; color:var(--vert); }

/* ============================================================
   STATS
   ============================================================ */
.stats-section { background:#fff; padding:72px 0; border-top:1px solid #f0f0ea; border-bottom:1px solid #f0f0ea; }
.stat-item { text-align:center; flex:1; }
.stat-number {
    font-family:'Playfair Display',serif;
    font-size: clamp(2.2rem,5vw,3.8rem);
    font-weight:900; color:var(--vert); line-height:1;
    display:flex; align-items:baseline; justify-content:center; gap:2px;
}
.stat-suffix { font-size:60%; color:var(--orange); }
.stat-label  { font-size:.82rem; color:#888; text-transform:uppercase; letter-spacing:.12em; margin-top:8px; }
.stat-divider { width:1px; background:#e5e5e0; }

/* ============================================================
   TEMOIGNAGES
   ============================================================ */
.temoignages-section { padding:96px 0; background:#fff; }
.temoignage-card {
    background: var(--creme);
    border-radius: 20px;
    padding: 32px 28px;
    position: relative;
    height: 100%;
    transition: transform .25s, box-shadow .25s;
    border: 1px solid rgba(26,92,42,.08);
}
.temoignage-card:hover { transform:translateY(-5px); box-shadow:0 16px 48px rgba(26,92,42,.12); }
.quote-icon { font-size:3rem; line-height:1; color:#d4a017; opacity:.6; font-family:'Playfair Display',serif; display:block; margin-bottom:8px; }
.temo-text  { font-size:.9rem; line-height:1.75; color:#444; margin-bottom:20px; font-style:italic; }
.temo-author { display:flex; align-items:center; gap:12px; }
.temo-avatar { width:44px;height:44px;border-radius:50%;background:var(--vert);color:#fff;font-weight:700;font-size:1.1rem;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;flex-shrink:0; }
.temo-name { font-weight:600; font-size:.9rem; color:#0f2d16; }
.temo-role { font-size:.75rem; color:#888; }
.stars { color:#f97316; font-size:.85rem; margin-bottom:12px; }

/* ============================================================
   CTA FINAL — corrige taille + emojis + badges
   ============================================================ */
.cta-section {
    background: linear-gradient(135deg, #1a5c2a 0%, #0f2d16 50%, #236b34 100%);
    padding: 30px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.cta-section::before {
    content:''; position:absolute; top:-100px; left:50%; transform:translateX(-50%);
    width:700px; height:700px;
    background:radial-gradient(circle,rgba(212,160,23,.15),transparent 70%);
    pointer-events:none;
}
.cta-emojis {
    font-size: 2rem;
    margin-bottom: 8px;
    line-height: 1.3;
}
.cta-title {
    font-family:'Playfair Display',serif;
    font-size: clamp(1.6rem, 3vw, 2.4rem);
    font-weight:900; color:#fff;
    margin-bottom: 10px;
}
.cta-title em { color:#d4a017; font-style:italic; }
.cta-sub {
    font-size: .95rem;
    color:rgba(255,255,255,.7);
    margin-bottom: 22px;
    line-height: 1.6;
}
.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    justify-content: center;
    margin-bottom: 18px;
}
.btn-cta-white {
    background:#fff; color:#1a5c2a; border-radius:50px; padding:14px 36px;
    font-weight:700; font-size:.95rem; text-decoration:none;
    display:inline-flex; align-items:center; gap:10px;
    box-shadow:0 12px 40px rgba(0,0,0,.25); transition:all .25s; font-family:'DM Sans',sans-serif;
}
.btn-cta-white:hover { background:#d4a017; color:#fff; transform:translateY(-3px); box-shadow:0 18px 50px rgba(0,0,0,.3); }
.cta-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    justify-content: center;
    color: rgba(255,255,255,.55);
    font-size: .82rem;
}
.cta-badges span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}

/* ============================================================
   REVEAL ANIMATION
   ============================================================ */
.reveal { opacity:0; transform:translateY(30px); transition:opacity .65s ease, transform .65s ease; }
.reveal.visible { opacity:1; transform:translateY(0); }
.reveal-delay-1 { transition-delay:.1s; }
.reveal-delay-2 { transition-delay:.2s; }
.reveal-delay-3 { transition-delay:.3s; }
.reveal-delay-4 { transition-delay:.4s; }
</style>

<!-- HERO -->
<section class="hero-section">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-orb hero-orb-1"></div>
    <div class="hero-orb hero-orb-2"></div>

    <div class="container position-relative" style="z-index:2;padding-top:40px;padding-bottom:30px;">
        <div class="row align-items-center gy-5">
            <div class="col-lg-6">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="dot"></span>
                        Fournisseur Officiel · Auchan · EDK · Hôtels
                    </div>
                    <h1 class="hero-title">
                        Fruits Frais<br>
                        <em>Premium</em><br>
                        <span class="line-vert">Directs du Verger</span>
                    </h1>
                    <p class="hero-sub">
                        Darou Salam Business Company — votre partenaire de confiance pour l'approvisionnement en fruits frais de qualité supérieure à Dakar et dans toute la sous-région.
                    </p>
                    <div class="hero-ctas">
                        <a href="/darousalam/catalogue" class="btn-cta-primary">
                            <i class="bi bi-bag-fill"></i> Commander Maintenant
                        </a>
                        <a href="https://wa.me/221774715353?text=Bonjour%20Darou%20Salam%2C%20je%20souhaite%20un%20devis%20fruits." target="_blank" class="btn-cta-secondary">
                            <i class="bi bi-whatsapp"></i> Demander un Devis
                        </a>
                    </div>
                    <div class="hero-stats">
                        <div class="hstat">
                            <div class="hstat-num">10<span>+</span></div>
                            <div class="hstat-label">Ans d'expérience</div>
                        </div>
                        <div class="hstat">
                            <div class="hstat-num">50<span>+</span></div>
                            <div class="hstat-label">Clients fidèles</div>
                        </div>
                        <div class="hstat">
                            <div class="hstat-num">10</div>
                            <div class="hstat-label">Variétés</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-mosaic">
                    <div class="mosaic-img mosaic-1">
                        <img src="/darousalam/photos/PHOTO-2026-05-14-21-57-40.jpg" alt="Mangues vertes fraîches">
                        <span class="mosaic-badge">Mangues Sélectionnées</span>
                    </div>
                    <div class="mosaic-img mosaic-2">
                        <img src="/darousalam/photos/PHOTO-2026-05-14-22-05-35.jpg" alt="Mandarines fraîches">
                    </div>
                    <div class="mosaic-img mosaic-3">
                        <img src="/darousalam/photos/PHOTO-2026-05-14-22-20-31.jpg" alt="Pêches fraîches">
                    </div>
                    <div class="mosaic-img mosaic-4">
                        <img src="/darousalam/photos/PHOTO-2026-05-14-22-41-42.jpg" alt="Pastèques fraîches">
                        <span class="mosaic-badge">100% Frais</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="scroll-indicator">
        <div class="scroll-line"></div>
        <span>Découvrir</span>
    </div>
</section>

<!-- TICKER -->
<div class="ticker-wrap">
    <div class="ticker-track">
        <?php
        $items = [
            ['<i class="bi bi-truck-front-fill"></i>','Livraison Dakar & Sous-région'],
            ['<i class="bi bi-box-seam-fill"></i>','Vente au KG et au Carton'],
            ['<i class="bi bi-building-fill"></i>','Fournisseur Officiel Auchan'],
            ['<i class="bi bi-shop-window"></i>','Partenaire EDK Supermarché'],
            ['<i class="bi bi-award-fill"></i>','+10 ans d\'expérience'],
            ['<i class="bi bi-basket2-fill"></i>','Mangues, Avocats, Kiwis & Plus'],
            ['<i class="bi bi-lightning-fill"></i>','Livraison en 24h Dakar'],
            ['<i class="bi bi-star-fill"></i>','Hôtels & Supermarchés'],
            ['<i class="bi bi-patch-check-fill"></i>','Qualité Certifiée'],
            ['<i class="bi bi-tag-fill"></i>','Prix Grossiste Garanti'],
        ];
        $allItems = array_merge($items, $items);
        foreach ($allItems as $item): ?>
            <span class="ticker-item">
                <?= $item[0] ?> <?= $item[1] ?>
                <span class="ticker-sep">·</span>
            </span>
        <?php endforeach; ?>
    </div>
</div>

<!-- STATS -->
<section class="stats-section">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-0">
            <div class="stat-item px-4 py-2">
                <div class="stat-number"><span class="counter" data-target="10">0</span><span class="stat-suffix">+</span></div>
                <div class="stat-label">Années d'expérience</div>
            </div>
            <div class="stat-divider d-none d-md-block" style="width:1px;height:60px;background:#e5e5e0;"></div>
            <div class="stat-item px-4 py-2">
                <div class="stat-number"><span class="counter" data-target="50">0</span><span class="stat-suffix">+</span></div>
                <div class="stat-label">Clients Actifs</div>
            </div>
            <div class="stat-divider d-none d-md-block" style="width:1px;height:60px;background:#e5e5e0;"></div>
            <div class="stat-item px-4 py-2">
                <div class="stat-number"><span class="counter" data-target="100">0</span><span class="stat-suffix">T+</span></div>
                <div class="stat-label">Tonnes / An</div>
            </div>
            <div class="stat-divider d-none d-md-block" style="width:1px;height:60px;background:#e5e5e0;"></div>
            <div class="stat-item px-4 py-2">
                <div class="stat-number"><span class="counter" data-target="10">0</span></div>
                <div class="stat-label">Variétés de Fruits</div>
            </div>
        </div>
    </div>
</section>

<!-- FRUITS VEDETTES -->
<section class="fruits-section" id="fruits-vedettes">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-label">Sélection Premium</span>
            <h2 class="section-title">Nos Fruits <em>Vedettes</em></h2>
            <p class="section-sub">Cueillis à maturité optimale, disponibles au kilo et au carton pour les professionnels.</p>
        </div>
        <div class="row g-4 align-items-stretch">
            <?php
            $delays = [0,1,2,3,0,1,2,3];
            $badgeClasses = ['Saison'=>'saison','Local'=>'saison','Promo'=>'promo','Nouveau'=>'','Premium'=>''];
            foreach ($produitsFeatured as $fi => $f):
                $imgUrl = !empty($f['image_principale'])
                    ? '/darousalam/' . $f['image_principale']
                    : '/darousalam/photos/PHOTO-2026-05-14-21-57-41.jpg';
                $badge = $f['badge'] ?? ($f['saison'] ? 'Saison' : '');
                $badgeClass = $badgeClasses[$badge] ?? '';
                $prixKg = number_format($f['prix_kg'], 0, ',', ' ') . ' FCFA';
                $prixCarton = $f['prix_carton'] ? number_format($f['prix_carton'], 0, ',', ' ') . ' FCFA / ' . ($f['poids_carton_kg'] ?? '?') . 'kg' : '';
                $origine = $f['origine'] ?? 'Sénégal';
                $delay = $delays[$fi % 4];
            ?>
            <div class="col-sm-6 col-lg-3 reveal reveal-delay-<?= $delay ?>">
                <div class="fruit-card">
                    <div class="fruit-card-img">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($f['nom']) ?>" onerror="this.src='/darousalam/photos/PHOTO-2026-05-14-21-57-41.jpg'">
                        <?php if($badge): ?>
                        <span class="fruit-badge-card <?= $badgeClass ?>"><?= htmlspecialchars($badge) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="fruit-card-body">
                        <div class="fruit-name"><?= htmlspecialchars($f['nom']) ?></div>
                        <div class="fruit-origine"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($origine) ?></div>
                        <div class="fruit-prices">
                            <span class="price-kg"><?= $prixKg ?> <small style="font-size:.55em;font-weight:400;color:#888;">/kg</small></span>
                            <?php if($prixCarton): ?>
                            <span class="price-carton"><?= $prixCarton ?></span>
                            <?php endif; ?>
                        </div>
                        <button class="btn-add-cart" onclick="addToCart('<?= $f['id'] ?>','<?= htmlspecialchars($f['nom'],ENT_QUOTES) ?>','<?= $prixKg ?>','<?= htmlspecialchars($imgUrl) ?>')">
                            <i class="bi bi-bag-plus-fill"></i> Ajouter au panier
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5 reveal">
            <a href="/darousalam/catalogue" class="btn btn-outline-success btn-lg px-5 rounded-pill" style="border-color:#1a5c2a;color:#1a5c2a;font-family:'DM Sans',sans-serif;font-weight:600;">
                Voir tout le catalogue <i class="bi bi-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- CATEGORIES -->
<section class="categories-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-label">Nos Gammes</span>
            <h2 class="section-title">Explorer par <em>Catégorie</em></h2>
        </div>
        <div class="cat-grid">
            <?php
            $cats = [
                ['photo'=>'PHOTO-2026-05-14-21-57-40.jpg',    'nom'=>'Mangues',        'count'=>'3 variétés','border'=>'#fb923c','cat'=>'mangues'],
                ['photo'=>'PHOTO-2026-05-14-22-33-18.jpg',    'nom'=>'Pamplemousses',  'count'=>'2 variétés','border'=>'#fb7185','cat'=>'pamplemousses'],
                ['photo'=>'PHOTO-2026-05-14-22-41-06.jpg',    'nom'=>'Melons',         'count'=>'2 variétés','border'=>'#fcd34d','cat'=>'melon'],
                ['photo'=>'PHOTO-2026-05-14-22-05-35.jpg',    'nom'=>'Mandarines',     'count'=>'1 variété', 'border'=>'#fb923c','cat'=>'mandarines'],
                ['photo'=>'PHOTO-2026-05-14-22-33-14.jpg',    'nom'=>'Pommes Golden',  'count'=>'2 variétés','border'=>'#a3e635','cat'=>'pommes-golden'],
                ['photo'=>'PHOTO-2026-05-14-22-20-31.jpg',    'nom'=>'Pêches',         'count'=>'1 variété', 'border'=>'#fdba74','cat'=>'peches'],
                ['photo'=>'PHOTO-2026-05-14-22-41-42.jpg',    'nom'=>'Pastèques',      'count'=>'1 variété', 'border'=>'#4ade80','cat'=>'pasteque'],
                ['photo'=>'PHOTO-2026-05-14-22-41-31.jpg',    'nom'=>'Citrons Verts',  'count'=>'1 variété', 'border'=>'#84cc16','cat'=>'citrons'],
                ['photo'=>'PHOTO-2026-05-14-22-41-35.jpg',    'nom'=>'Papayes',        'count'=>'1 variété', 'border'=>'#fb923c','cat'=>'papayes'],
                ['photo'=>'PHOTO-2026-05-14-22-33-31.jpg',    'nom'=>'Melons Locaux',  'count'=>'1 variété', 'border'=>'#fbbf24','cat'=>'melon'],
            ];
            foreach ($cats as $c): ?>
            <a href="/darousalam/catalogue?cat=<?= $c['cat'] ?>" class="cat-card reveal" style="border:2px solid <?= $c['border'] ?>55;">
                <div class="cat-img-wrap">
                    <img class="cat-img" src="/darousalam/photos/<?= rawurlencode($c['photo']) ?>" alt="<?= htmlspecialchars($c['nom']) ?>">
                    <div class="cat-img-overlay"></div>
                </div>
                <div class="cat-body">
                    <span class="cat-name"><?= htmlspecialchars($c['nom']) ?></span>
                    <span class="cat-count"><?= $c['count'] ?></span>
                    <span class="cat-arrow">Voir <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- POURQUOI NOUS -->
<section class="why-section">
    <div class="container position-relative">
        <div class="text-center mb-4 reveal">
            <span class="section-label" style="color:#d4a017;">Nos Engagements</span>
            <h2 class="section-title" style="color:#fff;">Pourquoi Choisir <em style="color:#d4a017;">Darou Salam</em>&nbsp;?</h2>
            <p class="section-sub" style="color:rgba(255,255,255,.65);">Des années de savoir-faire au service de votre réussite commerciale.</p>
        </div>
        <div class="why-row">
            <?php
            $whys = [
                ['icon'=>'bi-patch-check-fill',  'color'=>'#d4a017', 'title'=>'Fraîcheur Garantie',  'text'=>'Chaque fruit est sélectionné à maturité optimale et livré dans les meilleures conditions de conservation.'],
                ['icon'=>'bi-lightning-charge-fill','color'=>'#f97316','title'=>'Livraison Express', 'text'=>'Livraison en 24h sur Dakar. Service logistique fiable pour la sous-région, du Sénégal à la Gambie.'],
                ['icon'=>'bi-tag-fill',          'color'=>'#4ade80', 'title'=>'Prix Grossiste',      'text'=>'Tarifs préférentiels pour les professionnels : supermarchés, hôtels, restaurateurs. Devis sur mesure.'],
                ['icon'=>'bi-award-fill',        'color'=>'#60a5fa', 'title'=>'Qualité Certifiée',   'text'=>'Nos fruits répondent aux normes exigées par Auchan et EDK. Traçabilité et contrôle qualité rigoureux.'],
            ];
            foreach ($whys as $i => $w): ?>
            <div class="why-col reveal reveal-delay-<?= $i ?>">
                <div class="why-card">
                    <div class="why-icon">
                        <i class="bi <?= $w['icon'] ?>" style="color:<?= $w['color'] ?>;"></i>
                    </div>
                    <h4 class="why-title"><?= $w['title'] ?></h4>
                    <p class="why-text"><?= $w['text'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CLIENTS -->
<section class="clients-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-label">Ils nous font confiance</span>
            <h2 class="section-title">Nos Clients <em>Partenaires</em></h2>
            <p class="section-sub">Des enseignes de référence au Sénégal qui choisissent la qualité Darou Salam.</p>
        </div>
        <div class="row g-4 justify-content-center align-items-center">
            <?php
            $clients = [
                ['nom'=>'AUCHAN',    'sous'=>'Supermarchés', 'color'=>'#e02020'],
                ['nom'=>'EDK',       'sous'=>'Supermarchés', 'color'=>'#1a5c2a'],
                ['nom'=>'KING FADH', 'sous'=>'Hôtel 5★',    'color'=>'#b8860b'],
                ['nom'=>'RADISSON',  'sous'=>'Hôtel Luxe',  'color'=>'#003087'],
                ['nom'=>'TERROU-BI', 'sous'=>'Hôtel Casino', 'color'=>'#8b0000'],
                ['nom'=>'CASINO',    'sous'=>'Supermarchés', 'color'=>'#e02020'],
            ];
            foreach ($clients as $i => $c): ?>
            <div class="col-6 col-md-4 col-lg-2 reveal reveal-delay-<?= $i % 4 ?>">
                <div class="client-logo-wrap">
                    <div class="text-center">
                        <div class="client-name" style="color:<?= $c['color'] ?>;"><?= $c['nom'] ?></div>
                        <div style="font-size:.68rem;color:#aaa;letter-spacing:.1em;text-transform:uppercase;margin-top:4px;"><?= $c['sous'] ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- TEMOIGNAGES -->
<section class="temoignages-section">
    <div class="container">
        <div class="text-center mb-5 reveal">
            <span class="section-label">Ce qu'ils disent</span>
            <h2 class="section-title">Témoignages <em>Clients</em></h2>
        </div>
        <div class="row g-4">
            <?php
            $temos = [
                ['initiale'=>'A','nom'=>'Amadou D.','role'=>'Responsable Achats, Auchan Dakar','stars'=>5,
                 'text'=>'"Depuis que nous travaillons avec Darou Salam Business Company, notre rayon fruits n\'a jamais été aussi bien achalandé. La fraîcheur des produits et la ponctualité des livraisons sont irréprochables."'],
                ['initiale'=>'F','nom'=>'Fatou N.','role'=>'Directrice, EDK Almadies','stars'=>5,
                 'text'=>'"Partenaire de confiance depuis plus de 5 ans. Les mangues et avocats de Darou Salam sont plébiscités par nos clients. Prix compétitifs et qualité au rendez-vous."'],
                ['initiale'=>'S','nom'=>'Serigne M.','role'=>'Chef Cuisine, Hôtel King Fahd Palace','stars'=>5,
                 'text'=>'"Pour nos buffets et restaurants, la qualité est non négociable. Darou Salam comprend nos exigences et nous livre toujours des fruits parfaits. Je recommande vivement."'],
            ];
            foreach ($temos as $i => $t): ?>
            <div class="col-md-4 reveal reveal-delay-<?= $i ?>">
                <div class="temoignage-card">
                    <span class="quote-icon">&ldquo;</span>
                    <div class="stars"><?= str_repeat('<i class="bi bi-star-fill"></i>', $t['stars']) ?></div>
                    <p class="temo-text"><?= $t['text'] ?></p>
                    <div class="temo-author">
                        <div class="temo-avatar"><?= $t['initiale'] ?></div>
                        <div>
                            <div class="temo-name"><?= htmlspecialchars($t['nom']) ?></div>
                            <div class="temo-role"><?= htmlspecialchars($t['role']) ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<section class="cta-section">
    <div class="container position-relative">
        <div class="reveal">
            <div class="cta-emojis">
                <img src="/darousalam/photos/PHOTO-2026-05-14-21-57-40.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/darousalam/photos/PHOTO-2026-05-14-22-41-06.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/darousalam/photos/PHOTO-2026-05-14-22-33-13.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/darousalam/photos/PHOTO-2026-05-14-22-33-56.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
            </div>
            <h2 class="cta-title">Prêt à Commander des Fruits <em>d'Exception</em>&nbsp;?</h2>
            <p class="cta-sub">Créez votre compte professionnel et bénéficiez de tarifs grossiste,<br class="d-none d-md-block">de livraison express et d'un service dédié.</p>
            <div class="cta-buttons">
                <a href="/darousalam/inscription" class="btn-cta-white">
                    <i class="bi bi-person-plus-fill"></i> Créer mon Compte
                </a>
                <a href="https://wa.me/221774715353?text=Bonjour%2C%20je%20souhaite%20commander%20des%20fruits%20en%20gros." target="_blank" class="btn-cta-secondary" style="padding:14px 32px;">
                    <i class="bi bi-whatsapp"></i> Commander via WhatsApp
                </a>
            </div>
            <div class="cta-badges">
                <span><i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>Sans engagement</span>
                <span><i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>Livraison offerte dès 50 kg</span>
                <span><i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>Paiement sécurisé</span>
                <span><i class="bi bi-check-circle-fill" style="color:#4ade80;"></i>Support 7j/7</span>
            </div>
        </div>
    </div>
</section>

<script>
// Scroll reveal
const revealEls = document.querySelectorAll('.reveal');
new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: .12 }).observe ? (() => {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: .12 });
    revealEls.forEach(el => obs.observe(el));
})() : revealEls.forEach(el => el.classList.add('visible'));

// Counters
const counterEls = document.querySelectorAll('.counter');
const cObs = new IntersectionObserver(entries => {
    entries.forEach(e => {
        if (e.isIntersecting && !e.target.dataset.done) {
            e.target.dataset.done = '1';
            const target = parseInt(e.target.dataset.target, 10);
            let current = 0;
            const step = Math.max(1, Math.ceil(target / 40));
            const t = setInterval(() => {
                current = Math.min(current + step, target);
                e.target.textContent = current;
                if (current >= target) clearInterval(t);
            }, 30);
        }
    });
}, { threshold: .4 });
counterEls.forEach(el => cObs.observe(el));
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
