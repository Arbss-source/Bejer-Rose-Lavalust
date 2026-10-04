<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * AuthMiddleware
 *
 * Blocks unauthenticated users from reaching the routes it is attached to.
 * A user is considered authenticated once AuthController::authenticate()
 * has set $_SESSION['user_id'] on a successful login.
 */
class AuthMiddleware
{
    /**
     * Handle the incoming request.
     *
     * @param Closure $next
     * @return mixed
     */
    public function handle(Closure $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $is_logged_in = isset($_SESSION['user_id']);

        if (!$is_logged_in) {
            // Remember where the user was headed so we can send them
            // back there after a successful login.
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/products';
            redirect('login?denied=1');
            return;
        }

        return $next();
    }
}
