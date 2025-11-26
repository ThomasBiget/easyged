<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$router = new Router();

$router->get('/invoices', 'InvoiceController@index');
$router->get('/invoices/show', 'InvoiceController@show');
$router->post('/invoices', 'InvoiceController@store');
$router->put('/invoices', 'InvoiceController@update');
$router->delete('/invoices', 'InvoiceController@delete');

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
