<?php

declare(strict_types=1);

use App\Core\Csrf;

/**
 * @var array{id: int, nom: string}[] $agences
 * @var array<string, mixed> $auteur
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
?>

<h1 class="h3 mb-4">Proposer un trajet</h1>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <form method="post" action="/trajets" class="bg-white p-4 rounded-4 border">
            <?= Csrf::field() ?>

            <fieldset class="mb-3" disabled>
                <legend class="fs-6 text-muted">Vos coordonnées (non modifiables)</legend>
                <div class="row g-2">
                    <div class="col-6">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($auteur['prenom'] . ' ' . $auteur['nom'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" value="<?= htmlspecialchars($auteur['telephone'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-12">
                        <input type="email" class="form-control" value="<?= htmlspecialchars($auteur['email'], ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                </div>
            </fieldset>

            <div class="mb-3">
                <label for="agence_depart_id" class="form-label">Agence de départ</label>
                <select class="form-select <?= isset($errors['agence_depart_id']) ? 'is-invalid' : '' ?>" id="agence_depart_id" name="agence_depart_id" required>
                    <option value="">— Choisir —</option>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['id'] ?>" <?= ($old['agence_depart_id'] ?? '') == $agence['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agence['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['agence_depart_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['agence_depart_id'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="agence_arrivee_id" class="form-label">Agence d'arrivée</label>
                <select class="form-select <?= isset($errors['agence_arrivee_id']) ? 'is-invalid' : '' ?>" id="agence_arrivee_id" name="agence_arrivee_id" required>
                    <option value="">— Choisir —</option>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['id'] ?>" <?= ($old['agence_arrivee_id'] ?? '') == $agence['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($agence['nom'], ENT_QUOTES, 'UTF-8') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['agence_arrivee_id'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['agence_arrivee_id'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label for="date_heure_depart" class="form-label">Départ</label>
                    <input type="datetime-local" class="form-control <?= isset($errors['date_heure_depart']) ? 'is-invalid' : '' ?>" id="date_heure_depart" name="date_heure_depart" value="<?= htmlspecialchars($old['date_heure_depart'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['date_heure_depart'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['date_heure_depart'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_heure_arrivee" class="form-label">Arrivée</label>
                    <input type="datetime-local" class="form-control <?= isset($errors['date_heure_arrivee']) ? 'is-invalid' : '' ?>" id="date_heure_arrivee" name="date_heure_arrivee" value="<?= htmlspecialchars($old['date_heure_arrivee'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['date_heure_arrivee'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['date_heure_arrivee'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mb-3">
                <label for="nb_places_total" class="form-label">Nombre de places proposées</label>
                <input type="number" min="1" class="form-control <?= isset($errors['nb_places_total']) ? 'is-invalid' : '' ?>" id="nb_places_total" name="nb_places_total" value="<?= htmlspecialchars($old['nb_places_total'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                <?php if (isset($errors['nb_places_total'])): ?>
                    <div class="invalid-feedback"><?= htmlspecialchars($errors['nb_places_total'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">Proposer ce trajet</button>
        </form>
    </div>
</div>
