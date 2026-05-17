<?php
$pageTitle  = htmlspecialchars($categorie['nom']) . ' - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?page=catalogue">Catalogue</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($categorie['nom']) ?></li>
    </ol>
</nav>

<div class="row">
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm p-3" style="border-radius:12px;">
            <h6 class="fw-bold mb-3 text-success"><i class="bi bi-funnel"></i> Catégories</h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-1"><a href="<?= BASE_URL ?>?page=catalogue" class="text-decoration-none text-dark">Tous les produits</a></li>
                <?php foreach ($categories as $cat): ?>
                <li class="mb-1">
                    <a href="<?= BASE_URL ?>?page=categorie&id=<?= $cat['id'] ?>"
                       class="text-decoration-none <?= $cat['id'] == $categorie['id'] ? 'fw-bold text-success' : 'text-dark' ?>">
                        <?= htmlspecialchars($cat['nom']) ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><?= htmlspecialchars($categorie['nom']) ?></h4>
            <span class="text-muted small"><?= $total ?> produit(s)</span>
        </div>

        <?php if (empty($produits)): ?>
            <div class="alert alert-info">Aucun produit dans cette catégorie.</div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($produits as $produit): ?>
                <div class="col-sm-6 col-md-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius:12px;overflow:hidden;">
                        <a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>">
                            <?php $catImg = !empty($produit['image_principale']) ? '/darousalam/'.$produit['image_principale'] : null; ?>
                            <?php if ($catImg): ?>
                                <img src="<?= htmlspecialchars($catImg) ?>" class="card-img-top" alt="" style="height:170px;object-fit:cover;">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light" style="height:170px;"><i class="bi bi-image text-muted fs-2"></i></div>
                            <?php endif; ?>
                        </a>
                        <div class="card-body d-flex flex-column p-3">
                            <h6 class="fw-bold mb-1">
                                <a href="<?= BASE_URL ?>?page=produit&id=<?= $produit['id'] ?>" class="text-dark text-decoration-none">
                                    <?= htmlspecialchars($produit['nom']) ?>
                                </a>
                            </h6>
                            <div class="mt-auto pt-2">
                                <span class="fw-bold text-success"><?= formatPrice((float)($produit['prix_kg'] ?? 0)) ?> / kg</span>
                                <form action="<?= BASE_URL ?>?page=panier_ajouter" method="POST" class="mt-2">
                                    <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
                                    <input type="hidden" name="quantite" value="1">
                                    <button class="btn btn-success btn-sm w-100" <?= $produit['stock_kg'] < 1 ? 'disabled' : '' ?>>
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
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
