<?php
include "db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produce_id = $_POST['produce_id'];
    $amount = $_POST['amount']; 
    $buyer_id = 1; // Pull this from $_SESSION['user_id'] in production
    
    // --- 1. SANDBOX CREDENTIALS (Use these for testing) ---
    $merchant_code = "MX26070"; // Official Interswitch Test Merchant Code
    $pay_item_id = "101";        // Official Test Pay Item ID
    $hash_key = "D3D135017409292815F057F605A401614C3D26190479A03074091A82568F80C6"; // Official Sandbox Secret Key
    
    $order_ref = "HF-" . time() . rand(100, 999);
    $amount_kobo = $amount * 100; 
    $redirect_url = "https://activate.ejsub.com/Harvestfi/farmer/payment_callback.php";
    
    // --- 2. THE CORRECT HASH FORMULA ---
    // Sequence: txn_ref + product_id (pay_item_id) + amount + redirect_url + hash_key
    // Note: Interswitch uses pay_item_id where some docs say product_id
    $raw_hash = $order_ref . $pay_item_id . $amount_kobo . $redirect_url . $hash_key;
    $hash = hash('sha512', $raw_hash);

    // --- 3. SAVE ORDER ---
    $stmt = $conn->prepare("INSERT INTO orders (order_ref, buyer_id, produce_id, amount, payment_status) VALUES (?, ?, ?, ?, 'pending')");
    $stmt->bind_param("siid", $order_ref, $buyer_id, $produce_id, $amount);
    
    if($stmt->execute()) {
        // --- 4. REDIRECT TO SANDBOX GATEWAY ---
        ?>
        <div style="text-align:center; margin-top:50px; font-family:sans-serif;">
            <p>Connecting to secure payment gateway...</p>
            <form id="interswitchForm" method="post" action="https://sandbox.interswitchng.com/collections/w/pay">
                <input type="hidden" name="merchant_code" value="<?php echo $merchant_code; ?>" />
                <input type="hidden" name="pay_item_id" value="<?php echo $pay_item_id; ?>" />
                <input type="hidden" name="site_redirect_url" value="<?php echo $redirect_url; ?>" />
                <input type="hidden" name="txn_ref" value="<?php echo $order_ref; ?>" />
                <input type="hidden" name="amount" value="<?php echo $amount_kobo; ?>" />
                <input type="hidden" name="currency" value="566" /> 
                <input type="hidden" name="hash" value="<?php echo $hash; ?>" />
            </form>
        </div>
        <script>document.getElementById('interswitchForm').submit();</script>
        <?php
    } else {
        echo "Database Error: Could not create order.";
    }
}