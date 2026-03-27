<?php
include "db.php";
$buyer_id = 1; // Replace with session ID

// --- Stats for the Buyer ---
$total_orders = $conn->query("SELECT COUNT(*) as total FROM payments WHERE buyer_code = (SELECT buyer_code FROM buyers WHERE id = $buyer_id)")->fetch_assoc()['total'];
$total_spent = $conn->query("SELECT SUM(amount) as total FROM payments WHERE buyer_code = (SELECT buyer_code FROM buyers WHERE id = $buyer_id)")->fetch_assoc()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Console | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient {
            background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80&w=1200');
            background-size: cover;
            background-position: center;
        }
        @media (max-width: 1024px) {
            .sidebar-closed { transform: translateX(-100%); }
            .sidebar-open { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen overflow-x-hidden">

    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-400 transition-transform duration-300 sidebar-closed lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
        <div class="p-8 flex justify-between items-center">
            <h1 class="text-white text-2xl font-bold italic">HarvestFi<span class="text-emerald-500">.</span></h1>
            <button onclick="toggleSidebar()" class="lg:hidden text-slate-400"><i data-lucide="x"></i></button>
        </div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="#" class="flex items-center p-3 text-white bg-emerald-600 rounded-xl shadow-lg shadow-emerald-900/20">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="marketplace.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 transition">
                <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i> Visit Marketplace
            </a>
            <a href="my_orders.php" class="flex items-center p-3 rounded-xl hover:bg-slate-800 transition">
                <i data-lucide="package" class="w-5 h-5 mr-3"></i> Procurement History
            </a>
            <a href="#" class="flex items-center p-3 rounded-xl hover:bg-slate-800 transition">
                <i data-lucide="bar-chart-3" class="w-5 h-5 mr-3"></i> Price Index
            </a>
        </nav>
        <div class="p-6 border-t border-slate-800">
            <div class="bg-slate-800/50 p-4 rounded-2xl border border-slate-700">
                <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Market Status</p>
                <p class="text-white text-sm">Trading is Active</p>
            </div>
        </div>
    </aside>

    <main class="flex-1 min-w-0 overflow-auto">
        
        <div class="lg:hidden bg-white p-4 border-b border-slate-200 flex justify-between items-center">
             <h1 class="text-slate-900 font-bold">HarvestFi</h1>
             <button onclick="toggleSidebar()" class="p-2 bg-slate-100 rounded-lg"><i data-lucide="menu" class="w-6 h-6"></i></button>
        </div>

        <div class="p-6 lg:p-10 max-w-7xl mx-auto">
            
            <div class="hero-gradient rounded-[2.5rem] p-8 md:p-12 mb-10 text-white shadow-2xl relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="bg-emerald-500 text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full mb-4 inline-block">Industry Insights</span>
                    <h2 class="text-4xl md:text-5xl font-bold leading-tight mb-4">Secure Your Supply Chain with Data.</h2>
                    <p class="text-slate-200 text-lg mb-8 opacity-90">Track seasonal price fluctuations and connect with 5,000+ verified smallholder farmers across the region.</p>
                    <a href="marketplace.php" class="inline-flex items-center bg-white text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-emerald-500 hover:text-white transition-all transform hover:scale-105">
                        Enter Marketplace <i data-lucide="arrow-right" class="ml-2 w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white p-8 rounded-[2rem] border border-slate-200 shadow-sm">
                    <div class="flex justify-between items-center mb-8">
                        <div>
                            <h3 class="font-bold text-slate-800 text-xl">Market Price Trend</h3>
                            <p class="text-slate-400 text-sm">Average price per KG (Grain/Tubers)</p>
                        </div>
                        <select class="bg-slate-50 border-none rounded-xl px-4 py-2 text-sm font-semibold text-slate-600 outline-none">
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                        </select>
                    </div>
                    <canvas id="marketChart" height="150"></canvas>
                </div>

                <div class="space-y-8">
                    <div class="bg-indigo-900 rounded-[2rem] p-8 text-white relative overflow-hidden">
                        <i data-lucide="pie-chart" class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10"></i>
                        <p class="text-indigo-300 text-sm font-medium mb-1">Your Total Volume</p>
                        <h4 class="text-4xl font-bold mb-4">₦<?= number_format($total_spent) ?></h4>
                        <div class="flex items-center text-emerald-400 text-sm font-bold">
                            <i data-lucide="arrow-up-right" class="w-4 h-4 mr-1"></i> +8.4% Buy Power
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 border border-slate-200 shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="p-2 bg-amber-100 text-amber-600 rounded-lg mr-3">
                                <i data-lucide="zap" class="w-5 h-5"></i>
                            </div>
                            <h4 class="font-bold text-slate-800">Analyst Note</h4>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed">
                            Expect a <span class="text-emerald-600 font-bold">15% surge</span> in Maize demand by next month. We recommend finalizing procurement contracts before the harvest peak.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
                <div class="rounded-[2rem] h-48 bg-cover bg-center flex items-end p-8" style="background-image: url('https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?auto=format&fit=crop&q=80&w=600');">
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl w-full">
                        <p class="text-white font-bold">Sustainable Sourcing</p>
                        <p class="text-white/70 text-xs">All our farmers use eco-friendly practices.</p>
                    </div>
                </div>
                <div class="rounded-[2rem] h-48 bg-cover bg-center flex items-end p-8" style="background-image: url('https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&q=80&w=600');">
                    <div class="bg-white/20 backdrop-blur-md p-4 rounded-2xl w-full">
                        <p class="text-white font-bold">Direct Logistics</p>
                        <p class="text-white/70 text-xs">Track your delivery from farm gate to warehouse.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Initialize Lucide
        lucide.createIcons();

        // Toggle Sidebar for Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('sidebar-closed');
            sidebar.classList.toggle('sidebar-open');
        }

        // Market Trend Chart
        const ctx = document.getElementById('marketChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Market Price (₦)',
                    data: [1200, 1900, 1700, 2100, 2400, 2200],
                    borderColor: '#10b981',
                    borderWidth: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: (context) => {
                        const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, 400);
                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                        return gradient;
                    },
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { display: false }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                }
            }
        });
    </script>
</body>
</html>