<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\FlashMessage;

/**
 * Connexion et déconnexion des employés.
 */
final class AuthController extends Controller
{
    /**
     * Affiche le formulaire de connexion (redirige si déjà connecté).
     */
    public function showLogin(): string
    {
        if ($this->auth->check()) {
            $this->redirect('/');
        }

        return $this->render('auth/login');
    }

    /**
     * Traite la soumission du formulaire de connexion.
     */
    public function login(): string
    {
        $this->verifyCsrf();

        $email = $this->input('email', '') ?? '';
        $motDePasse = $this->input('mot_de_passe', '') ?? '';

        if ($this->auth->attempt($email, $motDePasse)) {
            $this->redirect('/');
        }

        FlashMessage::add('error', 'Adresse email ou mot de passe incorrect.');

        return $this->render('auth/login', ['email' => $email]);
    }

    /**
     * Déconnecte l'utilisateur courant et revient à l'accueil.
     */
    public function logout(): string
    {
        $this->auth->logout();
        FlashMessage::add('success', 'Vous avez été déconnecté.');
        $this->redirect('/');
    }
}
