<?php
$pageTitle = 'Commande ' . $commande['reference'] . ' - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';

$statutActuel = $commande['statut'];
$clientNom = trim(($commande['prenom'] ?? '') . ' ' . ($commande['nom'] ?? ''));
if (!$clientNom) $clientNom = 'Client #' . $commande['client_id'];
$adr = json_decode($commande['adresse_livraison'] ?? '{}', true) ?: [];
$fraisLivr = (float)($commande['frais_livraison'] ?? 0);
$remise = (float)($commande['remise'] ?? 0);
$sousTotal = 0;
foreach ($lignes as $l) $sousTotal += (float)$l['total_ligne'];

$modePaieLabel = match($commande['mode_paiement'] ?? '') {
    'wave'         => 'Wave',
    'orange_money' => 'Orange Money',
    'free_money'   => 'Free Money',
    'virement'     => 'Virement bancaire',
    'carte'        => 'Carte bancaire',
    'cash'         => 'Espèces',
    default        => 'Paiement à la livraison',
};

$statutConfig = [
    'en_attente'     => ['label'=>'En attente',     'color'=>'#92400e', 'bg'=>'#fef3c7', 'icon'=>'bi-clock-fill'],
    'confirmee'      => ['label'=>'Confirmée',      'color'=>'#1d4ed8', 'bg'=>'#eff6ff', 'icon'=>'bi-check-circle-fill'],
    'en_preparation' => ['label'=>'En préparation', 'color'=>'#92400e', 'bg'=>'#fffbeb', 'icon'=>'bi-box-seam-fill'],
    'en_livraison'   => ['label'=>'En livraison',   'color'=>'#fff',    'bg'=>'#6d28d9', 'icon'=>'bi-bicycle'],
    'livree'         => ['label'=>'Livrée',         'color'=>'#fff',    'bg'=>'#16a34a', 'icon'=>'bi-house-check-fill'],
    'annulee'        => ['label'=>'Annulée',        'color'=>'#fff',    'bg'=>'#dc2626', 'icon'=>'bi-x-circle-fill'],
];
$sc = $statutConfig[$statutActuel] ?? ['label'=>$statutActuel,'color'=>'#374151','bg'=>'#f3f4f6','icon'=>'bi-circle'];

$etapes = [
    'en_attente'     => ['lib'=>'Commande reçue',     'icon'=>'bi-bag-check-fill',       'desc'=>'Votre commande a bien été enregistrée.'],
    'confirmee'      => ['lib'=>'Commande confirmée', 'icon'=>'bi-clipboard2-check-fill', 'desc'=>'Notre équipe a confirmé votre commande.'],
    'en_preparation' => ['lib'=>'En préparation',     'icon'=>'bi-box-seam-fill',         'desc'=>'Vos produits sont en cours de préparation.'],
    'en_livraison'   => ['lib'=>'En livraison',       'icon'=>'bi-bicycle',               'desc'=>'Votre commande est en route vers vous.'],
    'livree'         => ['lib'=>'Livrée',             'icon'=>'bi-house-check-fill',      'desc'=>'Commande livrée avec succès !'],
];
$ordre = array_keys($etapes);
$idxActuel = array_search($statutActuel === 'annulee' ? 'en_attente' : $statutActuel, $ordre);
?>

<style>
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.6;transform:scale(1.18)} }
@keyframes ring  { 0%{transform:scale(1);opacity:.6} 100%{transform:scale(1.7);opacity:0} }
@keyframes fadeUp{ from{opacity:0;transform:translateY(18px)} to{opacity:1;transform:translateY(0)} }
.detail-fade { animation: fadeUp .45s ease both; }
.detail-fade-2 { animation: fadeUp .45s .1s ease both; }
.detail-fade-3 { animation: fadeUp .45s .2s ease both; }
.ds-card { background:#fff; border-radius:16px; box-shadow:0 2px 16px rgba(0,0,0,.07); border:1px solid #f0f0f0; overflow:hidden; }
.ds-card-header { padding:16px 22px; display:flex; align-items:center; gap:10px; border-bottom:1px solid #f3f4f6; }
.ds-card-body { padding:22px; }
.info-chip { display:inline-flex; align-items:center; gap:6px; padding:5px 13px; border-radius:50px; font-size:.78rem; font-weight:600; }
.action-btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:10px; font-size:.84rem; font-weight:700; text-decoration:none; border:none; cursor:pointer; transition:all .18s; }
.action-btn:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.13); }
.step-line { width:2px; height:28px; margin:3px auto; border-radius:2px; }
</style>

<div class="container" style="padding-top:32px;padding-bottom:72px;">

<!-- ══ STICKY ACTIONS ══ -->
<div id="detail-actions" class="d-flex align-items-center gap-2 mb-4"
     style="position:sticky;z-index:100;background:rgba(248,250,252,.97);backdrop-filter:blur(6px);padding:10px 0;border-bottom:1px solid #e5e7eb;">
    <a href="<?= BASE_URL ?>?page=historique" class="action-btn" style="background:#f1f5f9;color:#374151;">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
    <?php if (in_array($statutActuel, ['confirmee','en_preparation','en_livraison','livree'])): ?>
    <a href="<?= BASE_URL ?>?page=commande_facture&id=<?= $commande['id'] ?>" target="_blank"
       class="action-btn" style="background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;">
        <i class="bi bi-file-earmark-pdf-fill"></i> Télécharger la facture
    </a>
    <?php endif; ?>
    <?php if (in_array($statutActuel, ['en_attente','confirmee'])): ?>
    <button type="button"
       class="action-btn ms-auto" style="background:#fff1f2;color:#dc2626;border:1.5px solid #fecaca;cursor:pointer;"
       onclick="ouvrirModalAnnulation(<?= $commande['id'] ?>)">
        <i class="bi bi-x-circle"></i> Annuler
    </a>
    <?php endif; ?>
</div>

<!-- ══ HERO BANNER ══ -->
<div class="detail-fade mb-4" style="background:linear-gradient(135deg,#050d07 0%,#0f2d16 50%,#1a5c2a 100%);border-radius:20px;padding:32px 36px;position:relative;overflow:hidden;">
    <!-- Décoratives -->
    <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;border-radius:50%;background:radial-gradient(circle,rgba(255,255,255,.06),transparent);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-30px;left:30%;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(109,222,138,.08),transparent);pointer-events:none;"></div>

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
        <div class="d-flex align-items-center gap-4">
            <!-- Icône statut animée -->
            <div style="position:relative;width:64px;height:64px;flex-shrink:0;">
                <?php if (!in_array($statutActuel, ['livree','annulee'])): ?>
                <div style="position:absolute;inset:0;border-radius:50%;border:2px solid <?= $sc['bg'] ?>;animation:ring 1.8s infinite;"></div>
                <?php endif; ?>
                <div style="width:64px;height:64px;border-radius:50%;background:<?= $sc['bg'] ?>;display:flex;align-items:center;justify-content:center;position:relative;">
                    <i class="bi <?= $sc['icon'] ?>" style="font-size:1.6rem;color:<?= $sc['color'] ?>;"></i>
                </div>
            </div>
            <div>
                <div style="font-size:.75rem;color:rgba(255,255,255,.55);font-weight:600;letter-spacing:1.5px;text-transform:uppercase;margin-bottom:4px;">Commande</div>
                <div style="font-size:1.35rem;font-weight:800;color:#fff;line-height:1.1;"><?= htmlspecialchars($commande['reference']) ?></div>
                <div style="font-size:.8rem;color:rgba(255,255,255,.55);margin-top:4px;">
                    <i class="bi bi-calendar3 me-1"></i><?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?>
                </div>
            </div>
        </div>
        <div class="d-flex flex-wrap gap-3">
            <div style="text-align:center;">
                <div style="font-size:.68rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Statut</div>
                <span class="statut-badge-live info-chip" style="background:<?= $sc['bg'] ?>;color:<?= $sc['color'] ?>;">
                    <i class="bi <?= $sc['icon'] ?>"></i> <?= $sc['label'] ?>
                </span>
            </div>
            <div style="text-align:center;">
                <div style="font-size:.68rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Total</div>
                <div style="font-size:1.25rem;font-weight:800;color:#6dde8a;"><?= formatPrice($commande['total']) ?></div>
            </div>
            <div style="text-align:center;">
                <div style="font-size:.68rem;color:rgba(255,255,255,.45);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Articles</div>
                <div style="font-size:1.25rem;font-weight:800;color:#fff;"><?= count($lignes) ?></div>
            </div>
        </div>
    </div>
</div>

<!-- ══ GRID PRINCIPAL ══ -->
<div class="row g-4">

    <!-- ══ COLONNE GAUCHE ══ -->
    <div class="col-lg-8">

        <!-- ARTICLES -->
        <div class="ds-card detail-fade-2 mb-4">
            <div class="ds-card-header" style="background:#f8fafc;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#1a5c2a,#2d8a42);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-bag-fill" style="color:#fff;font-size:.8rem;"></i>
                </div>
                <span style="font-weight:700;color:#1c1917;font-size:.95rem;">Détail des articles</span>
                <span class="ms-auto info-chip" style="background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;">
                    <?= count($lignes) ?> article<?= count($lignes) > 1 ? 's' : '' ?>
                </span>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f8fafc;border-bottom:2px solid #e5e7eb;">
                            <th style="padding:11px 18px;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.6px;text-align:left;">Produit</th>
                            <th style="padding:11px 12px;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.6px;text-align:center;">Qté</th>
                            <th style="padding:11px 12px;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.6px;text-align:right;">Prix unit.</th>
                            <th style="padding:11px 18px;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.6px;text-align:right;">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lignes as $i => $ligne):
                            $isCarton = ($ligne['unite'] ?? 'kg') === 'carton';
                            $qteAff = fmod((float)$ligne['quantite'], 1) == 0
                                ? (int)$ligne['quantite']
                                : number_format((float)$ligne['quantite'], 2, ',', ' ');
                        ?>
                        <tr style="border-bottom:1px solid #f3f4f6;<?= $i%2!==0?'background:#fafafa;':'' ?>transition:background .15s;" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='<?= $i%2!==0?'#fafafa':'#fff' ?>'">
                            <td style="padding:14px 18px;">
                                <div style="display:flex;align-items:center;gap:13px;">
                                    <?php if (!empty($ligne['image_principale'])): ?>
                                    <img src="<?= BASE_URL ?>public/uploads/<?= htmlspecialchars($ligne['image_principale']) ?>"
                                         alt="<?= htmlspecialchars($ligne['nom_produit']) ?>"
                                         style="width:54px;height:54px;object-fit:cover;border-radius:12px;border:1px solid #e5e7eb;flex-shrink:0;">
                                    <?php else: ?>
                                    <div style="width:54px;height:54px;border-radius:12px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid #bbf7d0;">
                                        <i class="bi bi-basket2-fill" style="color:#16a34a;font-size:1.2rem;"></i>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <div style="font-weight:700;color:#1c1917;font-size:.9rem;"><?= htmlspecialchars($ligne['nom_produit']) ?></div>
                                        <?php if ($isCarton): ?>
                                        <span style="font-size:.68rem;font-weight:700;color:#0284c7;background:#f0f9ff;border:1px solid #bae6fd;border-radius:20px;padding:1px 8px;margin-top:3px;display:inline-block;">
                                            <i class="bi bi-box-seam"></i> Carton
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 12px;text-align:center;">
                                <span style="font-weight:700;font-size:.9rem;color:#1c1917;"><?= $qteAff ?></span>
                                <span style="color:#9ca3af;font-size:.8rem;"> <?= $isCarton ? 'ctn' : 'kg' ?></span>
                            </td>
                            <td style="padding:14px 12px;text-align:right;color:#6b7280;font-size:.88rem;"><?= formatPrice($ligne['prix_unitaire']) ?></td>
                            <td style="padding:14px 18px;text-align:right;font-weight:800;font-size:.95rem;color:#1a5c2a;"><?= formatPrice($ligne['total_ligne']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Totaux -->
            <div style="padding:18px 22px;border-top:2px solid #f3f4f6;background:#fafafa;">
                <div style="max-width:280px;margin-left:auto;">
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.85rem;color:#6b7280;">
                        <span>Sous-total</span><span><?= formatPrice($sousTotal) ?></span>
                    </div>
                    <?php if ($remise > 0): ?>
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.85rem;color:#16a34a;">
                        <span><i class="bi bi-scissors me-1"></i>Remise<?= !empty($commande['code_promo']) ? ' <span style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:4px;padding:0 6px;font-family:monospace;font-size:.7rem;font-weight:700;">' . htmlspecialchars($commande['code_promo']) . '</span>' : '' ?></span>
                        <span style="font-weight:700;">-<?= formatPrice($remise) ?></span>
                    </div>
                    <?php endif; ?>
                    <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.85rem;color:#6b7280;">
                        <span><i class="bi bi-truck me-1"></i>Livraison</span>
                        <span style="<?= $fraisLivr > 0 ? '' : 'color:#16a34a;font-weight:700;' ?>">
                            <?= $fraisLivr > 0 ? formatPrice($fraisLivr) : 'Gratuite' ?>
                        </span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding:12px 14px;margin-top:8px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:10px;border:1px solid #bbf7d0;">
                        <span style="font-weight:800;font-size:1rem;color:#1a5c2a;">Total</span>
                        <span style="font-weight:800;font-size:1.1rem;color:#1a5c2a;"><?= formatPrice($commande['total']) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFOS LIVRAISON + PAIEMENT -->
        <div class="row g-3 detail-fade-3">
            <!-- Adresse -->
            <div class="col-sm-6">
                <div class="ds-card h-100">
                    <div class="ds-card-header" style="background:#f8fafc;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#0ea5e9,#0284c7);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-geo-alt-fill" style="color:#fff;font-size:.8rem;"></i>
                        </div>
                        <span style="font-weight:700;color:#1c1917;font-size:.9rem;">Adresse de livraison</span>
                    </div>
                    <div class="ds-card-body">
                        <?php if (!empty($adr['adresse'])): ?>
                        <div style="font-size:.88rem;color:#374151;margin-bottom:4px;font-weight:600;"><?= htmlspecialchars($adr['adresse']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($adr['ville'])): ?>
                        <div style="font-size:.82rem;color:#6b7280;margin-bottom:4px;"><i class="bi bi-building me-1"></i><?= htmlspecialchars($adr['ville']) ?></div>
                        <?php endif; ?>
                        <?php if (!empty($adr['telephone'])): ?>
                        <a href="tel:<?= htmlspecialchars($adr['telephone']) ?>" style="font-size:.82rem;color:#1a5c2a;text-decoration:none;font-weight:600;display:block;margin-top:6px;">
                            <i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($adr['telephone']) ?>
                        </a>
                        <?php endif; ?>
                        <?php if (empty($adr['adresse']) && empty($adr['ville'])): ?>
                        <div style="font-size:.82rem;color:#9ca3af;font-style:italic;">Non renseignée</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Paiement -->
            <div class="col-sm-6">
                <div class="ds-card h-100">
                    <div class="ds-card-header" style="background:#f8fafc;">
                        <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-credit-card-fill" style="color:#fff;font-size:.8rem;"></i>
                        </div>
                        <span style="font-weight:700;color:#1c1917;font-size:.9rem;">Mode de paiement</span>
                    </div>
                    <div class="ds-card-body">
                        <div style="font-size:.92rem;font-weight:700;color:#374151;margin-bottom:8px;">
                            <?php
                            $paieIcon = match($commande['mode_paiement'] ?? '') {
                                'wave'         => 'bi-phone-fill',
                                'orange_money' => 'bi-phone-fill',
                                'free_money'   => 'bi-phone-fill',
                                'virement'     => 'bi-bank',
                                'carte'        => 'bi-credit-card',
                                default        => 'bi-cash-coin',
                            };
                            ?>
                            <i class="bi <?= $paieIcon ?> me-2" style="color:#f97316;"></i><?= $modePaieLabel ?>
                        </div>
                        <div style="font-size:.78rem;color:#9ca3af;">Réf: <?= htmlspecialchars($commande['reference']) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTE AIDE -->
        <div class="mt-4 detail-fade-3" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:14px;padding:18px 22px;border:1px solid #bbf7d0;display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            <div style="width:42px;height:42px;border-radius:12px;background:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.08);">
                <i class="bi bi-headset" style="color:#1a5c2a;font-size:1.2rem;"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;color:#1a5c2a;font-size:.9rem;margin-bottom:2px;">Besoin d'aide ?</div>
                <div style="font-size:.8rem;color:#15803d;">Notre équipe est disponible pour toute question concernant votre commande.</div>
            </div>
            <a href="https://wa.me/221774715353?text=Bonjour, j'ai une question sur ma commande <?= urlencode($commande['reference']) ?>"
               target="_blank" class="action-btn" style="background:#25d366;color:#fff;font-size:.8rem;padding:8px 16px;">
                <i class="bi bi-whatsapp"></i> WhatsApp
            </a>
        </div>

    </div>

    <!-- ══ COLONNE DROITE ══ -->
    <div class="col-lg-4">

        <!-- SUIVI LIVRAISON -->
        <div class="ds-card detail-fade-2 mb-3">
            <div style="background:linear-gradient(135deg,#1a5c2a,#236b34);padding:16px 20px;">
                <h6 style="color:#fff;font-weight:700;margin:0;display:flex;align-items:center;gap:8px;font-size:.9rem;">
                    <i class="bi bi-geo-alt-fill" style="color:#6dde8a;"></i> Suivi de livraison
                </h6>
            </div>
            <div class="ds-card-body">
                <?php if ($statutActuel === 'annulee'): ?>
                <div style="text-align:center;padding:16px 0;color:#dc2626;">
                    <div style="width:56px;height:56px;border-radius:50%;background:#fff1f2;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;">
                        <i class="bi bi-x-circle-fill" style="font-size:1.6rem;"></i>
                    </div>
                    <strong style="font-size:.9rem;">Commande annulée</strong>
                    <div style="font-size:.78rem;color:#9ca3af;margin-top:4px;">Cette commande a été annulée.</div>
                </div>
                <?php else: ?>
                <?php
                /* Dates estimées par étape : seule en_attente a une date réelle (created_at).
                   Pour les étapes passées antérieures au statut actuel, on affiche "Complété". */
                $dateEtape = [
                    'en_attente'     => date('d/m/Y H:i', strtotime($commande['created_at'])),
                    'confirmee'      => null,
                    'en_preparation' => null,
                    'en_livraison'   => null,
                    'livree'         => !empty($livraison['date_prevue'])
                                        ? date('d/m/Y', strtotime($livraison['date_prevue'])) : null,
                ];
                ?>
                <div style="padding:4px 0;">
                    <?php foreach ($etapes as $key => $etape):
                        $idx   = array_search($key, $ordre);
                        $fait  = $idx <= $idxActuel;
                        $actif = $idx === $idxActuel;
                        $color = $actif ? '#ffffff' : ($fait ? '#1a5c2a' : '#9ca3af');
                        $bgIc  = $actif ? '#1a5c2a' : ($fait ? '#dcfce7' : '#f3f4f6');
                        $borderColor = $actif ? '#1a5c2a' : ($fait ? '#16a34a' : '#e5e7eb');
                    ?>
                    <div style="display:flex;gap:14px;margin-bottom:2px;">
                        <!-- Icône + connecteur vertical -->
                        <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                            <div style="width:38px;height:38px;border-radius:50%;background:<?= $bgIc ?>;border:2.5px solid <?= $borderColor ?>;display:flex;align-items:center;justify-content:center;box-shadow:<?= $actif ? '0 0 0 4px rgba(26,92,42,.15)' : 'none' ?>;">
                                <i class="bi <?= $etape['icon'] ?>" style="color:<?= $color ?>;font-size:.85rem;<?= $actif ? 'animation:pulse 1.5s infinite;' : '' ?>"></i>
                            </div>
                            <?php if ($key !== 'livree'): ?>
                            <div style="width:3px;min-height:30px;border-radius:2px;margin:3px 0;background:<?= ($fait && $idx < $idxActuel) ? 'linear-gradient(#16a34a,#bbf7d0)' : '#e5e7eb' ?>;"></div>
                            <?php endif; ?>
                        </div>
                        <!-- Texte étape -->
                        <div style="padding-top:8px;padding-bottom:<?= $key !== 'livree' ? '0' : '0' ?>;min-width:0;">
                            <div style="font-size:.84rem;font-weight:<?= $actif ? '800' : ($fait ? '600' : '500') ?>;color:<?= $actif ? '#1a5c2a' : ($fait ? '#1c1917' : '#9ca3af') ?>;display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
                                <?= $etape['lib'] ?>
                                <?php if ($actif): ?>
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#dcfce7;color:#15803d;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #bbf7d0;">
                                    <span style="width:5px;height:5px;background:#16a34a;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span> En cours
                                </span>
                                <?php elseif ($fait): ?>
                                <i class="bi bi-check2" style="color:#16a34a;font-size:.9rem;font-weight:900;"></i>
                                <?php endif; ?>
                            </div>
                            <?php if ($actif): ?>
                            <div style="font-size:.73rem;color:#6b7280;margin-top:3px;"><?= $etape['desc'] ?></div>
                            <?php endif; ?>
                            <?php if (!empty($dateEtape[$key]) && ($fait || $actif)): ?>
                            <div style="font-size:.7rem;color:<?= $actif ? '#15803d' : '#9ca3af' ?>;margin-top:2px;display:flex;align-items:center;gap:4px;">
                                <i class="bi bi-clock" style="font-size:.65rem;"></i><?= $dateEtape[$key] ?>
                            </div>
                            <?php elseif ($fait && !$actif && $key !== 'en_attente'): ?>
                            <div style="font-size:.7rem;color:#a3a3a3;margin-top:2px;">Complété</div>
                            <?php elseif (!$fait): ?>
                            <div style="font-size:.7rem;color:#d1d5db;margin-top:2px;font-style:italic;">En attente...</div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Livreur -->
                <?php if (!empty($livraison) && !empty($livraison['livreur_nom'])): ?>
                <div style="margin-top:16px;padding:13px 15px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;">
                    <div style="font-size:.7rem;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:.6px;margin-bottom:9px;">
                        <i class="bi bi-person-badge-fill me-1"></i>Votre livreur
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:50%;background:#1a5c2a;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.9rem;flex-shrink:0;">
                            <?= strtoupper(substr($livraison['livreur_nom'],0,1)) ?>
                        </div>
                        <div>
                            <div style="font-weight:700;font-size:.88rem;color:#1c1917;"><?= htmlspecialchars($livraison['livreur_nom']) ?></div>
                            <?php if (!empty($livraison['livreur_tel'])): ?>
                            <a href="tel:<?= htmlspecialchars($livraison['livreur_tel']) ?>" style="font-size:.78rem;color:#1a5c2a;text-decoration:none;font-weight:600;">
                                <i class="bi bi-telephone-fill me-1"></i><?= htmlspecialchars($livraison['livreur_tel']) ?>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($livraison['date_prevue'])): ?>
                    <div style="margin-top:9px;font-size:.75rem;color:#6b7280;padding-top:9px;border-top:1px solid #dcfce7;">
                        <i class="bi bi-calendar-event me-1"></i>Livraison prévue le <strong><?= date('d/m/Y', strtotime($livraison['date_prevue'])) ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- RÉCAP RAPIDE -->
        <div class="ds-card detail-fade-3 mb-3">
            <div class="ds-card-header" style="background:#f8fafc;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#7c3aed,#6d28d9);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-receipt" style="color:#fff;font-size:.8rem;"></i>
                </div>
                <span style="font-weight:700;color:#1c1917;font-size:.9rem;">Récapitulatif</span>
            </div>
            <div class="ds-card-body" style="padding:16px 18px;">
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.82rem;border-bottom:1px solid #f3f4f6;">
                    <span style="color:#6b7280;">Référence</span>
                    <span style="font-weight:700;font-family:monospace;color:#1c1917;font-size:.78rem;"><?= htmlspecialchars($commande['reference']) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.82rem;border-bottom:1px solid #f3f4f6;">
                    <span style="color:#6b7280;">Date</span>
                    <span style="font-weight:600;color:#374151;"><?= date('d/m/Y', strtotime($commande['created_at'])) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.82rem;border-bottom:1px solid #f3f4f6;">
                    <span style="color:#6b7280;">Articles</span>
                    <span style="font-weight:600;color:#374151;"><?= count($lignes) ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:5px 0;font-size:.82rem;border-bottom:1px solid #f3f4f6;">
                    <span style="color:#6b7280;">Paiement</span>
                    <span style="font-weight:600;color:#374151;font-size:.78rem;"><?= $modePaieLabel ?></span>
                </div>
                <div style="display:flex;justify-content:space-between;padding:10px 12px;margin-top:8px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:10px;border:1px solid #bbf7d0;">
                    <span style="font-weight:800;color:#1a5c2a;font-size:.9rem;">Total payé</span>
                    <span style="font-weight:800;color:#1a5c2a;font-size:1rem;"><?= formatPrice($commande['total']) ?></span>
                </div>
            </div>
        </div>

        <!-- ACTIONS RAPIDES -->
        <div class="ds-card detail-fade-3">
            <div class="ds-card-header" style="background:#f8fafc;">
                <div style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-lightning-fill" style="color:#fff;font-size:.8rem;"></i>
                </div>
                <span style="font-weight:700;color:#1c1917;font-size:.9rem;">Actions rapides</span>
            </div>
            <div class="ds-card-body" style="padding:16px 18px;display:flex;flex-direction:column;gap:8px;">
                <a href="<?= BASE_URL ?>?page=catalogue" class="action-btn w-100" style="background:#f0fdf4;color:#1a5c2a;border:1.5px solid #bbf7d0;justify-content:center;">
                    <i class="bi bi-bag-plus-fill"></i> Nouvelle commande
                </a>
                <?php if (in_array($statutActuel, ['confirmee','en_preparation','en_livraison','livree'])): ?>
                <a href="<?= BASE_URL ?>?page=commande_facture&id=<?= $commande['id'] ?>" target="_blank"
                   class="action-btn w-100" style="background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;justify-content:center;">
                    <i class="bi bi-file-earmark-pdf-fill"></i> Télécharger la facture
                </a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>?page=historique" class="action-btn w-100" style="background:#f8fafc;color:#374151;border:1.5px solid #e5e7eb;justify-content:center;">
                    <i class="bi bi-clock-history"></i> Historique des commandes
                </a>
                <?php if (in_array($statutActuel, ['en_attente','confirmee'])): ?>
                <button type="button"
                   class="action-btn w-100" style="background:#fff1f2;color:#dc2626;border:1.5px solid #fecaca;justify-content:center;cursor:pointer;"
                   onclick="ouvrirModalAnnulation(<?= $commande['id'] ?>)">
                    <i class="bi bi-x-circle-fill"></i> Annuler la commande
                </button>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
</div>

<script>
(function() {
    // Ajuster le top du sticky selon paddingTop réel du body
    function fixStickyTop() {
        var pt = parseInt(getComputedStyle(document.body).paddingTop) || 0;
        var el = document.getElementById('detail-actions');
        if (el) el.style.top = pt + 'px';
    }
    document.addEventListener('DOMContentLoaded', fixStickyTop);
    var closeBtn = document.getElementById('promo-close-btn');
    if (closeBtn) closeBtn.addEventListener('click', function(){ setTimeout(fixStickyTop, 60); });
})();

// Polling statut AJAX
(function() {
    var commandeId   = <?= (int)$commande['id'] ?>;
    var statutActuel = <?= json_encode($statutActuel) ?>;
    var terminalStatuts = ['livree', 'annulee'];
    if (terminalStatuts.indexOf(statutActuel) !== -1) return;

    var labels = {
        'en_attente':'En attente','confirmee':'Confirmée','en_preparation':'En préparation',
        'en_livraison':'En livraison','livree':'Livrée','annulee':'Annulée'
    };

    function poll() {
        fetch('<?= BASE_URL ?>?page=api_commande_statut&id=' + commandeId)
            .then(function(r){ return r.json(); })
            .then(function(d){
                if (d.error) return;
                if (d.statut !== statutActuel) {
                    statutActuel = d.statut;
                    document.querySelectorAll('.statut-badge-live').forEach(function(b){
                        b.querySelector('i') && (b.querySelector('i').className = '');
                        b.childNodes[b.childNodes.length-1].textContent = ' ' + (labels[d.statut] || d.statut);
                    });
                    window.location.reload();
                }
                if (terminalStatuts.indexOf(d.statut) !== -1) clearInterval(t);
            }).catch(function(){});
    }
    var t = setInterval(poll, 30000);
})();
</script>

<!-- MODAL ANNULATION -->
<div id="modalAnnulation" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.55);align-items:center;justify-content:center;padding:16px;">
  <div style="background:#fff;border-radius:22px;max-width:480px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25);overflow:hidden;">
    <!-- Header -->
    <div style="background:linear-gradient(135deg,#7f1d1d,#dc2626);padding:22px 26px;display:flex;align-items:center;gap:14px;">
      <div style="width:48px;height:48px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:#fff;flex-shrink:0;">
        <i class="bi bi-x-circle-fill"></i>
      </div>
      <div>
        <div style="font-size:1.05rem;font-weight:800;color:#fff;">Annuler la commande</div>
        <div style="font-size:.78rem;color:rgba(255,255,255,.7);">Cette action est irréversible</div>
      </div>
    </div>
    <!-- Body -->
    <div style="padding:26px;">
      <p style="font-size:.88rem;color:#374151;margin-bottom:20px;">
        <i class="bi bi-exclamation-triangle-fill" style="color:#f59e0b;"></i>
        Êtes-vous sûr de vouloir annuler cette commande ? Le stock sera automatiquement remis à jour.
      </p>
      <label style="display:block;font-size:.8rem;font-weight:700;color:#374151;margin-bottom:8px;text-transform:uppercase;letter-spacing:.04em;">
        Raison de l'annulation <span style="color:#dc2626;">*</span>
      </label>
      <select id="raisonAnnulation" style="width:100%;padding:11px 14px;border:1.5px solid #e5e7eb;border-radius:11px;font-size:.88rem;font-family:inherit;background:#fafafa;margin-bottom:8px;outline:none;">
        <option value="">— Choisir une raison —</option>
        <option value="changement_avis">Changement d'avis</option>
        <option value="erreur_commande">Erreur dans la commande</option>
        <option value="delai_trop_long">Délai de livraison trop long</option>
        <option value="prix_eleve">Prix trop élevé</option>
        <option value="commande_doublon">Commande en double</option>
        <option value="autre">Autre raison</option>
      </select>
      <div id="raisonErreur" style="display:none;font-size:.78rem;color:#dc2626;margin-bottom:12px;">
        <i class="bi bi-exclamation-circle"></i> Veuillez choisir une raison.
      </div>
      <div style="display:flex;gap:10px;margin-top:16px;">
        <button type="button" onclick="fermerModalAnnulation()"
          style="flex:1;padding:12px;border:1.5px solid #e5e7eb;border-radius:11px;background:#f9fafb;font-size:.88rem;font-weight:600;color:#374151;cursor:pointer;transition:all .15s;">
          <i class="bi bi-arrow-left"></i> Garder la commande
        </button>
        <button type="button" onclick="confirmerAnnulation()"
          style="flex:1;padding:12px;border:none;border-radius:11px;background:#dc2626;color:#fff;font-size:.88rem;font-weight:700;cursor:pointer;box-shadow:0 4px 14px rgba(220,38,38,.3);transition:all .15s;">
          <i class="bi bi-x-circle-fill"></i> Confirmer l'annulation
        </button>
      </div>
    </div>
  </div>
</div>

<script>
var _annulationId = 0;

function ouvrirModalAnnulation(id) {
    _annulationId = id;
    document.getElementById('raisonAnnulation').value = '';
    document.getElementById('raisonErreur').style.display = 'none';
    var m = document.getElementById('modalAnnulation');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function fermerModalAnnulation() {
    document.getElementById('modalAnnulation').style.display = 'none';
    document.body.style.overflow = '';
}

function confirmerAnnulation() {
    var raison = document.getElementById('raisonAnnulation').value;
    if (!raison) {
        document.getElementById('raisonErreur').style.display = 'block';
        return;
    }
    window.location.href = '<?= BASE_URL ?>?page=commande_annuler&id=' + _annulationId + '&raison=' + encodeURIComponent(raison);
}

document.getElementById('modalAnnulation').addEventListener('click', function(e) {
    if (e.target === this) fermerModalAnnulation();
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
