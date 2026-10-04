<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * ProductModel
 *
 * Represents the `products` table created in Laboratory Exercise No. 5.
 * Columns: id, product_name, description, price, quantity, created_at
 */
class ProductModel extends Model
{
    /**
     * Database table this model represents.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Primary key of the table.
     *
     * @var string
     */
    protected $primary_key = 'id';

    /**
     * Mass-assignable fields.
     * `id` and `created_at` are intentionally excluded so they can
     * never be overwritten through form input.
     *
     * @var array
     */
    protected $fillable = ['product_name', 'description', 'price', 'quantity'];
}
