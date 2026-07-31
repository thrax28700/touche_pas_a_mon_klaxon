<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;

/**
 * Page d'atterrissage du tableau de bord administrateur.
 */
final class DashboardController extends Controller
{
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/dashboard');
    }
}
