<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Controller;

/**
 * Page d'atterrissage du tableau de bord administrateur.
 */
final class DashboardController extends Controller
{
    /**
     * Affiche les liens vers les différentes sections d'administration,
     * avec un décompte rapide de chaque ressource.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/dashboard', [
            'nbUtilisateurs' => count(App::utilisateurs()->all()),
            'nbAgences' => count(App::agences()->all()),
            'nbTrajets' => count(App::trajets()->all()),
        ]);
    }
}
