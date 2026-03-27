<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Fetch user by email or phone (Table name assumed to be 'users' based on previous context)
    $sql = "SELECT * FROM buyers WHERE email = ? OR phone = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email_or_phone, $email_or_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role']; // Stores 'admin' or 'buyer'
            $_SESSION['user_name'] = $user['full_name'];

            // Remember me logic
            if (isset($_POST['remember'])) {
                setcookie("user_login", $user['id'], time() + (7 * 24 * 60 * 60), "/");
            }

            // --- ROLE-BASED REDIRECTION ---
            if ($user['role'] === 'admin') {
                header("Location: ../Admin/admin_dashboard.php");
            } else {
                header("Location: buyer_dashboard.php");
            }
            exit();

        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "User not found.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>