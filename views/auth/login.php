<?php

declare(strict_types=1);

use App\Core\Csrf;

/** @var string|null $email */
?>

<h1 class="h3 mb-4">Connexion</h1>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <form method="post" action="/login" class="bg-white p-4 rounded-4 border">
            <?= Csrf::field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Adresse email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($email ?? '', ENT_QUOTES, 'UTF-8') ?>" required autofocus>
            </div>
            <div class="mb-3">
                <label for="mot_de_passe" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
    </div>
</div>
