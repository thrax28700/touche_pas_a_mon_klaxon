<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Validation\AgenceValidator;
use PHPUnit\Framework\TestCase;

final class AgenceValidatorTest extends TestCase
{
    public function testValidNameProducesNoErrors(): void
    {
        self::assertSame([], AgenceValidator::validate(['nom' => 'Bordeaux']));
    }

    public function testEmptyNameIsRejected(): void
    {
        $errors = AgenceValidator::validate(['nom' => '   ']);

        self::assertArrayHasKey('nom', $errors);
    }

    public function testTooLongNameIsRejected(): void
    {
        $errors = AgenceValidator::validate(['nom' => str_repeat('a', 101)]);

        self::assertArrayHasKey('nom', $errors);
    }
}
