<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Exceptions\NotFoundException;
use App\Validation\AgenceValidator;

/**
 * CRUD des agences (villes), réservé à l'administrateur.
 */
final class AgenceController extends Controller
{
    /**
     * Liste toutes les agences.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/agences/index', [
            'agences' => App::agences()->all(),
        ]);
    }

    /**
     * Affiche le formulaire de création d'une agence.
     */
    public function create(): string
    {
        $this->requireAdmin();

        return $this->render('admin/agences/create', ['errors' => [], 'old' => []]);
    }

    /**
     * Valide et enregistre une nouvelle agence.
     */
    public function store(): string
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $input = ['nom' => $this->input('nom', '')];
        $errors = AgenceValidator::validate($input);

        if (!isset($errors['nom']) && App::agences()->findByNom((string) $input['nom']) !== null) {
            $errors['nom'] = 'Une agence porte déjà ce nom.';
        }

        if ($errors !== []) {
            return $this->render('admin/agences/create', ['errors' => $errors, 'old' => $input]);
        }

        App::agences()->create((string) $input['nom']);

        FlashMessage::add('success', "L'agence a été créée.");
        $this->redirect('/admin/agences');
    }

    /**
     * Affiche le formulaire de modification d'une agence.
     */
    public function edit(int $id): string
    {
        $this->requireAdmin();
        $agence = $this->findOrFail($id);

        return $this->render('admin/agences/edit', ['agence' => $agence, 'errors' => [], 'old' => []]);
    }

    /**
     * Valide et applique la modification d'une agence.
     */
    public function update(int $id): string
    {
        $this->requireAdmin();
        $agence = $this->findOrFail($id);
        $this->verifyCsrf();

        $input = ['nom' => $this->input('nom', '')];
        $errors = AgenceValidator::validate($input);

        $existing = isset($errors['nom']) ? null : App::agences()->findByNom((string) $input['nom']);
        if ($existing !== null && (int) $existing['id'] !== $id) {
            $errors['nom'] = 'Une agence porte déjà ce nom.';
        }

        if ($errors !== []) {
            return $this->render('admin/agences/edit', [
                'agence' => array_merge($agence, $input),
                'errors' => $errors,
                'old' => $input,
            ]);
        }

        App::agences()->update($id, (string) $input['nom']);

        FlashMessage::add('success', "L'agence a été modifiée.");
        $this->redirect('/admin/agences');
    }

    /**
     * Supprime une agence, sauf si elle est encore référencée par un trajet.
     */
    public function destroy(int $id): string
    {
        $this->requireAdmin();
        $this->findOrFail($id);
        $this->verifyCsrf();

        if (App::agences()->isReferencedByTrajet($id)) {
            FlashMessage::add('error', "Impossible de supprimer cette agence : elle est utilisée par au moins un trajet.");
            $this->redirect('/admin/agences');
        }

        App::agences()->delete($id);

        FlashMessage::add('success', "L'agence a été supprimée.");
        $this->redirect('/admin/agences');
    }

    /**
     * @return array{id: int, nom: string}
     */
    private function findOrFail(int $id): array
    {
        $agence = App::agences()->find($id);

        if ($agence === null) {
            throw new NotFoundException("Agence #{$id} introuvable.");
        }

        return $agence;
    }
}
