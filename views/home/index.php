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
body { overflow-x: hidden; touch-action: pan-y; }
* { touch-action: pan-y; }

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
    overflow: hidden;
    background: var(--sombre);
    touch-action: pan-y;
}
.hero-bg {
    position: absolute;
    inset: 0;
    background:
        radial-gradient(ellipse 70% 80% at 70% 50%, rgba(26,92,42,.6) 0%, transparent 70%),
        radial-gradient(ellipse 50% 60% at 20% 80%, rgba(249,115,22,.18) 0%, transparent 60%),
        url('/photos/PHOTO-2026-05-14-21-57-41.jpg') center/cover no-repeat;
    filter: brightness(.62) saturate(1.2);
    transform-origin: center;
    animation: heroZoom 18s ease-in-out infinite alternate;
    pointer-events: none;
    touch-action: none;
}
@keyframes heroZoom {
    from { transform: scale(1); }
    to   { transform: scale(1.06); }
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, rgba(15,45,22,.92) 0%, rgba(15,45,22,.55) 50%, rgba(15,45,22,.2) 100%);
    pointer-events: none;
    touch-action: none;
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

.hero-content { position: relative; z-index: 2; touch-action: pan-y; }

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
.hero-title .line-vert {
    display: block;
    color: #6dde8a;
    font-size: clamp(1.1rem, 3vw, 2rem);
    line-height: 1.2;
    word-break: break-word;
}
@media (max-width: 576px) {
    .hero-title .line-vert { font-size: clamp(.95rem, 4.5vw, 1.4rem); }
    .hero-sub { font-size: .92rem; }
}

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
    z-index: 1;
    touch-action: pan-y;
    pointer-events: none;
}
.ticker-track {
    display: flex;
    white-space: nowrap;
    animation: ticker 30s linear infinite;
    touch-action: pan-y;
    pointer-events: none;
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
    padding: 0;
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
.stats-section { background:#fff; padding:28px 0; border-top:1px solid #f0f0ea; border-bottom:1px solid #f0f0ea; }
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
.temoignages-section { padding:36px 0; background:#fff; }
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
@media (min-width: 992px) {
    .col-lg-vedette { flex: 0 0 20%; max-width: 20%; }
}

/* ============================================================
   MOBILE OVERFLOW FIXES
   ============================================================ */
html, body { overflow-x: hidden; max-width: 100vw; }

/* Contain all sections so nothing bleeds out */
.hero-section,
.ticker-wrap,
.stats-section,
.fruits-section,
.categories-section,
.why-section,
.clients-section,
.temoignages-section,
.cta-section { overflow-x: hidden; }

/* Hero orbs must not push page width on mobile */
.hero-orb-1 { width: min(500px, 90vw); height: min(500px, 90vw); }
.hero-orb-2 { width: min(300px, 70vw); height: min(300px, 70vw); }

/* CTA pseudo-element must not overflow viewport */
.cta-section::before { width: min(700px, 100vw); }

.container { padding-left: 12px; padding-right: 12px; }

@media (max-width: 576px) {
    .container { padding-left: 10px; padding-right: 10px; }

    /* Fruit cards — two columns with no negative-margin bleed */
    .row.g-2 { --bs-gutter-x: 0.5rem; --bs-gutter-y: 0.5rem; }
    .fruit-card { margin: 0; }
    .fruit-card-img { height: 160px; }
    .fruit-card-body { padding: 10px 12px 14px; }
    .fruit-name { font-size: .92rem; }
    .price-kg { font-size: 1.1rem; }
    .btn-add-cart { font-size: .78rem; padding: 9px; }

    /* Hero stats — allow wrap on very small screens */
    .hero-stats { flex-wrap: wrap; gap: 8px; }
    .hstat { flex: 1 1 30%; padding: 0 10px; }

    /* Why cards full-width on mobile */
    .why-col { flex: 1 1 100%; max-width: 100%; }

    /* CTA buttons stack */
    .cta-buttons { flex-direction: column; align-items: center; }
    .btn-cta-white, .btn-cta-secondary { width: 100%; max-width: 320px; justify-content: center; }
}

/* ============================================================
   MOBILE TOUCH SCROLL FIX
   ============================================================ */
@media (max-width: 767px) {
    /* Disable zoom animation on mobile — prevents GPU layer blocking touch */
    .hero-bg { animation: none !important; }

    /* Hero height auto on mobile */
    .hero-section { min-height: auto !important; }

    /* All hero children must pass touch events through */
    .hero-section * { touch-action: pan-y !important; }

    /* Absolutely positioned decorative layers must never capture touch */
    .hero-bg,
    .hero-overlay,
    .hero-orb,
    .hero-orb-1,
    .hero-orb-2,
    .scroll-indicator,
    .scroll-line { pointer-events: none !important; touch-action: none !important; }
}
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
                        Fournisseur Officiel · Grandes Surfaces · Hôtels
                    </div>
                    <h1 class="hero-title">
                        Fruits Frais<br>
                        <em>Premium</em><br>
                        <span class="line-vert">Cueillis à Maturité, Livrés chez Vous</span>
                    </h1>
                    <p class="hero-sub">
                        Darou Salam Business Company — votre partenaire de confiance pour l'approvisionnement en fruits frais de qualité supérieure à Dakar et dans toute la sous-région.
                    </p>
                    <div class="hero-ctas">
                        <a href="catalogue" class="btn-cta-primary">
                            <i class="bi bi-bag-fill"></i> Commander Maintenant
                        </a>
                        <a href="<?= BASE_URL ?>?page=tarifs_pro" class="btn-cta-secondary">
                            <i class="bi bi-file-earmark-text-fill"></i> Tarifs Pro
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
                            <div class="hstat-num">30</div>
                            <div class="hstat-label">Variétés</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <div class="hero-mosaic">
                    <div class="mosaic-img mosaic-1">
                        <img src="/photos/PHOTO-2026-05-14-21-57-40.jpg" alt="Mangues vertes fraîches">
                        <span class="mosaic-badge">Mangues Sélectionnées</span>
                    </div>
                    <div class="mosaic-img mosaic-2">
                        <img src="/photos/PHOTO-2026-05-14-22-05-35.jpg" alt="Mandarines fraîches">
                    </div>
                    <div class="mosaic-img mosaic-3">
                        <img src="/photos/PHOTO-2026-05-14-22-20-31.jpg" alt="Pêches fraîches">
                    </div>
                    <div class="mosaic-img mosaic-4">
                        <img src="/photos/PHOTO-2026-05-14-22-41-42.jpg" alt="Pastèques fraîches">
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
            ['<i class="bi bi-building-fill"></i>','Fournisseur Grandes Surfaces'],
            ['<i class="bi bi-shop-window"></i>','Partenaire Supermarchés & Hôtels'],
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
                <div class="stat-number"><span class="counter" data-target="30">0</span></div>
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
            <p class="section-sub">Cueillis à maturité optimale, disponibles au kilo et au carton pour les professionnels et particuliers.</p>
        </div>
        <div class="row g-2 g-md-4 align-items-stretch">
            <?php
            $delays = [0,1,2,3,0,1,2,3];
            $badgeClasses = ['Saison'=>'saison','Local'=>'saison','Promo'=>'promo','Nouveau'=>'','Premium'=>''];
            foreach ($produitsFeatured as $fi => $f):
                $imgUrl = !empty($f['image_principale'])
                    ? '/public/uploads/' . $f['image_principale']
                    : '/photos/PHOTO-2026-05-14-21-57-41.jpg';
                $badge = $f['badge'] ?? ($f['saison'] ? 'Saison' : '');
                $badgeClass = $badgeClasses[$badge] ?? '';
                $prixKg = number_format($f['prix_kg'], 0, ',', ' ') . ' FCFA';
                $prixCarton = $f['prix_carton'] ? number_format($f['prix_carton'], 0, ',', ' ') . ' FCFA / ' . ($f['poids_carton_kg'] ?? '?') . 'kg' : '';
                $origine = $f['origine'] ?? 'Sénégal';
                $delay = $delays[$fi % 4];
            ?>
            <div class="col-6 col-sm-4 col-lg-vedette reveal reveal-delay-<?= $delay ?>">
                <div class="fruit-card">
                    <div class="fruit-card-img">
                        <img src="<?= htmlspecialchars($imgUrl) ?>" alt="<?= htmlspecialchars($f['nom']) ?>" onerror="this.src='/photos/PHOTO-2026-05-14-21-57-41.jpg'">
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
            <a href="catalogue" class="btn btn-outline-success btn-lg px-5 rounded-pill" style="border-color:#1a5c2a;color:#1a5c2a;font-family:'DM Sans',sans-serif;font-weight:600;">
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
            $borderColors = ['#fb923c','#fb7185','#fcd34d','#4ade80','#84cc16','#fdba74','#60a5fa','#a78bfa','#f472b6','#34d399','#fbbf24','#f87171'];
            foreach ($categories as $ci => $c):
                $nb = (int)($c['nb_produits'] ?? 0);
                $countLabel = $nb > 1 ? $nb . ' variétés' : ($nb === 1 ? '1 variété' : 'Bientôt');
                $border = $borderColors[$ci % count($borderColors)];
                $imgSrc = !empty($c['image']) ? '/public/uploads/' . $c['image'] : '/photos/PHOTO-2026-05-14-21-57-41.jpg';
            ?>
            <a href="<?= BASE_URL ?>?page=catalogue&cat=<?= $c['id'] ?>" class="cat-card reveal" style="border:2px solid <?= $border ?>55;">
                <div class="cat-img-wrap">
                    <img class="cat-img" src="<?= htmlspecialchars($imgSrc) ?>" alt="<?= htmlspecialchars($c['nom']) ?>" onerror="this.src='/photos/PHOTO-2026-05-14-21-57-41.jpg'">
                    <div class="cat-img-overlay"></div>
                </div>
                <div class="cat-body">
                    <span class="cat-name"><?= htmlspecialchars($c['nom']) ?></span>
                    <span class="cat-count"><?= $countLabel ?></span>
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
                ['icon'=>'bi-award-fill',        'color'=>'#60a5fa', 'title'=>'Qualité Certifiée',   'text'=>'Nos fruits répondent aux normes des grandes surfaces et hôtels partenaires. Traçabilité et contrôle qualité rigoureux.'],
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

<!-- TÉMOIGNAGES CLIENTS -->
<section style="background:linear-gradient(135deg,#050d07 0%,#0f2d16 55%,#1a5c2a 100%);padding:20px 0;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-60px;right:-60px;width:300px;height:300px;border-radius:50%;background:rgba(212,160,23,.06);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-40px;left:-40px;width:200px;height:200px;border-radius:50%;background:rgba(249,115,22,.05);pointer-events:none;"></div>
    <div class="container" style="position:relative;z-index:2;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:16px;" class="reveal">
            <div>
                <h2 style="font-family:'Playfair Display',Georgia,serif;font-size:1.5rem;font-weight:900;color:#fff;margin:0 0 2px;">Ce que disent <em style="color:#d4a017;font-style:italic;">nos clients</em></h2>
                <p style="color:rgba(255,255,255,.45);font-size:.78rem;margin:0;">Des professionnels et particuliers qui nous font confiance.</p>
            </div>
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="display:flex;gap:2px;"><?php for($s=0;$s<5;$s++): ?><i class="bi bi-star-fill" style="color:#f59e0b;font-size:.85rem;"></i><?php endfor; ?></div>
                <span style="color:#fff;font-weight:800;font-size:.95rem;">4.9</span>
                <span style="color:rgba(255,255,255,.4);font-size:.75rem;">/ 5 · +200 avis</span>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $temoignages = [
                ['nom'=>'Amadou D.',  'role'=>'Gérant de restaurant, Dakar',     'avatar'=>'AD','color'=>'#1a5c2a','note'=>5,'delay'=>0,
                 'texte'=>'Qualité irréprochable et livraison toujours à l\'heure. Nos clients remarquent la fraîcheur des fruits. Je recommande vivement Darou Salam à tous les restaurateurs.'],
                ['nom'=>'Fatou N.',   'role'=>'Responsable achats, grande surface','avatar'=>'FN','color'=>'#b45309','note'=>5,'delay'=>1,
                 'texte'=>'Partenaire fiable depuis 3 ans. Les cartons sont toujours bien calibrés, sans mauvaises surprises. Le service client répond rapidement sur WhatsApp.'],
                ['nom'=>'Moussa S.',  'role'=>'Client particulier, Dakar Plateau', 'avatar'=>'MS','color'=>'#0369a1','note'=>5,'delay'=>2,
                 'texte'=>'Je commande chaque semaine pour ma famille. Les prix sont compétitifs et les fruits bien frais. La livraison à domicile est un vrai plus !'],
            ];
            foreach ($temoignages as $t): ?>
            <div class="col-md-4 reveal reveal-delay-<?= $t['delay'] ?>">
                <div style="background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:14px;padding:16px 18px;height:100%;backdrop-filter:blur(8px);transition:all .2s;"
                     onmouseover="this.style.background='rgba(255,255,255,.09)';this.style.borderColor='rgba(212,160,23,.4)'"
                     onmouseout="this.style.background='rgba(255,255,255,.05)';this.style.borderColor='rgba(255,255,255,.1)'">
                    <!-- Étoiles -->
                    <div style="display:flex;gap:3px;margin-bottom:10px;">
                        <?php for($s=0;$s<$t['note'];$s++): ?>
                        <i class="bi bi-star-fill" style="color:#f59e0b;font-size:.8rem;"></i>
                        <?php endfor; ?>
                    </div>
                    <!-- Guillemet -->
                    <div style="font-size:2rem;line-height:.7;color:rgba(212,160,23,.4);font-family:Georgia,serif;margin-bottom:6px;">"</div>
                    <!-- Texte -->
                    <p style="color:rgba(255,255,255,.8);font-size:.84rem;line-height:1.6;margin-bottom:14px;font-style:italic;"><?= $t['texte'] ?></p>
                    <!-- Auteur -->
                    <div style="display:flex;align-items:center;gap:12px;border-top:1px solid rgba(255,255,255,.08);padding-top:12px;">
                        <div style="width:42px;height:42px;border-radius:50%;background:<?= $t['color'] ?>;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;color:#fff;flex-shrink:0;">
                            <?= $t['avatar'] ?>
                        </div>
                        <div>
                            <div style="font-weight:700;color:#fff;font-size:.88rem;"><?= $t['nom'] ?></div>
                            <div style="font-size:.72rem;color:rgba(255,255,255,.45);"><?= $t['role'] ?></div>
                        </div>
                        <i class="bi bi-patch-check-fill ms-auto" style="color:#d4a017;font-size:1rem;" title="Client vérifié"></i>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA bas -->
        <div class="text-center mt-3 reveal">
            <a href="<?= BASE_URL ?>?page=catalogue" style="display:inline-flex;align-items:center;gap:9px;padding:11px 24px;background:#d4a017;color:#1c1917;border-radius:14px;font-weight:800;font-size:.88rem;text-decoration:none;box-shadow:0 4px 16px rgba(212,160,23,.35);transition:all .2s;"
               onmouseover="this.style.background='#b8880e'" onmouseout="this.style.background='#d4a017'">
                <i class="bi bi-bag-fill"></i> Commander maintenant
            </a>
        </div>
    </div>
</section>

<!-- TEMOIGNAGES -->
<section class="temoignages-section">
    <div class="container">
        <div class="text-center mb-3 reveal">
            <span class="section-label">Simple & Rapide</span>
            <h2 class="section-title" style="font-size:1.6rem;margin-bottom:4px;">Comment <em>Commander</em>&nbsp;?</h2>
            <p class="section-sub" style="font-size:.82rem;margin-bottom:0;">En 3 étapes, recevez vos fruits frais directement chez vous.</p>
        </div>
        <div class="row g-3 justify-content-center">
            <div class="col-md-4 reveal reveal-delay-0">
                <div class="temoignage-card text-center" style="padding:20px 18px;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#1a5c2a;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-search" style="color:#fff;font-size:1.1rem;"></i>
                    </div>
                    <div style="font-size:1.6rem;font-weight:900;color:#e8a020;margin-bottom:4px;">01</div>
                    <h4 style="font-weight:700;color:#1a5c2a;margin-bottom:6px;font-size:.95rem;">Choisissez vos fruits</h4>
                    <p style="color:#666;line-height:1.5;font-size:.82rem;margin:0;">Parcourez notre catalogue, au kg ou au carton selon vos besoins.</p>
                </div>
            </div>
            <div class="col-md-4 reveal reveal-delay-1">
                <div class="temoignage-card text-center" style="padding:20px 18px;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#e8a020;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-cart-check-fill" style="color:#fff;font-size:1.1rem;"></i>
                    </div>
                    <div style="font-size:1.6rem;font-weight:900;color:#e8a020;margin-bottom:4px;">02</div>
                    <h4 style="font-weight:700;color:#1a5c2a;margin-bottom:6px;font-size:.95rem;">Passez votre commande</h4>
                    <p style="color:#666;line-height:1.5;font-size:.82rem;margin:0;">Ajoutez au panier et validez en ligne ou via WhatsApp.</p>
                </div>
            </div>
            <div class="col-md-4 reveal reveal-delay-2">
                <div class="temoignage-card text-center" style="padding:20px 18px;">
                    <div style="width:48px;height:48px;border-radius:50%;background:#4ade80;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-truck" style="color:#fff;font-size:1.1rem;"></i>
                    </div>
                    <div style="font-size:1.6rem;font-weight:900;color:#e8a020;margin-bottom:4px;">03</div>
                    <h4 style="font-weight:700;color:#1a5c2a;margin-bottom:6px;font-size:.95rem;">Recevez en 24h</h4>
                    <p style="color:#666;line-height:1.5;font-size:.82rem;margin:0;">Livraison express sur Dakar et toute la sous-région.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA FINAL -->
<!-- ============================================================
   LIVRAISON & CARTE
   ============================================================ -->
<section style="background:#f8faf8;padding:28px 0;border-top:1px solid #e8f0e8;">
  <div class="container">
    <div style="text-align:center;margin-bottom:18px;">
      <span style="display:inline-block;background:#e8f5e9;color:#1a5c2a;font-size:.75rem;font-weight:700;letter-spacing:.08em;padding:5px 14px;border-radius:50px;text-transform:uppercase;margin-bottom:12px;">
        <i class="bi bi-truck-front-fill"></i> Livraison
      </span>
      <h2 style="font-size:1.9rem;font-weight:800;color:#0f2d16;margin:0 0 10px;">Nous livrons partout à Dakar</h2>
      <p style="color:#6b7280;font-size:.95rem;max-width:500px;margin:0 auto;">Livraison express depuis notre entrepôt à Dakar Médina. Zones et tarifs transparents.</p>
    </div>

    <div class="livraison-grid">

      <!-- Carte Google Maps -->
      <div style="border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);border:1px solid #e5e7eb;">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.123456789!2d-17.4511!3d14.6937!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTTCsDQxJzM3LjMiTiAxN8KwMjcnMDMuOSJX!5e0!3m2!1sfr!2ssn!4v1716000000000!5m2!1sfr!2ssn&q=Dakar+Médina+Rue+47"
          width="100%" height="300" style="border:0;display:block;" allowfullscreen="" loading="lazy"
          referrerpolicy="no-referrer-when-downgrade" title="Darou Salam Business - Dakar Médina"></iframe>
        <div style="padding:14px 16px;background:#fff;display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
          <i class="bi bi-geo-alt-fill" style="color:#1a5c2a;font-size:1.1rem;flex-shrink:0;"></i>
          <div style="flex:1;min-width:0;">
            <div style="font-weight:700;font-size:.85rem;color:#111827;">Darou Salam Business Company</div>
            <div style="font-size:.78rem;color:#6b7280;">Dakar Médina, Rue 47×37 — Ouvert Lun–Sam, 07h–19h</div>
          </div>
          <a href="https://www.google.com/maps/search/Dakar+Médina+Rue+47" target="_blank"
             style="font-size:.75rem;font-weight:700;color:#1a5c2a;text-decoration:none;white-space:nowrap;flex-shrink:0;">
            <i class="bi bi-box-arrow-up-right"></i> Itinéraire
          </a>
        </div>
      </div>

      <!-- Tableau des zones -->
      <div>
        <div style="background:#fff;border-radius:16px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.06);">
          <div style="padding:16px 20px;background:linear-gradient(135deg,#0f2d16,#1a5c2a);color:#fff;">
            <div style="font-weight:800;font-size:.95rem;"><i class="bi bi-map-fill me-2"></i>Zones de livraison</div>
            <div style="font-size:.75rem;opacity:.8;margin-top:2px;">Tarifs TTC · Livraison à domicile</div>
          </div>
          <div style="overflow-x:auto;">
          <table style="width:100%;border-collapse:collapse;min-width:280px;">
            <thead>
              <tr style="background:#f9fafb;border-bottom:1px solid #e5e7eb;">
                <th style="padding:10px 12px;text-align:left;font-size:.7rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.05em;">Zone</th>
                <th style="padding:10px 10px;text-align:center;font-size:.7rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Délai</th>
                <th style="padding:10px 12px;text-align:right;font-size:.7rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap;">Frais</th>
              </tr>
            </thead>
            <tbody>
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;"><i class="bi bi-star-fill" style="color:#f59e0b;font-size:.6rem;margin-right:4px;"></i>Dakar Centre</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">2–4h</td>
                <td style="padding:10px 12px;text-align:right;"><span style="background:#dcfce7;color:#166534;font-size:.75rem;font-weight:700;padding:3px 9px;border-radius:50px;white-space:nowrap;">Gratuit</span></td>
              </tr>
              <tr style="border-bottom:1px solid #f3f4f6;background:#fafafa;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Dakar Sud</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">2–4h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">1 000 FCFA</td>
              </tr>
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Dakar Banlieue</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">4–6h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">1 500 FCFA</td>
              </tr>
              <tr style="border-bottom:1px solid #f3f4f6;background:#fafafa;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Dakar Ouest</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">3–5h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">2 000 FCFA</td>
              </tr>
              <tr style="border-bottom:1px solid #f3f4f6;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Rufisque & Bargny</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">24h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">3 000 FCFA</td>
              </tr>
              <tr style="border-bottom:1px solid #f3f4f6;background:#fafafa;">
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Thiès</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">24–48h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">5 000 FCFA</td>
              </tr>
              <tr>
                <td style="padding:10px 12px;font-size:.8rem;font-weight:600;color:#111827;">Autres régions</td>
                <td style="padding:10px 10px;text-align:center;font-size:.78rem;color:#6b7280;white-space:nowrap;">48–72h</td>
                <td style="padding:10px 12px;text-align:right;font-size:.8rem;font-weight:700;color:#1a5c2a;white-space:nowrap;">8 000 FCFA</td>
              </tr>
            </tbody>
          </table>
          </div>
          <div style="padding:12px 16px;background:#f0fdf4;border-top:1px solid #dcfce7;display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
            <i class="bi bi-info-circle-fill" style="color:#1a5c2a;font-size:.85rem;flex-shrink:0;"></i>
            <span style="font-size:.75rem;color:#166534;">Livraison gratuite dès <strong>50 000 FCFA</strong> d'achat sur Dakar</span>
          </div>
        </div>
      </div>

    </div>
<style>
.livraison-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start; }
@media (max-width:767px) { .livraison-grid { grid-template-columns:1fr; } }
</style>
  </div>
</section>

<section class="cta-section">
    <div class="container position-relative">
        <div class="reveal">
            <div class="cta-emojis">
                <img src="/photos/PHOTO-2026-05-14-21-57-40.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/photos/PHOTO-2026-05-14-22-41-06.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/photos/PHOTO-2026-05-14-22-33-13.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
                <img src="/photos/PHOTO-2026-05-14-22-33-56.jpg" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.3);">
            </div>
            <h2 class="cta-title">Prêt à Commander des Fruits <em>d'Exception</em>&nbsp;?</h2>
            <p class="cta-sub">Créez votre compte professionnel et bénéficiez de tarifs grossiste,<br class="d-none d-md-block">de livraison express et d'un service dédié.</p>
            <div class="cta-buttons">
                <a href="inscription" class="btn-cta-white">
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
// Scroll reveal — désactivé sur mobile pour fluidifier le scroll
const revealEls = document.querySelectorAll('.reveal');
if (window.innerWidth < 768) {
    revealEls.forEach(el => el.classList.add('visible'));
} else {
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
    }, { threshold: 0.05 });
    revealEls.forEach(el => obs.observe(el));
}

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

/* ===== ANIMATION FLY-TO-CART + AJOUT AU PANIER AJAX (accueil) ===== */
(function() {
  /* CSS fly-clone */
  const styleEl = document.createElement('style');
  styleEl.textContent = '.fly-clone{position:fixed;border-radius:50%;pointer-events:none;z-index:99999;background-size:cover;background-position:center;box-shadow:0 4px 16px rgba(0,0,0,.25);}@keyframes cartShake{0%,100%{transform:scale(1) rotate(0deg)}20%{transform:scale(1.35) rotate(-12deg)}40%{transform:scale(1.2) rotate(10deg)}60%{transform:scale(1.3) rotate(-6deg)}80%{transform:scale(1.15) rotate(4deg)}}';
  document.head.appendChild(styleEl);

  function getActiveCartIcon() {
    const mobile  = document.getElementById('cart-icon-mobile');
    const desktop = document.getElementById('cart-icon');
    if (mobile && mobile.offsetParent !== null) return mobile;
    return desktop;
  }
  function flyToCart(sourceEl, imgSrc) {
    const cartIcon = getActiveCartIcon();
    if (!cartIcon || !sourceEl) return;
    const srcRect  = sourceEl.getBoundingClientRect();
    const cartRect = cartIcon.getBoundingClientRect();
    const size  = Math.min(srcRect.width, srcRect.height, 90);
    const clone = document.createElement('div');
    clone.className = 'fly-clone';
    clone.style.cssText = 'width:'+size+'px;height:'+size+'px;left:'+(srcRect.left+srcRect.width/2-size/2)+'px;top:'+(srcRect.top+srcRect.height/2-size/2)+'px;background-image:url('+imgSrc+');background-size:cover;background-position:center;';
    document.body.appendChild(clone);
    const destX = cartRect.left+cartRect.width/2  - (srcRect.left+srcRect.width/2);
    const destY = cartRect.top +cartRect.height/2 - (srcRect.top +srcRect.height/2);
    const dur = 680, start = performance.now();
    function easeOut(t){ return 1-Math.pow(1-t,3); }
    function easeInBack(t){ const c1=1.70158,c3=c1+1; return 1+c3*Math.pow(t-1,3)+c1*Math.pow(t-1,2); }
    (function tick(now){
      const t = Math.min((now-start)/dur,1);
      const p = easeOut(t);
      const sc = 1-easeInBack(t)*.88;
      const op = t<.7 ? 1 : 1-(t-.7)/.3;
      clone.style.transform = 'translate('+(destX*p)+'px,'+(destY*p)+'px) scale('+Math.max(sc,.05)+')';
      clone.style.opacity   = Math.max(op,0);
      if (t<1) requestAnimationFrame(tick);
      else {
        clone.remove();
        const bag = cartIcon.querySelector('i') || cartIcon;
        bag.style.animation = 'none';
        void bag.offsetWidth;
        bag.style.animation = 'cartShake .5s ease';
        setTimeout(()=>bag.style.animation='',500);
      }
    })(start);
  }

  const toast = document.createElement('div');
  toast.style.cssText = 'position:fixed;bottom:90px;right:24px;z-index:9999;background:#fff;border:1.5px solid #bbf7d0;border-radius:14px;box-shadow:0 8px 32px rgba(0,0,0,.13);padding:14px 18px;display:flex;align-items:center;gap:12px;min-width:260px;max-width:340px;transition:all .35s cubic-bezier(.4,0,.2,1);transform:translateX(120%);opacity:0;';
  const toastIcon  = document.createElement('i');
  toastIcon.style.cssText = 'font-size:1.3rem;flex-shrink:0;';
  const toastWrap  = document.createElement('div');
  const toastTitle = document.createElement('div');
  toastTitle.style.cssText = 'font-weight:700;font-size:.87rem;color:#0f2d16;';
  const toastSub   = document.createElement('div');
  toastSub.style.cssText = 'font-size:.75rem;color:#6b7280;margin-top:2px;';
  toastSub.textContent = 'Continuez vos achats';
  toastWrap.appendChild(toastTitle);
  toastWrap.appendChild(toastSub);
  toast.appendChild(toastIcon);
  toast.appendChild(toastWrap);
  document.body.appendChild(toast);
  let toastTimer;

  function showToast(msg, isError) {
    toast.style.borderColor  = isError ? '#fecaca' : '#bbf7d0';
    toastIcon.className      = 'bi bi-' + (isError ? 'x-circle-fill' : 'check-circle-fill');
    toastIcon.style.color    = isError ? '#ef4444' : '#22c55e';
    toastTitle.textContent   = msg;
    toast.style.transform    = 'translateX(0)';
    toast.style.opacity      = '1';
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => { toast.style.transform = 'translateX(120%)'; toast.style.opacity = '0'; }, 3000);
  }

  window.addToCart = function(produitId, nom, prix, img) {
    const btn = (typeof event !== 'undefined' && event.currentTarget) ? event.currentTarget : null;
    const origHTML = btn ? btn.innerHTML : '';
    if (btn) { btn.disabled = true; btn.textContent = 'Ajout…'; }

    /* Lancer l'animation fly depuis l'image du produit */
    const card   = btn ? btn.closest('.product-card, [class*="featured"], .col-6, .col-md-3') : null;
    const imgEl  = card ? card.querySelector('img') : null;
    const src    = img || (imgEl ? imgEl.src : '');
    flyToCart(imgEl || btn, src);

    const fd = new FormData();
    fd.append('produit_id', produitId);
    fd.append('quantite', '1');
    fd.append('unite', 'kg');

    (async function() {
      try {
        const r   = await fetch('<?= BASE_URL ?>?page=panier_ajouter', { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, body: fd });
        const res = await r.json();
        if (res.success) {
          if (typeof window.playCartSound === 'function') window.playCartSound();
          showToast('Ajouté au panier !', false);
          const badge = document.getElementById('cart-count');
          if (badge && res.nb_articles != null) {
            badge.textContent = res.nb_articles > 0 ? res.nb_articles : '';
            badge.style.animation = 'none';
            void badge.offsetWidth;
            badge.style.animation = 'cartBump .35s ease';
          }
        } else {
          showToast(res.message || 'Stock insuffisant', true);
        }
      } catch(e) {
        // silencieux
      } finally {
        if (btn) { btn.disabled = false; btn.innerHTML = origHTML; }
      }
    })();
  };
})();
</script>

<?php
/* ============================================================
   SECTION TEMOIGNAGES — 3 derniers avis approuves depuis la DB
   (fallback : 3 avis fictifs si la table est vide)
   ============================================================ */

$temoignages = [];
try {
    $dbHome = Database::getInstance();
    $temoignages = $dbHome->fetchAll(
        "SELECT nom, note, commentaire, created_at FROM avis WHERE statut='approuve' ORDER BY created_at DESC LIMIT 3"
    ) ?: [];
} catch (Exception $e) {
    $temoignages = [];
}

// Fallback : avis fictifs si aucun avis approuve en base
if (empty($temoignages)) {
    $temoignages = [
        ['nom' => 'Fatou Diagne',  'note' => 5, 'commentaire' => 'Des mangues incroyables, juteuses et bien emballées. Livraison rapide à Dakar. Je recommande vivement Darou Salam !', 'created_at' => '2026-05-10 10:00:00'],
        ['nom' => 'Moussa Sarr',   'note' => 5, 'commentaire' => 'Qualité impeccable pour les pastèques et les melons. Prix compétitifs, service client très réactif.', 'created_at' => '2026-05-08 14:30:00'],
        ['nom' => 'Aissatou Bâ',   'note' => 4, 'commentaire' => 'Très satisfaite de ma commande. Fruits frais et savoureux. Je commande régulièrement pour ma famille.', 'created_at' => '2026-05-05 09:15:00'],
    ];
}
?>

<section style="background:#fdf8ee;padding:72px 0;position:relative;overflow:hidden;">
    <!-- Decoration -->
    <div style="position:absolute;top:-60px;left:-60px;width:220px;height:220px;border-radius:50%;background:radial-gradient(circle,rgba(26,92,42,.07),transparent);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:radial-gradient(circle,rgba(249,115,22,.07),transparent);pointer-events:none;"></div>

    <div class="container" style="position:relative;z-index:1;">
        <!-- En-tete -->
        <div class="text-center mb-5">
            <div style="display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid #e5e7eb;border-radius:30px;padding:6px 18px;font-size:.78rem;font-weight:700;color:#1a5c2a;letter-spacing:.06em;text-transform:uppercase;margin-bottom:16px;">
                <i class="bi bi-star-fill" style="color:#f59e0b;"></i>
                Temoignages clients
            </div>
            <h2 style="font-family:'Playfair Display',Georgia,serif;font-size:clamp(1.8rem,3.5vw,2.5rem);font-weight:800;color:#0f2d16;margin-bottom:12px;">
                Ce que disent nos clients
            </h2>
            <p style="color:#6b7280;font-size:1rem;max-width:460px;margin:0 auto;">
                Des centaines de familles nous font confiance chaque semaine au Sénégal.
            </p>
        </div>

        <!-- Cards -->
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:960px;margin:0 auto 36px;">
            <?php foreach ($temoignages as $tm): ?>
            <div style="background:#fff;border-radius:20px;padding:28px;border:1px solid #e5e7eb;box-shadow:0 2px 16px rgba(0,0,0,.05);transition:transform .2s,box-shadow .2s;position:relative;"
                 onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 40px rgba(0,0,0,.1)';"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 2px 16px rgba(0,0,0,.05)';">
                <div style="position:absolute;top:18px;right:20px;font-size:2.8rem;color:#f0fdf4;font-family:Georgia,serif;line-height:1;">&ldquo;</div>
                <!-- Etoiles -->
                <div style="color:#f59e0b;font-size:.95rem;margin-bottom:12px;">
                    <?php for ($s = 1; $s <= 5; $s++): ?>
                        <i class="bi bi-star<?= $s <= (int)$tm['note'] ? '-fill' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
                <!-- Commentaire -->
                <p style="color:#374151;font-size:.9rem;line-height:1.65;font-style:italic;margin-bottom:20px;">
                    &ldquo;<?= htmlspecialchars($tm['commentaire'], ENT_QUOTES, 'UTF-8') ?>&rdquo;
                </p>
                <!-- Auteur -->
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#1a5c2a,#236b34);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;">
                        <?= mb_strtoupper(mb_substr($tm['nom'], 0, 1)) ?>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.88rem;color:#111827;"><?= htmlspecialchars($tm['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                        <div style="font-size:.72rem;color:#9ca3af;"><?= date('d/m/Y', strtotime($tm['created_at'])) ?></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- CTA vers page avis -->
        <div class="text-center">
            <a href="<?= BASE_URL ?>?page=avis" style="display:inline-flex;align-items:center;gap:8px;background:#1a5c2a;color:#fff;border-radius:50px;padding:14px 30px;font-size:.92rem;font-weight:700;text-decoration:none;transition:background .2s;"
               onmouseover="this.style.background='#236b34';" onmouseout="this.style.background='#1a5c2a';">
                <i class="bi bi-chat-square-quote-fill"></i>
                Voir tous les avis et laisser le vôtre
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
