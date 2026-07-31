<?php

declare(strict_types=1);

use App\Core\Csrf;

/** @var \App\Core\Auth $auth */
$utilisateurConnecte = $auth->user();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Touche pas au klaxon</title>
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body>
<div class="container py-4">
    <header class="app-header d-flex justify-content-between align-items-center rounded-4 border px-4 py-3 mb-4">
        <a href="<?= $auth->isAdmin() ? '/admin' : '/' ?>" class="app-brand text-decoration-none fs-4 fw-semibold">
            Touche pas au klaxon
        </a>
        <div class="d-flex align-items-center gap-2">
            <?php if (!$auth->check()): ?>
                <a href="/login" class="btn btn-dark">Connexion</a>
            <?php elseif ($auth->isAdmin()): ?>
                <nav class="d-flex gap-2">
                    <a href="/admin/utilisateurs" class="btn btn-secondary">Utilisateurs</a>
                    <a href="/admin/agences" class="btn btn-secondary">Agences</a>
                    <a href="/admin/trajets" class="btn btn-secondary">Trajets</a>
                </nav>
                <span class="ms-2">
                    Bonjour <?= htmlspecialchars($utilisateurConnecte['prenom'] . ' ' . $utilisateurConnecte['nom'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <form method="post" action="/logout" class="m-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-dark">Déconnexion</button>
                </form>
            <?php else: ?>
                <a href="/trajets/create" class="btn btn-primary">Créer un trajet</a>
                <span class="ms-2">
                    Bonjour <?= htmlspecialchars($utilisateurConnecte['prenom'] . ' ' . $utilisateurConnecte['nom'], ENT_QUOTES, 'UTF-8') ?>
                </span>
                <form method="post" action="/logout" class="m-0">
                    <?= Csrf::field() ?>
                    <button type="submit" class="btn btn-dark">Déconnexion</button>
                </form>
            <?php endif; ?>
        </div>
    </header>
