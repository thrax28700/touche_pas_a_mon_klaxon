<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\App;
use App\Core\Controller;
use App\Core\FlashMessage;
use App\Exceptions\NotFoundException;
use App\Validation\TrajetValidator;

/**
 * Création, modification et suppression d'un trajet par son auteur
 * (ou par un administrateur).
 */
final class TrajetController extends Controller
{
    public function create(): string
    {
        $this->requireAuth();

        return $this->render('trajets/create', [
            'agences' => App::agences()->all(),
            'auteur' => $this->auth->user(),
            'errors' => [],
            'old' => [],
        ]);
    }

    public function store(): string
    {
        $this->requireAuth();
        $this->verifyCsrf();

        $input = [
            'agence_depart_id' => $this->input('agence_depart_id', ''),
            'agence_arrivee_id' => $this->input('agence_arrivee_id', ''),
            'date_heure_depart' => $this->input('date_heure_depart', ''),
            'date_heure_arrivee' => $this->input('date_heure_arrivee', ''),
            'nb_places_total' => $this->input('nb_places_total', ''),
        ];

        $errors = TrajetValidator::validate($input);

        if ($errors !== []) {
            return $this->render('trajets/create', [
                'agences' => App::agences()->all(),
                'auteur' => $this->auth->user(),
                'errors' => $errors,
                'old' => $input,
            ]);
        }

        App::trajets()->create([
            'agence_depart_id' => (int) $input['agence_depart_id'],
            'agence_arrivee_id' => (int) $input['agence_arrivee_id'],
            'date_heure_depart' => TrajetValidator::toMysqlDatetime($input['date_heure_depart']),
            'date_heure_arrivee' => TrajetValidator::toMysqlDatetime($input['date_heure_arrivee']),
            'nb_places_total' => (int) $input['nb_places_total'],
            'utilisateur_id' => (int) $this->auth->id(),
        ]);

        FlashMessage::add('success', 'Le trajet a été créé.');
        $this->redirect('/');
    }

    public function edit(int $id): string
    {
        $trajet = $this->findOrFail($id);
        $this->requireAuthorOrAdmin($trajet['utilisateur_id']);

        return $this->render('trajets/edit', [
            'trajet' => $trajet,
            'agences' => App::agences()->all(),
            'errors' => [],
            'old' => [],
        ]);
    }

    public function update(int $id): string
    {
        $trajet = $this->findOrFail($id);
        $this->requireAuthorOrAdmin($trajet['utilisateur_id']);
        $this->verifyCsrf();

        $input = [
            'agence_depart_id' => $this->input('agence_depart_id', ''),
            'agence_arrivee_id' => $this->input('agence_arrivee_id', ''),
            'date_heure_depart' => $this->input('date_heure_depart', ''),
            'date_heure_arrivee' => $this->input('date_heure_arrivee', ''),
            'nb_places_total' => $this->input('nb_places_total', ''),
        ];

        $errors = TrajetValidator::validate($input);

        $nbPlacesDisponibles = (int) $this->input('nb_places_disponibles', '0');

        if ($nbPlacesDisponibles < 0 || $nbPlacesDisponibles > (int) $input['nb_places_total']) {
            $errors['nb_places_disponibles'] = 'Le nombre de places disponibles doit être compris entre 0 et le nombre total de places.';
        }

        if ($errors !== []) {
            return $this->render('trajets/edit', [
                'trajet' => array_merge($trajet, $input, ['nb_places_disponibles' => $nbPlacesDisponibles]),
                'agences' => App::agences()->all(),
                'errors' => $errors,
                'old' => $input,
            ]);
        }

        App::trajets()->update($id, [
            'agence_depart_id' => (int) $input['agence_depart_id'],
            'agence_arrivee_id' => (int) $input['agence_arrivee_id'],
            'date_heure_depart' => TrajetValidator::toMysqlDatetime($input['date_heure_depart']),
            'date_heure_arrivee' => TrajetValidator::toMysqlDatetime($input['date_heure_arrivee']),
            'nb_places_total' => (int) $input['nb_places_total'],
            'nb_places_disponibles' => $nbPlacesDisponibles,
        ]);

        FlashMessage::add('success', 'Le trajet a été modifié.');
        $this->redirect('/');
    }

    public function destroy(int $id): string
    {
        $trajet = $this->findOrFail($id);
        $this->requireAuthorOrAdmin($trajet['utilisateur_id']);
        $this->verifyCsrf();

        App::trajets()->delete($id);

        FlashMessage::add('success', 'Le trajet a été supprimé.');
        $this->redirect('/');
    }

    /**
     * @return array<string, mixed>
     */
    private function findOrFail(int $id): array
    {
        $trajet = App::trajets()->find($id);

        if ($trajet === null) {
            throw new NotFoundException("Trajet #{$id} introuvable.");
        }

        return $trajet;
    }
}
