<?php
/**
 * @var int $nbUtilisateurs
 * @var int $nbAgences
 * @var int $nbTrajets
 */
?>

<h1 class="h3 mb-4">Tableau de bord administrateur</h1>

<div class="row g-3">
    <div class="col-md-4">
        <a href="/admin/utilisateurs" class="card text-decoration-none text-body p-4 text-center h-100">
            <div class="display-6 fw-semibold"><?= $nbUtilisateurs ?></div>
            <div>Utilisateurs</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/agences" class="card text-decoration-none text-body p-4 text-center h-100">
            <div class="display-6 fw-semibold"><?= $nbAgences ?></div>
            <div>Agences</div>
        </a>
    </div>
    <div class="col-md-4">
        <a href="/admin/trajets" class="card text-decoration-none text-body p-4 text-center h-100">
            <div class="display-6 fw-semibold"><?= $nbTrajets ?></div>
            <div>Trajets</div>
        </a>
    </div>
</div>
