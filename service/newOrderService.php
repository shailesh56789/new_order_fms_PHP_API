<?php
require_once __DIR__ . '/../config/database.php';

class newOrderService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }



             /* =========================================================
   FETCH ORDER UPDATED MAP
========================================================= */
public function fetchOrderupdateCreatedAtMap(): object
{
     $sql = "
        SELECT 
            order_id,
            updated_at,
            actual,
            AccoutsVerFMS_actual,
            DispatchVerFMS_actual,
            address_reverify_status_actual_crr,
            AddressUpdateFMS_actual,
            transfer_to_dispatch_status,
            DispatchVerFMS_transfer_to_accounts_status,
            AccoutsVerFMS_transfer_to_dispatch_fms
        FROM spalabsdomain_Kairali_CRM_Db.orders_fms
        WHERE actual IS NULL or AccoutsVerFMS_actual IS NULL or DispatchVerFMS_actual IS NULL or address_reverify_status_actual_crr IS NULL or AddressUpdateFMS_actual IS NULL 
       
    ";
        //    OR actual = ''
    $stmt = $this->pdo->query($sql);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = new stdClass();

    foreach ($rows as $row) {

         $response->{ $row['order_id'] } = [

            'updated_at' => $row['updated_at'],
            'Order_actual' => $row['actual'],
             'Order_transfer_to_dispatch_status' => $row['transfer_to_dispatch_status'],  
              'DispatchVerFMS_actual' => $row['DispatchVerFMS_actual'],     
            'DispatchVerFMS_transfer_to_accounts_status' => $row['DispatchVerFMS_transfer_to_accounts_status'],
            'AccoutsVerFMS_actual' => $row['AccoutsVerFMS_actual'],
            'AccoutsVerFMS_transfer_to_dispatch_fms' => $row['AccoutsVerFMS_transfer_to_dispatch_fms'],
             'AddressUpdateFMS_actual' => $row['AddressUpdateFMS_actual'],
             'Address_reverify_status_actual_crr' => $row['address_reverify_status_actual_crr']
        ];
    }

    return $response;
}

       /* =========================================================
   FETCH ORDER CREATED MAP
========================================================= */
public function fetchOrderCreatedAtMap(): object
{
    $sql = "
        SELECT order_id, created_at
        FROM orders_fms
    ";

    $stmt = $this->pdo->query($sql);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response = new stdClass();

    foreach ($rows as $row) {

        $response->{ $row['order_id'] } = $row['created_at'];
    }

    return $response;
}


    /* =========================================================
       BULK INSERT (orders_fms)
    ========================================================= */
    public function bulkInsert(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            // ✅ collect order_id
            $orderIds = array_column($requests, 'order_id');

            // existing records check
            $existingMap = $this->existsByOrderIds($orderIds);

            $newRecords = [];
            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                if (isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'EXISTS',
                        'message' => 'record already exists'
                    ];

                } else {

                    $newRecords[] = $req;

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'INSERTED',
                        'message' => 'record inserted successfully'
                    ];
                }
            }

            /* ================= INSERT ================= */
            if (!empty($newRecords)) {

               $columns = [

    'timestamp',
    'buyer_id',
    'order_id',
    'client_name',
    'mobile',
    'email',
    'billing_type',
    'order_type',
    'billing_address',
    'shipping_address',
    'invoice_amount',
    'total_amount_before_discount',
    'uploaded_image_link',
    'payment_terms',
    'payment_collection_date',
    'order_taken_by',
    'whatsapp_sms',
    'planned',
    'actual',
    'time_delay',
    'fms_user_name',
    'order_status',
    'edit_order_link',
    'pi_no',
    'pi_url',
    'dispatch_from',
    'advance_payment_collection',
    'whatsapp_status',
    'remarks',
    'transfer_to_dispatch_status',
    'helping_ticket_status',
    'expected_dispatch_datetime',
    'cod',
    'cod_confirmation_status',
    'pin_code',
    'match_state',
    'match_pin_code',
    'distributor_name',
    'shipping_address_changed',
    'updated_address',
    'stage_allowed_users',

    // DispatchVerFMS
    'DispatchVerFMS_stage_allowed_users',
    'DispatchVerFMS_planned',
    'DispatchVerFMS_actual',
    'DispatchVerFMS_time_delay',
    'DispatchVerFMS_fms_users_name',
    'DispatchVerFMS_order_status',
    'DispatchVerFMS_edit_order_link',
    'DispatchVerFMS_delivery_note_no',
    'DispatchVerFMS_dn_url',
    'DispatchVerFMS_dispatch_from',
    'DispatchVerFMS_whatsapp_status',
    'DispatchVerFMS_transfer_to_accounts_status',
    'DispatchVerFMS_helping_ticket_status',

    // AccoutsVerFMS
    'AccoutsVerFMS_stage_allowed_users',
    'AccoutsVerFMS_planned',
    'AccoutsVerFMS_actual',
    'AccoutsVerFMS_time_delay',
    'AccoutsVerFMS_fms_users_name',
    'AccoutsVerFMS_order_status',
    'AccoutsVerFMS_edit_order_link',
    'AccoutsVerFMS_invoice_no',
    'AccoutsVerFMS_invoice_link',
    'AccoutsVerFMS_eway_bill_no',
    'AccoutsVerFMS_dispatch_from',
    'AccoutsVerFMS_whatsapp_status',
    'AccoutsVerFMS_transfer_to_dispatch_fms',
    'AccoutsVerFMS_transfer_to_collection_fms',
    'AccoutsVerFMS_advance_payment_collection',
    'AccoutsVerFMS_helping_ticket_status',

    // AddressUpdateFMS
    'AddressUpdateFMS_timestamp',
    'AddressUpdateFMS_stage_allowed_users',
    'AddressUpdateFMS_planned',
    'AddressUpdateFMS_actual',
    'AddressUpdateFMS_delay',
    'AddressUpdateFMS_fms_users_name',
    'AddressUpdateFMS_shipping_address_changed_status',
    'AddressUpdateFMS_updated_shipping_address',
    'AddressUpdateFMS_eshopbox_updated_status',
    'AddressUpdateFMS_shopify_updated_status',
    'AddressUpdateFMS_remarks',
    'AddressUpdateFMS_order_status',

    // Address Reverify Status
'address_reverify_status_planned_crr',
'address_reverify_status_actual_crr',
'address_reverify_status_time_delay_crr',
'address_reverify_status_address_verified_status',
'address_reverify_status_pincode',
'address_reverify_allowed_users'
];

                $placeholders = [];
                $values = [];

                foreach ($newRecords as $req) {

                    $placeholders[] = "(" . implode(",", array_fill(0, count($columns), "?")) . ")";

                    foreach ($columns as $col) {

                       $values[] = $req[$col] ?? null;
                    }
                }

                $sql = "INSERT INTO orders_fms (" . implode(",", $columns) . ")
                        VALUES " . implode(",", $placeholders);

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute($values);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeRecord($req);

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => 'UPDATED',
                    'message' => 'Record updated successfully'
                ];
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }
/* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeRecord(array $req): array
{
    return [

    'order_id' => $req['order_id'] ?? null,

    'timestamp' => $req['timestamp'] ?? null,
    'buyer_id' => $req['buyer_id'] ?? null,
    'client_name' => $req['client_name'] ?? null,
    'mobile' => $req['mobile'] ?? null,
    'email' => $req['email'] ?? null,

    'billing_type' => $req['billing_type'] ?? null,
    'order_type' => $req['order_type'] ?? null,
    'billing_address' => $req['billing_address'] ?? null,
    'shipping_address' => $req['shipping_address'] ?? null,

    'invoice_amount' => $req['invoice_amount'] ?? null,
    'total_amount_before_discount' => $req['total_amount_before_discount'] ?? null,

    'uploaded_image_link' => $req['uploaded_image_link'] ?? null,
    'payment_terms' => $req['payment_terms'] ?? null,
    'payment_collection_date' => $req['payment_collection_date'] ?? null,

    'order_taken_by' => $req['order_taken_by'] ?? null,
    'whatsapp_sms' => $req['whatsapp_sms'] ?? null,

    'planned' => $req['planned'] ?? null,
    'actual' => $req['actual'] ?? null,
    'time_delay' => $req['time_delay'] ?? null,

    'fms_user_name' => $req['fms_user_name'] ?? null,
    'order_status' => $req['order_status'] ?? null,
    'edit_order_link' => $req['edit_order_link'] ?? null,

    'pi_no' => $req['pi_no'] ?? null,
    'pi_url' => $req['pi_url'] ?? null,
    'dispatch_from' => $req['dispatch_from'] ?? null,

    'advance_payment_collection' => $req['advance_payment_collection'] ?? null,
    'whatsapp_status' => $req['whatsapp_status'] ?? null,
    'remarks' => $req['remarks'] ?? null,

    'transfer_to_dispatch_status' => $req['transfer_to_dispatch_status'] ?? null,
    'helping_ticket_status' => $req['helping_ticket_status'] ?? null,

    'expected_dispatch_datetime' => $req['expected_dispatch_datetime'] ?? null,

    'cod' => $req['cod'] ?? null,
    'cod_confirmation_status' => $req['cod_confirmation_status'] ?? null,

    'pin_code' => $req['pin_code'] ?? null,
    'match_state' => $req['match_state'] ?? null,
    'match_pin_code' => $req['match_pin_code'] ?? null,

    'distributor_name' => $req['distributor_name'] ?? null,

    'shipping_address_changed' => $req['shipping_address_changed'] ?? null,
    'updated_address' => $req['updated_address'] ?? null,

    'stage_allowed_users' => $req['stage_allowed_users'] ?? null,

    // DispatchVerFMS
    'DispatchVerFMS_stage_allowed_users' => $req['DispatchVerFMS_stage_allowed_users'] ?? null,
    'DispatchVerFMS_planned' => $req['DispatchVerFMS_planned'] ?? null,
    'DispatchVerFMS_actual' => $req['DispatchVerFMS_actual'] ?? null,
    'DispatchVerFMS_time_delay' => $req['DispatchVerFMS_time_delay'] ?? null,
    'DispatchVerFMS_fms_users_name' => $req['DispatchVerFMS_fms_users_name'] ?? null,
    'DispatchVerFMS_order_status' => $req['DispatchVerFMS_order_status'] ?? null,
    'DispatchVerFMS_edit_order_link' => $req['DispatchVerFMS_edit_order_link'] ?? null,
    'DispatchVerFMS_delivery_note_no' => $req['DispatchVerFMS_delivery_note_no'] ?? null,
    'DispatchVerFMS_dn_url' => $req['DispatchVerFMS_dn_url'] ?? null,
    'DispatchVerFMS_dispatch_from' => $req['DispatchVerFMS_dispatch_from'] ?? null,
    'DispatchVerFMS_whatsapp_status' => $req['DispatchVerFMS_whatsapp_status'] ?? null,
    'DispatchVerFMS_transfer_to_accounts_status' => $req['DispatchVerFMS_transfer_to_accounts_status'] ?? null,
    'DispatchVerFMS_helping_ticket_status' => $req['DispatchVerFMS_helping_ticket_status'] ?? null,

    // AccountsVerFMS
    'AccoutsVerFMS_stage_allowed_users' => $req['AccoutsVerFMS_stage_allowed_users'] ?? null,
    'AccoutsVerFMS_planned' => $req['AccoutsVerFMS_planned'] ?? null,
    'AccoutsVerFMS_actual' => $req['AccoutsVerFMS_actual'] ?? null,
    'AccoutsVerFMS_time_delay' => $req['AccoutsVerFMS_time_delay'] ?? null,
    'AccoutsVerFMS_fms_users_name' => $req['AccoutsVerFMS_fms_users_name'] ?? null,
    'AccoutsVerFMS_order_status' => $req['AccoutsVerFMS_order_status'] ?? null,
    'AccoutsVerFMS_edit_order_link' => $req['AccoutsVerFMS_edit_order_link'] ?? null,
    'AccoutsVerFMS_invoice_no' => $req['AccoutsVerFMS_invoice_no'] ?? null,
    'AccoutsVerFMS_invoice_link' => $req['AccoutsVerFMS_invoice_link'] ?? null,
    'AccoutsVerFMS_eway_bill_no' => $req['AccoutsVerFMS_eway_bill_no'] ?? null,
    'AccoutsVerFMS_dispatch_from' => $req['AccoutsVerFMS_dispatch_from'] ?? null,
    'AccoutsVerFMS_whatsapp_status' => $req['AccoutsVerFMS_whatsapp_status'] ?? null,
    'AccoutsVerFMS_transfer_to_dispatch_fms' => $req['AccoutsVerFMS_transfer_to_dispatch_fms'] ?? null,
    'AccoutsVerFMS_transfer_to_collection_fms' => $req['AccoutsVerFMS_transfer_to_collection_fms'] ?? null,
    'AccoutsVerFMS_advance_payment_collection' => $req['AccoutsVerFMS_advance_payment_collection'] ?? null,
    'AccoutsVerFMS_helping_ticket_status' => $req['AccoutsVerFMS_helping_ticket_status'] ?? null,

    // AddressUpdateFMS
    'AddressUpdateFMS_timestamp' => $req['AddressUpdateFMS_timestamp'] ?? null,
    'AddressUpdateFMS_stage_allowed_users' => $req['AddressUpdateFMS_stage_allowed_users'] ?? null,
    'AddressUpdateFMS_planned' => $req['AddressUpdateFMS_planned'] ?? null,
    'AddressUpdateFMS_actual' => $req['AddressUpdateFMS_actual'] ?? null,
    'AddressUpdateFMS_delay' => $req['AddressUpdateFMS_delay'] ?? null,
    'AddressUpdateFMS_fms_users_name' => $req['AddressUpdateFMS_fms_users_name'] ?? null,
    'AddressUpdateFMS_shipping_address_changed_status' => $req['AddressUpdateFMS_shipping_address_changed_status'] ?? null,
    'AddressUpdateFMS_updated_shipping_address' => $req['AddressUpdateFMS_updated_shipping_address'] ?? null,
    'AddressUpdateFMS_eshopbox_updated_status' => $req['AddressUpdateFMS_eshopbox_updated_status'] ?? null,
    'AddressUpdateFMS_shopify_updated_status' => $req['AddressUpdateFMS_shopify_updated_status'] ?? null,
    'AddressUpdateFMS_remarks' => $req['AddressUpdateFMS_remarks'] ?? null,
    'AddressUpdateFMS_order_status' => $req['AddressUpdateFMS_order_status'] ?? null

    // Address Reverify Status
    ,'address_reverify_status_planned_crr' => $req['address_reverify_status_planned_crr'] ?? null,
    'address_reverify_status_actual_crr' => $req['address_reverify_status_actual_crr'] ?? null,
    'address_reverify_status_time_delay_crr' => $req['address_reverify_status_time_delay_crr'] ?? null,
    'address_reverify_status_address_verified_status' => $req['address_reverify_status_address_verified_status'] ?? null,
    'address_reverify_status_pincode' => $req['address_reverify_status_pincode'] ?? null,
    'address_reverify_allowed_users' => $req['address_reverify_allowed_users'] ?? null     
];
}
    /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkUpdate(array $records): void
    {
        if (empty($records)) return;

       $columns = [
    'timestamp','buyer_id','order_id','client_name','mobile','email',
    'billing_type','order_type','billing_address','shipping_address',
    'invoice_amount','total_amount_before_discount',
    'uploaded_image_link','payment_terms','payment_collection_date',
    'order_taken_by','whatsapp_sms','planned','actual','time_delay',
    'fms_user_name','order_status','edit_order_link',
    'pi_no','pi_url','dispatch_from',
    'advance_payment_collection','whatsapp_status','remarks',
    'transfer_to_dispatch_status','helping_ticket_status',
    'expected_dispatch_datetime',
    'cod','cod_confirmation_status',
    'pin_code','match_state','match_pin_code',
    'distributor_name',
    'shipping_address_changed','updated_address',
    'stage_allowed_users',

    // DispatchVerFMS
    'DispatchVerFMS_stage_allowed_users',
    'DispatchVerFMS_planned',
    'DispatchVerFMS_actual',
    'DispatchVerFMS_time_delay',
    'DispatchVerFMS_fms_users_name',
    'DispatchVerFMS_order_status',
    'DispatchVerFMS_edit_order_link',
    'DispatchVerFMS_delivery_note_no',
    'DispatchVerFMS_dn_url',
    'DispatchVerFMS_dispatch_from',
    'DispatchVerFMS_whatsapp_status',
    'DispatchVerFMS_transfer_to_accounts_status',
    'DispatchVerFMS_helping_ticket_status',

    // AccoutsVerFMS
    'AccoutsVerFMS_stage_allowed_users',
    'AccoutsVerFMS_planned',
    'AccoutsVerFMS_actual',
    'AccoutsVerFMS_time_delay',
    'AccoutsVerFMS_fms_users_name',
    'AccoutsVerFMS_order_status',
    'AccoutsVerFMS_edit_order_link',
    'AccoutsVerFMS_invoice_no',
    'AccoutsVerFMS_invoice_link',
    'AccoutsVerFMS_eway_bill_no',
    'AccoutsVerFMS_dispatch_from',
    'AccoutsVerFMS_whatsapp_status',
    'AccoutsVerFMS_transfer_to_dispatch_fms',
    'AccoutsVerFMS_transfer_to_collection_fms',
    'AccoutsVerFMS_advance_payment_collection',
    'AccoutsVerFMS_helping_ticket_status',

    // AddressUpdateFMS
    'AddressUpdateFMS_timestamp',
    'AddressUpdateFMS_stage_allowed_users',
    'AddressUpdateFMS_planned',
    'AddressUpdateFMS_actual',
    'AddressUpdateFMS_delay',
    'AddressUpdateFMS_fms_users_name',
    'AddressUpdateFMS_shipping_address_changed_status',
    'AddressUpdateFMS_updated_shipping_address',
    'AddressUpdateFMS_eshopbox_updated_status',
    'AddressUpdateFMS_shopify_updated_status',
    'AddressUpdateFMS_remarks',
    'AddressUpdateFMS_order_status',

    // Address Reverify Status
'address_reverify_status_planned_crr',
'address_reverify_status_actual_crr',
'address_reverify_status_time_delay_crr',
'address_reverify_status_address_verified_status',
'address_reverify_status_pincode',
'address_reverify_allowed_users'
];
        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }

      /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkNeworderStageUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            // ✅ Fetch current DB state to correctly determine message
            // even when request payload sends only partial fields
            $dbStateMap = $this->fetchCurrentStageState($orderIds);

            $validUpdates = [];
            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeNeworderStageRecord($req);

                // =====================================================
                // RESPONSE MESSAGE
                // Use request value if present, else fall back to DB value
                // This handles partial-update payloads from Glide/AppSheet
                // =====================================================

                $dbRow = $dbStateMap[$orderId] ?? [];

                $actual = trim((string)($req['actual'] ?? $dbRow['actual'] ?? ''));
                $transferStatus = trim((string)($req['transfer_to_dispatch_status'] ?? $dbRow['transfer_to_dispatch_status'] ?? ''));

                // Case 1: actual filled, transfer not yet done
                // Case 2: actual filled AND transfer done → Final
                if ($actual !== '' && $transferStatus !== '') {
                    $message = 'Final record updated successfully';
                    $status = 'FINAL UPDATED';

                } else {
                    $message = 'Record updated successfully';
                    $status='UPDATED';
                }

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => $status,
                    'message' => $message
                ];
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkNeworderStageUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeNeworderStageRecord(array $req): array
{
       return [

        'order_id' => $req['order_id'] ?? null,

        'planned' => $req['planned'] ?? null,
        'actual' => $req['actual'] ?? null,
        'time_delay' => $req['time_delay'] ?? null,

        'fms_user_name' => $req['fms_user_name'] ?? null,
        'order_status' => $req['order_status'] ?? null,
        'edit_order_link' => $req['edit_order_link'] ?? null,

        'pi_no' => $req['pi_no'] ?? null,
        'pi_url' => $req['pi_url'] ?? null,
        'dispatch_from' => $req['dispatch_from'] ?? null,

        'advance_payment_collection' => $req['advance_payment_collection'] ?? null,
        'whatsapp_status' => $req['whatsapp_status'] ?? null,
        'remarks' => $req['remarks'] ?? null,

        'transfer_to_dispatch_status' => $req['transfer_to_dispatch_status'] ?? null,
        'helping_ticket_status' => $req['helping_ticket_status'] ?? null,

        'expected_dispatch_datetime' => $req['expected_dispatch_datetime'] ?? null,

        'cod' => $req['cod'] ?? null,
        'cod_confirmation_status' => $req['cod_confirmation_status'] ?? null,

        'pin_code' => $req['pin_code'] ?? null,
        'match_state' => $req['match_state'] ?? null,
        'match_pin_code' => $req['match_pin_code'] ?? null,

        'distributor_name' => $req['distributor_name'] ?? null,

        'shipping_address_changed' => $req['shipping_address_changed'] ?? null,
        'updated_address' => $req['updated_address'] ?? null,

        'stage_allowed_users' => $req['stage_allowed_users'] ?? null,

    ];

}

 /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkNeworderStageUpdate(array $records): void
    {
        if (empty($records)) return;
$columns = [

    'order_id',

    'planned',
    'actual',
    'time_delay',

    'fms_user_name',
    'order_status',
    'edit_order_link',

    'pi_no',
    'pi_url',
    'dispatch_from',

    'advance_payment_collection',
    'whatsapp_status',
    'remarks',

    'transfer_to_dispatch_status',
    'helping_ticket_status',

    'expected_dispatch_datetime',

    'cod',
    'cod_confirmation_status',

    'pin_code',
    'match_state',
    'match_pin_code',

    'distributor_name',

    'shipping_address_changed',
    'updated_address',

    'stage_allowed_users',

];

        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }



     /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkDispatchStageUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            // ✅ Fetch current DB state to correctly determine message
            // even when request payload sends only partial fields
            $dbStateMap = $this->fetchCurrentStageState($orderIds);

            $validUpdates = [];
            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeDispatchRecord($req);

                // =====================================================
                // RESPONSE MESSAGE
                // Use request value if present, else fall back to DB value
                // This handles partial-update payloads from Glide/AppSheet
                // =====================================================

                $dbRow = $dbStateMap[$orderId] ?? [];

                $actual = trim((string)($req['DispatchVerFMS_actual'] ?? $dbRow['DispatchVerFMS_actual'] ?? ''));
                $transferStatus = trim((string)($req['DispatchVerFMS_transfer_to_accounts_status'] ?? $dbRow['DispatchVerFMS_transfer_to_accounts_status'] ?? ''));

                if ($actual !== '' && $transferStatus !== '') {
                    $message = 'Final record updated successfully';
                       $status = 'FINAL UPDATED';

                } else {
                    $message = 'Record updated successfully';
                    $status = 'UPDATED';
                }

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => $status,
                    'message' => $message
                ];
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkDispatchUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeDispatchRecord(array $req): array
{
    return [

    'order_id' => $req['order_id'] ?? null,
    'DispatchVerFMS_stage_allowed_users' => $req['DispatchVerFMS_stage_allowed_users'] ?? null,
    'DispatchVerFMS_planned' => $req['DispatchVerFMS_planned'] ?? null,
    'DispatchVerFMS_actual' => $req['DispatchVerFMS_actual'] ?? null,
    'DispatchVerFMS_time_delay' => $req['DispatchVerFMS_time_delay'] ?? null,
    'DispatchVerFMS_fms_users_name' => $req['DispatchVerFMS_fms_users_name'] ?? null,
    'DispatchVerFMS_order_status' => $req['DispatchVerFMS_order_status'] ?? null,
    'DispatchVerFMS_edit_order_link' => $req['DispatchVerFMS_edit_order_link'] ?? null,
    'DispatchVerFMS_delivery_note_no' => $req['DispatchVerFMS_delivery_note_no'] ?? null,
    'DispatchVerFMS_dn_url' => $req['DispatchVerFMS_dn_url'] ?? null,
    'DispatchVerFMS_dispatch_from' => $req['DispatchVerFMS_dispatch_from'] ?? null,
    'DispatchVerFMS_whatsapp_status' => $req['DispatchVerFMS_whatsapp_status'] ?? null,
    'DispatchVerFMS_transfer_to_accounts_status' => $req['DispatchVerFMS_transfer_to_accounts_status'] ?? null,
    'DispatchVerFMS_helping_ticket_status' => $req['DispatchVerFMS_helping_ticket_status'] ?? null,

  ];
}

 /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkDispatchUpdate(array $records): void
    {
        if (empty($records)) return;

       $columns = [
'order_id',
    'DispatchVerFMS_stage_allowed_users',
    'DispatchVerFMS_planned',
    'DispatchVerFMS_actual',
    'DispatchVerFMS_time_delay',
    'DispatchVerFMS_fms_users_name',
    'DispatchVerFMS_order_status',
    'DispatchVerFMS_edit_order_link',
    'DispatchVerFMS_delivery_note_no',
    'DispatchVerFMS_dn_url',
    'DispatchVerFMS_dispatch_from',
    'DispatchVerFMS_whatsapp_status',
    'DispatchVerFMS_transfer_to_accounts_status',
    'DispatchVerFMS_helping_ticket_status',
];
        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }


       /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkAccountStageUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            // ✅ Fetch current DB state to correctly determine message
            // even when request payload sends only partial fields
            $dbStateMap = $this->fetchCurrentStageState($orderIds);

            $validUpdates = [];
            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeAccountStageRecord($req);

                // =====================================================
                // RESPONSE MESSAGE
                // Use request value if present, else fall back to DB value
                // This handles partial-update payloads from Glide/AppSheet
                // =====================================================

                $dbRow = $dbStateMap[$orderId] ?? [];

                $actual = trim((string)($req['AccoutsVerFMS_actual'] ?? $dbRow['AccoutsVerFMS_actual'] ?? ''));
                $transferStatus = trim((string)($req['AccoutsVerFMS_transfer_to_dispatch_fms'] ?? $dbRow['AccoutsVerFMS_transfer_to_dispatch_fms'] ?? ''));

                if ($actual !== '' && $transferStatus !== '') {
                    $message = 'Final record updated successfully';
                    $status = 'FINAL UPDATED';

                } else {
                    $message = 'Record updated successfully';
                    $status = 'UPDATED';
                }

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => $status,
                    'message' => $message
                ];
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkAccountStageUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeAccountStageRecord(array $req): array
{
    return [

        'order_id' => $req['order_id'] ?? null,

        'AccoutsVerFMS_stage_allowed_users' => $req['AccoutsVerFMS_stage_allowed_users'] ?? null,
        'AccoutsVerFMS_planned' => $req['AccoutsVerFMS_planned'] ?? null,
        'AccoutsVerFMS_actual' => $req['AccoutsVerFMS_actual'] ?? null,
        'AccoutsVerFMS_time_delay' => $req['AccoutsVerFMS_time_delay'] ?? null,
        'AccoutsVerFMS_fms_users_name' => $req['AccoutsVerFMS_fms_users_name'] ?? null,
        'AccoutsVerFMS_order_status' => $req['AccoutsVerFMS_order_status'] ?? null,
        'AccoutsVerFMS_edit_order_link' => $req['AccoutsVerFMS_edit_order_link'] ?? null,
        'AccoutsVerFMS_invoice_no' => $req['AccoutsVerFMS_invoice_no'] ?? null,
        'AccoutsVerFMS_invoice_link' => $req['AccoutsVerFMS_invoice_link'] ?? null,
        'AccoutsVerFMS_eway_bill_no' => $req['AccoutsVerFMS_eway_bill_no'] ?? null,
        'AccoutsVerFMS_dispatch_from' => $req['AccoutsVerFMS_dispatch_from'] ?? null,
        'AccoutsVerFMS_whatsapp_status' => $req['AccoutsVerFMS_whatsapp_status'] ?? null,
        'AccoutsVerFMS_transfer_to_dispatch_fms' => $req['AccoutsVerFMS_transfer_to_dispatch_fms'] ?? null,
        'AccoutsVerFMS_transfer_to_collection_fms' => $req['AccoutsVerFMS_transfer_to_collection_fms'] ?? null,
        'AccoutsVerFMS_advance_payment_collection' => $req['AccoutsVerFMS_advance_payment_collection'] ?? null,
        'AccoutsVerFMS_helping_ticket_status' => $req['AccoutsVerFMS_helping_ticket_status'] ?? null,

    ];
}

 /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkAccountStageUpdate(array $records): void
    {
        if (empty($records)) return;

 $columns = [

    'order_id',

    'AccoutsVerFMS_stage_allowed_users',
    'AccoutsVerFMS_planned',
    'AccoutsVerFMS_actual',
    'AccoutsVerFMS_time_delay',
    'AccoutsVerFMS_fms_users_name',
    'AccoutsVerFMS_order_status',
    'AccoutsVerFMS_edit_order_link',
    'AccoutsVerFMS_invoice_no',
    'AccoutsVerFMS_invoice_link',
    'AccoutsVerFMS_eway_bill_no',
    'AccoutsVerFMS_dispatch_from',
    'AccoutsVerFMS_whatsapp_status',
    'AccoutsVerFMS_transfer_to_dispatch_fms',
    'AccoutsVerFMS_transfer_to_collection_fms',
    'AccoutsVerFMS_advance_payment_collection',
    'AccoutsVerFMS_helping_ticket_status',

];
        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }

    
       /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkAddressStageUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeAddressStageRecord($req);

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => 'UPDATED',
                    'message' => 'Record updated successfully'
                ];
             
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkAddressStageUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeAddressStageRecord(array $req): array
{
 return [

        'order_id' => $req['order_id'] ?? null,

        'AddressUpdateFMS_timestamp' => $req['AddressUpdateFMS_timestamp'] ?? null,
        'AddressUpdateFMS_stage_allowed_users' => $req['AddressUpdateFMS_stage_allowed_users'] ?? null,
        'AddressUpdateFMS_planned' => $req['AddressUpdateFMS_planned'] ?? null,
        'AddressUpdateFMS_actual' => $req['AddressUpdateFMS_actual'] ?? null,
        'AddressUpdateFMS_delay' => $req['AddressUpdateFMS_delay'] ?? null,
        'AddressUpdateFMS_fms_users_name' => $req['AddressUpdateFMS_fms_users_name'] ?? null,
        'AddressUpdateFMS_shipping_address_changed_status' => $req['AddressUpdateFMS_shipping_address_changed_status'] ?? null,
        'AddressUpdateFMS_updated_shipping_address' => $req['AddressUpdateFMS_updated_shipping_address'] ?? null,
        'AddressUpdateFMS_eshopbox_updated_status' => $req['AddressUpdateFMS_eshopbox_updated_status'] ?? null,
        'AddressUpdateFMS_shopify_updated_status' => $req['AddressUpdateFMS_shopify_updated_status'] ?? null,
        'AddressUpdateFMS_remarks' => $req['AddressUpdateFMS_remarks'] ?? null,
        'AddressUpdateFMS_order_status' => $req['AddressUpdateFMS_order_status'] ?? null,

    ];
}

 /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkAddressStageUpdate(array $records): void
    {
        if (empty($records)) return;

 $columns = [

    'order_id',

    'AddressUpdateFMS_timestamp',
    'AddressUpdateFMS_stage_allowed_users',
    'AddressUpdateFMS_planned',
    'AddressUpdateFMS_actual',
    'AddressUpdateFMS_delay',
    'AddressUpdateFMS_fms_users_name',
    'AddressUpdateFMS_shipping_address_changed_status',
    'AddressUpdateFMS_updated_shipping_address',
    'AddressUpdateFMS_eshopbox_updated_status',
    'AddressUpdateFMS_shopify_updated_status',
    'AddressUpdateFMS_remarks',
    'AddressUpdateFMS_order_status',

];
        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }

    
       /* =========================================================
       BULK UPDATE
    ========================================================= */
    public function bulkAddressReverifyStageUpdateRecords(array $requests): array
    {
        if (empty($requests)) {
            return [
                'status' => 'FAILED',
                'message' => 'No data provided'
            ];
        }

        try {

            $this->pdo->beginTransaction();

            $orderIds = array_column($requests, 'order_id');
            $existingMap = $this->existsByOrderIds($orderIds);

            $responseList = [];

            foreach ($requests as $req) {

                $orderId = $req['order_id'] ?? null;

                if (!$orderId) {
                    $responseList[] = [
                        'order_id' => null,
                        'status' => 'FAILED',
                        'message' => 'order_id missing'
                    ];
                    continue;
                }

                 // ✅ record not found
                if (!isset($existingMap[$orderId])) {

                    $responseList[] = [
                        'order_id' => $orderId,
                        'status' => 'NOT_FOUND',
                        'message' => 'Record does not exist'
                    ];

                    continue;
                }

                // ✅ sanitize record
                $validUpdates[] = $this->sanitizeAddressReverifyStageRecord($req);

                $responseList[] = [
                    'order_id' => $orderId,
                    'status' => 'UPDATED',
                    'message' => 'Record updated successfully'
                ];
            }

            // ✅ STEP 3: update records
            if (!empty($validUpdates)) {
                $this->bulkAddressReverifyStageUpdate($validUpdates);
            }

            $this->pdo->commit();

            return $responseList;

        } catch (Exception $e) {

            $this->pdo->rollBack();

            return [
                'status' => 'FAILED',
                'message' => $e->getMessage()
            ];
        }
    }

    /* =========================================================
   SANITIZE RECORD
========================================================= */
private function sanitizeAddressReverifyStageRecord(array $req): array
{
   return [

        'order_id' => $req['order_id'] ?? null,

        'address_reverify_status_planned_crr' => $req['address_reverify_status_planned_crr'] ?? null,
        'address_reverify_status_actual_crr' => $req['address_reverify_status_actual_crr'] ?? null,
        'address_reverify_status_time_delay_crr' => $req['address_reverify_status_time_delay_crr'] ?? null,
        'address_reverify_status_address_verified_status' => $req['address_reverify_status_address_verified_status'] ?? null,
        'address_reverify_status_pincode' => $req['address_reverify_status_pincode'] ?? null,
        'address_reverify_allowed_users' => $req['address_reverify_allowed_users'] ?? null,

    ];
}

 /* =========================================================
       UPDATE QUERY
    ========================================================= */
    private function bulkAddressReverifyStageUpdate(array $records): void
    {
        if (empty($records)) return;

$columns = [

    'order_id',

    'address_reverify_status_planned_crr',
    'address_reverify_status_actual_crr',
    'address_reverify_status_time_delay_crr',
    'address_reverify_status_address_verified_status',
    'address_reverify_status_pincode',
    'address_reverify_allowed_users',

];
        foreach ($records as $req) {

            if (empty($req['order_id'])) continue;

            $setParts = [];
            $values = [];

            foreach ($columns as $col) {
                if (!array_key_exists($col, $req)) continue;

                $setParts[] = "`$col` = ?";
                $values[] = $req[$col];
            }

            if (empty($setParts)) continue;

            $values[] = $req['order_id'];

            $sql = "UPDATE orders_fms
                    SET " . implode(", ", $setParts) . "
                    WHERE order_id = ?";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
        }
    }
    /* =========================================================
       FETCH CURRENT ACTUAL + TRANSFER STATUS FROM DB
       Used to determine response message when request payload
       may not include these fields (partial update scenario)
    ========================================================= */
    private function fetchCurrentStageState(array $orderIds): array
    {
        if (empty($orderIds)) return [];

        $orderIds = array_unique($orderIds);
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        $stmt = $this->pdo->prepare("
            SELECT
                order_id,
                actual,
                transfer_to_dispatch_status,
                DispatchVerFMS_actual,
                DispatchVerFMS_transfer_to_accounts_status,
                AccoutsVerFMS_actual,
                AccoutsVerFMS_transfer_to_dispatch_fms
            FROM orders_fms
            WHERE order_id IN ($placeholders)
        ");

        $stmt->execute($orderIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $map = [];
        foreach ($rows as $row) {
            $map[$row['order_id']] = $row;
        }

        return $map;
    }

    /* =========================================================
       EXISTS CHECK
    ========================================================= */
    private function existsByOrderIds(array $orderIds): array
    {
        if (empty($orderIds)) return [];

        $orderIds = array_unique($orderIds);

        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));

        $stmt = $this->pdo->prepare("
            SELECT order_id
            FROM orders_fms
            WHERE order_id IN ($placeholders)
        ");

        $stmt->execute($orderIds);

        $existing = $stmt->fetchAll(PDO::FETCH_COLUMN);

        return array_flip($existing);
    }
}