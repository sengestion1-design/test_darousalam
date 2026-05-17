<?php
$pageTitle = 'Mon profil — ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';

$statutLabels = [
    'en_attente' => ['label' => 'En attente',  'color' => '#f59e0b', 'bg' => '#fef3c7'],
    'confirmee'  => ['label' => 'Confirmée',   'color' => '#2563eb', 'bg' => '#eff6ff'],
    'en_cours'   => ['label' => 'En cours',    'color' => '#7c3aed', 'bg' => '#ede9fe'],
    'expediee'   => ['label' => 'Expédiée',    'color' => '#0891b2', 'bg' => '#e0f2fe'],
    'livree'     => ['label' => 'Livrée',      'color' => '#16a34a', 'bg' => '#f0fdf4'],
    'annulee'    => ['label' => 'Annulée',     'color' => '#dc2626', 'bg' => '#fef2f2'],
];

// $membreDepuis est fourni par le controller via DATE_FORMAT MySQL
?>
<style>
:root{--vert:#1a5c2a;--vert-l:#236b34;--orange:#f97316;--or:#d4a017;}
*{box-sizing:border-box;}

.p-wrap { padding: 20px 0 30px; }

/* ── HERO COMPACT ── */
.p-hero {
  background: linear-gradient(120deg,#0f2d16 0%,#1a5c2a 55%,#2d8a42 100%);
  border-radius: 16px;
  padding: 18px 24px;
  display: flex;
  align-items: center;
  gap: 16px;
  margin-bottom: 14px;
  color: #fff;
}
.p-avatar {
  width: 50px; height: 50px;
  border-radius: 50%;
  background: var(--orange);
  display: flex; align-items: center; justify-content: center;
  font-size: 1.3rem; font-weight: 900; color: #fff;
  flex-shrink: 0;
  border: 2px solid rgba(255,255,255,.3);
}
.p-hero-info { flex: 1; min-width: 0; }
.p-hero-info .badge-type {
  background: rgba(255,255,255,.15);
  border: 1px solid rgba(255,255,255,.25);
  border-radius: 20px;
  padding: 2px 10px;
  font-size: .7rem; font-weight: 700;
  letter-spacing: .05em; text-transform: uppercase;
  display: inline-block; margin-bottom: 4px;
}
.p-hero-info h2 {
  font-family:'Playfair Display',serif;
  font-size: 1.3rem; font-weight: 800; margin: 0 0 3px; color: #fff;
}
.p-hero-info .sub { font-size: .8rem; color: rgba(255,255,255,.7); }
.p-hero-btns { display: flex; flex-direction: column; gap: 8px; flex-shrink: 0; }
.p-btn-white {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.3);
  color: #fff; border-radius: 9px; padding: 7px 16px;
  font-size: .8rem; font-weight: 700; text-decoration: none; cursor: pointer;
  transition: background .2s; white-space: nowrap;
}
.p-btn-white:hover { background: rgba(255,255,255,.25); color: #fff; }
.p-btn-white.solid { background: rgba(255,255,255,.9); color: #1a5c2a; }
.p-btn-white.solid:hover { background: #fff; }

/* ── STATS ROW ── */
.p-stats {
  display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 14px;
}
@media(max-width:700px){ .p-stats { grid-template-columns: repeat(2,1fr); } }
.p-stat {
  background: #fff; border-radius: 12px;
  padding: 12px 8px; text-align: center;
  border: 1px solid #f0f0f0;
  box-shadow: 0 1px 4px rgba(0,0,0,.04);
}
.p-stat .v { font-size: 1.3rem; font-weight: 800; color: #0f2d16; line-height: 1; }
.p-stat .l { font-size: .65rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; margin-top: 3px; }

/* ── MAIN GRID ── */
.p-grid { display: grid; grid-template-columns: 5fr 6fr; gap: 14px; align-items: start; }
@media(max-width:768px){ .p-grid { grid-template-columns: 1fr; } }

/* ── CARDS ── */
.p-card {
  background: #fff; border-radius: 14px;
  border: 1px solid #f0f0f0;
  box-shadow: 0 1px 8px rgba(0,0,0,.04);
  overflow: hidden;
}
.p-card-head {
  padding: 12px 18px; border-bottom: 1px solid #f5f5f5;
  display: flex; align-items: center; justify-content: space-between;
  background: #fafafa;
}
.p-card-head .ttl {
  font-weight: 700; font-size: .85rem; color: #0f2d16;
  display: flex; align-items: center; gap: 7px;
}
.p-card-head a { font-size: .73rem; color: var(--vert); text-decoration: none; font-weight: 700; }

/* ── INFO ROWS ── */
.p-info-row {
  display: flex; justify-content: space-between; align-items: center;
  padding: 7px 18px; border-bottom: 1px solid #fafafa;
  gap: 10px;
}
.p-info-row:last-child { border-bottom: none; }
.p-info-lbl { font-size: .73rem; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; white-space: nowrap; }
.p-info-val { font-size: .84rem; color: #1f2937; font-weight: 600; text-align: right; }
.p-info-empty { color: #d1d5db; font-style: italic; font-weight: 400; }

/* ── CMD ITEMS ── */
.p-cmd { display: flex; align-items: center; gap: 10px; padding: 10px 18px; border-bottom: 1px solid #fafafa; }
.p-cmd:last-child { border-bottom: none; }
.p-cmd-badge { padding: 2px 8px; border-radius: 20px; font-size: .67rem; font-weight: 700; white-space: nowrap; }

/* ── ACTIONS ── */
.p-action {
  display: flex; align-items: center; gap: 12px;
  padding: 10px 18px; border-bottom: 1px solid #fafafa;
  text-decoration: none; color: #1f2937;
  transition: background .15s;
}
.p-action:last-child { border-bottom: none; }
.p-action:hover { background: #f9fafb; }
.p-action-ico {
  width: 34px; height: 34px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  font-size: .95rem; flex-shrink: 0;
}
.p-action .p-action-text { flex: 1; min-width: 0; }
.p-action .p-action-text strong { display: block; font-size: .82rem; font-weight: 700; }
.p-action .p-action-text span { font-size: .72rem; color: #9ca3af; }
.p-action .p-chevron { color: #d1d5db; font-size: .75rem; }
</style>

<div class="container">
<div class="p-wrap">

  <!-- Hero -->
  <div class="p-hero">
    <div class="p-avatar">
      <?= strtoupper(substr($client['prenom'] ?? $client['nom'] ?? 'U', 0, 1)) ?>
    </div>
    <div class="p-hero-info">
      <span class="badge-type">
        <?= ($client['type'] ?? 'particulier') === 'professionnel' ? '🏢 Professionnel' : '👤 Particulier' ?>
      </span>
      <h2><?= htmlspecialchars(trim(($client['civilite'] ? $client['civilite'].' ' : '') . $client['prenom'] . ' ' . strtoupper($client['nom']))) ?></h2>
      <div class="sub">
        <i class="bi bi-envelope me-1"></i><?= htmlspecialchars($client['email']) ?>
        <?php if (!empty($client['telephone'])): ?>
          &nbsp;·&nbsp;<i class="bi bi-telephone me-1"></i><?= htmlspecialchars($client['telephone']) ?>
        <?php endif; ?>
      </div>
    </div>
    <div class="p-hero-btns">
      <a href="<?= BASE_URL ?>?page=modifier_profil" class="p-btn-white solid">
        <i class="bi bi-pencil-fill"></i> Modifier
      </a>
      <a href="<?= BASE_URL ?>?page=historique" class="p-btn-white">
        <i class="bi bi-bag-check"></i> Commandes
      </a>
    </div>
  </div>

  <!-- Stats -->
  <div class="p-stats">
    <div class="p-stat">
      <div class="v"><?= (int)($statsCmd['nb'] ?? 0) ?></div>
      <div class="l">Commandes</div>
    </div>
    <div class="p-stat">
      <div class="v" style="color:#16a34a;"><?= (int)($statsCmd['nb_livrees'] ?? 0) ?></div>
      <div class="l">Livrées</div>
    </div>
    <div class="p-stat">
      <div class="v" style="color:#f59e0b;"><?= (int)($statsCmd['nb_attente'] ?? 0) ?></div>
      <div class="l">En attente</div>
    </div>
    <div class="p-stat">
      <div class="v" style="font-size:1.1rem;"><?= number_format((float)($statsCmd['total_depense'] ?? 0), 0, ',', ' ') ?></div>
      <div class="l">FCFA dépensés</div>
    </div>
  </div>

  <!-- Grille -->
  <div class="p-grid">

    <!-- Infos personnelles -->
    <div class="p-card">
      <div class="p-card-head">
        <div class="ttl"><i class="bi bi-person-fill" style="color:var(--vert);"></i> Informations</div>
        <a href="<?= BASE_URL ?>?page=modifier_profil"><i class="bi bi-pencil"></i> Modifier</a>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Prénom</span>
        <span class="p-info-val"><?= htmlspecialchars($client['prenom'] ?? '') ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Nom</span>
        <span class="p-info-val"><?= htmlspecialchars($client['nom'] ?? '') ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Email</span>
        <span class="p-info-val" style="word-break:break-all;font-size:.78rem;"><?= htmlspecialchars($client['email']) ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Téléphone</span>
        <span class="p-info-val"><?= !empty($client['telephone']) ? htmlspecialchars($client['telephone']) : '<span class="p-info-empty">—</span>' ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Adresse</span>
        <span class="p-info-val"><?= !empty($client['adresse']) ? htmlspecialchars($client['adresse']) : '<span class="p-info-empty">—</span>' ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Ville</span>
        <span class="p-info-val"><?= !empty($client['ville']) ? htmlspecialchars($client['ville']) : '<span class="p-info-empty">—</span>' ?></span>
      </div>
      <div class="p-info-row">
        <span class="p-info-lbl">Pays</span>
        <span class="p-info-val"><?= htmlspecialchars($client['pays'] ?? 'Sénégal') ?></span>
      </div>
      <?php if (($client['type'] ?? '') === 'professionnel'): ?>
      <div class="p-info-row">
        <span class="p-info-lbl">Entreprise</span>
        <span class="p-info-val"><?= !empty($client['entreprise']) ? htmlspecialchars($client['entreprise']) : '<span class="p-info-empty">—</span>' ?></span>
      </div>
      <?php endif; ?>
      <div class="p-info-row">
        <span class="p-info-lbl">Membre depuis</span>
        <span class="p-info-val"><?= $membreDepuis ?></span>
      </div>
    </div>

    <!-- Colonne droite : une seule carte combinée -->
    <div class="p-card" style="align-self:start;">

      <!-- Dernières commandes -->
      <div class="p-card-head">
        <div class="ttl"><i class="bi bi-bag-check-fill" style="color:var(--vert);"></i> Dernières commandes</div>
        <a href="<?= BASE_URL ?>?page=historique">Voir tout <i class="bi bi-arrow-right"></i></a>
      </div>
      <?php if (empty($dernieresCommandes)): ?>
      <div style="padding:20px;text-align:center;color:#9ca3af;font-size:.82rem;">
        <i class="bi bi-bag" style="font-size:1.6rem;display:block;margin-bottom:6px;"></i>
        Aucune commande passée
      </div>
      <?php else: ?>
        <?php foreach ($dernieresCommandes as $cmd):
          $s = $statutLabels[$cmd['statut']] ?? ['label'=>ucfirst($cmd['statut']),'color'=>'#6b7280','bg'=>'#f3f4f6'];
        ?>
        <div class="p-cmd">
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.82rem;">#<?= $cmd['id'] ?></div>
            <div style="font-size:.7rem;color:#9ca3af;"><?= date('d/m/Y', strtotime($cmd['created_at'])) ?></div>
          </div>
          <span class="p-cmd-badge" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;"><?= $s['label'] ?></span>
          <div style="font-weight:700;font-size:.82rem;white-space:nowrap;"><?= number_format((float)$cmd['total'],0,',',' ') ?> <small style="font-weight:500;">FCFA</small></div>
          <a href="<?= BASE_URL ?>?page=commande_detail&id=<?= $cmd['id'] ?>" style="color:var(--vert);"><i class="bi bi-eye"></i></a>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Séparateur -->
      <div style="height:1px;background:#f3f4f6;margin:4px 0;"></div>

      <!-- Actions rapides (compactes, sans icônes carrés) -->
      <div class="p-card-head" style="border-top:none;">
        <div class="ttl"><i class="bi bi-lightning-fill" style="color:var(--orange);"></i> Actions rapides</div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
        <a href="<?= BASE_URL ?>?page=catalogue" class="p-action" style="border-right:1px solid #fafafa;">
          <i class="bi bi-grid-fill" style="color:var(--vert);font-size:.9rem;flex-shrink:0;"></i>
          <span style="font-size:.78rem;font-weight:600;color:#1f2937;">Catalogue</span>
        </a>
        <a href="<?= BASE_URL ?>?page=historique" class="p-action">
          <i class="bi bi-clock-history" style="color:#2563eb;font-size:.9rem;flex-shrink:0;"></i>
          <span style="font-size:.78rem;font-weight:600;color:#1f2937;">Commandes</span>
        </a>
        <a href="<?= BASE_URL ?>?page=modifier_profil" class="p-action" style="border-right:1px solid #fafafa;border-top:1px solid #fafafa;">
          <i class="bi bi-pencil-fill" style="color:#d97706;font-size:.9rem;flex-shrink:0;"></i>
          <span style="font-size:.78rem;font-weight:600;color:#1f2937;">Modifier profil</span>
        </a>
        <a href="<?= BASE_URL ?>?page=contact" class="p-action" style="border-top:1px solid #fafafa;">
          <i class="bi bi-headset" style="color:#7c3aed;font-size:.9rem;flex-shrink:0;"></i>
          <span style="font-size:.78rem;font-weight:600;color:#1f2937;">Support</span>
        </a>
      </div>
      <a href="<?= BASE_URL ?>?page=logout" class="p-action" style="border-top:1px solid #f3f4f6;color:#dc2626;gap:8px;">
        <i class="bi bi-box-arrow-right" style="font-size:.9rem;flex-shrink:0;"></i>
        <span style="font-size:.78rem;font-weight:700;">Déconnexion</span>
      </a>

    </div>
  </div>

</div><!-- /p-wrap -->
</div><!-- /container -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
