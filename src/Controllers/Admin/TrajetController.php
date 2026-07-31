<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Controller;
use App\Core\FlashMessage;

/**
 * Liste de tous les trajets et suppression, réservé à l'administrateur.
 */
final class TrajetController extends Controller
{
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/trajets/index', [
            'trajets' => App::trajets()->all(),
        ]);
    }

    public function destroy(int $id): string
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        App::trajets()->delete($id);

        FlashMessage::add('success', 'Le trajet a été supprimé.');
        $this->redirect('/admin/trajets');
    }
}
