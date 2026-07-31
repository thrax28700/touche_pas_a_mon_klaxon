<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\ForbiddenException;

/**
 * Contrôleur de base : rendu des vues (avec layout header/footer), aide
 * à la redirection, et vérifications d'autorisation communes.
 *
 * Le routeur instancie les contrôleurs sans argument (`new $class()`), les
 * dépendances partagées sont donc récupérées via {@see App}.
 */
abstract class Controller
{
    protected Auth $auth;

    public function __construct()
    {
        $this->auth = App::auth();
    }

    /**
     * Rend une vue enveloppée dans le layout (header + messages flash + footer).
     *
     * @param array<string, mixed> $data Variables extraites dans la vue.
     */
    protected function render(string $view, array $data = []): string
    {
        extract($data, EXTR_SKIP);

        $auth = $this->auth;
        $flashes = FlashMessage::pull();

        $viewsPath = dirname(__DIR__, 2) . '/views';

        ob_start();
        require $viewsPath . '/layouts/header.php';
        require $viewsPath . '/partials/flash.php';
        require $viewsPath . '/' . $view . '.php';
        require $viewsPath . '/layouts/footer.php';

        return (string) ob_get_clean();
    }

    /**
     * Redirige immédiatement le navigateur vers un autre chemin de l'application.
     */
    protected function redirect(string $path): never
    {
        header('Location: ' . $path);
        exit;
    }

    /**
     * Bloque l'accès et redirige vers la connexion si l'utilisateur n'est pas authentifié.
     */
    protected function requireAuth(): void
    {
        if (!$this->auth->check()) {
            FlashMessage::add('error', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect('/login');
        }
    }

    /**
     * Réserve l'accès à l'administrateur.
     */
    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (!$this->auth->isAdmin()) {
            $this->forbidden();
        }
    }

    /**
     * Réserve l'accès à l'auteur de la ressource ou à l'administrateur.
     */
    protected function requireAuthorOrAdmin(int $auteurId): void
    {
        $this->requireAuth();

        if ($this->auth->id() !== $auteurId && !$this->auth->isAdmin()) {
            $this->forbidden();
        }
    }

    /**
     * Vérifie le jeton CSRF soumis dans le formulaire courant.
     */
    protected function verifyCsrf(): void
    {
        if (!Csrf::isValid($this->input('csrf_token'))) {
            http_response_code(419);
            exit('Jeton de sécurité invalide ou expiré, veuillez recharger la page et réessayer.');
        }
    }

    /**
     * Récupère une valeur soumise en POST.
     */
    protected function input(string $key, ?string $default = null): ?string
    {
        $value = $_POST[$key] ?? $default;

        return is_string($value) ? trim($value) : $value;
    }

    private function forbidden(): never
    {
        throw new ForbiddenException("Vous n'avez pas les droits nécessaires pour effectuer cette action.");
    }
}
