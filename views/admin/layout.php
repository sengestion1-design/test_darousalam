<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= $pageTitle ?? 'Admin' ?> — Darou Salam</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
:root{
  --vert:#1a5c2a;--vert-l:#2d8a42;--sombre:#0f2d16;
  --orange:#f97316;--or:#d4a017;
  --sidebar-w:250px;--font:'DM Sans',sans-serif;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
html,body{height:100%;font-family:var(--font);background:#f5f5f0;color:#1c1917;}

/* SIDEBAR */
.admin-sidebar{
  position:fixed;top:0;left:0;bottom:0;
  width:var(--sidebar-w);
  background:linear-gradient(180deg,#050d07 0%,#0f2d16 50%,#1a5c2a 100%);
  display:flex;flex-direction:column;
  z-index:1000;overflow-y:auto;
}
.sidebar-brand{
  padding:22px 20px 18px;
  border-bottom:1px solid rgba(255,255,255,.08);
  display:flex;align-items:center;gap:10px;
  text-decoration:none;
}
.sidebar-brand img{width:40px;height:40px;border-radius:50%;object-fit:cover;border:2px solid var(--or);}
.sidebar-brand .name{font-family:'Playfair Display',serif;font-size:.95rem;font-weight:700;color:#fff;display:block;line-height:1.2;}
.sidebar-brand .tag{font-size:.58rem;letter-spacing:.14em;text-transform:uppercase;color:var(--or);}

.sidebar-section{padding:20px 14px 6px;font-size:.62rem;font-weight:700;color:rgba(255,255,255,.3);text-transform:uppercase;letter-spacing:.1em;}

.sidebar-link{
  display:flex;align-items:center;gap:10px;
  padding:10px 16px;margin:2px 8px;border-radius:10px;
  color:rgba(255,255,255,.65);font-size:.84rem;font-weight:500;
  text-decoration:none;transition:all .2s;
}
.sidebar-link i{font-size:1rem;width:18px;text-align:center;}
.sidebar-link:hover{background:rgba(255,255,255,.08);color:#fff;}
.sidebar-link.active{background:var(--vert);color:#fff;font-weight:700;}
.sidebar-link.active i{color:#fff;}
.sidebar-link .badge-count{margin-left:auto;background:var(--orange);color:#fff;font-size:.62rem;font-weight:700;padding:2px 7px;border-radius:20px;}

.sidebar-footer{
  margin-top:auto;padding:16px;
  border-top:1px solid rgba(255,255,255,.08);
}
.sidebar-user{display:flex;align-items:center;gap:10px;}
.sidebar-user-avatar{width:36px;height:36px;border-radius:50%;background:var(--vert);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;font-weight:700;flex-shrink:0;}
.sidebar-user-info .name{font-size:.82rem;font-weight:700;color:#fff;}
.sidebar-user-info .role{font-size:.68rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:.06em;}
.btn-logout{margin-top:12px;display:flex;align-items:center;gap:8px;padding:8px 12px;border-radius:8px;background:rgba(239,68,68,.12);border:1px solid rgba(239,68,68,.2);color:#fca5a5;font-size:.78rem;font-weight:600;text-decoration:none;transition:all .2s;}
.btn-logout:hover{background:rgba(239,68,68,.25);color:#fff;}

/* MAIN */
.admin-main{margin-left:var(--sidebar-w);min-height:100vh;display:flex;flex-direction:column;}
.admin-topbar{
  background:#fff;border-bottom:1px solid #e5e7eb;
  padding:14px 28px;display:flex;align-items:center;justify-content:space-between;
  position:sticky;top:0;z-index:100;
}
.page-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:800;color:var(--sombre);}
.page-breadcrumb{font-size:.75rem;color:#9ca3af;margin-top:1px;}
.topbar-actions{display:flex;align-items:center;gap:12px;}
.topbar-btn{display:flex;align-items:center;gap:6px;padding:8px 14px;border-radius:9px;font-size:.8rem;font-weight:600;text-decoration:none;cursor:pointer;border:none;font-family:var(--font);transition:all .2s;}
.topbar-btn.primary{background:var(--vert);color:#fff;}
.topbar-btn.primary:hover{background:var(--vert-l);}
.topbar-btn.outline{background:#fff;color:#374151;border:1.5px solid #e5e7eb;}
.topbar-btn.outline:hover{border-color:var(--vert);color:var(--vert);}

/* CONTENT */
.admin-content{padding:28px;flex:1;}

/* CARDS */
.a-card{background:#fff;border-radius:16px;border:1px solid #e5e7eb;box-shadow:0 1px 8px rgba(0,0,0,.04);overflow:hidden;margin-bottom:20px;}
.a-card-head{padding:16px 20px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;}
.a-card-title{font-size:.92rem;font-weight:700;color:#1c1917;display:flex;align-items:center;gap:8px;}

/* STATS */
.stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;}
.stat-box{background:#fff;border-radius:14px;padding:18px;border:1px solid #e5e7eb;box-shadow:0 1px 6px rgba(0,0,0,.04);}
.stat-box-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:12px;}
.stat-box-val{font-size:1.5rem;font-weight:800;color:#1c1917;line-height:1;}
.stat-box-lbl{font-size:.72rem;color:#9ca3af;margin-top:4px;}
.stat-box-delta{font-size:.72rem;font-weight:600;margin-top:6px;display:flex;align-items:center;gap:4px;}

/* TABLE */
.a-table{width:100%;border-collapse:collapse;}
.a-table th{font-size:.67rem;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:700;padding:11px 16px;background:#fafaf9;border-bottom:1px solid #f3f4f6;white-space:nowrap;}
.a-table td{padding:12px 16px;border-bottom:1px solid #f9fafb;font-size:.84rem;vertical-align:middle;}
.a-table tr:last-child td{border-bottom:none;}
.a-table tbody tr:hover td{background:#fafaf9;}

/* BADGE */
.status-badge{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:20px;font-size:.7rem;font-weight:700;}

/* SEARCH / FILTER */
.filter-bar{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
.filter-input{padding:8px 12px 8px 36px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.83rem;font-family:var(--font);background:#fafafa;width:220px;transition:all .2s;}
.filter-input:focus{outline:none;border-color:var(--vert);background:#fff;box-shadow:0 0 0 3px rgba(26,92,42,.08);}
.filter-wrap{position:relative;}
.filter-wrap i{position:absolute;left:11px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.88rem;pointer-events:none;}
.filter-select{padding:8px 12px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:.83rem;font-family:var(--font);background:#fafafa;cursor:pointer;}

/* ACTIONS BTNS */
.btn-sm-action{padding:5px 10px;border-radius:7px;font-size:.73rem;font-weight:600;font-family:var(--font);cursor:pointer;border:none;text-decoration:none;display:inline-flex;align-items:center;gap:4px;transition:all .2s;}
.btn-view{background:#eff6ff;color:#2563eb;}
.btn-view:hover{background:#2563eb;color:#fff;}
.btn-edit{background:#f0fdf4;color:#16a34a;}
.btn-edit:hover{background:#16a34a;color:#fff;}
.btn-del{background:#fef2f2;color:#dc2626;}
.btn-del:hover{background:#dc2626;color:#fff;}

@media(max-width:992px){
  .admin-sidebar{transform:translateX(-100%);}
  .admin-main{margin-left:0;}
  .stat-grid{grid-template-columns:1fr 1fr;}
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="admin-sidebar">
  <a href="<?= BASE_URL ?>?page=admin_dashboard" class="sidebar-brand">
    <img src="<?= BASE_URL ?>logo.jpg" alt="Logo">
    <div><span class="name">Darou Salam</span><span class="tag">Administration</span></div>
  </a>

  <div class="sidebar-section">Navigation</div>
  <a href="<?= BASE_URL ?>?page=admin_dashboard" class="sidebar-link <?= ($adminPage??'')==='dashboard'?'active':'' ?>">
    <i class="bi bi-speedometer2"></i> Tableau de bord
  </a>
  <a href="<?= BASE_URL ?>?page=adm_cmd" class="sidebar-link <?= ($adminPage??'')==='commandes'?'active':'' ?>">
    <i class="bi bi-bag-check"></i> Commandes
  </a>
  <a href="<?= BASE_URL ?>?page=adm_prd" class="sidebar-link <?= ($adminPage??'')==='produits'?'active':'' ?>">
    <i class="bi bi-box-seam"></i> Produits
  </a>
  <a href="<?= BASE_URL ?>?page=adm_stk" class="sidebar-link <?= ($adminPage??'')==='stocks'?'active':'' ?>">
    <i class="bi bi-bar-chart-line"></i> Stocks
  </a>
  <a href="<?= BASE_URL ?>?page=adm_stk_mvt" class="sidebar-link <?= ($adminPage??'')==='stock_mouvements'?'active':'' ?>">
    <i class="bi bi-arrow-left-right"></i> Mouvements
  </a>
  <a href="<?= BASE_URL ?>?page=adm_rpt" class="sidebar-link <?= ($adminPage??'')==='rapports'?'active':'' ?>">
    <i class="bi bi-graph-up-arrow"></i> Rapports
  </a>
  <a href="<?= BASE_URL ?>?page=adm_ship" class="sidebar-link <?= ($adminPage??'')==='livraisons'?'active':'' ?>">
    <i class="bi bi-truck"></i> Livraisons
  </a>
  <a href="<?= BASE_URL ?>?page=adm_drv" class="sidebar-link <?= ($adminPage??'')==='livreurs'?'active':'' ?>">
    <i class="bi bi-person-vcard"></i> Livreurs
  </a>
  <a href="<?= BASE_URL ?>?page=adm_msg" class="sidebar-link <?= ($adminPage??'')==='contacts'?'active':'' ?>" style="position:relative;">
    <i class="bi bi-envelope-fill"></i> Messages
    <?php
      $nbContactsNonLus = 0;
      try {
        $r = Database::getInstance()->fetch("SELECT COUNT(*) as nb FROM contacts WHERE lu = 0");
        $nbContactsNonLus = (int)($r['nb'] ?? 0);
      } catch(Exception $e) {}
      if ($nbContactsNonLus > 0): ?>
    <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:#dc2626;color:#fff;font-size:.6rem;font-weight:900;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;"><?= $nbContactsNonLus ?></span>
    <?php endif; ?>
  </a>
  <a href="<?= BASE_URL ?>?page=adm_avis" class="sidebar-link <?= ($adminPage??'')==='avis'?'active':'' ?>" style="position:relative;">
    <i class="bi bi-star-fill"></i> Avis clients
    <?php
      $nbAvisAttente = 0;
      try {
        $r2 = Database::getInstance()->fetch("SELECT COUNT(*) as nb FROM avis WHERE statut='en_attente'");
        $nbAvisAttente = (int)($r2['nb'] ?? 0);
      } catch(Exception $e2) {}
      if ($nbAvisAttente > 0): ?>
    <span style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:#f59e0b;color:#fff;font-size:.6rem;font-weight:900;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;"><?= $nbAvisAttente ?></span>
    <?php endif; ?>
  </a>
  <a href="<?= BASE_URL ?>?page=adm_promo" class="sidebar-link <?= ($adminPage??'')==='promotions'?'active':'' ?>">
    <i class="bi bi-tag-fill"></i> Promotions
  </a>
  <a href="<?= BASE_URL ?>?page=adm_zone" class="sidebar-link <?= ($adminPage??'')==='zones_livraison'?'active':'' ?>">
    <i class="bi bi-geo-alt-fill"></i> Zones livr.
  </a>
  <a href="<?= BASE_URL ?>?page=adm_clt" class="sidebar-link <?= ($adminPage??'')==='clients'?'active':'' ?>">
    <i class="bi bi-people"></i> Clients
  </a>
  <a href="<?= BASE_URL ?>?page=adm_cat" class="sidebar-link <?= ($adminPage??'')==='categories'?'active':'' ?>">
    <i class="bi bi-grid"></i> Catégories
  </a>
  <a href="<?= BASE_URL ?>?page=adm_usr" class="sidebar-link <?= ($adminPage??'')==='admins'?'active':'' ?>">
    <i class="bi bi-shield-lock"></i> Administrateurs
  </a>

  <div class="sidebar-section">Paramètres</div>
  <a href="<?= BASE_URL ?>?page=adm_cfg" class="sidebar-link <?= ($adminPage??'')==='settings'?'active':'' ?>">
    <i class="bi bi-gear-fill"></i> Paramètres
  </a>
  <a href="<?= BASE_URL ?>?page=adm_clt_add" class="sidebar-link">
    <i class="bi bi-person-plus-fill"></i> Ajouter utilisateur
  </a>
  <a href="<?= BASE_URL ?>" target="_blank" class="sidebar-link">
    <i class="bi bi-globe"></i> Voir le site
  </a>

  <div class="sidebar-footer">
    <div class="sidebar-user">
      <div class="sidebar-user-avatar"><?= strtoupper(substr($_SESSION['admin_nom']??'A',0,1)) ?></div>
      <div class="sidebar-user-info">
        <div class="name"><?= htmlspecialchars($_SESSION['admin_nom']??'Admin') ?></div>
        <div class="role"><?= htmlspecialchars($_SESSION['admin_role']??'admin') ?></div>
      </div>
    </div>
    <a href="<?= BASE_URL ?>?page=admin_logout" class="btn-logout">
      <i class="bi bi-box-arrow-left"></i> Déconnexion
    </a>
  </div>
</div>

<!-- MAIN -->
<div class="admin-main">
  <div class="admin-topbar">
    <div>
      <div class="page-title"><?= $pageTitle ?? 'Administration' ?></div>
      <div class="page-breadcrumb">Darou Salam &rsaquo; <?= $pageTitle ?? '' ?></div>
    </div>
    <div class="topbar-actions">
      <?php if (!empty($topbarActions)) echo $topbarActions; ?>

      <!-- Cloche notifications -->
      <div style="position:relative;" id="notif-wrap">
        <button id="notif-btn" onclick="toggleNotifPanel()"
          style="position:relative;width:40px;height:40px;border-radius:11px;border:1.5px solid #e5e7eb;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:border-color .15s,background .15s;"
          title="Commandes en attente">
          <i class="bi bi-bell-fill" style="font-size:1rem;color:#6b7280;display:block;transform-origin:top center;"></i>
          <span id="notif-count" style="display:none;position:absolute;top:-6px;right:-6px;background:#f97316;color:#fff;font-size:.6rem;font-weight:900;min-width:19px;height:19px;border-radius:10px;line-height:19px;text-align:center;padding:0 4px;border:2px solid #f5f5f0;"></span>
        </button>

        <!-- Dropdown panel -->
        <div id="notif-panel" style="display:none;position:absolute;top:50px;right:0;width:360px;background:#fff;border:1px solid #e5e7eb;border-radius:18px;box-shadow:0 16px 48px rgba(0,0,0,.13);z-index:9999;overflow:hidden;">
          <!-- Header -->
          <div style="padding:16px 20px 12px;border-bottom:1px solid #f3f4f6;display:flex;align-items:center;justify-content:space-between;">
            <div>
              <div style="font-size:.88rem;font-weight:800;color:#111827;">Commandes en attente</div>
              <div style="font-size:.68rem;color:#9ca3af;margin-top:1px;">Cliquez pour traiter</div>
            </div>
            <span id="notif-panel-count" style="background:#fff7ed;color:#f97316;font-size:.68rem;font-weight:800;padding:3px 10px;border-radius:20px;border:1px solid #fed7aa;"></span>
          </div>
          <!-- Liste -->
          <div id="notif-list" style="max-height:360px;overflow-y:auto;">
            <div id="notif-empty" style="padding:36px 20px;text-align:center;">
              <i class="bi bi-check2-circle" style="font-size:2rem;display:block;margin-bottom:10px;color:#d1d5db;"></i>
              <div style="font-size:.82rem;color:#9ca3af;font-weight:600;">Tout est traité</div>
              <div style="font-size:.72rem;color:#d1d5db;margin-top:3px;">Aucune commande en attente</div>
            </div>
          </div>
          <!-- Footer -->
          <div style="padding:10px 16px;border-top:1px solid #f3f4f6;background:#fafafa;">
            <a href="<?= BASE_URL ?>?page=adm_cmd"
               style="display:flex;align-items:center;justify-content:center;gap:6px;font-size:.76rem;font-weight:700;color:#1a5c2a;text-decoration:none;padding:8px;border-radius:9px;transition:background .12s;"
               onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
              <i class="bi bi-grid-3x3-gap"></i> Voir toutes les commandes
            </a>
          </div>
        </div>
      </div>

      <span style="font-size:.78rem;color:#9ca3af;"><?= date('d/m/Y H:i') ?></span>
    </div>
  </div>
  <div class="admin-content">
