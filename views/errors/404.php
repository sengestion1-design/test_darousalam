<?php
$pageTitle = '404 - Page introuvable - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="text-center py-5">
    <div style="font-size:6rem;color:#ddd;">404</div>
    <h3 class="fw-bold text-muted">Page introuvable</h3>
    <p class="text-muted">La page que vous cherchez n'existe pas ou a été déplacée.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-success mt-2">
        <i class="bi bi-house"></i> Retour à l'accueil
    </a>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
