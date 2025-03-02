<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\ZodiacController;
use App\Controllers\ProductController;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


$routes->group('api', function ($routes) {
    $routes->get('fullnames', 'ZodiacController::index');   
    $routes->get('fullnames/(:num)', 'ZodiacController::show/$1');  
    $routes->post('fullnames', 'ZodiacController::create'); 
    $routes->put('fullnames/(:num)', 'ZodiacController::update/$1'); 
    $routes->delete('fullnames/(:num)', 'ZodiacController::delete/$1');
});
$routes->get('/products', 'ProductController::index');      
$routes->get('/products/(:num)', 'ProductController::show/$1');  
$routes->post('/products', 'ProductController::create');    
$routes->put('/products/(:num)', 'ProductController::update/$1'); 
$routes->delete('/products/(:num)', 'ProductController::delete/$1');  
$routes->get('product/fetchFromAPI', 'ProductController::fetchFromAPI'); 


$routes->get('api/horoscope/(:segment)', 'ZodiacController::getHoroscope/$1');

