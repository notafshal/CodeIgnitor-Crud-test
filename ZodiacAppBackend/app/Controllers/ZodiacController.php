<?php

namespace App\Controllers;

use App\Models\ZodiacModel;
use CodeIgniter\RESTful\ResourceController;

class ZodiacController extends ResourceController
{
    protected $model;

    public function __construct()
    {
        // ✅ Manually load the model
        $this->model = new ZodiacModel();
    }

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($id = null)
    {
        $data = $this->model->find($id);
        return $data ? $this->respond($data) : $this->failNotFound('Record not found.');
    }

    public function create()
    {
        $input = $this->request->getJSON();
        $this->model->insert([
            'full_name' => $input->full_name,
            'zodiac'    => $input->zodiac
        ]);
        return $this->respondCreated(['message' => 'Record created successfully']);
    }

    public function update($id = null)
    {
        $input = $this->request->getJSON();
        $this->model->update($id, [
            'full_name' => $input->full_name,
            'zodiac'    => $input->zodiac
        ]);
        return $this->respond(['message' => 'Record updated successfully']);
    }

    public function delete($id = null)
    {
        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'Record deleted successfully']);
    }
}
