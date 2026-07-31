<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

/**
 * Levée lorsqu'une ressource demandée (trajet, agence, utilisateur) n'existe pas.
 */
final class NotFoundException extends RuntimeException
{
}
