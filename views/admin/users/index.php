<?php
/** @var list<array<string, mixed>> $utilisateurs */
?>

<h1 class="h3 mb-4">Utilisateurs</h1>

<div class="table-responsive">
    <table class="table table-bordered bg-white align-middle">
        <thead>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Rôle</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($utilisateurs as $utilisateur): ?>
            <tr>
                <td><?= htmlspecialchars($utilisateur['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($utilisateur['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($utilisateur['telephone'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($utilisateur['email'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($utilisateur['role'], ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
