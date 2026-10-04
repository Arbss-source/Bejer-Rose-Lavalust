<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthController extends Controller
{
    public function before_action()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    
    public function login()
    {
        // Already logged in? Skip straight to the product list.
        if (isset($_SESSION['user_id'])) {
            redirect('products');
            return;
        }

        $data['denied'] = isset($_GET['denied']);
        $data['registered'] = isset($_GET['registered']);
        $data['error'] = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        $this->call->view('login_view', $data);
    }

    
    public function authenticate()
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $_SESSION['auth_error'] = 'Please enter both username and password.';
            redirect('login');
            return;
        }

        $this->call->database();
        $this->call->model('UsersModel');

        $user = $this->UsersModel->find_by('username', $username);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['auth_error'] = 'Invalid username or password.';
            redirect('login');
            return;
        }

        if (isset($user['is_active']) && !$user['is_active']) {
            $_SESSION['auth_error'] = 'This account has been deactivated.';
            redirect('login');
            return;
        }

        
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        $redirect_to = $_SESSION['redirect_after_login'] ?? 'products';
        unset($_SESSION['redirect_after_login']);

        redirect($redirect_to);
    }

    
    public function register()
    {
        if (isset($_SESSION['user_id'])) {
            redirect('products');
            return;
        }

        $data['error'] = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        $this->call->view('register_view', $data);
    }

    
    public function store_register()
    {
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['auth_error'] = 'All fields are required.';
            redirect('register');
            return;
        }

        if (strlen($password) < 6) {
            $_SESSION['auth_error'] = 'Password must be at least 6 characters.';
            redirect('register');
            return;
        }

        $this->call->database();
        $this->call->model('UsersModel');

        if ($this->UsersModel->find_by('username', $username)) {
            $_SESSION['auth_error'] = 'That username is already taken.';
            redirect('register');
            return;
        }

        if ($this->UsersModel->find_by('email', $email)) {
            $_SESSION['auth_error'] = 'That email is already registered.';
            redirect('register');
            return;
        }

        $this->UsersModel->insert([
            'username'  => $username,
            'email'     => $email,
            'password'  => password_hash($password, PASSWORD_DEFAULT),
            'role'      => 'user',
            'is_active' => 1,
        ]);

        redirect('login?registered=1');
    }

    
    public function logout()
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        redirect('login');
    }
}
