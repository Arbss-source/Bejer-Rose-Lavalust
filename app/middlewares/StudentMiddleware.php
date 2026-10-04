<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');


class StudentMiddleware
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

        $has_badge = isset($_SESSION['profile_access']) && $_SESSION['profile_access'] === true;

        if (!$has_badge) {
            
            redirect('student?denied=1');
            return;
        }

       
        return $next();
    }
}
