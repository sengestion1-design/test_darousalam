<?php
$pageTitle = htmlspecialchars($produit['nom']) . ' - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
$prix = (float)($produit['prix_kg'] ?? 0);
$imgPath = !empty($produit['image_principale']) ? '/darousalam/' . $produit['image_principale'] : null;
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?page=catalogue">Catalogue</a></li>
        <?php if ($produit['categorie_nom']): ?>
            <li class="breadcrumb-item"><a href="<?= BASE_URL ?>?page=categorie&id=<?= $produit['categorie_id'] ?>"><?= htmlspecialchars($produit['categorie_nom']) ?></a></li>
        <?php endif; ?>
        <li class="breadcrumb-item active"><?= htmlspecialchars($produit['nom']) ?></li>
    </ol>
</nav>

<div class="row g-4 mb-5">
    <div class="col-md-5">
        <?php if ($imgPath): ?>
            <img src="<?= htmlspecialchars($imgPath) ?>"
                 alt="<?= htmlspecialchars($produit['nom']) ?>"
                 class="img-fluid rounded-3 shadow" style="width:100%;object-fit:cover;max-height:380px;">
        <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light rounded-3 shadow" style="height:300px;">
                <i class="bi bi-image text-muted" style="font-size:4rem;"></i>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-md-7">
        <div class="text-muted small mb-1"><?= htmlspecialchars($produit['categorie_nom'] ?? '') ?></div>
        <h1 class="fw-bold mb-2" style="font-size:1.8rem;"><?= htmlspecialchars($produit['nom']) ?></h1>
        <?php if (!empty($produit['origine'])): ?>
            <p class="text-muted small"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($produit['origine']) ?></p>
        <?php endif; ?>

        <div class="mb-3">
            <div class="fw-bold text-success" style="font-size:2rem;"><?= formatPrice($prix) ?> <span style="font-size:1rem;font-weight:400;color:#6b7280;">/ kg</span></div>
            <?php if (!empty($produit['prix_carton'])): ?>
                <div class="text-primary small mt-1"><i class="bi bi-box-seam"></i> <?= formatPrice((float)$produit['prix_carton']) ?> / carton
                    <?php if (!empty($produit['poids_carton_kg'])): ?>
                        <span class="text-muted">· <?= number_format((float)$produit['poids_carton_kg'], 0) ?> kg net / carton</span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($produit['badge'])): ?>
                <span class="badge bg-danger mt-1"><?= htmlspecialchars($produit['badge']) ?></span>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <?php if ($produit['stock_kg'] > 0): ?>
                <span class="badge bg-success"><i class="bi bi-check-circle"></i> En stock (<?= number_format((float)$produit['stock_kg'], 0, ',', ' ') ?> kg)</span>
            <?php else: ?>
                <span class="badge bg-danger">Rupture de stock</span>
            <?php endif; ?>
        </div>

        <?php if ($produit['stock_kg'] > 0 || !empty($produit['stock_cartons'])): ?>
        <form action="<?= BASE_URL ?>?page=panier_ajouter" method="POST" class="mb-3">
            <input type="hidden" name="produit_id" value="<?= $produit['id'] ?>">
            <?php if (!empty($produit['prix_carton']) && !empty($produit['stock_cartons'])): ?>
            <div class="d-flex gap-2 mb-2">
                <button type="button" class="btn btn-outline-success btn-sm <?= 'active' ?>" id="btn-kg" onclick="setUnite('kg')">
                    <i class="bi bi-rulers"></i> Par kg
                </button>
                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-carton" onclick="setUnite('carton')">
                    <i class="bi bi-box-seam"></i> Par carton
                </button>
            </div>
            <input type="hidden" name="unite" id="unite-input" value="kg">
            <?php else: ?>
            <input type="hidden" name="unite" value="kg">
            <?php endif; ?>
            <div class="d-flex gap-2">
                <input type="number" name="quantite" id="qty-input" value="1" min="1"
                       max="<?= $produit['stock_kg'] > 0 ? (int)$produit['stock_kg'] : (int)($produit['stock_cartons'] ?? 1) ?>"
                       class="form-control" style="max-width:90px;">
                <button type="submit" class="btn btn-success btn-lg fw-bold">
                    <i class="bi bi-cart-plus"></i> Ajouter au panier
                </button>
            </div>
        </form>
        <script>
        function setUnite(u) {
            document.getElementById('unite-input').value = u;
            var maxKg = <?= (int)$produit['stock_kg'] ?>;
            var maxC  = <?= (int)($produit['stock_cartons'] ?? 0) ?>;
            document.getElementById('qty-input').max = (u==='carton') ? maxC : maxKg;
            document.getElementById('btn-kg').classList.toggle('active', u==='kg');
            document.getElementById('btn-carton').classList.toggle('active', u==='carton');
            document.getElementById('btn-kg').classList.toggle('btn-success', u==='kg');
            document.getElementById('btn-kg').classList.toggle('btn-outline-success', u!=='kg');
            document.getElementById('btn-carton').classList.toggle('btn-primary', u==='carton');
            document.getElementById('btn-carton').classList.toggle('btn-outline-primary', u!=='carton');
        }
        </script>
        <?php endif; ?>

        <?php if (!empty($produit['description'])): ?>
        <div class="mt-3">
            <h6 class="fw-bold">Description</h6>
            <p class="text-muted"><?= nl2br(htmlspecialchars($produit['description'])) ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($produit['saison'])): ?>
        <div class="mt-2 text-muted small"><i class="bi bi-calendar3"></i> Saison : <?= htmlspecialchars($produit['saison']) ?></div>
        <?php endif; ?>
    </div>
</div>

<!-- Produits similaires -->
<?php if (!empty($similaires)): ?>
<section>
    <h4 class="fw-bold mb-3">Produits similaires</h4>
    <div class="row g-3">
        <?php foreach (array_slice($similaires, 0, 4) as $sim):
            $simImg = !empty($sim['image_principale']) ? '/darousalam/' . $sim['image_principale'] : null;
        ?>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius:10px;overflow:hidden;">
                <a href="<?= BASE_URL ?>?page=produit&id=<?= $sim['id'] ?>">
                    <?php if ($simImg): ?>
                        <img src="<?= htmlspecialchars($simImg) ?>" class="card-img-top" style="height:130px;object-fit:cover;" alt="">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light" style="height:130px;"><i class="bi bi-image text-muted fs-2"></i></div>
                    <?php endif; ?>
                </a>
                <div class="card-body p-2 small">
                    <a href="<?= BASE_URL ?>?page=produit&id=<?= $sim['id'] ?>" class="text-dark text-decoration-none fw-semibold">
                        <?= htmlspecialchars(truncate($sim['nom'], 40)) ?>
                    </a>
                    <div class="fw-bold text-success mt-1"><?= formatPrice((float)($sim['prix_kg'] ?? 0)) ?> / kg</div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
