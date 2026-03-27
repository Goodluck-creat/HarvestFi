<?php
// 🔥 DEBUG ONLY
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "db.php";

// ✅ 1. Collect & Validate Input
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: marketplace.php");
    exit();
}

$produce_id = intval($_POST['produce_id']);
$buyer_code = htmlspecialchars(trim($_POST['buyer_code']));
$amount_naira = floatval($_POST['total_price']);

if ($amount_naira <= 0) {
    die("Invalid payment amount.");
}

$amount_kobo = (int)($amount_naira * 100);

// ✅ 2. Sandbox Credentials
$merchant_code = "MX6072";
$pay_item_id = "9405967";
$secret_key = "secret";

// ✅ 3. Generate Unique Transaction Reference
$txn_ref = "GLV-" . time() . rand(1000, 9999);

// ✅ 4. Redirect URL after payment
$site_redirect_url = "https://activate.ejsub.com/Harvestfi/buyer/payment_callback.php";

// ✅ 5. Save transaction in DB
$user_id = $_SESSION['user_id'] ?? 0;
$stmt = $conn->prepare("INSERT INTO payments (user_id, txn_ref, amount, status, produce_id, buyer_code) VALUES (?, ?, ?, 'pending', ?, ?)");
$stmt->bind_param("isiss", $user_id, $txn_ref, $amount_kobo, $produce_id, $buyer_code);
$stmt->execute();

// ✅ 6. Session fallback for JS
$_SESSION['email'] = $_SESSION['email'] ?? 'twinklegoodluck@gmail.com';
$_SESSION['user_name'] = $_SESSION['user_name'] ?? 'Nwimo Goodluck Chioma';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Processing Payment...</title>
<script src="https://cdn.tailwindcss.com"></script>
<!-- Inline Checkout JS -->
<script src="https://newwebpay.qa.interswitchng.com/inline-checkout.js"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center h-screen">

<div class="text-center">
    <div class="animate-spin rounded-full h-12 w-12 border-4 border-green-500 border-t-transparent mx-auto mb-4"></div>
    <h2 class="text-xl font-bold text-gray-800">Processing Payment...</h2>
    <p class="text-gray-500">Do not refresh this page.</p>
</div>

<script>
// ✅ 7. Trigger Interswitch Inline Checkout
function paymentCallback(response) {
    console.log('Payment Response:', response);

    // Always verify server-side before giving value
    window.location.href = "<?= $site_redirect_url ?>?txn_ref=<?= $txn_ref ?>";
}

window.webpayCheckout({
    merchant_code: "<?= $merchant_code ?>",
    pay_item_id: "<?= $pay_item_id ?>",
    txn_ref: "<?= $txn_ref ?>",
    amount: <?= $amount_kobo ?>,
    currency: 566,
    cust_email: "<?= $_SESSION['email'] ?>",
    cust_name: "<?= $_SESSION['user_name'] ?>",
    site_redirect_url: "<?= $site_redirect_url ?>",
    mode: 'TEST',
    onComplete: paymentCallback
});

// ✅ 8. Fallback if JS fails or user closes popup
setTimeout(function() {
    window.location.href = "<?= $site_redirect_url ?>?txn_ref=<?= $txn_ref ?>";
}, 30000); // 30 seconds fallback
</script>

</body>
</html>