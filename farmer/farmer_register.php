<?php
include "db.php"; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Clean inputs
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $farm_location = htmlspecialchars(trim($_POST['address']));
    $password = htmlspecialchars(trim($_POST['password']));

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
        $allowed_ext = ['jpg','jpeg','png','pdf'];
        $file_name = $_FILES['documents']['name'];
        $file_tmp = $_FILES['documents']['tmp_name'];
        $file_size = $_FILES['documents']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $new_file_name = uniqid('farmdoc_', true) . "." . $file_ext;
        $upload_dir = "uploads/";

        // Create upload directory if not exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        if (in_array($file_ext, $allowed_ext) && $file_size <= 5 * 1024 * 1024) { // max 5MB
            $file_path = $upload_dir . $new_file_name;
            move_uploaded_file($file_tmp, $file_path);
        } else {
            die("Invalid file type or size exceeds 5MB.");
        }
    } else {
        die("Please upload your farm proof document.");
    }

    // Check if email exists
    $check_sql = "SELECT * FROM farmers WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        die("Email already registered. Please login.");
    }

    // Insert new farmer
    $insert_sql = "INSERT INTO farmers (full_name, email, phone, farm_location, document, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssss", $full_name, $email, $phone, $farm_location, $file_path, $hashed_password);

    if ($stmt->execute()) {
        echo "Registration successful! Your account is pending approval. <a href='login.php'>Login here</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request method.";
}
?>