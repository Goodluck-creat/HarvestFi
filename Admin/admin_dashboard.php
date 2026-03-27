<?php
session_start();
include "db.php";

// Fetching statistics
$queries = [
    'farmers' => "SELECT COUNT(*) as total FROM farmers",
    'buyers' => "SELECT COUNT(*) as total FROM buyers",
    'payments' => "SELECT COUNT(*) as total FROM payments",
    'loans_pending' => "SELECT COUNT(*) as total FROM loans WHERE status='pending'",
    'loans_approved' => "SELECT COUNT(*) as total FROM loans WHERE status='approved'"
];

$stats = [];
foreach ($queries as $key => $sql) {
    $result = $conn->query($sql);
    $stats[$key] = $result->fetch_assoc()['total'] ?? 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Intelligence | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(229, 231, 235, 0.5);
        }
        /* Mobile Sidebar Transition */
        #sidebar { transition: transform 0.3s ease-in-out; }
        .sidebar-open { transform: translateX(0) !important; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 z-50 -translate-x-full md:translate-x-0 md:static md:flex flex-col">
        <div class="p-6 flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold tracking-tight">HarvestFi<span class="text-emerald-500">.</span></h1>
            <button id="close-sidebar" class="md:hidden text-slate-400">
                <i data-lucide="x"></i>
            </button>
        </div>
        <nav class="flex-1 px-4 space-y-2 mt-4">
            <a href="#" class="sidebar-item flex items-center p-3 text-white bg-emerald-600 rounded-lg">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="admin_farmers.php" class="sidebar-item flex items-center p-3 rounded-lg transition hover:bg-slate-800">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i> Farmers
            </a>
            <a href="admin_buyers.php" class="sidebar-item flex items-center p-3 rounded-lg transition hover:bg-slate-800">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i> Buyers
            </a>
            <a href="admin_payments.php" class="sidebar-item flex items-center p-3 rounded-lg transition hover:bg-slate-800">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Transactions
            </a>
            <a href="admin_loans.php" class="sidebar-item flex items-center p-3 rounded-lg transition hover:bg-slate-800">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> Loan Requests
            </a>
        </nav>
        <div class="p-4 border-t border-slate-800">
            <div class="bg-slate-800 rounded-xl p-4 text-xs">
                <p class="text-slate-400 mb-2">System Status</p>
                <div class="flex items-center text-emerald-400">
                    <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span> Operational
                </div>
            </div>
        </div>
    </aside>

    <main class="flex-1 p-4 md:p-8 w-full">
        <header class="flex justify-between items-center mb-10">
            <div class="flex items-center">
                <button id="open-sidebar" class="mr-4 md:hidden text-slate-600 bg-white p-2 rounded-lg border border-slate-200 shadow-sm">
                    <i data-lucide="menu"></i>
                </button>
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Overview</h2>
                    <p class="text-slate-500 text-sm md:text-base mt-1">Welcome back, Administrator.</p>
                </div>
            </div>
            <div class="flex space-x-2 md:space-x-4">
                <button class="bg-white p-2 rounded-full border border-slate-200 text-slate-600 shadow-sm">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                </button>
                <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center text-emerald-700 font-bold">
                    AD
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="glass-card p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase">Total Farmers</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($stats['farmers']) ?></h3>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <i data-lucide="user-plus"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-emerald-600 font-semibold">
                    <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> +12% from last month
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl shadow-sm">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase">Revenue</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= number_format($stats['payments']) ?></h3>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <i data-lucide="dollar-sign"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-xs text-blue-600 font-semibold">
                    <i data-lucide="activity" class="w-3 h-3 mr-1"></i> Real-time sync
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl shadow-sm border-l-4 border-amber-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase">Pending Loans</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $stats['loans_pending'] ?></h3>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl text-amber-600">
                        <i data-lucide="clock"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-400 italic underline cursor-pointer hover:text-amber-600">
                    Needs immediate review
                </div>
            </div>

            <div class="glass-card p-6 rounded-2xl shadow-sm border-l-4 border-emerald-400">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-slate-500 uppercase">Disbursed</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-1"><?= $stats['loans_approved'] ?></h3>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <i data-lucide="check-circle"></i>
                    </div>
                </div>
                <div class="mt-4 text-xs text-slate-400">
                    Total approved capital
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 glass-card p-6 rounded-2xl shadow-sm">
                <h4 class="font-bold text-slate-700 mb-4">Growth Analysis</h4>
                <div class="relative h-[250px] md:h-auto">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
            <div class="glass-card p-6 rounded-2xl shadow-sm flex flex-col justify-center">
                <h4 class="font-bold text-slate-700 mb-4 text-center">User Distribution</h4>
                <canvas id="userDistChart"></canvas>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide Icons
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

        // Growth Chart (Line)
        new Chart(document.getElementById('growthChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Farmers',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#10b981',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(16, 185, 129, 0.1)'
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } } 
            }
        });

        // Distribution Chart (Doughnut)
        new Chart(document.getElementById('userDistChart'), {
            type: 'doughnut',
            data: {
                labels: ['Farmers', 'Buyers'],
                datasets: [{
                    data: [<?= (int)$stats['farmers'] ?>, <?= (int)$stats['buyers'] ?>],
                    backgroundColor: ['#10b981', '#3b82f6'],
                    borderWidth: 0
                }]
            },
            options: { 
                cutout: '80%', 
                plugins: { legend: { position: 'bottom' } } 
            }
        });
    </script>
</body>
</html>