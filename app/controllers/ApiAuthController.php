<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ApiAuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        $this->call->database();
    }

    public function register()
    {
        $this->api->require_method('POST');
        $in = $this->api->body();

        $username = trim($in['username'] ?? '');
        $email    = trim($in['email'] ?? '');
        $password = $in['password'] ?? '';

        if ($username === '' || $email === '' || strlen($password) < 6) {
            $this->api->respond_error('Username, email and a password of at least 6 characters are required', 422);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->api->respond_error('Invalid email address', 422);
        }

        $stmt = $this->db->raw('SELECT id FROM users WHERE username = ? OR email = ?', [$username, $email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->api->respond_error('Username or email already exists', 409);
        }

        $this->db->raw(
            'INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)',
            [$username, $email, password_hash($password, PASSWORD_BCRYPT), 'user']
        );
        $this->api->respond(['message' => 'Account created'], 201);
    }

    public function login()
    {
        $this->api->require_method('POST');
        $in = $this->api->body();
        $username = $in['username'] ?? '';
        $password = $in['password'] ?? '';

        $stmt = $this->db->raw('SELECT * FROM users WHERE username = ?', [$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $tokens = $this->api->issue_tokens([
                'id'       => $user['id'],
                'username' => $user['username'],
                'role'     => $user['role'],
            ]);
            $this->api->respond($tokens);
        }
        $this->api->respond_error('Invalid username or password', 401);
    }

    public function refresh()
    {
        $this->api->require_method('POST');
        $in = $this->api->body();
        $this->api->refresh_access_token($in['refresh_token'] ?? '');
    }

    public function logout()
    {
        $this->api->require_method('POST');
        $in = $this->api->body();
        $this->api->revoke_refresh_token($in['refresh_token'] ?? '');
        $this->api->respond(['message' => 'Logged out']);
    }
}