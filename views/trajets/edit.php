<?php

declare(strict_types=1);

use App\Core\Csrf;

/**
 * @var array<string, mixed> $trajet
 * @var array{id: int, nom: string}[] $agences
 * @var array<string, string> $errors
 * @var array<string, string> $old
 */
$toDatetimeLocal = static fn (string $mysqlDatetime): string => substr(str_replace(' ', 'T', $mysqlDatetime), 0, 16);

$agenceDepartId = $old['agence_depart_id'] ?? (string) $trajet['agence_depart_id'];
$agenceArriveeId = $old['agence_arrivee_id'] ?? (string) $trajet['agence_arrivee_id'];
$dateDepart = $old['date_heure_depart'] ?? $toDatetimeLocal((string) $trajet['date_heure_depart']);
$dateArrivee = $old['date_heure_arrivee'] ?? $toDatetimeLocal((string) $trajet['date_heure_arrivee']);
$nbPlacesTotal = $old['nb_places_total'] ?? (string) $trajet['nb_places_total'];
$nbPlacesDisponibles = $trajet['nb_places_disponibles'] ?? 0;
?>

<h1 class="h3 mb-4">Modifier le trajet</h1>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <form method="post" action="/trajets/<?= (int) $trajet['id'] ?>" class="bg-white p-4 rounded-4 border">
            <?= Csrf::field() ?>

            <div class="mb-3">
                <label for="agence_depart_id" class="form-label">Agence de départ</label>
                <select class="form-select <?= isset($errors['agence_depart_id']) ? 'is-invalid' : '' ?>" id="agence_depart_id" name="agence_depart_id" required>
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['id'] ?>" <?= $agenceDepartId == $agence['id'] ? 'selected' : '' ?>>
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
                    <?php foreach ($agences as $agence): ?>
                        <option value="<?= $agence['id'] ?>" <?= $agenceArriveeId == $agence['id'] ? 'selected' : '' ?>>
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
                    <input type="datetime-local" class="form-control <?= isset($errors['date_heure_depart']) ? 'is-invalid' : '' ?>" id="date_heure_depart" name="date_heure_depart" value="<?= htmlspecialchars($dateDepart, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['date_heure_depart'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['date_heure_depart'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="date_heure_arrivee" class="form-label">Arrivée</label>
                    <input type="datetime-local" class="form-control <?= isset($errors['date_heure_arrivee']) ? 'is-invalid' : '' ?>" id="date_heure_arrivee" name="date_heure_arrivee" value="<?= htmlspecialchars($dateArrivee, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['date_heure_arrivee'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['date_heure_arrivee'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row g-2">
                <div class="col-md-6 mb-3">
                    <label for="nb_places_total" class="form-label">Nombre total de places</label>
                    <input type="number" min="1" class="form-control <?= isset($errors['nb_places_total']) ? 'is-invalid' : '' ?>" id="nb_places_total" name="nb_places_total" value="<?= htmlspecialchars($nbPlacesTotal, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['nb_places_total'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nb_places_total'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nb_places_disponibles" class="form-label">Places disponibles restantes</label>
                    <input type="number" min="0" class="form-control <?= isset($errors['nb_places_disponibles']) ? 'is-invalid' : '' ?>" id="nb_places_disponibles" name="nb_places_disponibles" value="<?= htmlspecialchars((string) $nbPlacesDisponibles, ENT_QUOTES, 'UTF-8') ?>" required>
                    <?php if (isset($errors['nb_places_disponibles'])): ?>
                        <div class="invalid-feedback"><?= htmlspecialchars($errors['nb_places_disponibles'], ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-100">Enregistrer les modifications</button>
        </form>
    </div>
</div>
