<?php

require_once __DIR__ . '/service/newOrderService.php';
require_once __DIR__ . '/controller/newOrderController.php';
require_once __DIR__ . '/config/database.php';

// =========================================================
// CORS HEADERS
// =========================================================

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// =========================================================
// REQUEST INFO
// =========================================================

$requestUri    = $_SERVER['REQUEST_URI'] ?? '/';
$requestMethod = $_SERVER['REQUEST_METHOD'];

$path = parse_url($requestUri, PHP_URL_PATH);
$path = rtrim($path, '/');

// Remove Base Folder
$path = str_replace('/new_order_fms', '', $path);

// ✅ FIX: Read body ONCE here, extract stage from body (not $_GET)
$rawBody = file_get_contents('php://input');
$body = json_decode($rawBody, true) ?? [];
$stage = $body['stage'] ?? ($_GET['stage'] ?? null);

$controller = new newOrderController();

// =========================================================
// ROUTES
// =========================================================

switch ($path) {

    // =====================================================
    // BULK INSERT
    // =====================================================

    case '/api/bulk-insert':

        if ($requestMethod === 'POST') {
            $controller->bulkInsert($body);
            exit;
        }

        break;

    // =====================================================
    // BULK STAGE UPDATE
    // =====================================================

    case '/api/bulk-update':

        if ($requestMethod === 'POST') {

            switch ($stage) {

                case 'dispatch':
                    $controller->bulkDispatchUpdate($body);
                    break;

                case 'account':
                    $controller->bulkAccountStageUpdate($body);
                    break;

                case 'address':
                    $controller->bulkAddressStageUpdate($body);
                    break;

                case 'address-reverify':
                    $controller->bulkAddressReverifyStageUpdate($body);
                    break;

                case 'neworder':
                    $controller->bulkNeworderStageUpdate($body);
                    break;

                default:
                    http_response_code(400);
                    echo json_encode([
                        "success" => false,
                        "message" => "Invalid stage parameter",
                        "allowed_stages" => [
                            "dispatch",
                            "account",
                            "address",
                            "address-reverify",
                            "neworder"
                        ]
                    ]);
                    break;
            }

            exit;
        }

        break;

    // =====================================================
    // BULK GET
    // =====================================================

    case '/api/bulk-get':

        if ($requestMethod === 'GET') {
            $controller->bulkget();
            exit;
        }

        break;

    // =====================================================
    // BULK UPDATE GET
    // =====================================================

    case '/api/bulkupdate-get':

        if ($requestMethod === 'GET') {
            $controller->bulkupdateget();
            exit;
        }

        break;
}

// =========================================================
// 404
// =========================================================

http_response_code(404);

echo json_encode([
    'success' => false,
    'message' => 'Route not found'
]);