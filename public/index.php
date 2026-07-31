<?php

declare(strict_types=1);

use App\Config\Env;
use App\Exceptions\ForbiddenException;
use App\Exceptions\NotFoundException;
use Buki\Router\Router;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require dirname(__DIR__) . '/vendor/autoload.php';

Env::load(dirname(__DIR__) . '/.env');

session_name(Env::get('SESSION_NAME', 'tpak_session'));
session_start();

$router = new Router([
    'debug' => Env::get('APP_DEBUG', 'false') === 'true',
    'paths' => [
        'controllers' => dirname(__DIR__) . '/src/Controllers',
    ],
    'namespaces' => [
        'controllers' => 'App\\Controllers',
    ],
    'cache' => dirname(__DIR__) . '/storage/routes-cache.php',
]);

$router->notFound(function (Request $request, Response $response): Response {
    $response->setStatusCode(404);
    $response->setContent('<h1>404 — Page introuvable</h1><p><a href="/">Retour à l\'accueil</a></p>');

    return $response;
});

$router->error(function (Request $request, Response $response, ?Throwable $exception = null): Response {
    if ($exception instanceof NotFoundException) {
        $response->setStatusCode(404);
        $response->setContent('<h1>404 — ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</h1>');

        return $response;
    }

    if ($exception instanceof ForbiddenException) {
        $response->setStatusCode(403);
        $response->setContent('<h1>403 — ' . htmlspecialchars($exception->getMessage(), ENT_QUOTES, 'UTF-8') . '</h1>');

        return $response;
    }

    $response->setStatusCode(500);
    $response->setContent('<h1>500 — Une erreur est survenue</h1>');

    return $response;
});

require dirname(__DIR__) . '/routes/web.php';

$router->run();
