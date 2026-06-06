1. BULK INSERT API
Method

POST

URL
http://localhost/new_order_fms/api/bulk-insert
Payload (Complete All Columns)
[
  {
    "timestamp": "2026-05-27 10:00:00",
    "buyer_id": "BUY1001",
    "order_id": "ORD1001",
    "client_name": "Rahul Sharma",
    "mobile": "9876543210",
    "email": "rahul@gmail.com",
    "billing_type": "Prepaid",
    "order_type": "Online",
    "billing_address": "Delhi Billing Address",
    "shipping_address": "Delhi Shipping Address",
    "invoice_amount": "5000",
    "total_amount_before_discount": "5500",
    "uploaded_image_link": "https://img.com/order1.jpg",
    "payment_terms": "Full Advance",
    "payment_collection_date": "2026-05-28",
    "order_taken_by": "Admin",
    "whatsapp_sms": "Yes",
    "planned": "2026-05-28 12:00:00",
    "actual": "",
    "time_delay": "",
    "fms_user_name": "neworder_user",
    "order_status": "Pending",
    "edit_order_link": "https://edit.com/1",
    "pi_no": "PI1001",
    "pi_url": "https://pi.com/1",
    "dispatch_from": "Delhi Warehouse",
    "advance_payment_collection": "Yes",
    "whatsapp_status": "Sent",
    "remarks": "New Order",
    "transfer_to_dispatch_status": "",
    "helping_ticket_status": "Open",
    "expected_dispatch_datetime": "2026-05-29 10:00:00",
    "cod": "No",
    "cod_confirmation_status": "Confirmed",
    "pin_code": "110001",
    "match_state": "Delhi",
    "match_pin_code": "Matched",
    "distributor_name": "ABC Distributor",
    "shipping_address_changed": "No",
    "updated_address": "",
    "stage_allowed_users": "user1,user2",

    "DispatchVerFMS_stage_allowed_users": "dispatch1",
    "DispatchVerFMS_planned": "2026-05-29 12:00:00",
    "DispatchVerFMS_actual": "",
    "DispatchVerFMS_time_delay": "",
    "DispatchVerFMS_fms_users_name": "dispatch_user",
    "DispatchVerFMS_order_status": "Pending",
    "DispatchVerFMS_edit_order_link": "https://dispatch-edit.com/1",
    "DispatchVerFMS_delivery_note_no": "DN1001",
    "DispatchVerFMS_dn_url": "https://dn.com/1",
    "DispatchVerFMS_dispatch_from": "Delhi",
    "DispatchVerFMS_whatsapp_status": "Pending",
    "DispatchVerFMS_transfer_to_accounts_status": "",
    "DispatchVerFMS_helping_ticket_status": "Open",

    "AccoutsVerFMS_stage_allowed_users": "accounts1",
    "AccoutsVerFMS_planned": "2026-05-30 11:00:00",
    "AccoutsVerFMS_actual": "",
    "AccoutsVerFMS_time_delay": "",
    "AccoutsVerFMS_fms_users_name": "accounts_user",
    "AccoutsVerFMS_order_status": "Pending",
    "AccoutsVerFMS_edit_order_link": "https://accounts-edit.com/1",
    "AccoutsVerFMS_invoice_no": "INV1001",
    "AccoutsVerFMS_invoice_link": "https://invoice.com/1",
    "AccoutsVerFMS_eway_bill_no": "EWAY1001",
    "AccoutsVerFMS_dispatch_from": "Delhi",
    "AccoutsVerFMS_whatsapp_status": "Pending",
    "AccoutsVerFMS_transfer_to_dispatch_fms": "",
    "AccoutsVerFMS_transfer_to_collection_fms": "",
    "AccoutsVerFMS_advance_payment_collection": "Done",
    "AccoutsVerFMS_helping_ticket_status": "Open",

    "AddressUpdateFMS_timestamp": "2026-05-27 10:10:00",
    "AddressUpdateFMS_stage_allowed_users": "address1",
    "AddressUpdateFMS_planned": "2026-05-28 14:00:00",
    "AddressUpdateFMS_actual": "",
    "AddressUpdateFMS_delay": "",
    "AddressUpdateFMS_fms_users_name": "address_user",
    "AddressUpdateFMS_shipping_address_changed_status": "No",
    "AddressUpdateFMS_updated_shipping_address": "",
    "AddressUpdateFMS_eshopbox_updated_status": "Pending",
    "AddressUpdateFMS_shopify_updated_status": "Pending",
    "AddressUpdateFMS_remarks": "NA",
    "AddressUpdateFMS_order_status": "Pending",

    "address_reverify_status_planned_crr": "2026-05-28 15:00:00",
    "address_reverify_status_actual_crr": "",
    "address_reverify_status_time_delay_crr": "",
    "address_reverify_status_address_verified_status": "Pending",
    "address_reverify_status_pincode": "110001",
    "address_reverify_allowed_users": "reverify1"
  }
]
Response
[
  {
    "order_id": "ORD1001",
    "status": "INSERTED",
    "message": "record inserted successfully"
  }
]
2. BULK NEW ORDER STAGE UPDATE
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "neworder",
  "data": [
    {
      "order_id": "ORD1001",
      "planned": "2026-05-28 12:00:00",
      "actual": "2026-05-28 13:00:00",
      "time_delay": "1 Hour",
      "fms_user_name": "neworder_user",
      "order_status": "Completed",
      "edit_order_link": "https://edit.com/1",
      "pi_no": "PI1001",
      "pi_url": "https://pi.com/1",
      "dispatch_from": "Delhi",
      "advance_payment_collection": "Done",
      "whatsapp_status": "Sent",
      "remarks": "Completed",
      "transfer_to_dispatch_status": "Transferred",
      "helping_ticket_status": "Closed",
      "expected_dispatch_datetime": "2026-05-29 10:00:00",
      "cod": "No",
      "cod_confirmation_status": "Confirmed",
      "pin_code": "110001",
      "match_state": "Delhi",
      "match_pin_code": "Matched",
      "distributor_name": "ABC Distributor",
      "shipping_address_changed": "No",
      "updated_address": "",
      "stage_allowed_users": "user1,user2"
    }
  ]
}
Response
[
  {
    "order_id": "ORD1001",
    "status": "UPDATED",
    "message": "Final record updated successfully"
  }
]
3. BULK DISPATCH STAGE UPDATE
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "dispatch",
  "data": [
    {
      "order_id": "ORD1001",
      "DispatchVerFMS_stage_allowed_users": "dispatch1",
      "DispatchVerFMS_planned": "2026-05-29 12:00:00",
      "DispatchVerFMS_actual": "2026-05-29 14:00:00",
      "DispatchVerFMS_time_delay": "2 Hours",
      "DispatchVerFMS_fms_users_name": "dispatch_user",
      "DispatchVerFMS_order_status": "Completed",
      "DispatchVerFMS_edit_order_link": "https://dispatch-edit.com/1",
      "DispatchVerFMS_delivery_note_no": "DN1001",
      "DispatchVerFMS_dn_url": "https://dn.com/1",
      "DispatchVerFMS_dispatch_from": "Delhi",
      "DispatchVerFMS_whatsapp_status": "Sent",
      "DispatchVerFMS_transfer_to_accounts_status": "Transferred",
      "DispatchVerFMS_helping_ticket_status": "Closed"
    }
  ]
}
Response
[
  {
    "order_id": "ORD1001",
    "status": "UPDATED",
    "message": "Final record updated successfully"
  }
]
4. BULK ACCOUNT STAGE UPDATE
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "account",
  "data": [
    {
      "order_id": "ORD1001",
      "AccoutsVerFMS_stage_allowed_users": "accounts1",
      "AccoutsVerFMS_planned": "2026-05-30 11:00:00",
      "AccoutsVerFMS_actual": "2026-05-30 12:00:00",
      "AccoutsVerFMS_time_delay": "1 Hour",
      "AccoutsVerFMS_fms_users_name": "accounts_user",
      "AccoutsVerFMS_order_status": "Completed",
      "AccoutsVerFMS_edit_order_link": "https://accounts-edit.com/1",
      "AccoutsVerFMS_invoice_no": "INV1001",
      "AccoutsVerFMS_invoice_link": "https://invoice.com/1",
      "AccoutsVerFMS_eway_bill_no": "EWAY1001",
      "AccoutsVerFMS_dispatch_from": "Delhi",
      "AccoutsVerFMS_whatsapp_status": "Sent",
      "AccoutsVerFMS_transfer_to_dispatch_fms": "Transferred",
      "AccoutsVerFMS_transfer_to_collection_fms": "Transferred",
      "AccoutsVerFMS_advance_payment_collection": "Done",
      "AccoutsVerFMS_helping_ticket_status": "Closed"
    }
  ]
}
Response
[
  {
    "order_id": "ORD1001",
    "status": "UPDATED",
    "message": "Final record updated successfully"
  }
]
5. BULK ADDRESS UPDATE STAGE
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "address",
  "data": [
    {
      "order_id": "ORD1001",
      "AddressUpdateFMS_timestamp": "2026-05-27 10:10:00",
      "AddressUpdateFMS_stage_allowed_users": "address1",
      "AddressUpdateFMS_planned": "2026-05-28 14:00:00",
      "AddressUpdateFMS_actual": "2026-05-28 15:00:00",
      "AddressUpdateFMS_delay": "1 Hour",
      "AddressUpdateFMS_fms_users_name": "address_user",
      "AddressUpdateFMS_shipping_address_changed_status": "Yes",
      "AddressUpdateFMS_updated_shipping_address": "New Delhi Address",
      "AddressUpdateFMS_eshopbox_updated_status": "Done",
      "AddressUpdateFMS_shopify_updated_status": "Done",
      "AddressUpdateFMS_remarks": "Address Updated",
      "AddressUpdateFMS_order_status": "Completed"
    }
  ]
}
Response
[
  {
    "order_id": "ORD1001",
    "status": "UPDATED",
    "message": "Record updated successfully"
  }
]
6. BULK ADDRESS REVERIFY UPDATE
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "address-reverify",
  "data": [
    {
      "order_id": "ORD1001",
      "address_reverify_status_planned_crr": "2026-05-28 15:00:00",
      "address_reverify_status_actual_crr": "2026-05-28 16:00:00",
      "address_reverify_status_time_delay_crr": "1 Hour",
      "address_reverify_status_address_verified_status": "Verified",
      "address_reverify_status_pincode": "110001",
      "address_reverify_allowed_users": "reverify1"
    }
  ]
}
Response
[
  {
    "order_id": "ORD1001",
    "status": "UPDATED",
    "message": "Record updated successfully"
  }
]
7. BULK GET API
Method

GET

URL
http://localhost/new_order_fms/api/bulk-get
Response
{
  "ORD1001": "2026-05-27 10:00:00"
}
8. BULK UPDATE GET API
Method

GET

URL
http://localhost/new_order_fms/api/bulkupdate-get
Response
{
  "ORD1001": {
    "updated_at": "2026-05-27 12:00:00",
    "Order_actual": "2026-05-28 13:00:00",
    "Order_transfer_to_dispatch_status": "Transferred",
    "DispatchVerFMS_actual": "2026-05-29 14:00:00",
    "DispatchVerFMS_transfer_to_accounts_status": "Transferred",
    "AccoutsVerFMS_actual": "2026-05-30 12:00:00",
    "AccoutsVerFMS_transfer_to_dispatch_fms": "Transferred",
    "AddressUpdateFMS_actual": "2026-05-28 15:00:00",
    "Address_reverify_status_actual_crr": "2026-05-28 16:00:00"
  }
}
9. INVALID STAGE TEST
Method

POST

URL
http://localhost/new_order_fms/api/bulk-update
Payload
{
  "stage": "wrong-stage",
  "data": []
}
Response
{
  "success": false,
  "message": "Invalid stage parameter",
  "allowed_stages": [
    "dispatch",
    "account",
    "address",
    "address-reverify",
    "neworder"
  ]
}
10. RECORD NOT FOUND TEST
Payload
{
  "stage": "dispatch",
  "data": [
    {
      "order_id": "INVALID999",
      "DispatchVerFMS_actual": "2026-05-29 14:00:00"
    }
  ]
}
Response
[
  {
    "order_id": "INVALID999",
    "status": "NOT_FOUND",
    "message": "Record does not exist"
  }
]
11. ORDER ID MISSING TEST
Payload
{
  "stage": "dispatch",
  "data": [
    {
      "DispatchVerFMS_actual": "2026-05-29 14:00:00"
    }
  ]
}
Response
[
  {
    "order_id": null,
    "status": "FAILED",
    "message": "order_id missing"
  }
]