<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Database\Database;
use App\Repository\InvoiceRepository;
use App\Repository\LineItemRepository;
use App\Service\InvoiceService;
use App\Controller\InvoiceController;

// ✅ HEADERS API
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

/*
|--------------------------------------------------------------------------
| ✅ BOOTSTRAP APPLICATION
|--------------------------------------------------------------------------
*/

// ✅ 1. Une seule connexion PDO pour toute l'app
$db = Database::getInstance()->getConnection();

// ✅ 2. Repositories
$invoiceRepository = new InvoiceRepository($db);
$lineItemRepository = new LineItemRepository($db);

// ✅ 3. Service métier (avec transaction)
$invoiceService = new InvoiceService(
    $db,
    $invoiceRepository,
    $lineItemRepository
);

// ✅ 4. Controller (ne connaît QUE le service)
$invoiceController = new InvoiceController($invoiceService);

/*
|--------------------------------------------------------------------------
| ✅ ROUTER
|--------------------------------------------------------------------------
*/

$router = new Router();

// ✅ On passe maintenant DES OBJETS, plus des strings
$router->get('/invoices', [$invoiceController, 'index']);
$router->get('/invoices/show', [$invoiceController, 'show']);
$router->post('/invoices', [$invoiceController, 'store']);
$router->put('/invoices', [$invoiceController, 'update']);
$router->delete('/invoices', [$invoiceController, 'delete']);

$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
