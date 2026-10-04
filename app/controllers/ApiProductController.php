<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ApiProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    $this->call->database();
    $this->call->library('api');
    }

    // GET /api/products
    public function index()
    {
        $this->api->require_method('GET');
        $this->api->require_jwt();

        $stmt = $this->db->raw('SELECT * FROM products ORDER BY id ASC');
        $this->api->respond(['data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    }

    // POST /api/products
    public function store()
    {
        $this->api->require_method('POST');
        $this->api->require_jwt();
        $in = $this->validated($this->api->body());

        $this->db->raw(
            'INSERT INTO products (product_name, description, price, quantity) VALUES (?, ?, ?, ?)',
            [$in['product_name'], $in['description'], $in['price'], $in['quantity']]
        );
        $this->api->respond(['message' => 'Product created'], 201);
    }

    // PUT /api/products/{id}
    public function update($id)
    {
        $this->api->require_method('PUT');
        $this->api->require_jwt();
        $this->ensure_exists($id);
        $in = $this->validated($this->api->body());

        $this->db->raw(
            'UPDATE products SET product_name = ?, description = ?, price = ?, quantity = ? WHERE id = ?',
            [$in['product_name'], $in['description'], $in['price'], $in['quantity'], $id]
        );
        $this->api->respond(['message' => 'Product updated']);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $this->api->require_method('DELETE');
        $this->api->require_jwt();
        $this->ensure_exists($id);

        $this->db->raw('DELETE FROM products WHERE id = ?', [$id]);
        $this->api->respond(['message' => 'Product deleted']);
    }

    private function ensure_exists($id)
    {
        $stmt = $this->db->raw('SELECT id FROM products WHERE id = ?', [$id]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            $this->api->respond_error('Product not found', 404);
        }
    }

    private function validated(array $in)
    {
        $name  = trim($in['product_name'] ?? '');
        $price = $in['price'] ?? null;
        $qty   = $in['quantity'] ?? null;

        if ($name === '' || strlen($name) > 100) {
            $this->api->respond_error('Product name is required (max 100 characters)', 422);
        }
        if (!is_numeric($price) || $price < 0) {
            $this->api->respond_error('Price must be a number, 0 or higher', 422);
        }
        if (filter_var($qty, FILTER_VALIDATE_INT) === false || $qty < 0) {
            $this->api->respond_error('Quantity must be a whole number, 0 or higher', 422);
        }
        return [
            'product_name' => $name,
            'description'  => trim($in['description'] ?? ''),
            'price'        => $price,
            'quantity'     => (int) $qty,
        ];
    }
}