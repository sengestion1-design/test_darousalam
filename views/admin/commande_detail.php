<?php
$adminPage = 'commandes';
$pageTitle = 'Commande ' . htmlspecialchars($commande['reference']);
require_once __DIR__ . '/layout.php';

$statutsMap = [
    'en_attente'     => ['lib'=>'En attente',   'color'=>'#1d4ed8','bg'=>'#dbeafe'],
    'confirmee'      => ['lib'=>'Confirmée',    'color'=>'#0e7490','bg'=>'#cffafe'],
    'en_preparation' => ['lib'=>'Préparation',  'color'=>'#92400e','bg'=>'#fef3c7'],
    'en_livraison'   => ['lib'=>'En livraison', 'color'=>'#7c3aed','bg'=>'#faf5ff'],
    'livree'         => ['lib'=>'Livrée',       'color'=>'#166534','bg'=>'#dcfce7'],
    'annulee'        => ['lib'=>'Annulée',      'color'=>'#991b1b','bg'=>'#fee2e2'],
];

$paiementsMap = [
    'cash'         => ['lib'=>'Paiement à la livraison', 'icon'=>'bi-cash-coin'],
    'wave'         => ['lib'=>'Wave',                    'icon'=>'bi-phone'],
    'orange_money' => ['lib'=>'Orange Money',            'icon'=>'bi-phone-fill'],
];

$statut  = $statutsMap[$commande['statut']] ?? ['lib'=>ucfirst($commande['statut']),'color'=>'#6b7280','bg'=>'#f3f4f6'];
$paie    = $paiementsMap[$commande['mode_paiement']] ?? ['lib'=>$commande['mode_paiement'],'icon'=>'bi-credit-card'];
$adresse = json_decode($commande['adresse_livraison'] ?? '{}', true);
$clientNom = trim(($commande['client_prenom']??'') . ' ' . ($commande['client_nom']??''));
if(!$clientNom) $clientNom = 'Client #' . $commande['client_id'];
?>

<!-- Breadcrumb retour -->
<div style="margin-bottom:20px;">
  <a href="/darousalam/admin/commandes" style="display:inline-flex;align-items:center;gap:6px;color:#6b7280;font-size:.82rem;text-decoration:none;font-weight:600;">
    <i class="bi bi-arrow-left"></i> Retour aux commandes
  </a>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

  <!-- Colonne gauche -->
  <div>

    <!-- En-tête commande -->
    <div class="a-card" style="margin-bottom:20px;">
      <div style="padding:22px 24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
          <div style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:800;color:var(--sombre);">
            <?= htmlspecialchars($commande['reference']) ?>
          </div>
          <div style="font-size:.78rem;color:#9ca3af;margin-top:4px;">
            <i class="bi bi-calendar3"></i> <?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?>
            &nbsp;·&nbsp; #<?= $commande['id'] ?>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
          <span class="status-badge" style="color:<?= $statut['color'] ?>;background:<?= $statut['bg'] ?>;font-size:.8rem;padding:6px 14px;">
            <?= $statut['lib'] ?>
          </span>
          <!-- Changer statut -->
          <form method="POST" action="<?= BASE_URL ?>?page=admin_commande_statut">
            <input type="hidden" name="id" value="<?= $commande['id'] ?>">
            <input type="hidden" name="redirect" value="admin_commande_detail&id=<?= $commande['id'] ?>">
            <select name="statut" onchange="this.form.submit()"
              style="padding:6px 10px;border-radius:9px;font-size:.78rem;font-weight:600;border:1.5px solid #e5e7eb;cursor:pointer;font-family:var(--font);">
              <?php foreach($statutsMap as $k=>$s): ?>
              <option value="<?= $k ?>" <?= $commande['statut']===$k?'selected':'' ?>><?= $s['lib'] ?></option>
              <?php endforeach; ?>
            </select>
          </form>
        </div>
      </div>
    </div>

    <!-- Articles commandés -->
    <div class="a-card" style="margin-bottom:20px;">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-basket2" style="color:var(--vert);"></i> Articles commandés</div>
        <span style="font-size:.78rem;color:#9ca3af;"><?= count($lignes) ?> article(s)</span>
      </div>
      <div style="padding:16px 20px;">
        <?php foreach($lignes as $ligne):
          $img = null;
          if(!empty($ligne['image_principale'])) {
            $parts = explode('/', $ligne['image_principale'], 2);
            $img = '/darousalam/' . $parts[0] . '/' . rawurlencode($parts[1] ?? '');
          }
        ?>
        <div style="display:flex;align-items:center;gap:16px;padding:14px 0;border-bottom:1px solid #f3f4f6;">
          <?php if($img): ?>
          <img src="<?= htmlspecialchars($img) ?>" style="width:64px;height:64px;border-radius:12px;object-fit:cover;border:1px solid #e5e7eb;" onerror="this.style.display='none'">
          <?php else: ?>
          <div style="width:64px;height:64px;border-radius:12px;background:#f3f4f6;display:flex;align-items:center;justify-content:center;">
            <i class="bi bi-image" style="color:#9ca3af;font-size:1.3rem;"></i>
          </div>
          <?php endif; ?>
          <div style="flex:1;">
            <div style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($ligne['nom_produit']) ?></div>
            <div style="font-size:.76rem;color:#9ca3af;margin-top:3px;">
              <?= number_format($ligne['quantite'],1,',',' ') ?> <?= htmlspecialchars($ligne['unite']??'kg') ?>
              &times; <?= number_format($ligne['prix_unitaire'],0,',',' ') ?> FCFA
            </div>
          </div>
          <div style="font-weight:800;color:var(--orange);font-size:.92rem;white-space:nowrap;">
            <?= number_format($ligne['total_ligne'],0,',',' ') ?> FCFA
          </div>
        </div>
        <?php endforeach; ?>

        <!-- Totaux -->
        <div style="margin-top:16px;">
          <div style="display:flex;justify-content:space-between;font-size:.83rem;color:#6b7280;padding:6px 0;">
            <span>Sous-total</span>
            <span><?= number_format($commande['sous_total'],0,',',' ') ?> FCFA</span>
          </div>
          <?php if (!empty($commande['remise']) && (float)$commande['remise'] > 0): ?>
          <div style="display:flex;justify-content:space-between;font-size:.83rem;padding:6px 0;color:#16a34a;">
            <span style="display:flex;align-items:center;gap:6px;">
              <i class="bi bi-scissors"></i> Remise
              <?php if (!empty($commande['code_promo'])): ?>
              <span style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:5px;padding:1px 8px;font-family:monospace;font-size:.75rem;font-weight:700;color:#15803d;letter-spacing:1px;">
                <?= htmlspecialchars($commande['code_promo']) ?>
              </span>
              <?php endif; ?>
            </span>
            <span style="font-weight:700;">-<?= number_format((float)$commande['remise'],0,',',' ') ?> FCFA</span>
          </div>
          <?php endif; ?>
          <div style="display:flex;justify-content:space-between;font-size:.83rem;color:#6b7280;padding:6px 0;">
            <span>Frais de livraison</span>
            <span><?= $commande['frais_livraison'] > 0 ? number_format($commande['frais_livraison'],0,',',' ').' FCFA' : 'Offerts' ?></span>
          </div>
          <div style="display:flex;justify-content:space-between;font-weight:800;font-size:1rem;color:#1c1917;padding:12px 0 0;border-top:2px solid #f3f4f6;margin-top:6px;">
            <span>Total</span>
            <span style="color:var(--orange);"><?= number_format($commande['total'],0,',',' ') ?> FCFA</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Notes -->
    <?php if(!empty($commande['notes'])): ?>
    <div class="a-card">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-chat-left-text" style="color:var(--vert);"></i> Notes client</div>
      </div>
      <div style="padding:16px 20px;font-size:.85rem;color:#374151;font-style:italic;">
        "<?= htmlspecialchars($commande['notes']) ?>"
      </div>
    </div>
    <?php endif; ?>

  </div>

  <!-- Colonne droite -->
  <div>

    <!-- Infos client -->
    <div class="a-card" style="margin-bottom:16px;">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-person" style="color:var(--vert);"></i> Client</div>
      </div>
      <div style="padding:16px 20px;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
          <div style="width:44px;height:44px;border-radius:50%;background:var(--vert);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;">
            <?= strtoupper(substr($commande['client_prenom']??'C',0,1).substr($commande['client_nom']??'',0,1)) ?>
          </div>
          <div>
            <div style="font-weight:700;font-size:.88rem;"><?= htmlspecialchars($clientNom) ?></div>
            <div style="font-size:.74rem;color:#9ca3af;"><?= htmlspecialchars($commande['client_email']??'') ?></div>
          </div>
        </div>
        <?php if(!empty($commande['client_tel'])): ?>
        <div style="display:flex;align-items:center;gap:8px;font-size:.82rem;color:#374151;padding:6px 0;">
          <i class="bi bi-telephone" style="color:#9ca3af;"></i>
          <?= htmlspecialchars($commande['client_tel']) ?>
        </div>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>?page=admin_clients" style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:.76rem;color:var(--vert);font-weight:600;text-decoration:none;">
          <i class="bi bi-arrow-right-circle"></i> Voir profil client
        </a>
      </div>
    </div>

    <!-- Adresse livraison -->
    <div class="a-card" style="margin-bottom:16px;">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-geo-alt" style="color:var(--vert);"></i> Livraison</div>
      </div>
      <div style="padding:16px 20px;font-size:.83rem;color:#374151;">
        <?php if(!empty($adresse['adresse'])): ?>
        <div style="margin-bottom:6px;"><i class="bi bi-house" style="color:#9ca3af;margin-right:6px;"></i><?= htmlspecialchars($adresse['adresse']) ?></div>
        <?php endif; ?>
        <?php if(!empty($adresse['ville'])): ?>
        <div style="margin-bottom:6px;"><i class="bi bi-buildings" style="color:#9ca3af;margin-right:6px;"></i><?= htmlspecialchars($adresse['ville']) ?></div>
        <?php endif; ?>
        <?php if(!empty($adresse['telephone'])): ?>
        <div><i class="bi bi-telephone" style="color:#9ca3af;margin-right:6px;"></i><?= htmlspecialchars($adresse['telephone']) ?></div>
        <?php endif; ?>
        <?php if(empty($adresse)): ?>
        <span style="color:#9ca3af;">Adresse non renseignée</span>
        <?php endif; ?>
      </div>
    </div>

    <!-- Suivi livreur -->
    <div class="a-card" style="margin-bottom:16px;">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-bicycle" style="color:var(--vert);"></i> Suivi livreur</div>
      </div>
      <div style="padding:16px 20px;">
        <?php
          $etapesCmd = [
            'en_attente'     => ['lib'=>'Commande reçue',      'icon'=>'bi-bag-check-fill'],
            'confirmee'      => ['lib'=>'Commande confirmée',  'icon'=>'bi-clipboard2-check-fill'],
            'en_preparation' => ['lib'=>'En préparation',      'icon'=>'bi-box-seam-fill'],
            'en_livraison'   => ['lib'=>'En livraison',        'icon'=>'bi-bicycle'],
            'livree'         => ['lib'=>'Livrée',              'icon'=>'bi-house-check-fill'],
          ];
          $ordreCmd   = array_keys($etapesCmd);
          $statutCmd  = $commande['statut'];
          $idxCmd     = array_search($statutCmd === 'annulee' ? 'en_attente' : $statutCmd, $ordreCmd);
        ?>

        <!-- Tracker visuel -->
        <div style="margin-bottom:20px;">
          <?php if ($statutCmd === 'annulee'): ?>
          <div style="text-align:center;padding:8px;color:#dc2626;font-size:.85rem;font-weight:700;">
            <i class="bi bi-x-circle-fill me-1"></i> Commande annulée
          </div>
          <?php else: ?>
          <div style="position:relative;">
            <?php foreach ($etapesCmd as $key => $etape):
              $idx  = array_search($key, $ordreCmd);
              $fait = $idx <= $idxCmd;
              $actif= $idx === $idxCmd;
              $col  = $fait ? '#1a5c2a' : '#d1d5db';
              $bg   = $fait ? '#dcfce7' : '#f3f4f6';
            ?>
            <div style="display:flex;gap:12px;">
              <div style="display:flex;flex-direction:column;align-items:center;">
                <div style="width:34px;height:34px;border-radius:50%;background:<?= $bg ?>;border:2px solid <?= $col ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="bi <?= $etape['icon'] ?>" style="color:<?= $col ?>;font-size:.8rem;"></i>
                </div>
                <?php if ($key !== 'livree'): ?>
                <div style="width:2px;height:22px;background:<?= ($fait && $idx < $idxCmd) ? '#1a5c2a' : '#e5e7eb' ?>;margin:2px 0;"></div>
                <?php endif; ?>
              </div>
              <div style="padding-top:5px;">
                <div style="font-size:.8rem;font-weight:<?= $actif?'800':'600' ?>;color:<?= $actif?'#1a5c2a':($fait?'#374151':'#9ca3af') ?>;">
                  <?= $etape['lib'] ?>
                  <?php if ($actif): ?>
                  <span style="display:inline-block;width:6px;height:6px;background:#1a5c2a;border-radius:50%;margin-left:4px;vertical-align:middle;"></span>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
          <?php endif; ?>
        </div>

        <div style="border-top:1px solid #f3f4f6;margin-bottom:16px;"></div>

        <?php if (!empty($livraison)): ?>
          <?php
            $lvMap = [
              'en_attente' => ['lib'=>'En attente',  'bg'=>'#f3f4f6','color'=>'#6b7280','icon'=>'bi-clock'],
              'assignee'   => ['lib'=>'Assignée',    'bg'=>'#fef9c3','color'=>'#92400e','icon'=>'bi-person-check-fill'],
              'en_cours'   => ['lib'=>'En cours',    'bg'=>'#dbeafe','color'=>'#1d4ed8','icon'=>'bi-bicycle'],
              'livree'     => ['lib'=>'Livrée',      'bg'=>'#dcfce7','color'=>'#15803d','icon'=>'bi-check-circle-fill'],
              'echec'      => ['lib'=>'Échec',       'bg'=>'#fee2e2','color'=>'#b91c1c','icon'=>'bi-x-circle-fill'],
            ];
            $lvS = $lvMap[$livraison['statut']] ?? $lvMap['en_attente'];
          ?>
          <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
            <div style="width:42px;height:42px;border-radius:50%;background:#f0fdf4;border:2px solid #bbf7d0;display:flex;align-items:center;justify-content:center;font-size:1.1rem;color:#16a34a;flex-shrink:0;">
              <i class="bi bi-person-badge-fill"></i>
            </div>
            <div>
              <div style="font-weight:700;font-size:.9rem;"><?= htmlspecialchars($livraison['livreur_nom'] ?? 'Non assigné') ?></div>
              <?php if (!empty($livraison['livreur_tel'])): ?>
              <div style="font-size:.75rem;color:#9ca3af;"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($livraison['livreur_tel']) ?></div>
              <?php endif; ?>
              <?php if (!empty($livraison['livreur_zone'])): ?>
              <div style="font-size:.75rem;color:#9ca3af;"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($livraison['livreur_zone']) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
            <span style="background:<?= $lvS['bg'] ?>;color:<?= $lvS['color'] ?>;border-radius:8px;padding:4px 12px;font-size:.78rem;font-weight:700;display:inline-flex;align-items:center;gap:5px;">
              <i class="bi <?= $lvS['icon'] ?>"></i> <?= $lvS['lib'] ?>
            </span>
            <?php if (!empty($livraison['date_prevue'])): ?>
            <span style="font-size:.75rem;color:#9ca3af;"><i class="bi bi-calendar-event me-1"></i>Prévue le <?= date('d/m/Y', strtotime($livraison['date_prevue'])) ?></span>
            <?php endif; ?>
          </div>
          <!-- Changer statut livraison -->
          <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_statut" style="display:flex;gap:6px;align-items:center;">
            <input type="hidden" name="livraison_id" value="<?= $livraison['id'] ?>">
            <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
            <select name="statut" style="flex:1;font-size:.78rem;border:1px solid #e5e7eb;border-radius:7px;padding:5px 8px;">
              <?php foreach ($lvMap as $k => $s): ?>
              <option value="<?= $k ?>" <?= $livraison['statut']===$k?'selected':'' ?>><?= $s['lib'] ?></option>
              <?php endforeach; ?>
            </select>
            <button type="submit" style="background:var(--vert);color:#fff;border:none;border-radius:7px;padding:5px 12px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap;">
              <i class="bi bi-check2"></i> Mettre à jour
            </button>
          </form>
          <?php if (!empty($livraison['notes'])): ?>
          <div style="margin-top:10px;font-size:.78rem;color:#6b7280;font-style:italic;padding:8px 12px;background:#f9fafb;border-radius:8px;">
            <i class="bi bi-chat-left-text me-1"></i><?= htmlspecialchars($livraison['notes']) ?>
          </div>
          <?php endif; ?>
        <?php else: ?>
          <div style="text-align:center;padding:10px 0 6px;color:#9ca3af;font-size:.83rem;margin-bottom:12px;">
            <i class="bi bi-person-x" style="font-size:1.5rem;display:block;margin-bottom:6px;"></i>
            Aucun livreur assigné
          </div>
          <form method="POST" action="<?= BASE_URL ?>?page=admin_livraison_assigner">
            <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
            <div style="margin-bottom:8px;">
              <select name="livreur_id" required style="width:100%;font-size:.82rem;border:1.5px solid #e5e7eb;border-radius:8px;padding:7px 10px;">
                <option value="">— Choisir un livreur —</option>
                <?php foreach ($livreurs as $lr): ?>
                <option value="<?= $lr['id'] ?>"><?= htmlspecialchars($lr['nom']) ?> <?= !empty($lr['zone']) ? '('.$lr['zone'].')' : '' ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div style="margin-bottom:8px;">
              <input type="date" name="date_prevue" placeholder="Date prévue (optionnel)"
                style="width:100%;font-size:.82rem;border:1.5px solid #e5e7eb;border-radius:8px;padding:7px 10px;">
            </div>
            <button type="submit" style="width:100%;background:var(--vert);color:#fff;border:none;border-radius:8px;padding:9px;font-size:.85rem;font-weight:700;cursor:pointer;">
              <i class="bi bi-send-fill"></i> Assigner le livreur
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>

    <!-- Paiement -->
    <div class="a-card">
      <div class="a-card-head">
        <div class="a-card-title"><i class="bi bi-credit-card" style="color:var(--vert);"></i> Paiement</div>
      </div>
      <div style="padding:16px 20px;">

        <?php
          $paieStatuts = [
            'en_attente' => ['lib'=>'En attente',  'icon'=>'bi-hourglass-split',    'bg'=>'#f3f4f6','color'=>'#6b7280'],
            'partiel'    => ['lib'=>'Partiel',      'icon'=>'bi-pie-chart-fill',     'bg'=>'#fef9c3','color'=>'#92400e'],
            'paye'       => ['lib'=>'Payé',         'icon'=>'bi-check-circle-fill',  'bg'=>'#dcfce7','color'=>'#15803d'],
            'rembourse'  => ['lib'=>'Remboursé',    'icon'=>'bi-arrow-counterclockwise','bg'=>'#dbeafe','color'=>'#1d4ed8'],
          ];
          $statutPaie = $commande['statut_paiement'] ?? 'en_attente';
          $pS = $paieStatuts[$statutPaie] ?? $paieStatuts['en_attente'];

          // Tracker paiement
          $etapesPaie = [
            'en_attente' => ['lib'=>'En attente de paiement', 'icon'=>'bi-hourglass-split'],
            'partiel'    => ['lib'=>'Paiement partiel reçu',  'icon'=>'bi-pie-chart-fill'],
            'paye'       => ['lib'=>'Paiement complet',       'icon'=>'bi-check-circle-fill'],
          ];
          $ordrePaie  = array_keys($etapesPaie);
          $idxPaie    = $statutPaie === 'rembourse' ? 2 : (array_search($statutPaie, $ordrePaie) ?: 0);
        ?>

        <!-- Mode de paiement -->
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px;padding:12px 14px;background:#f9fafb;border-radius:10px;">
          <i class="bi <?= $paie['icon'] ?>" style="font-size:1.3rem;color:var(--vert);"></i>
          <div>
            <div style="font-size:.75rem;color:#9ca3af;font-weight:600;text-transform:uppercase;letter-spacing:.4px;">Mode de paiement</div>
            <div style="font-size:.88rem;font-weight:700;color:#1c1917;"><?= $paie['lib'] ?></div>
          </div>
        </div>

        <!-- Tracker visuel paiement -->
        <div style="margin-bottom:16px;">
          <?php if ($statutPaie === 'rembourse'): ?>
          <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#dbeafe;border-radius:10px;">
            <i class="bi bi-arrow-counterclockwise" style="color:#1d4ed8;font-size:1.1rem;"></i>
            <span style="font-size:.85rem;font-weight:700;color:#1d4ed8;">Paiement remboursé</span>
          </div>
          <?php else: ?>
          <?php foreach ($etapesPaie as $k => $ep):
            $idx  = array_search($k, $ordrePaie);
            $fait = $idx <= $idxPaie;
            $actif= $idx === $idxPaie;
            $col  = $fait ? '#1a5c2a' : '#d1d5db';
            $bg   = $fait ? '#dcfce7' : '#f3f4f6';
          ?>
          <div style="display:flex;gap:12px;">
            <div style="display:flex;flex-direction:column;align-items:center;">
              <div style="width:32px;height:32px;border-radius:50%;background:<?= $bg ?>;border:2px solid <?= $col ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi <?= $ep['icon'] ?>" style="color:<?= $col ?>;font-size:.75rem;"></i>
              </div>
              <?php if ($k !== 'paye'): ?>
              <div style="width:2px;height:20px;background:<?= ($fait && $idx < $idxPaie)?'#1a5c2a':'#e5e7eb' ?>;margin:2px 0;"></div>
              <?php endif; ?>
            </div>
            <div style="padding-top:4px;">
              <div style="font-size:.8rem;font-weight:<?= $actif?'800':'600' ?>;color:<?= $actif?'#1a5c2a':($fait?'#374151':'#9ca3af') ?>;">
                <?= $ep['lib'] ?>
                <?php if ($actif): ?>
                <span style="display:inline-block;width:6px;height:6px;background:#1a5c2a;border-radius:50%;margin-left:4px;vertical-align:middle;"></span>
                <?php endif; ?>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Changer statut paiement -->
        <form method="POST" action="<?= BASE_URL ?>?page=admin_commande_paiement" style="display:flex;gap:6px;align-items:center;">
          <input type="hidden" name="id" value="<?= $commande['id'] ?>">
          <select name="statut_paiement" style="flex:1;font-size:.78rem;border:1px solid #e5e7eb;border-radius:7px;padding:5px 8px;background:<?= $pS['bg'] ?>;color:<?= $pS['color'] ?>;font-weight:600;">
            <?php foreach ($paieStatuts as $k => $s): ?>
            <option value="<?= $k ?>" <?= $statutPaie===$k?'selected':'' ?>><?= $s['lib'] ?></option>
            <?php endforeach; ?>
          </select>
          <button type="submit" style="background:var(--vert);color:#fff;border:none;border-radius:7px;padding:5px 12px;font-size:.78rem;font-weight:600;cursor:pointer;white-space:nowrap;">
            <i class="bi bi-check2"></i> Valider
          </button>
        </form>

        <div style="margin-top:10px;padding:8px 12px;background:#f9fafb;border-radius:8px;font-size:.75rem;color:#9ca3af;">
          <i class="bi bi-info-circle me-1"></i>Réf : <?= htmlspecialchars($commande['reference']) ?>
          <?php if (!empty($commande['remise']) && (float)$commande['remise'] > 0): ?>
          &nbsp;·&nbsp;<i class="bi bi-scissors me-1" style="color:#16a34a;"></i>
          <span style="color:#16a34a;font-weight:600;">Remise <?= htmlspecialchars($commande['code_promo'] ?? '') ?> : -<?= number_format((float)$commande['remise'],0,',',' ') ?> FCFA</span>
          <?php endif; ?>
        </div>

        <?php if ($statutPaie === 'paye'): ?>
        <a href="<?= BASE_URL ?>?page=admin_facture_pdf&id=<?= $commande['id'] ?>"
           style="display:flex;align-items:center;justify-content:center;gap:8px;margin-top:14px;padding:10px 16px;background:linear-gradient(135deg,#1a5c2a,#236b34);color:#fff;border-radius:10px;text-decoration:none;font-size:.85rem;font-weight:700;transition:opacity .2s;"
           onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
          <i class="bi bi-file-earmark-pdf-fill" style="font-size:1.1rem;"></i>
          Télécharger la facture PDF
        </a>
        <?php endif; ?>


      </div>
    </div>

  </div>
</div>

<?php require_once __DIR__ . '/layout_end.php'; ?>
