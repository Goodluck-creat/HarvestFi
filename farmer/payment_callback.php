<?php
include "db.php";

// 1. GET THE TRANSACTION REFERENCE
// Interswitch Sandbox often sends this as 'txnref'
$txn_ref = $_POST['txnref'] ?? $_GET['txnref'] ?? $_POST['transactionReference'] ?? null;

if (!$txn_ref) {
    die("Error: No transaction reference received. Please do not access this page directly.");
}

// 2. FETCH THE ORDER FROM YOUR DATABASE
$stmt = $conn->prepare("SELECT * FROM orders WHERE order_ref = ? LIMIT 1");
$stmt->bind_param("s", $txn_ref);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Error: Order reference not found in our records.");
}

// 3. SANDBOX VERIFICATION CREDENTIALS
$merchant_code = "MX26070"; 
$hash_key = "D3D135017409292815F057F605A401614C3D26190479A03074091A82568F80C6"; 
$amount_kobo = $order['amount'] * 100;

// 4. CREATE VERIFICATION HASH
// Formula: merchant_code + txn_ref + hash_key
$v_hash = hash('sha512', $merchant_code . $txn_ref . $hash_key);

// 5. QUERY INTERSWITCH SANDBOX API
// Note the 'sandbox' in the URL below
$url = "https://sandbox.interswitchng.com/collections/api/v1/gettransaction.json?merchantcode=$merchant_code&transactionreference=$txn_ref&amount=$amount_kobo";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["Hash: $v_hash"]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 

$response_json = curl_exec($ch);
$response = json_decode($response_json, true);
curl_close($ch);

// 6. DISPLAY RESULTS
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Result | HarvestFi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; display: flex; align-items: center; min-height: 100vh; padding: 20px; }
        .result-card { background: white; max-width: 500px; margin: auto; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.05); text-align: center; }
        .status-icon { font-size: 70px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="result-card">
    <?php
    // Check if ResponseCode is "00" (Successful)
    if (isset($response['ResponseCode']) && $response['ResponseCode'] === "00") {
        
        // UPDATE ORDER AS SUCCESS
        $update = $conn->prepare("UPDATE orders SET payment_status = 'success', interswitch_response = ? WHERE order_ref = ?");
        $update->bind_param("ss", $response_json, $txn_ref);
        $update->execute();

        // MARK PRODUCE AS SOLD
        $produce_id = $order['produce_id'];
        $conn->query("UPDATE produce SET status = 'sold' WHERE id = $produce_id");

        echo '<div class="status-icon">✅</div>';
        echo '<h2 class="fw-bold text-success">Payment Received!</h2>';
        echo '<p class="text-muted">Transaction Ref: <b>' . $txn_ref . '</b></p>';
        echo '<p>Your order for produce #'.$produce_id.' has been successfully placed. The farmer will be notified shortly.</p>';
        
    } else {
        // PAYMENT FAILED OR CANCELLED
        $msg = $response['ResponseDescription'] ?? "Transaction was not completed.";
        
        $update = $conn->prepare("UPDATE orders SET payment_status = 'failed', interswitch_response = ? WHERE order_ref = ?");
        $update->bind_param("ss", $response_json, $txn_ref);
        $update->execute();

        echo '<div class="status-icon">❌</div>';
        echo '<h2 class="fw-bold text-danger">Payment Failed</h2>';
        echo '<p class="text-muted">Reason: ' . htmlspecialchars($msg) . '</p>';
        echo '<p class="small text-muted">Ref: ' . $txn_ref . '</p>';
    }
    ?>
    
    <div class="d-grid gap-2 mt-5">
        <a href="buyer_dashboard.php" class="btn btn-success py-3 rounded-pill fw-bold">Back to Marketplace</a>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm border-0">Print Receipt</button>
    </div>
</div>

</body>
</html>