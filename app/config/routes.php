<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
/**
 * ------------------------------------------------------------------
 * LavaLust - an opensource lightweight PHP MVC Framework
 * ------------------------------------------------------------------
 *
 * MIT License
 *
 * Copyright (c) 2020 Ronald M. Marasigan
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package LavaLust
 * @author Ronald M. Marasigan <ronald.marasigan@yahoo.com>
 * @since Version 1
 * @link https://github.com/ronmarasigan/LavaLust
 * @license https://opensource.org/licenses/MIT MIT License
 */

/*
| -------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------
| Here is where you can register web routes for your application.
|
|
*/
/** @var object $router **/

// Load middleware registrations early so kernel Middleware class can see them
// before the router dispatches (Config::load() would run too late for this).
// Wrapped in a closure so the local $config array inside middleware.php
// does not collide with the global $config Config object used elsewhere.
(function () {
    require_once APP_DIR . 'config/middleware.php';
    get_config($config);
})();

$router->get('/', 'AuthController::login');

$router->get('/student', 'StudentController::index');
$router->get('/student/profile', 'StudentController::profile')->middleware('student');

$router->get('/users', 'UsersController::index');


// For Authentication

$router->get('/login', 'AuthController::login');
$router->post('/login', 'AuthController::authenticate');
$router->get('/register', 'AuthController::register');
$router->post('/register', 'AuthController::store_register');
$router->get('/logout', 'AuthController::logout');



// Viewing requires login only. To Create/Update/Delete --------


$router->get('/products', 'ProductController::index')->middleware('auth');
$router->get('/products/create', 'ProductController::create')->middleware(['auth', 'admin']);
$router->post('/products/create', 'ProductController::store')->middleware(['auth', 'admin']);
$router->get('/products/edit/{id}', 'ProductController::edit')->middleware(['auth', 'admin'])->where_number('id');
$router->post('/products/edit/{id}', 'ProductController::update')->middleware(['auth', 'admin'])->where_number('id');
$router->post('/products/delete/{id}', 'ProductController::delete')->middleware(['auth', 'admin'])->where_number('id');


// new routes for migration controller
// Migration Routes
$router->get('create-migration/{migration_class}', 'MigrationController::create_migration');
$router->get('migrate', 'MigrationController::migrate');
$router->get('rollback', 'MigrationController::rollback');
$router->get('rollback-all', 'MigrationController::rollback_all');
$router->get('refresh', 'MigrationController::refresh');
$router->get('status', 'MigrationController::status');




// API Routes (Lab 6)
$router->get('/api', function () {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'ok', 'message' => 'LavaLust API is running']);
});

$router->group(['prefix' => '/api'], function ($router) {
    // Auth
    $router->match('/auth/register', 'ApiAuthController::register', ['post', 'options']);
    $router->match('/auth/login',    'ApiAuthController::login',    ['post', 'options']);
    $router->match('/auth/refresh',  'ApiAuthController::refresh',  ['post', 'options']);
    $router->match('/auth/logout',   'ApiAuthController::logout',   ['post', 'options']);

    // Products (protected by JWT inside the controller)
    $router->match('/products',       'ApiProductController::index',  ['get', 'options']);
    $router->post('/products',        'ApiProductController::store');
    $router->match('/products/{id}',  'ApiProductController::update', ['put', 'options']);
    $router->delete('/products/{id}', 'ApiProductController::destroy');
});