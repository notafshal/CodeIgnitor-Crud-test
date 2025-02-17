<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ZodiacController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->group('api', function ($routes) {
    $routes->get('fullnames', 'ZodiacController::index');   // Get all records
    $routes->get('fullnames/(:num)', 'ZodiacController::show/$1');  // Get one record
    $routes->post('fullnames', 'ZodiacController::create'); // Create record
    $routes->put('fullnames/(:num)', 'ZodiacController::update/$1'); // Update record
    $routes->delete('fullnames/(:num)', 'ZodiacController::delete/$1'); // Delete record
});

