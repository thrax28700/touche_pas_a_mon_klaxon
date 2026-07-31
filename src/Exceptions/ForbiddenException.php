<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Levée lorsqu'un utilisateur connecté tente une action qu'il n'est pas
 * autorisé à effectuer (ex: modifier le trajet d'un autre employé).
 */
final class ForbiddenException extends RuntimeException
{
}
