<?php

declare(strict_types=1);

use App\Core\Csrf;

/**
 * @var \App\Core\Auth $auth
 * @var list<array<string, mixed>> $trajets
 */
$estConnecte = $auth->check();
$utilisateurId = $auth->id();
?>

<h1 class="h3 mb-4">
    <?= $estConnecte ? 'Trajets proposés' : 'Pour obtenir plus d\'informations sur un trajet, veuillez vous connecter' ?>
</h1>

<div class="table-responsive">
    <table class="table align-middle table-bordered bg-white">
        <thead>
        <tr>
            <th>Départ</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if ($trajets === []): ?>
            <tr>
                <td colspan="8" class="text-center text-muted py-4">Aucun trajet disponible pour le moment.</td>
            </tr>
        <?php endif; ?>
        <?php foreach ($trajets as $trajet): ?>
            <?php
            $depart = new DateTimeImmutable($trajet['date_heure_depart']);
            $arrivee = new DateTimeImmutable($trajet['date_heure_arrivee']);
            $estAuteur = $utilisateurId === (int) $trajet['utilisateur_id'];
            ?>
            <tr>
                <td><?= htmlspecialchars($trajet['agence_depart_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $depart->format('d/m/Y') ?></td>
                <td><?= $depart->format('H:i') ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $arrivee->format('d/m/Y') ?></td>
                <td><?= $arrivee->format('H:i') ?></td>
                <td><?= (int) $trajet['nb_places_disponibles'] ?></td>
                <td class="text-nowrap">
                    <?php if ($estConnecte): ?>
                        <button type="button" class="btn btn-sm btn-link" data-bs-toggle="modal" data-bs-target="#modalDetails<?= (int) $trajet['id'] ?>" title="Détails">
                            &#128065;
                        </button>
                        <?php if ($estAuteur || $auth->isAdmin()): ?>
                            <a href="/trajets/<?= (int) $trajet['id'] ?>/edit" class="btn btn-sm btn-link text-success" title="Modifier">&#9998;</a>
                            <form method="post" action="/trajets/<?= (int) $trajet['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer ce trajet ?');">
                                <?= Csrf::field() ?>
                                <button type="submit" class="btn btn-sm btn-link text-danger" title="Supprimer">&#128465;</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php if ($estConnecte): ?>
    <?php foreach ($trajets as $trajet): ?>
        <?php require __DIR__ . '/../partials/modal_details.php'; ?>
    <?php endforeach; ?>
<?php endif; ?>
