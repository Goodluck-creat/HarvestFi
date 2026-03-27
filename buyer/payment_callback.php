<?php
// 🔥 ERROR DEBUG (REMOVE IN PRODUCTION)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

// ✅ 1. Grab transaction reference from GET or POST
$txn_ref = $_GET['txn_ref'] ?? $_POST['txn_ref'] ?? null;

if (!$txn_ref) {
    die("No transaction reference provided.");
}

// ✅ 2. Fetch the transaction from DB
$stmt = $conn->prepare("SELECT * FROM payments WHERE txn_ref = ?");
$stmt->bind_param("s", $txn_ref);
$stmt->execute();
$result_db = $stmt->get_result();

if ($result_db->num_rows === 0) {
    die("Transaction not found.");
}

$payment = $result_db->fetch_assoc();

// ✅ 3. Sandbox Interswitch credentials
$merchant_code = "MX6072";

// ✅ 4. Verify transaction server-side
$verify_url = "https://qa.interswitchng.com/collections/api/v1/gettransaction.json";
$verify_url .= "?merchantcode={$merchant_code}&transactionreference={$txn_ref}&amount={$payment['amount']}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $verify_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
$response = curl_exec($ch);

if ($response === false) {
    die("Curl Error: " . curl_error($ch));
}

curl_close($ch);

$result = json_decode($response, true);

// ✅ 5. Validate response
$payment_success = false;

if (
    isset($result['ResponseCode']) &&
    $result['ResponseCode'] === "00" &&
    $result['Amount'] == $payment['amount']
) {
    $payment_success = true;
}

// ✅ 6. Update DB accordingly
if ($payment_success) {
    // Prevent double processing
    if ($payment['status'] !== 'success') {
        $update = $conn->prepare("UPDATE payments SET status='success' WHERE txn_ref=?");
        $update->bind_param("s", $txn_ref);
        $update->execute();

        // Optional: credit wallet, mark order paid, notify farmer, etc.
    }
} else {
    // Mark failed if not already failed
    if ($payment['status'] !== 'failed') {
        $update = $conn->prepare("UPDATE payments SET status='failed' WHERE txn_ref=?");
        $update->bind_param("s", $txn_ref);
        $update->execute();
    }
}

// ✅ 7. Display user-friendly page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Status - HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
<div class="bg-white p-10 rounded-3xl shadow-xl max-w-md w-full text-center">

<?php if ($payment_success): ?>
    <div class="mb-6 text-green-500 text-7xl">✔</div>
    <h1 class="text-2xl font-bold mb-2">Payment Successful!</h1>
    <p class="text-gray-500 mb-4">
        Ref: <span class="font-mono font-bold"><?= htmlspecialchars($txn_ref) ?></span>
    </p>
    <div class="bg-green-50 p-4 rounded-xl mb-6">
        <p class="text-green-700 text-sm">
            Your payment has been verified successfully.
        </p>
    </div>
    <a href="marketplace.php" class="bg-green-600 text-white px-6 py-3 rounded-lg">
        Back to Market
    </a>

<?php else: ?>
    <div class="mb-6 text-red-500 text-7xl">✖</div>
    <h1 class="text-2xl font-bold mb-2">Payment Failed</h1>
    <p class="text-gray-500 mb-4">
        <?= htmlspecialchars($result['ResponseDescription'] ?? 'Transaction failed or cancelled.') ?>
    </p>
    <a href="marketplace.php" class="bg-gray-800 text-white px-6 py-3 rounded-lg">
        Try Again
    </a>
<?php endif; ?>

</div>
</body>
</html>