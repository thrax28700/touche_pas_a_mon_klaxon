<?php

declare(strict_types=1);

use App\Core\Csrf;

/** @var list<array{id: int, nom: string}> $agences */
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Agences</h1>
    <a href="/admin/agences/create" class="btn btn-primary">Créer une agence</a>
</div>

<div class="table-responsive">
    <table class="table table-bordered bg-white align-middle">
        <thead>
        <tr>
            <th>Nom</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($agences as $agence): ?>
            <tr>
                <td><?= htmlspecialchars($agence['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td class="text-nowrap">
                    <a href="/admin/agences/<?= $agence['id'] ?>/edit" class="btn btn-sm btn-link text-success" title="Modifier">&#9998;</a>
                    <form method="post" action="/admin/agences/<?= $agence['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Supprimer cette agence ?');">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn btn-sm btn-link text-danger" title="Supprimer">&#128465;</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
