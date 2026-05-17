<?php
$pageTitle = 'Mes favoris - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<h3 class="fw-bold mb-4"><i class="bi bi-heart"></i> Mes favoris</h3>

<?php if (empty($produits)): ?>
    <div class="text-center py-5">
        <i class="bi bi-heart" style="font-size:4rem;color:#ccc;"></i>
        <h5 class="mt-3 text-muted">Aucun favori pour le moment</h5>
        <a href="<?= BASE_URL ?>?page=catalogue" class="btn btn-success mt-2">Explorer le catalogue</a>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($produits as $produit): ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                <a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>">
                    <?php if ($produit['image']): ?>
                        <img src="<?= BASE_URL ?>public/images/produits/<?= htmlspecialchars($produit['image']) ?>" class="card-img-top" alt="" style="height:160px;object-fit:cover;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:160px;"><i class="bi bi-image text-muted fs-2"></i></div>
                    <?php endif; ?>
                </a>
                <div class="card-body p-3">
                    <h6 class="fw-bold"><a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>" class="text-dark text-decoration-none"><?= htmlspecialchars($produit['nom']) ?></a></h6>
                    <div class="fw-bold text-success mb-2"><?= formatPrice($produit['prix_promo'] ?: $produit['prix']) ?></div>
                    <div class="d-flex gap-1">
                        <form action="<?= BASE_URL ?>?page=panier_ajouter" method="POST" class="flex-fill">
                            <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                            <input type="hidden" name="quantite" value="1">
                            <button class="btn btn-success btn-sm w-100"><i class="bi bi-cart-plus"></i></button>
                        </form>
                        <a href="<?= BASE_URL ?>?page=retirer_favori&id=<?= $produit['id'] ?>" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
