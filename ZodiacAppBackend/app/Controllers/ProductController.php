<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\RESTful\ResourceController;

class ProductController extends ResourceController
{
    protected $modelName = ProductModel::class; // Use dependency injection
    protected $format = 'json';

    /**
     * Fetch products from FakeStore API and store them in the database
     */
    public function fetchFromAPI()
    {
        $apiUrl = "https://fakestoreapi.com/products";
        $response = @file_get_contents($apiUrl); // Suppress errors

        if ($response === false) {
            log_message('error', 'Failed to fetch products from API.');
            return $this->failServerError('Failed to fetch products from API.');
        }

        $products = json_decode($response, true);

        if (!is_array($products)) {
            return $this->failServerError('Invalid API response.');
        }

        foreach ($products as $product) {
            $this->model->insert([
                'title' => $product['title'] ?? 'Unknown Title',
                'price' => $product['price'] ?? 0
            ]);
        }

        return $this->respond(['message' => 'Products fetched and stored successfully']);
    }

    /**
     * Get all products
     */
    public function index()
    {
        $products = $this->model->findAll();
        return empty($products)
            ? $this->failNotFound('No products found.')
            : $this->respond($products);
    }

    /**
     * Get a single product
     */
    public function show($id = null)
    {
        $product = $this->model->find($id);
        return $product 
            ? $this->respond($product) 
            : $this->failNotFound('Product not found.');
    }

    /**
     * Create a new product
     */
    public function create()
    {
        $validationRules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'price' => 'required|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true);
        if (!$this->model->insert($input)) {
            return $this->failServerError('Failed to create product.');
        }

        return $this->respondCreated(['message' => 'Product created successfully']);
    }

    /**
     * Update an existing product
     */
    public function update($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Product not found.');
        }

        $validationRules = [
            'title' => 'required|min_length[3]|max_length[255]',
            'price' => 'required|numeric'
        ];

        if (!$this->validate($validationRules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $input = $this->request->getJSON(true);
        if (!$this->model->update($id, $input)) {
            return $this->failServerError('Failed to update product.');
        }

        return $this->respond(['message' => 'Product updated successfully']);
    }

    /**
     * Delete a product
     */
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Product not found.');
        }

        if (!$this->model->delete($id)) {
            return $this->failServerError('Failed to delete product.');
        }

        return $this->respondDeleted(['message' => 'Product deleted successfully']);
    }
}
