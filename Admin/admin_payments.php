<?php
session_start();
include "db.php";

// --- Pagination Logic ---
$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total records
$total_results = $conn->query("SELECT COUNT(*) as id FROM payments")->fetch_assoc()['id'];
$total_pages = ceil($total_results / $limit);

// --- Query with Pagination ---
$sql = "
SELECT 
    p.id AS payment_id, p.txn_ref, p.amount AS payment_amount, 
    p.status AS payment_status, p.created_at AS payment_date,
    prod.name AS produce_name, f.full_name AS farmer_name,
    b.full_name AS buyer_name, b.buyer_code,
    l.status AS loan_status
FROM payments p
LEFT JOIN produce prod ON p.produce_id = prod.id
LEFT JOIN farmers f ON prod.farmer_id = f.id
LEFT JOIN buyers b ON p.buyer_code = b.buyer_code
LEFT JOIN loans l ON l.farmer_id = f.id
ORDER BY p.created_at DESC
LIMIT $limit OFFSET $offset
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions | HarvestFi Admin</title>
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
            <h1 class="text-white text-2xl font-bold italic">HarvestFi<span class="text-emerald-500">.</span></h1>
            <button id="close-sidebar" class="md:hidden text-slate-400">
                <i data-lucide="x"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="admin_dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="#" class="flex items-center p-3 text-white bg-emerald-600 rounded-lg shadow-lg">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Payments
            </a>
            <a href="admin_farmers.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i> Farmers
            </a>
            <a href="admin_loans.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> Loans
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-4 md:p-8 w-full">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8 gap-4">
                <div class="flex items-center">
                    <button id="open-sidebar" class="mr-4 md:hidden text-slate-600 bg-white p-2 rounded-lg border border-slate-200 shadow-sm">
                        <i data-lucide="menu"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">Payment Ledger</h2>
                        <p class="text-slate-500 mt-1 text-sm">Audit and process incoming supply chain transactions.</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <button class="w-full sm:w-auto flex items-center justify-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 shadow-sm hover:bg-slate-50 transition">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i> Export CSV
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Transaction Details</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stakeholders</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Loan Security</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if($result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900">#<?= htmlspecialchars($row['txn_ref']) ?></div>
                                    <div class="text-xs text-slate-400 mt-1 flex items-center">
                                        <i data-lucide="package" class="w-3 h-3 mr-1"></i> <?= htmlspecialchars($row['produce_name']) ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-slate-700 font-semibold"><?= htmlspecialchars($row['buyer_name']) ?></div>
                                    <div class="text-xs text-emerald-600">Farmer: <?= htmlspecialchars($row['farmer_name']) ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm font-bold text-slate-900">₦<?= number_format($row['payment_amount'], 2) ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($row['loan_status'] == 'approved'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            <i data-lucide="alert-circle" class="w-3 h-3 mr-1"></i> Lien Active
                                        </span>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-400">No Debt</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($row['payment_status'] == 'pending'): ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">Pending</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-700/10">Settled</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <?php if($row['payment_status'] == 'pending'): ?>
                                        <form method="POST" action="process_payment.php">
                                            <input type="hidden" name="payment_id" value="<?= $row['payment_id'] ?>">
                                            <button class="text-sm font-bold text-emerald-600 hover:text-emerald-700 flex items-center ml-auto transition">
                                                Process <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="text-slate-300 hover:text-slate-500 transition">
                                            <i data-lucide="printer" class="w-5 h-5 ml-auto"></i>
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr><td colspan="6" class="py-12 text-center text-slate-400">No transactions recorded.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-slate-500 order-2 sm:order-1">
                        Showing <span class="font-semibold"><?= $offset + 1 ?></span> to <span class="font-semibold"><?= min($offset + $limit, $total_results) ?></span> of <?= $total_results ?>
                    </div>
                    <div class="flex space-x-1 order-1 sm:order-2">
                        <?php if($page > 1): ?>
                            <a href="?page=<?= $page - 1 ?>" class="p-2 border border-slate-300 rounded-lg bg-white hover:bg-slate-50 text-slate-600 transition"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
                        <?php endif; ?>
                        
                        <?php for($p = 1; $p <= $total_pages; $p++): ?>
                            <a href="?page=<?= $p ?>" class="px-3.5 py-1.5 border <?= $p == $page ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-300 hover:bg-slate-50' ?> rounded-lg text-sm font-medium transition">
                                <?= $p ?>
                            </a>
                        <?php endfor; ?>

                        <?php if($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?>" class="p-2 border border-slate-300 rounded-lg bg-white hover:bg-slate-50 text-slate-600 transition"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        <?php endif; ?>
                    </div>
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