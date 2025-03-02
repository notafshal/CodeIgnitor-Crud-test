<?php

namespace App\Controllers;

use App\Models\ZodiacModel;
use CodeIgniter\RESTful\ResourceController;

class ZodiacController extends ResourceController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ZodiacModel();
    }

    /**
     * Get all zodiac records
     */
    public function index()
    {
        $data = $this->model->findAll();
        return empty($data) ? $this->failNotFound('No records found.') : $this->respond($data);
    }

    /**
     * Get a specific zodiac record by ID
     */
    public function show($id = null)
    {
        $data = $this->model->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Record not found.');
    }

    /**
     * Create a new zodiac record
     */
    public function create()
    {
        $input = $this->request->getJSON(true);

        // Validate input
        if (!isset($input['full_name']) || !isset($input['zodiac'])) {
            return $this->failValidationErrors('full_name and zodiac are required.');
        }

        if (!$this->model->insert($input)) {
            return $this->failServerError('Failed to create record.');
        }

        return $this->respondCreated(['message' => 'Record created successfully']);
    }

    /**
     * Update an existing zodiac record
     */
    public function update($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Record not found.');
        }

        $input = $this->request->getJSON(true);

        if (!$this->model->update($id, $input)) {
            return $this->failServerError('Failed to update record.');
        }

        return $this->respond(['message' => 'Record updated successfully']);
    }

    /**
     * Delete a zodiac record
     */
    public function delete($id = null)
    {
        if (!$this->model->find($id)) {
            return $this->failNotFound('Record not found.');
        }

        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'Record deleted successfully']);
    }

    /**
     * Fetch horoscope from external API
     */
    public function getHoroscope($zodiac)
{
    try {
        $data = $this->model->getHoroscope($zodiac);

        if (!$data) {
            return $this->failNotFound("Horoscope data not found for zodiac: $zodiac");
        }

        if (isset($data['error'])) {
            return $this->failServerError($data['error']);
        }

        return $this->respond($data, 200);
    } catch (\Exception $e) {
        return $this->failServerError("An unexpected error occurred: " . $e->getMessage());
    }
}

}
