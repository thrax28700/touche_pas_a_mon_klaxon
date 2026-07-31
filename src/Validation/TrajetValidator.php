<?php

declare(strict_types=1);

namespace App\Validation;

use DateTimeImmutable;
use Exception;

/**
 * Règles de cohérence métier pour la création/modification d'un trajet.
 * Ne dépend d'aucun accès base de données : entièrement testable en isolation.
 */
final class TrajetValidator
{
    /**
     * @param array{
     *     agence_depart_id?: string|null,
     *     agence_arrivee_id?: string|null,
     *     date_heure_depart?: string|null,
     *     date_heure_arrivee?: string|null,
     *     nb_places_total?: string|null
     * } $input
     *
     * @return array<string, string> Erreurs indexées par champ, vide si valide.
     */
    public static function validate(array $input): array
    {
        $errors = [];

        $agenceDepartId = $input['agence_depart_id'] ?? '';
        $agenceArriveeId = $input['agence_arrivee_id'] ?? '';

        if ($agenceDepartId === '' || !ctype_digit((string) $agenceDepartId)) {
            $errors['agence_depart_id'] = "Veuillez choisir une agence de départ.";
        }

        if ($agenceArriveeId === '' || !ctype_digit((string) $agenceArriveeId)) {
            $errors['agence_arrivee_id'] = "Veuillez choisir une agence d'arrivée.";
        }

        if (!isset($errors['agence_depart_id']) && !isset($errors['agence_arrivee_id'])
            && $agenceDepartId === $agenceArriveeId) {
            $errors['agence_arrivee_id'] = "L'agence d'arrivée doit être différente de l'agence de départ.";
        }

        $depart = self::parseDate($input['date_heure_depart'] ?? '');
        $arrivee = self::parseDate($input['date_heure_arrivee'] ?? '');

        if ($depart === null) {
            $errors['date_heure_depart'] = 'Veuillez saisir une date et heure de départ valides.';
        } elseif ($depart <= new DateTimeImmutable()) {
            $errors['date_heure_depart'] = 'La date de départ doit être dans le futur.';
        }

        if ($arrivee === null) {
            $errors['date_heure_arrivee'] = "Veuillez saisir une date et heure d'arrivée valides.";
        } elseif ($depart !== null && $arrivee <= $depart) {
            $errors['date_heure_arrivee'] = "La date d'arrivée doit être postérieure à la date de départ.";
        }

        $nbPlaces = $input['nb_places_total'] ?? '';

        if ($nbPlaces === '' || !ctype_digit((string) $nbPlaces) || (int) $nbPlaces < 1) {
            $errors['nb_places_total'] = 'Le nombre de places doit être un entier positif.';
        }

        return $errors;
    }

    /**
     * Convertit une chaîne issue d'un champ `datetime-local` (ex: 2026-08-01T14:30)
     * au format attendu par MySQL (`Y-m-d H:i:s`).
     */
    public static function toMysqlDatetime(string $value): string
    {
        $date = self::parseDate($value);

        return $date === null ? '' : $date->format('Y-m-d H:i:00');
    }

    private static function parseDate(string $value): ?DateTimeImmutable
    {
        if ($value === '') {
            return null;
        }

        try {
            return new DateTimeImmutable($value);
        } catch (Exception) {
            return null;
        }
    }
}
