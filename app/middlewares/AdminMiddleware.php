<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * AdminMiddleware
 *
 * Runs after AuthMiddleware. Restricts routes to users whose
 * session role is 'admin'. Logged-in non-admin users (role
 * 'user' or 'moderator') are bounced back to the product list
 * with a message instead of a login redirect, since they are
 * already authenticated - they just lack permission.
 */
class AdminMiddleware
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

        $role = $_SESSION['role'] ?? null;

        if ($role !== 'admin') {
            $_SESSION['flash_error'] = 'Only administrators can manage products.';
            redirect('products');
            return;
        }

        return $next();
    }
}
