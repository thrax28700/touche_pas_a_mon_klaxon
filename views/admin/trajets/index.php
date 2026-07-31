<?php

declare(strict_types=1);

use App\Core\Csrf;

/** @var list<array<string, mixed>> $trajets */
?>

<h1 class="h3 mb-4">Trajets</h1>

<div class="table-responsive">
    <table class="table table-bordered bg-white align-middle">
        <thead>
        <tr>
            <th>Départ</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Destination</th>
            <th>Date</th>
            <th>Heure</th>
            <th>Places</th>
            <th>Auteur</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($trajets as $trajet): ?>
            <?php
            $depart = new DateTimeImmutable($trajet['date_heure_depart']);
            $arrivee = new DateTimeImmutable($trajet['date_heure_arrivee']);
            ?>
            <tr>
                <td><?= htmlspecialchars($trajet['agence_depart_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $depart->format('d/m/Y') ?></td>
                <td><?= $depart->format('H:i') ?></td>
                <td><?= htmlspecialchars($trajet['agence_arrivee_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $arrivee->format('d/m/Y') ?></td>
                <td><?= $arrivee->format('H:i') ?></td>
                <td><?= (int) $trajet['nb_places_disponibles'] ?>/<?= (int) $trajet['nb_places_total'] ?></td>
                <td><?= htmlspecialchars($trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td>
                    <form method="post" action="/admin/trajets/<?= (int) $trajet['id'] ?>/delete" onsubmit="return confirm('Supprimer ce trajet ?');">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-sm btn-link text-danger" title="Supprimer">&#128465;</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
