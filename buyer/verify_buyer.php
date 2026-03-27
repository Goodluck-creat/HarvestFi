<?php
include "db.php";
header('Content-Type: application/json');

$code = isset($_GET['code']) ? trim($_GET['code']) : '';

if (empty($code)) {
    echo json_encode(['status' => 'error', 'message' => 'No code provided']);
    exit;
}

// Check the 'buyers' table specifically
$stmt = $conn->prepare("SELECT full_name FROM buyers WHERE buyer_code = ? LIMIT 1");
$stmt->bind_param("s", $code);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        'status' => 'success', 
        'name' => htmlspecialchars($user['full_name'])
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid code']);
}
?>