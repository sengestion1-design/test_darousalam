<?php
requireLogin();
$cl = $client ?? [];
$prenom       = htmlspecialchars($cl['prenom'] ?? 'Client');
$nom          = htmlspecialchars($cl['nom'] ?? '');
$email        = htmlspecialchars($cl['email'] ?? '');
$telephone    = htmlspecialchars($cl['telephone'] ?? '—');
$typeClient   = htmlspecialchars($cl['type'] ?? 'particulier');
$adresse      = htmlspecialchars($cl['adresse'] ?? '');
$ville        = htmlspecialchars($cl['ville'] ?? '');
$entreprise   = htmlspecialchars($cl['entreprise'] ?? '');
$created      = isset($cl['created_at']) ? date('M Y', strtotime($cl['created_at'])) : '—';

// Commandes (réelles ou vide si modèle absent)
$commandes = $dernieresCommandes ?? [];
$totalCmds = $totalCommandes ?? 0;

// Stats
$totalDepense = 0;
$enCours = 0;
$livrees = 0;
foreach ($commandes as $c) {
    $totalDepense += $c['total'] ?? 0;
    $s = $c['statut'] ?? '';
    if (in_array($s, ['preparation','en_route','confirmee'])) $enCours++;
    if ($s === 'livree') $livrees++;
}

$statuts = [
    'livree'      => ['lib'=>'Livrée',      'color'=>'#22c55e','bg'=>'#f0fdf4'],
    'en_route'    => ['lib'=>'En route',    'color'=>'#f59e0b','bg'=>'#fffbeb'],
    'preparation' => ['lib'=>'Préparation', 'color'=>'#3b82f6','bg'=>'#eff6ff'],
    'confirmee'   => ['lib'=>'Confirmée',   'color'=>'#8b5cf6','bg'=>'#faf5ff'],
    'annulee'     => ['lib'=>'Annulée',     'color'=>'#ef4444','bg'=>'#fef2f2'],
    'en_attente'  => ['lib'=>'En attente',  'color'=>'#6b7280','bg'=>'#f9fafb'],
];
$cmdActive = null;
foreach ($commandes as $c) {
    if (in_array($c['statut'] ?? '', ['preparation','en_route','confirmee'])) { $cmdActive = $c; break; }
}
$progressPct = $cmdActive ? ($cmdActive['statut']==='confirmee'?15:($cmdActive['statut']==='preparation'?40:72)) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Mon espace — Darou Salam Business Company</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --vert:#1a5c2a; --vert-l:#2d8a42; --vert-xl:#3aad56;
  --vert-bg:#f0fdf4; --orange:#f97316; --or:#d4a017;
  --sombre:#0f2d16; --gris:#6b7280; --gris-l:#f4f4f0;
  --card-radius:18px; --font:'DM Sans',sans-serif;
}
*, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
html { overflow-x:hidden; }
body { font-family:var(--font); background:#f5f5f0; color:#1c1917; overflow-x:hidden; min-height:100vh; }

/* ===== TOPBAR ===== */
.dash-top {
  background:var(--sombre);
  padding:14px 0;
  position:sticky; top:0; z-index:1000;
  box-shadow:0 2px 12px rgba(0,0,0,.3);
  width:100%; left:0;
}
.dash-brand {
  font-family:'Playfair Display',serif;
  font-weight:900; font-size:1.1rem; color:#fff; text-decoration:none;
  display:flex; align-items:center; gap:10px;
}
.dash-brand img { width:36px;height:36px;border-radius:50%;object-fit:cover;border:2px solid var(--or); }
.dash-brand span { color:var(--or); }
.dash-nav-link {
  display:flex; align-items:center; gap:6px;
  color:rgba(255,255,255,.7); text-decoration:none;
  font-size:.82rem; font-weight:500;
  padding:7px 12px; border-radius:8px;
  transition:all .2s;
}
.dash-nav-link:hover { background:rgba(255,255,255,.08); color:#fff; }
.dash-nav-link.danger:hover { background:rgba(239,68,68,.15); color:#ef4444; }

/* ===== HERO ===== */
.dash-hero {
  background:linear-gradient(135deg, var(--sombre) 0%, var(--vert) 60%, var(--vert-l) 100%);
  padding:48px 0 80px;
  position:relative; overflow:hidden;
  width:100%; left:0;
}
.dash-hero::before {
  content:''; position:absolute;
  width:600px; height:600px; border-radius:50%;
  background:radial-gradient(circle, rgba(212,160,23,.12), transparent 70%);
  top:-200px; right:-100px;
}
.dash-hero::after {
  content:''; position:absolute;
  width:300px; height:300px; border-radius:50%;
  background:rgba(249,115,22,.08);
  bottom:-120px; left:-50px;
}
.hero-avatar {
  width:72px; height:72px; border-radius:50%;
  background:rgba(255,255,255,.12);
  border:3px solid rgba(255,255,255,.25);
  display:flex; align-items:center; justify-content:center;
  font-size:1.8rem; color:rgba(255,255,255,.8);
  flex-shrink:0;
}
.hero-name {
  font-family:'Playfair Display',serif;
  font-size:1.9rem; font-weight:900; color:#fff; line-height:1.15;
}
.hero-meta { font-size:.82rem; color:rgba(255,255,255,.65); margin-top:4px; }
.hero-badge {
  display:inline-flex; align-items:center; gap:6px;
  background:rgba(212,160,23,.2); border:1px solid rgba(212,160,23,.45);
  color:var(--or); border-radius:50px; padding:4px 14px;
  font-size:.72rem; font-weight:700; letter-spacing:.06em;
  text-transform:uppercase; margin-top:10px;
}

/* ===== STATS ===== */
.stats-row {
  display:grid; grid-template-columns:repeat(4,1fr); gap:16px;
  margin-top:-48px; position:relative; z-index:20;
  margin-bottom:28px;
}
.stat-card {
  background:#fff; border-radius:var(--card-radius);
  padding:20px 16px; text-align:center;
  box-shadow:0 4px 24px rgba(0,0,0,.08);
  border:1px solid rgba(0,0,0,.04);
  transition:transform .2s, box-shadow .2s;
}
.stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 32px rgba(0,0,0,.12); }
.stat-icon {
  width:48px; height:48px; border-radius:14px;
  display:flex; align-items:center; justify-content:center;
  font-size:1.3rem; margin:0 auto 12px;
}
.stat-val { font-size:1.5rem; font-weight:800; color:#1c1917; line-height:1; }
.stat-lbl { font-size:.72rem; color:var(--gris); margin-top:4px; font-weight:500; }

/* ===== CARDS ===== */
.ds-card {
  background:#fff; border-radius:var(--card-radius);
  box-shadow:0 2px 16px rgba(0,0,0,.06);
  border:1px solid rgba(0,0,0,.04);
  overflow:hidden; margin-bottom:20px;
}
.ds-card-head {
  padding:16px 20px; border-bottom:1px solid #f3f4f6;
  display:flex; align-items:center; justify-content:space-between;
}
.ds-card-title {
  font-size:.95rem; font-weight:700; color:#1c1917;
  display:flex; align-items:center; gap:8px;
}
.ds-card-title i { font-size:1.05rem; }

/* ===== COMMANDE ACTIVE ===== */
.active-order {
  margin:16px; border-radius:14px;
  border:1.5px solid #fed7aa;
  background:linear-gradient(135deg,#fff7ed,#fff);
  padding:18px;
}
.order-progress { background:#e5e7eb; border-radius:10px; height:8px; margin:14px 0 10px; overflow:hidden; }
.order-progress-fill {
  height:100%;
  background:linear-gradient(90deg, var(--orange), #ea580c);
  border-radius:10px; transition:width .6s ease;
}
.order-steps { display:flex; justify-content:space-between; }
.order-step { display:flex; flex-direction:column; align-items:center; gap:4px; font-size:.68rem; color:#9ca3af; }
.order-step .step-dot { width:20px; height:20px; border-radius:50%; background:#e5e7eb; display:flex; align-items:center; justify-content:center; font-size:.65rem; }
.order-step.done .step-dot { background:var(--vert); color:#fff; }
.order-step.done { color:var(--vert); font-weight:600; }
.order-step.current .step-dot { background:var(--orange); color:#fff; box-shadow:0 0 0 3px rgba(249,115,22,.25); }
.order-step.current { color:var(--orange); font-weight:700; }

/* ===== TABLE ===== */
.ds-table { width:100%; border-collapse:collapse; }
.ds-table th {
  font-size:.68rem; text-transform:uppercase; letter-spacing:.08em;
  color:var(--gris); font-weight:700; padding:12px 16px;
  background:#fafaf9; border-bottom:1px solid #f3f4f6;
  white-space:nowrap;
}
.ds-table td { padding:13px 16px; border-bottom:1px solid #f9fafb; font-size:.85rem; vertical-align:middle; }
.ds-table tr:last-child td { border-bottom:none; }
.ds-table tbody tr:hover td { background:#fafaf9; }
.order-num { font-weight:700; color:var(--vert); font-size:.83rem; }
.total-val { font-weight:700; color:var(--orange); }

/* ===== STATUT BADGE ===== */
.statut-badge {
  display:inline-flex; align-items:center; gap:5px;
  padding:4px 10px; border-radius:20px;
  font-size:.7rem; font-weight:700;
}

/* ===== FILTER TABS ===== */
.filter-tabs { display:flex; gap:6px; flex-wrap:wrap; }
.ftab {
  padding:5px 14px; border-radius:20px;
  font-size:.78rem; font-weight:600; cursor:pointer;
  border:1.5px solid #e5e7eb; background:#fff; color:var(--gris);
  transition:all .2s;
}
.ftab.active { background:var(--vert); color:#fff; border-color:var(--vert); }
.ftab:hover:not(.active) { border-color:var(--vert); color:var(--vert); }

/* ===== PROFIL ===== */
.profile-grid { display:grid; grid-template-columns:1fr 1fr; gap:0; }
.profile-item {
  padding:14px 20px; border-bottom:1px solid #f3f4f6;
}
.profile-item:nth-last-child(-n+2) { border-bottom:none; }
.profile-item label { font-size:.68rem; font-weight:700; color:#9ca3af; text-transform:uppercase; letter-spacing:.07em; display:block; margin-bottom:3px; }
.profile-item .val { font-size:.88rem; font-weight:600; color:#1c1917; }
.profile-link {
  display:flex; align-items:center; gap:8px;
  padding:11px 16px; border-radius:10px;
  background:#fafaf9; border:1.5px solid #f3f4f6;
  font-size:.82rem; font-weight:600; color:#1c1917;
  text-decoration:none; transition:all .2s; margin-bottom:8px;
}
.profile-link:hover { border-color:var(--vert); background:var(--vert-bg); color:var(--vert); }
.profile-link i { color:var(--vert); font-size:1rem; }

/* ===== QUICK ACTIONS ===== */
.qa-btn {
  display:flex; align-items:center; gap:12px;
  padding:14px 16px; border-radius:14px;
  font-size:.88rem; font-weight:700;
  text-decoration:none; margin-bottom:10px;
  transition:all .2s;
}
.qa-btn:last-child { margin-bottom:0; }
.qa-btn.primary { background:linear-gradient(135deg,var(--vert),var(--vert-l)); color:#fff; }
.qa-btn.primary:hover { box-shadow:0 6px 20px rgba(26,92,42,.35); transform:translateY(-1px); }
.qa-btn.whatsapp { background:#f0fdf4; color:#15803d; border:1.5px solid #bbf7d0; }
.qa-btn.whatsapp:hover { background:#dcfce7; }
.qa-btn.outline { background:#fff; color:#1c1917; border:1.5px solid #e5e7eb; }
.qa-btn.outline:hover { border-color:var(--vert); color:var(--vert); }
.qa-icon { width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.2);display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0; }
.qa-btn.whatsapp .qa-icon { background:rgba(21,128,61,.1); color:#15803d; }
.qa-btn.outline .qa-icon { background:var(--gris-l); color:var(--gris); }

/* ===== BTN DETAIL ===== */
.btn-ds {
  padding:6px 14px; border-radius:8px;
  font-size:.75rem; font-weight:600; font-family:var(--font);
  cursor:pointer; text-decoration:none; border:none; transition:all .2s;
}
.btn-ds-outline { background:var(--vert-bg); color:var(--vert); border:1px solid #bbf7d0; }
.btn-ds-outline:hover { background:var(--vert); color:#fff; }
.btn-ds-primary { background:var(--vert); color:#fff; }
.btn-ds-primary:hover { background:var(--vert-l); }

/* ===== MODAL PROFIL ===== */
.modal-overlay {
  position:fixed; inset:0; background:rgba(0,0,0,.5);
  z-index:2000; display:none; align-items:center; justify-content:center;
  backdrop-filter:blur(4px);
}
.modal-overlay.open { display:flex; }
.modal-box {
  background:#fff; border-radius:22px; width:100%; max-width:560px;
  max-height:90vh; overflow-y:auto;
  box-shadow:0 24px 80px rgba(0,0,0,.25);
  animation:modalIn .25s ease;
}
@keyframes modalIn { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
.modal-head {
  padding:22px 24px 16px;
  border-bottom:1px solid #f3f4f6;
  display:flex; align-items:center; justify-content:space-between;
  position:sticky; top:0; background:#fff; z-index:1;
}
.modal-title { font-family:'Playfair Display',serif; font-size:1.3rem; font-weight:700; color:var(--sombre); }
.modal-close {
  width:34px;height:34px;border-radius:50%;
  background:#f3f4f6;border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;
  font-size:1rem;color:var(--gris);transition:all .2s;
}
.modal-close:hover { background:#fee2e2; color:#ef4444; }
.modal-body { padding:22px 24px; }
.modal-foot { padding:16px 24px; border-top:1px solid #f3f4f6; display:flex; gap:10px; justify-content:flex-end; }

/* Form fields dans modal */
.mf-group { margin-bottom:16px; }
.mf-label { font-size:.78rem; font-weight:600; color:#374151; display:block; margin-bottom:6px; letter-spacing:.02em; }
.mf-row { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
.mf-wrap { position:relative; }
.mf-icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9ca3af; font-size:.9rem; pointer-events:none; }
.mf-wrap input, .mf-wrap select {
  width:100%; padding:10px 12px 10px 36px;
  border:1.5px solid #e5e7eb; border-radius:10px;
  font-size:.86rem; font-family:var(--font); color:#1c1917;
  background:#fafafa; transition:all .2s;
}
.mf-wrap input:focus, .mf-wrap select:focus {
  outline:none; border-color:var(--vert); background:#fff;
  box-shadow:0 0 0 3px rgba(26,92,42,.08);
}
.mf-section-title {
  font-size:.72rem; font-weight:700; color:var(--vert);
  text-transform:uppercase; letter-spacing:.07em;
  display:flex; align-items:center; gap:6px;
  margin:20px 0 12px; padding-top:16px;
  border-top:1px solid #f3f4f6;
}

/* Flash message */
.flash-banner {
  position:fixed; top:20px; right:20px; z-index:3000;
  background:var(--vert); color:#fff;
  padding:12px 20px; border-radius:12px;
  font-size:.86rem; font-weight:600;
  box-shadow:0 8px 24px rgba(26,92,42,.35);
  display:flex; align-items:center; gap:10px;
  animation:flashIn .3s ease, flashOut .4s ease 3s forwards;
}
@keyframes flashIn  { from{opacity:0;transform:translateX(30px)} to{opacity:1;transform:translateX(0)} }
@keyframes flashOut { to{opacity:0;transform:translateX(30px)} }

@media(max-width:768px) {
  .stats-row { grid-template-columns:1fr 1fr; }
  .profile-grid { grid-template-columns:1fr; }
  .mf-row { grid-template-columns:1fr; }
  .dash-hero { padding:32px 0 64px; }
}
</style>
</head>
<body>

<?php $flash = getFlashMessage(); if ($flash): ?>
<div class="flash-banner">
  <i class="bi bi-<?= $flash['type']==='success'?'check-circle-fill':'info-circle-fill' ?>"></i>
  <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>

<!-- ===== TOPBAR ===== -->
<div class="dash-top">
  <div class="container d-flex justify-content-between align-items-center">
    <a href="/darousalam/" class="dash-brand">
      <img src="/darousalam/logo.jpg" alt="Logo">
      Darou Salam <span>Business Co.</span>
    </a>
    <div class="d-flex align-items-center gap-2">
      <a href="<?= BASE_URL ?>?page=catalogue" class="dash-nav-link">
        <i class="bi bi-grid-3x3-gap-fill"></i> Catalogue
      </a>
      <a href="<?= BASE_URL ?>?page=panier" class="dash-nav-link">
        <i class="bi bi-bag-fill"></i> Panier
      </a>
      <a href="<?= BASE_URL ?>?page=logout" class="dash-nav-link danger">
        <i class="bi bi-box-arrow-right"></i> Déconnexion
      </a>
    </div>
  </div>
</div>

<!-- ===== HERO ===== -->
<div class="dash-hero">
  <div class="container" style="position:relative;z-index:2;">
    <div class="d-flex align-items-center gap-3">
      <div class="hero-avatar">
        <i class="bi bi-person-fill"></i>
      </div>
      <div>
        <div class="hero-name">Bonjour, <?= $prenom ?> !</div>
        <div class="hero-meta">
          <i class="bi bi-envelope" style="opacity:.7;"></i> <?= $email ?>
          &nbsp;&bull;&nbsp;
          <i class="bi bi-calendar3" style="opacity:.7;"></i> Membre depuis <?= $created ?>
        </div>
        <div class="hero-badge">
          <i class="bi bi-<?= $typeClient==='professionnel'?'briefcase-fill':'person-fill' ?>"></i>
          <?= ucfirst($typeClient) ?>
        </div>
      </div>
      <div class="ms-auto d-none d-md-block">
        <button onclick="openModal()" class="btn-ds btn-ds-primary" style="display:flex;align-items:center;gap:7px;padding:10px 18px;font-size:.84rem;">
          <i class="bi bi-pencil-fill"></i> Modifier mon profil
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ===== CONTENU ===== -->
<div class="container pb-5" style="position:relative;z-index:10;">

  <!-- Stats flottantes -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon" style="background:#e0f2fe;">
        <i class="bi bi-bag-check-fill" style="color:#0284c7;"></i>
      </div>
      <div class="stat-val"><?= $totalCmds ?></div>
      <div class="stat-lbl">Commandes</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#dcfce7;">
        <i class="bi bi-patch-check-fill" style="color:#16a34a;"></i>
      </div>
      <div class="stat-val"><?= $livrees ?></div>
      <div class="stat-lbl">Livrées</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#fff7ed;">
        <i class="bi bi-truck" style="color:#ea580c;"></i>
      </div>
      <div class="stat-val"><?= $enCours ?></div>
      <div class="stat-lbl">En cours</div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="background:#faf5ff;">
        <i class="bi bi-wallet2" style="color:#7c3aed;"></i>
      </div>
      <div class="stat-val" style="font-size:1rem;"><?= number_format($totalDepense,0,',',' ') ?></div>
      <div class="stat-lbl">FCFA dépensés</div>
    </div>
  </div>

  <div class="row g-4">

    <!-- === COLONNE GAUCHE === -->
    <div class="col-lg-8">

      <!-- Commande active -->
      <?php if ($cmdActive): ?>
      <div class="ds-card">
        <div class="ds-card-head">
          <div class="ds-card-title">
            <i class="bi bi-truck" style="color:var(--orange);"></i>
            Commande en cours
          </div>
          <?php $st = $statuts[$cmdActive['statut']] ?? $statuts['en_attente']; ?>
          <span class="statut-badge" style="color:<?= $st['color'] ?>;background:<?= $st['bg'] ?>;">
            <i class="bi bi-circle-fill" style="font-size:.45rem;"></i>
            <?= $st['lib'] ?>
          </span>
        </div>
        <div class="active-order">
          <div class="d-flex justify-content-between align-items-center mb-1">
            <strong style="font-size:.9rem;color:#1c1917;"><?= htmlspecialchars($cmdActive['reference'] ?? $cmdActive['num'] ?? '—') ?></strong>
            <span style="font-size:.78rem;color:var(--gris);"><?= htmlspecialchars($cmdActive['date_commande'] ?? $cmdActive['date'] ?? '') ?></span>
          </div>
          <div class="order-progress">
            <div class="order-progress-fill" style="width:<?= $progressPct ?>%;"></div>
          </div>
          <div class="order-steps">
            <div class="order-step done">
              <div class="step-dot"><i class="bi bi-check" style="font-size:.7rem;"></i></div>
              Confirmée
            </div>
            <div class="order-step <?= in_array($cmdActive['statut'],['preparation','en_route']) ? 'done' : '' ?> <?= $cmdActive['statut']==='preparation' ? 'current' : '' ?>">
              <div class="step-dot"><i class="bi bi-box-seam" style="font-size:.65rem;"></i></div>
              Préparation
            </div>
            <div class="order-step <?= $cmdActive['statut']==='en_route' ? 'current' : '' ?>">
              <div class="step-dot"><i class="bi bi-truck" style="font-size:.65rem;"></i></div>
              En route
            </div>
            <div class="order-step">
              <div class="step-dot"><i class="bi bi-house-check" style="font-size:.65rem;"></i></div>
              Livrée
            </div>
          </div>
          <div class="d-flex justify-content-between align-items-center mt-3">
            <span style="font-size:.81rem;color:var(--gris);">
              <i class="bi bi-calendar-event"></i>
              Livraison prévue : <strong style="color:#1c1917;"><?= htmlspecialchars($cmdActive['date_livraison'] ?? $cmdActive['livraison'] ?? '—') ?></strong>
            </span>
            <a href="<?= BASE_URL ?>?page=commande_detail&id=<?= $cmdActive['id'] ?? '' ?>" class="btn-ds btn-ds-outline">
              <i class="bi bi-eye"></i> Voir détail
            </a>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- Historique commandes -->
      <div class="ds-card">
        <div class="ds-card-head">
          <div class="ds-card-title">
            <i class="bi bi-clock-history" style="color:var(--vert);"></i>
            Historique des commandes
          </div>
          <div class="filter-tabs">
            <button class="ftab active" onclick="filtrer('tous',this)">Toutes</button>
            <button class="ftab" onclick="filtrer('livree',this)">Livrées</button>
            <button class="ftab" onclick="filtrer('cours',this)">En cours</button>
            <button class="ftab" onclick="filtrer('annulee',this)">Annulées</button>
          </div>
        </div>
        <?php if (empty($commandes)): ?>
        <div style="padding:40px;text-align:center;color:var(--gris);">
          <i class="bi bi-bag-x" style="font-size:2.5rem;opacity:.4;display:block;margin-bottom:12px;"></i>
          <div style="font-size:.9rem;">Aucune commande pour le moment</div>
          <a href="<?= BASE_URL ?>?page=catalogue" class="btn-ds btn-ds-primary" style="margin-top:14px;display:inline-flex;align-items:center;gap:6px;">
            <i class="bi bi-basket2-fill"></i> Découvrir le catalogue
          </a>
        </div>
        <?php else: ?>
        <div style="overflow-x:auto;">
          <table class="ds-table" id="orders-table">
            <thead>
              <tr>
                <th>N° commande</th>
                <th>Date</th>
                <th>Articles</th>
                <th>Total</th>
                <th>Livraison</th>
                <th>Statut</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($commandes as $c):
                $s = $c['statut'] ?? 'en_attente';
                $st = $statuts[$s] ?? $statuts['en_attente'];
                $isCours = in_array($s,['preparation','en_route','confirmee']);
              ?>
              <tr data-statut="<?= $s ?>">
                <td class="order-num"><?= htmlspecialchars($c['reference'] ?? $c['num'] ?? '—') ?></td>
                <td style="color:var(--gris);font-size:.82rem;"><?= htmlspecialchars($c['date_commande'] ?? $c['date'] ?? '—') ?></td>
                <td style="font-size:.83rem;"><?= (int)($c['nb_articles'] ?? $c['items'] ?? 0) ?> art.</td>
                <td class="total-val"><?= number_format($c['total'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                <td style="font-size:.82rem;color:var(--gris);"><?= htmlspecialchars($c['date_livraison'] ?? $c['livraison'] ?? '—') ?></td>
                <td>
                  <span class="statut-badge" style="color:<?= $st['color'] ?>;background:<?= $st['bg'] ?>;">
                    <i class="bi bi-circle-fill" style="font-size:.4rem;"></i>
                    <?= $st['lib'] ?>
                  </span>
                </td>
                <td>
                  <a href="<?= BASE_URL ?>?page=commande_detail&id=<?= $c['id'] ?? '' ?>" class="btn-ds btn-ds-outline">
                    <i class="bi bi-eye"></i> Détail
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- === COLONNE DROITE === -->
    <div class="col-lg-4">

      <!-- Profil -->
      <div class="ds-card">
        <div class="ds-card-head">
          <div class="ds-card-title">
            <i class="bi bi-person-badge-fill" style="color:var(--vert);"></i>
            Mon profil
          </div>
          <button onclick="openModal()" style="display:flex;align-items:center;gap:5px;background:none;border:none;color:var(--orange);font-size:.8rem;font-weight:700;cursor:pointer;font-family:var(--font);padding:4px 8px;border-radius:6px;transition:background .2s;" onmouseover="this.style.background='#fff7ed'" onmouseout="this.style.background='none'">
            <i class="bi bi-pencil-fill"></i> Modifier
          </button>
        </div>
        <div class="profile-grid">
          <div class="profile-item">
            <label><i class="bi bi-person me-1"></i>Prénom</label>
            <div class="val"><?= $prenom ?></div>
          </div>
          <div class="profile-item">
            <label><i class="bi bi-person me-1"></i>Nom</label>
            <div class="val"><?= $nom ?></div>
          </div>
          <div class="profile-item" style="grid-column:1/-1;">
            <label><i class="bi bi-envelope me-1"></i>Email</label>
            <div class="val"><?= $email ?></div>
          </div>
          <div class="profile-item">
            <label><i class="bi bi-telephone me-1"></i>Téléphone</label>
            <div class="val"><?= $telephone ?: '—' ?></div>
          </div>
          <div class="profile-item">
            <label><i class="bi bi-geo-alt me-1"></i>Ville</label>
            <div class="val"><?= $ville ?: 'Dakar' ?></div>
          </div>
          <?php if ($entreprise): ?>
          <div class="profile-item" style="grid-column:1/-1;">
            <label><i class="bi bi-briefcase me-1"></i>Entreprise</label>
            <div class="val"><?= $entreprise ?></div>
          </div>
          <?php endif; ?>
        </div>
        <div style="padding:12px 16px 16px;">
          <a href="<?= BASE_URL ?>?page=mot_de_passe_oublie" class="profile-link">
            <i class="bi bi-shield-lock-fill"></i>
            Changer mon mot de passe
          </a>
          <a href="<?= BASE_URL ?>?page=adresses" class="profile-link" style="margin-bottom:0;">
            <i class="bi bi-geo-alt-fill"></i>
            Mes adresses de livraison
          </a>
        </div>
      </div>

      <!-- Actions rapides -->
      <div class="ds-card">
        <div class="ds-card-head">
          <div class="ds-card-title">
            <i class="bi bi-lightning-charge-fill" style="color:var(--orange);"></i>
            Actions rapides
          </div>
        </div>
        <div style="padding:14px 16px;">
          <a href="<?= BASE_URL ?>?page=catalogue" class="qa-btn primary">
            <div class="qa-icon"><i class="bi bi-basket2-fill"></i></div>
            <div>
              <div>Commander des fruits</div>
              <div style="font-size:.72rem;opacity:.8;font-weight:400;">Voir tous nos produits</div>
            </div>
            <i class="bi bi-arrow-right ms-auto"></i>
          </a>
          <a href="https://wa.me/221774715353?text=Bonjour%20Darou%20Salam%2C%20j%27ai%20besoin%20d%27aide" target="_blank" class="qa-btn whatsapp">
            <div class="qa-icon"><i class="bi bi-whatsapp"></i></div>
            <div>
              <div>Contacter le support</div>
              <div style="font-size:.72rem;color:#6b7280;font-weight:400;">Disponible 7j/7</div>
            </div>
          </a>
          <a href="<?= BASE_URL ?>?page=historique" class="qa-btn outline">
            <div class="qa-icon"><i class="bi bi-clock-history"></i></div>
            <div>Toutes mes commandes</div>
          </a>
          <a href="<?= BASE_URL ?>?page=favoris" class="qa-btn outline" style="margin-bottom:0;">
            <div class="qa-icon"><i class="bi bi-heart-fill" style="color:#ef4444;"></i></div>
            <div>Mes favoris</div>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ===== MODAL MODIFICATION PROFIL ===== -->
<div class="modal-overlay" id="profileModal" onclick="if(event.target===this)closeModal()">
  <div class="modal-box">
    <div class="modal-head">
      <div class="modal-title">
        <i class="bi bi-person-gear" style="color:var(--vert);margin-right:8px;"></i>
        Modifier mon profil
      </div>
      <button class="modal-close" onclick="closeModal()"><i class="bi bi-x-lg"></i></button>
    </div>
    <form method="POST" action="<?= BASE_URL ?>?page=modifier_profil" id="profileForm">
      <div class="modal-body">

        <!-- Infos personnelles -->
        <div style="font-size:.72rem;font-weight:700;color:var(--vert);text-transform:uppercase;letter-spacing:.07em;display:flex;align-items:center;gap:6px;margin-bottom:14px;">
          <i class="bi bi-person-fill"></i> Informations personnelles
        </div>
        <div class="mf-row">
          <div class="mf-group">
            <label class="mf-label">Prénom <span style="color:#ef4444">*</span></label>
            <div class="mf-wrap">
              <i class="bi bi-person mf-icon"></i>
              <input type="text" name="prenom" value="<?= $prenom ?>" placeholder="Votre prénom" required>
            </div>
          </div>
          <div class="mf-group">
            <label class="mf-label">Nom <span style="color:#ef4444">*</span></label>
            <div class="mf-wrap">
              <i class="bi bi-person mf-icon"></i>
              <input type="text" name="nom" value="<?= $nom ?>" placeholder="Votre nom" required>
            </div>
          </div>
        </div>
        <div class="mf-row">
          <div class="mf-group">
            <label class="mf-label">Téléphone</label>
            <div class="mf-wrap">
              <i class="bi bi-telephone mf-icon"></i>
              <input type="tel" name="telephone" value="<?= $telephone !== '—' ? $telephone : '' ?>" placeholder="7X XXX XX XX">
            </div>
          </div>
          <div class="mf-group">
            <label class="mf-label">Ville</label>
            <div class="mf-wrap">
              <i class="bi bi-building mf-icon"></i>
              <input type="text" name="ville" value="<?= $ville ?>" placeholder="Dakar">
            </div>
          </div>
        </div>
        <div class="mf-group">
          <label class="mf-label">Adresse</label>
          <div class="mf-wrap">
            <i class="bi bi-geo-alt mf-icon"></i>
            <input type="text" name="adresse" value="<?= $adresse ?>" placeholder="Rue, quartier">
          </div>
        </div>

        <?php if ($typeClient === 'professionnel'): ?>
        <!-- Infos pro -->
        <div class="mf-section-title"><i class="bi bi-briefcase-fill"></i> Informations professionnelles</div>
        <div class="mf-group">
          <label class="mf-label">Nom de l'entreprise</label>
          <div class="mf-wrap">
            <i class="bi bi-shop mf-icon"></i>
            <input type="text" name="nom_entreprise" value="<?= $entreprise ?>" placeholder="Ma Société SARL">
          </div>
        </div>
        <?php endif; ?>

        <!-- Nouveau mot de passe -->
        <div class="mf-section-title"><i class="bi bi-shield-lock-fill"></i> Changer le mot de passe (optionnel)</div>
        <div class="mf-row">
          <div class="mf-group">
            <label class="mf-label">Nouveau mot de passe</label>
            <div class="mf-wrap" style="position:relative;">
              <i class="bi bi-lock mf-icon"></i>
              <input type="password" name="password" id="mpwd" placeholder="Laisser vide = inchangé">
              <button type="button" onclick="toggleMpwd()" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;padding:0;" tabindex="-1">
                <i class="bi bi-eye" id="meye"></i>
              </button>
            </div>
          </div>
          <div class="mf-group">
            <label class="mf-label">Confirmer</label>
            <div class="mf-wrap">
              <i class="bi bi-lock-fill mf-icon"></i>
              <input type="password" name="password_conf" placeholder="Répéter le mot de passe">
            </div>
          </div>
        </div>

      </div>
      <div class="modal-foot">
        <button type="button" onclick="closeModal()" style="padding:10px 20px;border-radius:10px;border:1.5px solid #e5e7eb;background:#fff;font-family:var(--font);font-size:.86rem;font-weight:600;cursor:pointer;color:var(--gris);">
          Annuler
        </button>
        <button type="submit" style="padding:10px 22px;border-radius:10px;border:none;background:var(--vert);color:#fff;font-family:var(--font);font-size:.86rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:7px;transition:background .2s;" onmouseover="this.style.background='var(--vert-l)'" onmouseout="this.style.background='var(--vert)'">
          <i class="bi bi-check-lg"></i> Enregistrer les modifications
        </button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Filter commandes
function filtrer(type, btn) {
  document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  document.querySelectorAll('#orders-table tbody tr').forEach(row => {
    const s = row.dataset.statut;
    const cours = ['preparation','en_route','confirmee'];
    if (type === 'tous') row.style.display = '';
    else if (type === 'cours') row.style.display = cours.includes(s) ? '' : 'none';
    else row.style.display = s === type ? '' : 'none';
  });
}

// Modal
function openModal()  { document.getElementById('profileModal').classList.add('open'); document.body.style.overflow='hidden'; }
function closeModal() { document.getElementById('profileModal').classList.remove('open'); document.body.style.overflow=''; }

// Toggle mot de passe
function toggleMpwd() {
  const inp = document.getElementById('mpwd');
  const icon = document.getElementById('meye');
  inp.type = inp.type === 'password' ? 'text' : 'password';
  icon.className = inp.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
}

// Auto-hide flash
setTimeout(() => { const f = document.querySelector('.flash-banner'); if(f) f.style.display='none'; }, 4000);
</script>
</body>
</html>
