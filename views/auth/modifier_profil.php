<?php
$pageTitle = 'Modifier mon profil - ' . APP_NAME;
require_once __DIR__ . '/../layouts/header.php';
?>
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card border-0 shadow" style="border-radius:16px;">
            <div class="card-body p-4">
                <h3 class="fw-bold mb-4">Modifier mon profil</h3>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>
                <form method="POST" action="<?= BASE_URL ?>?page=modifier_profil" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom</label>
                            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Téléphone</label>
                            <input type="tel" class="form-control" name="telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Ville</label>
                            <input type="text" class="form-control" name="ville" value="<?= htmlspecialchars($client['ville'] ?? '') ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Adresse</label>
                            <input type="text" class="form-control" name="adresse" value="<?= htmlspecialchars($client['adresse'] ?? '') ?>">
                        </div>
                        <?php if ($client['type_client'] === 'professionnel'): ?>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Nom de l'entreprise</label>
                            <input type="text" class="form-control" name="nom_entreprise" value="<?= htmlspecialchars($client['nom_entreprise'] ?? '') ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">NINEA</label>
                            <input type="text" class="form-control" name="ninea" value="<?= htmlspecialchars($client['ninea'] ?? '') ?>">
                        </div>
                        <?php endif; ?>
                        <div class="col-12"><hr><p class="text-muted small">Laissez vide pour garder le mot de passe actuel.</p></div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="password" placeholder="Min. 8 caractères">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirmer</label>
                            <input type="password" class="form-control" name="password_conf">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success fw-bold"><i class="bi bi-check-lg"></i> Enregistrer</button>
                        <a href="<?= BASE_URL ?>?page=profil" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
