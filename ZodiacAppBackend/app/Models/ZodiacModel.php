<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\HTTP\Client;

class ZodiacModel extends Model
{
    protected $table = 'full_name_zodiac';
    protected $primaryKey = 'id';
    protected $allowedFields = ['full_name', 'zodiac'];

    protected $client;
    protected $apiUrl;
    protected $apiHost;
    protected $apiKey;

    public function __construct()
    {
        parent::__construct();
        $this->client = service('curlrequest');
        $this->apiUrl = getenv('API_KEY');  
        $this->apiHost = getenv('X_RAPIDAPI_HOST'); 
        $this->apiKey = getenv('X_RAPIDAPI_KEY'); 
    }

   
    public function getHoroscope($zodiac)
    {
        $apiEndpoint = "{$this->apiUrl}?zodiac={$zodiac}";

        try {
            $response = $this->client->request('GET', $apiEndpoint, [
                'headers' => [
                    'X-RapidAPI-Key'  => $this->apiKey,
                    'X-RapidAPI-Host' => $this->apiHost
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (\Exception $e) {
            return ['error' => 'API request failed: ' . $e->getMessage()];
        }
    }
}
