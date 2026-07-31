<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\TrajetValidator;
use PHPUnit\Framework\TestCase;

final class TrajetValidatorTest extends TestCase
{
    private function validInput(): array
    {
        $depart = (new \DateTimeImmutable('+2 days'))->format('Y-m-d\TH:i');
        $arrivee = (new \DateTimeImmutable('+2 days +3 hours'))->format('Y-m-d\TH:i');

        return [
            'agence_depart_id' => '1',
            'agence_arrivee_id' => '2',
            'date_heure_depart' => $depart,
            'date_heure_arrivee' => $arrivee,
            'nb_places_total' => '3',
        ];
    }

    public function testValidInputProducesNoErrors(): void
    {
        self::assertSame([], TrajetValidator::validate($this->validInput()));
    }

    public function testSameAgenceIsRejected(): void
    {
        $input = $this->validInput();
        $input['agence_arrivee_id'] = $input['agence_depart_id'];

        $errors = TrajetValidator::validate($input);

        self::assertArrayHasKey('agence_arrivee_id', $errors);
    }

    public function testDepartureInThePastIsRejected(): void
    {
        $input = $this->validInput();
        $input['date_heure_depart'] = (new \DateTimeImmutable('-1 day'))->format('Y-m-d\TH:i');

        $errors = TrajetValidator::validate($input);

        self::assertArrayHasKey('date_heure_depart', $errors);
    }

    public function testArrivalBeforeDepartureIsRejected(): void
    {
        $input = $this->validInput();
        $input['date_heure_arrivee'] = $input['date_heure_depart'];

        $errors = TrajetValidator::validate($input);

        self::assertArrayHasKey('date_heure_arrivee', $errors);
    }

    public function testZeroPlacesIsRejected(): void
    {
        $input = $this->validInput();
        $input['nb_places_total'] = '0';

        $errors = TrajetValidator::validate($input);

        self::assertArrayHasKey('nb_places_total', $errors);
    }

    public function testMissingFieldsAreRejected(): void
    {
        $errors = TrajetValidator::validate([]);

        self::assertArrayHasKey('agence_depart_id', $errors);
        self::assertArrayHasKey('agence_arrivee_id', $errors);
        self::assertArrayHasKey('date_heure_depart', $errors);
        self::assertArrayHasKey('date_heure_arrivee', $errors);
        self::assertArrayHasKey('nb_places_total', $errors);
    }

    public function testToMysqlDatetimeConvertsDatetimeLocalFormat(): void
    {
        self::assertSame('2026-09-01 14:30:00', TrajetValidator::toMysqlDatetime('2026-09-01T14:30'));
    }
}
