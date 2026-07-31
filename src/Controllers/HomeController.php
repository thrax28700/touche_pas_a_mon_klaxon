<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;

/**
 * Page d'accueil : liste des trajets à venir avec places disponibles.
 * Accessible à tous, avec des informations supplémentaires pour les
 * utilisateurs connectés.
 */
final class HomeController extends Controller
{
    /**
     * Affiche les trajets à venir ayant encore des places disponibles.
     */
    public function index(): string
    {
        $trajets = App::trajets()->findAvailableUpcoming();

        return $this->render('home/index', [
            'trajets' => $trajets,
        ]);
    }
}
