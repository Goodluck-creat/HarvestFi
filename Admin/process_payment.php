<?php
session_start();
include "db.php";

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $payment_id = $_POST['payment_id'];
    $loan_id = $_POST['loan_id'] ?? null;

    // Get payment details
    $stmt = $conn->prepare("SELECT * FROM payments WHERE id=?");
    $stmt->bind_param("i", $payment_id);
    $stmt->execute();
    $payment = $stmt->get_result()->fetch_assoc();

    if(!$payment || $payment['status'] != 'pending'){
        die("Payment already processed or invalid.");
    }

    $amount = $payment['amount'];

    // Calculate platform fee (e.g., 5%)
    $platform_fee = $amount * 0.05;

    // If loan exists, repay some amount
    $loan_repayment = 0;
    if($loan_id){
        $stmt = $conn->prepare("SELECT * FROM loans WHERE id=?");
        $stmt->bind_param("i", $loan_id);
        $stmt->execute();
        $loan = $stmt->get_result()->fetch_assoc();
        if($loan && $loan['status'] == 'pending'){
            $loan_repayment = min($loan['amount'], $amount * 0.3); // e.g., 30% goes to loan repayment
            // Update loan
            $new_loan_amount = $loan['amount'] - $loan_repayment;
            $status = $new_loan_amount <= 0 ? 'approved' : 'pending';
            $update = $conn->prepare("UPDATE loans SET amount=?, status=? WHERE id=?");
            $update->bind_param("dsi", $new_loan_amount, $status, $loan_id);
            $update->execute();
        }
    }

    // Remaining amount goes to farmer
    $farmer_amount = $amount - $platform_fee - $loan_repayment;

    // TODO: Credit farmer wallet here (if exists)
    // $stmt = $conn->prepare("UPDATE farmers SET wallet_balance = wallet_balance + ? WHERE id=?");

    // Mark payment as success
    $update = $conn->prepare("UPDATE payments SET status='success' WHERE id=?");
    $update->bind_param("i", $payment_id);
    $update->execute();

    // Redirect back to admin page
    header("Location: admin_dashboard.php");
    exit;
}