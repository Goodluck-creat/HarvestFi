<?php
session_start();
include "db.php";

// ✅ Handle Mark as Refunded
if (isset($_GET['refund_id'])) {
    $refund_id = intval($_GET['refund_id']);
    $stmt = $conn->prepare("UPDATE loans SET status='refunded' WHERE id=? AND status='approved'");
    $stmt->bind_param("i", $refund_id);
    $stmt->execute();
    header("Location: admin_loans.php");
    exit;
}

// ✅ Fetch all loans with farmer info
$sql = "
SELECT loans.*, farmers.full_name AS farmer_name
FROM loans
LEFT JOIN farmers ON loans.farmer_id = farmers.id
ORDER BY loans.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Loans | HarvestFi Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        #sidebar { transition: transform 0.3s ease-in-out; }
        .sidebar-open { transform: translateX(0) !important; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-400 z-50 -translate-x-full md:translate-x-0 md:static md:flex flex-col">
        <div class="p-8 flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold">HarvestFi<span class="text-emerald-500">.</span></h1>
            <button id="close-sidebar" class="md:hidden text-slate-400">
                <i data-lucide="x"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="admin_dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="admin_farmers.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i> Farmers
            </a>
            <a href="admin_loans.php" class="flex items-center p-3 text-white bg-emerald-600 rounded-lg shadow-lg">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> Loan Requests
            </a>
            <a href="admin_payments.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Payments
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-4 md:p-8 w-full">
        <div class="max-w-7xl mx-auto">
            
            <header class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <button id="open-sidebar" class="mr-4 md:hidden text-slate-600 bg-white p-2 rounded-lg border border-slate-200 shadow-sm">
                        <i data-lucide="menu"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">Manage Loans</h2>
                        <p class="text-slate-500 text-sm mt-1">Review and process agricultural credit facilities.</p>
                    </div>
                </div>
            </header>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-semibold tracking-wider">
                                <th class="px-6 py-4">Loan ID</th>
                                <th class="px-6 py-4">Farmer</th>
                                <th class="px-6 py-4">Amount</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Due Date</th>
                                <th class="px-6 py-4">Requested On</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while($loan = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4 text-sm font-medium text-slate-400">#<?= $loan['id'] ?></td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($loan['farmer_name'] ?? 'N/A') ?></div>
                                </td>
                                <td class="px-6 py-4 text-sm font-bold text-slate-800">
                                    ₦<?= number_format($loan['amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status = $loan['status'];
                                    $status_map = [
                                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'approved' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'refunded' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    ];
                                    $current_style = $status_map[$status] ?? 'bg-slate-100 text-slate-800 border-slate-200';
                                    ?>
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium border <?= $current_style ?>">
                                        <?= ucfirst($status) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-500"><?= $loan['due_date'] ?? '-' ?></td>
                                <td class="px-6 py-4 text-sm text-slate-500"><?= date('M d, Y', strtotime($loan['created_at'])) ?></td>
                                <td class="px-6 py-4 text-right">
                                    <?php if($loan['status'] === 'approved'): ?>
                                    <a href="admin_loans.php?refund_id=<?= $loan['id'] ?>" 
                                       onclick="return confirm('Mark this loan as fully refunded?');" 
                                       class="inline-flex items-center bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5 mr-1"></i> Refund
                                    </a>
                                    <?php else: ?>
                                    <span class="text-xs text-slate-300 italic">No actions</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        // Mobile Sidebar Logic
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const openBtn = document.getElementById('open-sidebar');
        const closeBtn = document.getElementById('close-sidebar');

        const toggleSidebar = () => {
            sidebar.classList.toggle('sidebar-open');
            overlay.classList.toggle('hidden');
        };

        openBtn.addEventListener('click', toggleSidebar);
        closeBtn.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', toggleSidebar);
    </script>
</body>
</html>