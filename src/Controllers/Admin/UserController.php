<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Controller;

/**
 * Liste en lecture seule des utilisateurs importés du SIRH.
 */
final class UserController extends Controller
{
    /**
     * Liste tous les utilisateurs importés du SIRH.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/users/index', [
            'utilisateurs' => App::utilisateurs()->all(),
        ]);
    }
}
