<?php
/**
 * Main entry point for the REST API.
 * This file configures headers, handles basic authentication (API key),
 * and routes the incoming HTTP requests to the appropriate controllers.
 */

require_once __DIR__ . '/../config/bootstrap.php';

use App\Controller\StoreController;
use App\Controller\ProductController;
use App\Controller\EmployeeController;
use App\Controller\StockController;
use App\Controller\BrandController;
use App\Controller\CategoryController;

// Set default headers for JSON responses and CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');

/**
 * @var string $method The HTTP request method (GET, POST, PUT, DELETE).
 */
$method = $_SERVER['REQUEST_METHOD'];

/**
 * @var string $route The requested route parameter, trimmed of trailing slashes.
 */
$route = isset($_GET['route']) ? rtrim($_GET['route'], '/') : (isset($_GET['action']) ? $_GET['action'] : '');

/**
 * @var array $result The array that will be JSON encoded and returned to the client.
 */
$result = [];

/**
 * @var string $apiKeySecrete The secret API key required for write operations.
 */
$apiKeySecrete = "e8f1997c763";

// Authentication Check: All non-GET requests (except employee login) require a valid API key.
if ($method !== 'GET' && $route !== 'employees/login') {
    $providedKey = isset($_GET['api_key']) ? $_GET['api_key'] : '';
    
    if ($providedKey !== $apiKeySecrete) {
        http_response_code(401); 
        echo json_encode(['error' => 'Accès refusé. Clé API manquante ou invalide.']);
        exit; 
    }
}

// Parse the route into resource and ID segments
$parts = explode('/', $route);
$resource = $parts[0] ?? '';
$id = $parts[1] ?? null;

/**
 * @var string $actionDemandee The specific action requested via query parameters.
 */
$actionDemandee = isset($_GET['action']) ? $_GET['action'] : '';

// Routing alias resolution (mapping specific actions/routes to core resources)
if ($resource === 'getStores') $resource = 'stores';
if ($resource === 'getProducts' || $actionDemandee === 'addProduct' || $actionDemandee === 'updateProduct' || $actionDemandee === 'deleteProduct') $resource = 'products';
if ($resource === 'getStocksByStore' || $resource === 'updateStock' || $resource === 'deleteStock' || $resource === 'addStock') $resource = 'stocks';
if ($resource === 'getBrands' || $actionDemandee === 'addBrand' || $actionDemandee === 'updateBrand' || $actionDemandee === 'deleteBrand') $resource = 'brands';
if ($resource === 'getCategories' || $actionDemandee === 'addCategory' || $actionDemandee === 'updateCategory' || $actionDemandee === 'deleteCategory') $resource = 'categories';
if ($resource === 'employees' || $actionDemandee === 'getAllEmployees' || $actionDemandee === 'getEmployeesByStore' || $actionDemandee === 'addEmployee' || $actionDemandee === 'updateEmployee' || $actionDemandee === 'deleteEmployee') $resource = 'employees';

// Core routing switch based on the resolved resource
switch ($resource) {
    case 'stores':
        $controller = new StoreController($entityManager);
        $result = $controller->handleRequest($method, $id);
        break;

    case 'stocks':
        $controller = new StockController($entityManager);
        $actionStocks = $actionDemandee ?: $id;
        
        if (empty($actionStocks) && $method === 'GET') $actionStocks = 'getStocksByStore';
        $result = $controller->handleRequest($method, $actionStocks);
        break;

    case 'products':
        $controller = new ProductController($entityManager);
        $actionProd = $actionDemandee ?: $id;
        if ($method === 'GET' && empty($actionProd)) $actionProd = 'getProducts';
        $result = $controller->handleRequest($method, $actionProd);
        break;

    case 'employees':
        $controller = new EmployeeController($entityManager);
        $actionEmp = $actionDemandee;
        
        if (empty($actionEmp) && $method === 'GET') {
            if ($id) {
                $actionEmp = 'getEmployeeById';
                $_GET['id'] = $id; 
            } elseif (isset($_GET['store_id'])) {
                $actionEmp = 'getEmployeesByStore';
            } else {
                $actionEmp = 'getAllEmployees';
            }
        } else {
            $actionEmp = $actionEmp ?: $id;
        }
        
        $result = $controller->handleRequest($method, $actionEmp);
        break;

    case 'brands':
        $controller = new BrandController($entityManager);
        $actionBrand = $actionDemandee ?: $id;
        if ($method === 'GET' && empty($actionBrand)) $actionBrand = 'getBrands';
        $result = $controller->handleRequest($method, $actionBrand);
        break;

    case 'categories':
        $controller = new CategoryController($entityManager);
        $actionCat = $actionDemandee ?: $id;
        if ($method === 'GET' && empty($actionCat)) $actionCat = 'getCategories';
        $result = $controller->handleRequest($method, $actionCat);
        break;

    default:
        http_response_code(404);
        $result = ['error' => 'Ressource non trouvée.'];
        break;
}

// Output the final computed result as a JSON string
echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>