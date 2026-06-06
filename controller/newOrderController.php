<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../service/newOrderService.php';


class newOrderController
{
    private newOrderService $service;

    public function __construct()
    {
        $this->service = new newOrderService();
    }

    // ✅ FIX: accept $body passed from index.php so php://input is not re-read
    public function bulkNeworderStageUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkNeworderStageUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkDispatchUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkDispatchStageUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkAccountStageUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkAccountStageUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkAddressStageUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkAddressStageUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkAddressReverifyStageUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkAddressReverifyStageUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkInsert(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkInsert($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkUpdate(array $body = []): void
    {
        $requests = $this->parseBody($body);
        if ($requests === null) return;

        $response = $this->service->bulkUpdateRecords($requests);
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkget(): void
    {
        $response = $this->service->fetchOrderCreatedAtMap();
        http_response_code(200);
        echo json_encode($response);
    }

    public function bulkupdateget(): void
    {
        $response = $this->service->fetchOrderupdateCreatedAtMap();
        http_response_code(200);
        echo json_encode($response);
    }

    // ✅ FIX: if $body already parsed and passed in, use it directly
    // otherwise fall back to reading php://input (for direct route calls)
    private function parseBody(array $body = []): ?array
    {
        if (!empty($body)) {
            $data = $body['data'] ?? null;

            // if body has a 'data' key, use that (stage-based routes)
            if ($data !== null) {
                if (!is_array($data)) {
                    http_response_code(400);
                    echo json_encode([
                        'success' => false,
                        'message' => 'Invalid request format. Expected data to be an array.'
                    ]);
                    return null;
                }
                return $data;
            }

            // body is already a flat array (bulk-insert style)
            return $body;
        }

        // fallback: read php://input directly (GET routes won't hit this)
        $raw = file_get_contents('php://input');
        $parsed = json_decode($raw, true);

        if (!is_array($parsed)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request format. Expected an array of objects.'
            ]);
            return null;
        }

        return $parsed;
    }
}