<?php
$pageTitle = 'Commande ' . $commande['reference'] . ' - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<style>
@keyframes pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.6; transform:scale(1.15); }
}
</style>

<div class="container" style="padding-top:30px;padding-bottom:60px;">

<div class="mb-3 d-flex gap-2">
    <a href="<?= BASE_URL ?>?page=historique" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
    <?php if (in_array($commande['statut'] ?? '', ['confirmee','en_preparation','en_cours','expediee','livree'])): ?>
    <a href="<?= BASE_URL ?>?page=commande_facture&id=<?= $commande['id'] ?>"
       class="btn btn-sm" target="_blank"
       style="background:#eff6ff;color:#2563eb;border:1.5px solid #bfdbfe;font-weight:700;">
        <i class="bi bi-file-earmark-pdf-fill"></i> Télécharger la facture
    </a>
    <?php endif; ?>
</div>

<div class="row g-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h5 class="fw-bold mb-0">Commande <?= htmlspecialchars($commande['reference']) ?></h5>
                        <small class="text-muted"><?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?></small>
                    </div>
                    <?= statutBadge($commande['statut']) ?>
                </div>
                <table class="table table-borderless mb-0">
                    <thead class="table-light">
                        <tr><th>Produit</th><th class="text-center">Qté / Unité</th><th class="text-end">Prix unitaire</th><th class="text-end">Sous-total</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lignes as $ligne):
                            $isCarton = ($ligne['unite'] ?? 'kg') === 'carton';
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($ligne['nom_produit']) ?></td>
                            <td class="text-center">
                                <?= (int)$ligne['quantite'] ?> <?= $isCarton ? 'carton'.($ligne['quantite']>1?'s':'') : 'kg' ?>
                                <?php if ($isCarton): ?>
                                <br><span style="font-size:.7rem;font-weight:700;color:#0284c7;background:#f0f9ff;border:1px solid #bae6fd;border-radius:5px;padding:1px 6px;"><i class="bi bi-box-seam"></i> Carton</span>
                                <?php if (!empty($ligne['poids_carton_kg'])): ?>
                                <br><small style="color:#6b7280;"><?= number_format((float)$ligne['poids_carton_kg'],0) ?> kg net / carton</small>
                                <?php endif; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-end"><?= formatPrice($ligne['prix_unitaire']) ?> / <?= $isCarton ? 'carton' : 'kg' ?></td>
                            <td class="text-end fw-semibold"><?= formatPrice($ligne['total_ligne']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Sous-total</td>
                            <td class="text-end small text-muted"><?= formatPrice((float)($commande['sous_total'] ?: $commande['total'])) ?></td>
                        </tr>
                        <?php if (!empty($commande['remise']) && (float)$commande['remise'] > 0): ?>
                        <tr style="color:#16a34a;">
                            <td colspan="3" class="text-end small">
                                <i class="bi bi-scissors"></i> Remise
                                <?php if (!empty($commande['code_promo'])): ?>
                                <span style="background:#dcfce7;border:1px solid #bbf7d0;border-radius:5px;padding:1px 8px;font-family:monospace;font-size:.72rem;font-weight:700;letter-spacing:1px;"><?= htmlspecialchars($commande['code_promo']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end small fw-bold">-<?= formatPrice((float)$commande['remise']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Livraison</td>
                            <?php $fraisLivr = (float)($commande['frais_livraison'] ?? 0); ?>
                            <td class="text-end small <?= $fraisLivr > 0 ? '' : 'text-success fw-bold' ?>">
                                <?= $fraisLivr > 0 ? formatPrice($fraisLivr) : 'Gratuite' ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3" class="fw-bold text-end">Total</td>
                            <td class="fw-bold text-success text-end fs-5"><?= formatPrice($commande['total']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">

        <!-- SUIVI LIVRAISON -->
        <?php
        $etapes = [
            'en_attente'     => ['lib'=>'Commande reçue',      'icon'=>'bi-bag-check-fill',      'desc'=>'Votre commande a bien été enregistrée.'],
            'confirmee'      => ['lib'=>'Commande confirmée',  'icon'=>'bi-clipboard2-check-fill','desc'=>'Notre équipe a confirmé votre commande.'],
            'en_preparation' => ['lib'=>'En préparation',      'icon'=>'bi-box-seam-fill',        'desc'=>'Vos produits sont en cours de préparation.'],
            'en_livraison'   => ['lib'=>'En livraison',        'icon'=>'bi-bicycle',              'desc'=>'Votre commande est en route vers vous.'],
            'livree'         => ['lib'=>'Livrée',              'icon'=>'bi-house-check-fill',     'desc'=>'Commande livrée avec succès.'],
        ];
        $ordre = array_keys($etapes);
        $statutActuel = $commande['statut'];
        $idxActuel = array_search($statutActuel === 'annulee' ? 'en_attente' : $statutActuel, $ordre);
        ?>
        <div class="card border-0 shadow-sm mb-3" style="border-radius:14px;overflow:hidden;">
            <div style="background:linear-gradient(135deg,#1a5c2a,#236b34);padding:16px 20px;">
                <h6 style="color:#fff;font-weight:700;margin:0;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-geo-alt-fill" style="color:#6dde8a;"></i> Suivi de livraison
                </h6>
            </div>
            <div class="card-body p-4">
                <?php if ($commande['statut'] === 'annulee'): ?>
                <div style="text-align:center;padding:10px 0;color:#dc2626;">
                    <i class="bi bi-x-circle-fill" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                    <strong>Commande annulée</strong>
                </div>
                <?php else: ?>
                <!-- Stepper vertical -->
                <div style="position:relative;">
                    <?php foreach ($etapes as $key => $etape):
                        $idx = array_search($key, $ordre);
                        $fait = $idx <= $idxActuel;
                        $actif = $idx === $idxActuel;
                        $color = $fait ? '#1a5c2a' : '#d1d5db';
                        $bgIcon = $fait ? '#dcfce7' : '#f3f4f6';
                    ?>
                    <div style="display:flex;gap:14px;margin-bottom:<?= $key !== 'livree' ? '0' : '0' ?>;">
                        <div style="display:flex;flex-direction:column;align-items:center;">
                            <div style="width:36px;height:36px;border-radius:50%;background:<?= $bgIcon ?>;border:2px solid <?= $color ?>;display:flex;align-items:center;justify-content:center;flex-shrink:0;transition:all .3s;">
                                <i class="bi <?= $etape['icon'] ?>" style="color:<?= $color ?>;font-size:.85rem;<?= $actif ? 'animation:pulse 1.5s infinite;' : '' ?>"></i>
                            </div>
                            <?php if ($key !== 'livree'): ?>
                            <div style="width:2px;height:28px;background:<?= $fait && $idx < $idxActuel ? '#1a5c2a' : '#e5e7eb' ?>;margin:3px 0;"></div>
                            <?php endif; ?>
                        </div>
                        <div style="padding-top:6px;padding-bottom:<?= $key !== 'livree' ? '0' : '0' ?>;">
                            <div style="font-size:.83rem;font-weight:<?= $actif ? '800' : '600' ?>;color:<?= $actif ? '#1a5c2a' : ($fait ? '#374151' : '#9ca3af') ?>;">
                                <?= $etape['lib'] ?>
                                <?php if ($actif): ?>
                                <span style="display:inline-block;width:7px;height:7px;background:#1a5c2a;border-radius:50%;margin-left:5px;animation:pulse 1.5s infinite;vertical-align:middle;"></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($actif): ?>
                            <div style="font-size:.74rem;color:#6b7280;margin-top:2px;"><?= $etape['desc'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Livreur assigné -->
                <?php if (!empty($livraison) && !empty($livraison['livreur_nom'])): ?>
                <div style="margin-top:16px;padding:12px 14px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;">
                    <div style="font-size:.75rem;font-weight:700;color:#15803d;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;">
                        <i class="bi bi-person-badge-fill me-1"></i>Votre livreur
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:38px;height:38px;border-radius:50%;background:#1a5c2a;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;">
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
                    <div style="margin-top:8px;font-size:.75rem;color:#6b7280;">
                        <i class="bi bi-calendar-event me-1"></i>Livraison prévue le <strong><?= date('d/m/Y', strtotime($livraison['date_prevue'])) ?></strong>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Adresse & paiement -->
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body p-4">
                <?php
                $adr = json_decode($commande['adresse_livraison'] ?? '{}', true) ?: [];
                ?>
                <h6 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-success me-2"></i>Adresse de livraison</h6>
                <?php if (!empty($adr['adresse'])): ?>
                <div class="small mb-1 text-muted"><?= htmlspecialchars($adr['adresse']) ?></div>
                <?php endif; ?>
                <?php if (!empty($adr['ville'])): ?>
                <div class="small mb-1 text-muted"><?= htmlspecialchars($adr['ville']) ?></div>
                <?php endif; ?>
                <?php if (!empty($adr['telephone'])): ?>
                <div class="small mb-3 text-muted"><i class="bi bi-telephone me-1"></i><?= htmlspecialchars($adr['telephone']) ?></div>
                <?php endif; ?>
                <h6 class="fw-bold mb-2"><i class="bi bi-credit-card text-success me-2"></i>Paiement</h6>
                <div class="small text-muted"><?= htmlspecialchars(str_replace('_', ' ', ucfirst($commande['mode_paiement']))) ?></div>
                <?php if (in_array($commande['statut'], ['en_attente', 'confirmee'])): ?>
                    <a href="<?= BASE_URL ?>?page=commande_annuler&id=<?= $commande['id'] ?>"
                       class="btn btn-outline-danger btn-sm mt-3 w-100"
                       onclick="return confirm('Annuler cette commande ?')">
                        <i class="bi bi-x-circle"></i> Annuler la commande
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div><!-- /container -->

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
