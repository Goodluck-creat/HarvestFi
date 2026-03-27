<?php
// buyer_register.php
include "db.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clean and validate input
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Hash password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 1. Generate unique buyer_code (e.g., HFI-B-65A1B)
    $buyer_code = "HFI-B-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

    // Check if email already exists
    $check_sql = "SELECT email FROM buyers WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email already registered. Please login.";
    } else {
        // 2. Updated Insert SQL to include buyer_code
        $insert_sql = "INSERT INTO buyers (buyer_code, full_name, email, phone, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        
        // Bind 5 strings (sssss)
        $stmt->bind_param("sssss", $buyer_code, $full_name, $email, $phone, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful! Your Buyer Code is: <strong>$buyer_code</strong>. you can now login";
        } else {
            echo "Error: " . $stmt->error;
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>