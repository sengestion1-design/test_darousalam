<?php
$pageTitle = 'Recherche - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<h4 class="fw-bold mb-3">
    Résultats pour : <em>"<?= htmlspecialchars($terme) ?>"</em>
    <?php if ($total > 0): ?><span class="text-muted fs-6">(<?= $total ?> résultat(s))</span><?php endif; ?>
</h4>

<?php if (empty($terme)): ?>
    <div class="alert alert-warning">Veuillez entrer un terme de recherche.</div>
<?php elseif (empty($produits)): ?>
    <div class="alert alert-info">Aucun produit trouvé pour "<?= htmlspecialchars($terme) ?>".</div>
    <a href="<?= BASE_URL ?>?page=catalogue" class="btn btn-success">Voir tout le catalogue</a>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($produits as $produit):
            $rImg = !empty($produit['image_principale']) ? '/darousalam/' . $produit['image_principale'] : null;
        ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                <a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>">
                    <?php if ($rImg): ?>
                        <img src="<?= htmlspecialchars($rImg) ?>" class="card-img-top" alt="" style="height:160px;object-fit:cover;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:160px;"><i class="bi bi-image text-muted fs-2"></i></div>
                    <?php endif; ?>
                </a>
                <div class="card-body p-3 d-flex flex-column">
                    <div class="small text-muted"><?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></div>
                    <h6 class="fw-bold mt-1">
                        <a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>" class="text-dark text-decoration-none">
                            <?= htmlspecialchars($produit['nom']) ?>
                        </a>
                    </h6>
                    <div class="mt-auto">
                        <span class="fw-bold text-success"><?= formatPrice((float)($produit['prix_kg'] ?? 0)) ?> / kg</span>
                        <form action="<?= BASE_URL ?>?page=panier_ajouter" method="POST" class="mt-2">
                            <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                            <input type="hidden" name="quantite" value="1">
                            <button type="submit" class="btn btn-success btn-sm w-100" <?= ($produit['stock_kg'] ?? 0) < 1 ? 'disabled' : '' ?>>
                                <i class="bi bi-cart-plus"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="mt-4"><?= $paginationHtml ?></div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
