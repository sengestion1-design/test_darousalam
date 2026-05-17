<?php
$pageTitle = 'Mes adresses - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<h3 class="fw-bold mb-4"><i class="bi bi-geo-alt"></i> Mes adresses</h3>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="fw-bold mb-0">Adresse principale</h6>
                    <span class="badge bg-success">Par défaut</span>
                </div>
                <?php if ($client['adresse']): ?>
                    <div><?= htmlspecialchars($client['adresse']) ?></div>
                    <div><?= htmlspecialchars($client['ville']) ?></div>
                    <div class="text-muted small mt-1"><?= htmlspecialchars($client['telephone'] ?? '') ?></div>
                <?php else: ?>
                    <div class="text-muted">Aucune adresse renseignée.</div>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>?page=modifier_profil" class="btn btn-outline-success btn-sm mt-3">
                    <i class="bi bi-pencil"></i> Modifier
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
