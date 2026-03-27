<?php
session_start();
include "db.php";

// --- 1. AUTHENTICATION CHECK ---
// Assuming you store the farmer's ID in $_SESSION['farmer_id'] upon login
if (!isset($_SESSION['farmer_id'])) {
    header("Location: login.php"); // Redirect to your login page
    exit();
}

$farmer_id = $_SESSION['farmer_id'];

// --- 2. FETCH FARMER PROFILE ---
$stmt_profile = $conn->prepare("SELECT full_name, farm_location FROM farmers WHERE id = ?");
$stmt_profile->bind_param("i", $farmer_id);
$stmt_profile->execute();
$farmer_data = $stmt_profile->get_result()->fetch_assoc();

// --- 3. HANDLE AJAX POST REQUEST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $amount = (float)$_POST['amount'];

    // Insert new loan for THIS specific farmer
    $stmt = $conn->prepare("INSERT INTO loans (farmer_id, amount, status) VALUES (?, ?, 'pending')");
    $stmt->bind_param("id", $farmer_id, $amount);
    $stmt->execute();

    // Re-calculate totals for this farmer only
    $resApproved = $conn->prepare("SELECT SUM(amount) as total FROM loans WHERE farmer_id = ? AND status = 'approved'");
    $resApproved->bind_param("i", $farmer_id);
    $resApproved->execute();
    $rowApproved = $resApproved->get_result()->fetch_assoc();

    $resPending = $conn->prepare("SELECT SUM(amount) as total FROM loans WHERE farmer_id = ? AND status = 'pending'");
    $resPending->bind_param("i", $farmer_id);
    $resPending->execute();
    $rowPending = $resPending->get_result()->fetch_assoc();

    echo json_encode([
        "status" => "success",
        "totalApproved" => (float)($rowApproved['total'] ?? 0),
        "totalPending" => (float)($rowPending['total'] ?? 0)
    ]);
    exit(); 
}

// --- 4. INITIAL PAGE LOAD DATA (Scoped to Farmer) ---
$resApproved = $conn->prepare("SELECT SUM(amount) as total FROM loans WHERE farmer_id = ? AND status = 'approved'");
$resApproved->bind_param("i", $farmer_id);
$resApproved->execute();
$totalApproved = $resApproved->get_result()->fetch_assoc()['total'] ?? 0;

$resPending = $conn->prepare("SELECT SUM(amount) as total FROM loans WHERE farmer_id = ? AND status = 'pending'");
$resPending->bind_param("i", $farmer_id);
$resPending->execute();
$totalPending = $resPending->get_result()->fetch_assoc()['total'] ?? 0;

$history = $conn->prepare("SELECT * FROM loans WHERE farmer_id = ? ORDER BY id DESC LIMIT 5");
$history->bind_param("i", $farmer_id);
$history->execute();
$history_result = $history->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Management | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background: #10b981; color: white !important; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.2); }
    </style>
</head>
<body class="flex min-h-screen text-slate-900">

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">
        <div class="p-8">
            <h1 class="text-2xl font-bold flex items-center text-emerald-600">
                <i data-lucide="sprout" class="mr-2"></i> HarvestFi<span class="text-slate-900">.</span>
            </h1>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="farmer_dashboard.php" class="flex items-center p-3 text-slate-500 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                <i data-lucide="layout-grid" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="#" class="sidebar-active flex items-center p-3 rounded-xl transition">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> Apply Loan
            </a>
            <a href="logout.php" class="flex items-center p-3 text-red-400 hover:bg-red-50 rounded-xl transition mt-10">
                <i data-lucide="log-out" class="w-5 h-5 mr-3"></i> Sign Out
            </a>
        </nav>
        <div class="p-4 border-t border-slate-100">
            <div class="flex items-center p-2 bg-slate-50 rounded-2xl">
                <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold mr-3">
                    <?= strtoupper(substr($farmer_data['full_name'], 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate"><?= htmlspecialchars($farmer_data['full_name']) ?></p>
                    <p class="text-[10px] text-slate-400 truncate"><?= htmlspecialchars($farmer_data['farm_location']) ?></p>
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 lg:ml-72 p-4 md:p-10">
        <div class="max-w-5xl mx-auto">
            
            <header class="flex justify-between items-center mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Financial Hub</h2>
                    <p class="text-slate-500 mt-1">Personalized credit for <b><?= htmlspecialchars($farmer_data['full_name']) ?></b></p>
                </div>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                <div class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
                    <p class="text-emerald-400 font-bold uppercase tracking-widest text-xs mb-2">Approved Balance</p>
                    <h3 class="text-4xl font-bold" id="approvedDisplay">₦<?= number_format($totalApproved, 2) ?></h3>
                </div>

                <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm relative overflow-hidden">
                    <p class="text-orange-500 font-bold uppercase tracking-widest text-xs mb-2">Total Pending</p>
                    <h3 class="text-4xl font-bold text-slate-800" id="pendingDisplay">₦<?= number_format($totalPending, 2) ?></h3>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1">
                    <div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm sticky top-10">
                        <h4 class="text-xl font-bold mb-6 flex items-center">
                            <i data-lucide="plus-circle" class="mr-2 text-emerald-500"></i> New Request
                        </h4>
                        <form id="loanForm" class="space-y-6">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3 tracking-widest">Amount Required (₦)</label>
                                <input type="number" id="amount" step="0.01" class="w-full bg-slate-50 border-2 border-transparent focus:border-emerald-500 focus:bg-white rounded-2xl py-4 px-6 outline-none transition-all text-lg font-bold" placeholder="0.00" required>
                            </div>
                            <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-emerald-100 flex items-center justify-center space-x-2">
                                <span>Submit Application</span>
                                <i data-lucide="arrow-right" class="w-5 h-5"></i>
                            </button>
                        </form>
                        <div id="msg" class="hidden mt-6 p-4 bg-emerald-50 text-emerald-700 rounded-2xl text-sm font-medium border border-emerald-100 flex items-start">
                            <i data-lucide="party-popper" class="w-5 h-5 mr-3 shrink-0"></i>
                            Request submitted!
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2rem] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-8 border-b border-slate-100">
                            <h4 class="text-xl font-bold">Your Loan History</h4>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold tracking-widest">
                                    <tr>
                                        <th class="px-8 py-4">Ref ID</th>
                                        <th class="px-8 py-4">Amount</th>
                                        <th class="px-8 py-4">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <?php while($row = $history_result->fetch_assoc()): ?>
                                    <tr class="hover:bg-slate-50/50 transition">
                                        <td class="px-8 py-5 text-sm font-medium text-slate-500">#LN-<?= $row['id'] ?></td>
                                        <td class="px-8 py-5 font-bold text-slate-800">₦<?= number_format($row['amount'], 2) ?></td>
                                        <td class="px-8 py-5">
                                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight 
                                                <?= $row['status'] == 'approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-orange-100 text-orange-700' ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        lucide.createIcons();
        $("#loanForm").submit(function(e){
            e.preventDefault();
            const btn = $("#submitBtn");
            const amount = $("#amount").val();
            btn.prop('disabled', true).addClass('opacity-70').html('Processing...');

            $.ajax({
                url: "apply_loan.php",
                type: "POST",
                data: { amount: amount },
                dataType: "json",
                success: function(data) {
                    if(data.status === "success") {
                        $("#approvedDisplay").text("₦" + data.totalApproved.toLocaleString(undefined, {minimumFractionDigits: 2}));
                        $("#pendingDisplay").text("₦" + data.totalPending.toLocaleString(undefined, {minimumFractionDigits: 2}));
                        $("#msg").removeClass("hidden").hide().fadeIn();
                        $("#amount").val('');
                        setTimeout(() => { location.reload(); }, 2000); // Reload to show new history row
                    }
                }
            });
        });
    </script>
</body>
</html>