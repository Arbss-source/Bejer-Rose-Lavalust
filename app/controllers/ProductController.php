<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductController extends Controller
{
    /**
     * Actions only an admin is allowed to perform.
     * Enforced again here as a backstop in case route middleware
     * is ever changed - the route-level 'admin' middleware is the
     * primary guard.
     *
     * @var array
     */
    private $admin_only_actions = ['create', 'store', 'edit', 'update', 'delete'];

    public function before_action()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->call->database();
        $this->call->model('ProductModel');

        $action = $this->current_action();
        if (in_array($action, $this->admin_only_actions, true) && ($_SESSION['role'] ?? null) !== 'admin') {
            $_SESSION['flash_error'] = 'Only administrators can manage products.';
            redirect('products');
            exit;
        }
    }

    /**
     * Best-effort detection of the action about to be invoked,
     * based on the request method and URI shape.
     *
     * @return string
     */
    private function current_action()
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if (preg_match('#products/create$#', $uri)) {
            return $method === 'POST' ? 'store' : 'create';
        }
        if (preg_match('#products/edit/\d+$#', $uri)) {
            return $method === 'POST' ? 'update' : 'edit';
        }
        if (preg_match('#products/delete/\d+$#', $uri)) {
            return 'delete';
        }

        return 'index';
    }

    
    public function index()
    {
        $data['products'] = $this->ProductModel->all();
        $data['success'] = $_SESSION['flash_success'] ?? null;
        $data['error'] = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $this->call->view('products_index', $data);
    }

    
    public function create()
    {
        $data['mode'] = 'create';
        $data['product'] = null;
        $data['error'] = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        $this->call->view('products_form', $data);
    }

    
    public function store()
    {
        [$valid, $result] = $this->validated($_POST);

        if (!$valid) {
            $_SESSION['flash_error'] = $result;
            redirect('products/create');
            return;
        }

        $this->ProductModel->insert($result);

        $_SESSION['flash_success'] = 'Product added successfully.';
        redirect('products');
    }

    /**
     * Show the edit-product form.
     *
     * @param int $id
     */
    public function edit($id)
    {
        $product = $this->ProductModel->find($id);

        if (!$product) {
            $_SESSION['flash_error'] = 'Product not found.';
            redirect('products');
            return;
        }

        $data['mode'] = 'edit';
        $data['product'] = $product;
        $data['error'] = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        $this->call->view('products_form', $data);
    }

    /**
     * Update - changes to an existing product.
     *
     * @param int $id
     */
    public function update($id)
    {
        $product = $this->ProductModel->find($id);

        if (!$product) {
            $_SESSION['flash_error'] = 'Product not found.';
            redirect('products');
            return;
        }

        [$valid, $result] = $this->validated($_POST);

        if (!$valid) {
            $_SESSION['flash_error'] = $result;
            redirect('products/edit/' . $id);
            return;
        }

        $this->ProductModel->update($id, $result);

        $_SESSION['flash_success'] = 'Product updated successfully.';
        redirect('products');
    }

    /**
     * Delete a product
     *
     * @param int $id
     */
    public function delete($id)
    {
        $product = $this->ProductModel->find($id);

        if (!$product) {
            $_SESSION['flash_error'] = 'Product not found.';
            redirect('products');
            return;
        }

        $this->ProductModel->delete($id);

        $_SESSION['flash_success'] = 'Product deleted successfully.';
        redirect('products');
    }

    /**
     * Validating and normalizing product form inputs.
     *
     * @param array $input
     * @return array [bool $valid, array|string $dataOrError]
     */
    private function validated(array $input)
    {
        $product_name = trim($input['product_name'] ?? '');
        $description  = trim($input['description'] ?? '');
        $price        = $input['price'] ?? '';
        $quantity     = $input['quantity'] ?? '';

        if ($product_name === '') {
            return [false, 'Product name is required.'];
        }

        if (strlen($product_name) > 100) {
            return [false, 'Product name must be 100 characters or fewer.'];
        }

        if ($price === '' || !is_numeric($price) || (float) $price < 0) {
            return [false, 'Price must be a valid non-negative number.'];
        }

        if ($quantity === '' || !ctype_digit((string) $quantity) || (int) $quantity < 0) {
            return [false, 'Quantity must be a valid non-negative whole number.'];
        }

        return [true, [
            'product_name' => $product_name,
            'description'  => $description,
            'price'        => number_format((float) $price, 2, '.', ''),
            'quantity'     => (int) $quantity,
        ]];
    }
}
