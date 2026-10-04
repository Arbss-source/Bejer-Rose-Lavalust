<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller
{
    /**
     * Retrieve all users from the database via UsersModel
     * and pass them to the view for display.
     */
    public function index()
    {
        $this->call->database();
        $this->call->model('UsersModel');

        $data['users'] = $this->UsersModel->all();

        $this->call->view('users_view', $data);
    }
}
