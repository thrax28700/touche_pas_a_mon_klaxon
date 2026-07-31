<?php

declare(strict_types=1);

use App\Core\Csrf;

/**
 * @var array{id: int, nom: string} $agence
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<h1 class="h3 mb-4">Modifier l'agence</h1>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <form method="post" action="/admin/agences/<?= $agence['id'] ?>" class="bg-white p-4 rounded-4 border">
            <?= Csrf::field() ?>
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de l'agence</label>
                <input type="text" class="form-control <?= isset($errors['nom']) ? 'is-invalid' : '' ?>" id="nom" name="nom" value="<?= htmlspecialchars($old['nom'] ?? $agence['nom'], ENT_QUOTES, 'UTF-8') ?>" required>
                <?php if (isset($errors['nom'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['nom'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-success w-100">Enregistrer</button>
        </form>
    </div>
</div>
