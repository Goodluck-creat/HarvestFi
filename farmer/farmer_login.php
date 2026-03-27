<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Fetch farmer by email or phone
    $sql = "SELECT * FROM farmers WHERE email = ? OR phone = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_phone, $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $farmer = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $farmer['password'])) {
            // Check if account is approved
            if ($farmer['status'] != 'approved') {
                die("Account not approved yet.");
            }

            // Set session variables
            $_SESSION['user_id'] = $farmer['id'];
            $_SESSION['user_type'] = 'farmer';
            $_SESSION['user_name'] = $farmer['full_name'];

            // Remember me (optional, cookie valid 7 days)
            if (isset($_POST['remember'])) {
                setcookie("farmer_login", $farmer['id'], time() + (7 * 24 * 60 * 60), "/");
            }

            header("Location: farmer_dashboard.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Farmer not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>