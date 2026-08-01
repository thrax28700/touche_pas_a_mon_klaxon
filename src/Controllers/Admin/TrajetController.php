<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\App;
use App\Core\Controller;
use App\Core\FlashMessage;
use DateTimeImmutable;

/**
 * Liste de tous les trajets et suppression, réservé à l'administrateur.
 */
final class TrajetController extends Controller
{
    /**
     * Liste tous les trajets, sans filtre de date ni de disponibilité.
     */
    public function index(): string
    {
        $this->requireAdmin();

        return $this->render('admin/trajets/index', [
            'trajets' => App::trajets()->all(),
        ]);
    }

    /**
     * Supprime n'importe quel trajet.
     */
    public function destroy(int $id): string
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        App::trajets()->delete($id);

        FlashMessage::add('success', 'Le trajet a été supprimé.');
        $this->redirect('/admin/trajets');
    }

    /**
     * Exporte tous les trajets au format CSV (UTF-8, séparateur ";").
     */
    public function exportCsv(): never
    {
        $this->requireAdmin();

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="trajets.csv"');

        echo "\xEF\xBB\xBF";

        $output = fopen('php://output', 'w');
        fputcsv($output, [
            'Depart', 'Date/heure depart', 'Arrivee', 'Date/heure arrivee',
            'Places disponibles', 'Places totales', 'Auteur', 'Telephone', 'Email',
        ], ';');

        foreach (App::trajets()->all() as $trajet) {
            fputcsv($output, [
                $trajet['agence_depart_nom'],
                (new DateTimeImmutable($trajet['date_heure_depart']))->format('d/m/Y H:i'),
                $trajet['agence_arrivee_nom'],
                (new DateTimeImmutable($trajet['date_heure_arrivee']))->format('d/m/Y H:i'),
                $trajet['nb_places_disponibles'],
                $trajet['nb_places_total'],
                $trajet['auteur_prenom'] . ' ' . $trajet['auteur_nom'],
                $trajet['auteur_telephone'],
                $trajet['auteur_email'],
            ], ';');
        }

        fclose($output);
        exit;
    }
}
