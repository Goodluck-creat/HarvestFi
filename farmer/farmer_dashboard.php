<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Dashboard | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(10px); }
        #sidebar { transition: all 0.3s ease; }
        .sidebar-item:hover { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .active-nav { background: #10b981; color: white !important; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.3); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex">

    <div id="overlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    <aside id="sidebar" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 z-50 -translate-x-full lg:translate-x-0 flex flex-col">
        <div class="p-8">
            <h1 class="text-2xl font-bold flex items-center text-emerald-600">
                <i data-lucide="sprout" class="mr-2"></i> HarvestFi<span class="text-slate-900">.</span>
            </h1>
        </div>
        
        <nav class="flex-1 px-4 space-y-2">
            <a href="#" class="active-nav flex items-center p-3 rounded-xl transition">
                <i data-lucide="layout-grid" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="apply_loan.php" class="sidebar-item flex items-center p-3 rounded-xl text-slate-500 transition">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> My Loans
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-xl text-slate-500 transition">
                <i data-lucide="shopping-basket" class="w-5 h-5 mr-3"></i> My Produce
            </a>
            <a href="#" class="sidebar-item flex items-center p-3 rounded-xl text-slate-500 transition">
                <i data-lucide="trending-up" class="w-5 h-5 mr-3"></i> Sales Analytics
            </a>
        </nav>

        <div class="p-4 border-t border-slate-100">
            <div class="bg-emerald-50 rounded-2xl p-4">
                <p class="text-xs font-bold text-emerald-600 uppercase tracking-wider mb-1">Support</p>
                <p class="text-sm text-emerald-800 mb-3">Need help with your crops?</p>
                <button class="w-full bg-white text-emerald-600 py-2 rounded-lg text-sm font-bold shadow-sm">Contact Agent</button>
            </div>
        </div>
    </aside>

    <main class="flex-1 lg:ml-72 min-w-0">
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-slate-200 flex items-center justify-between px-4 md:px-8 sticky top-0 z-30">
            <div class="flex items-center">
                <button id="menuBtn" class="lg:hidden mr-4 p-2 bg-slate-100 rounded-lg">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <h2 class="text-xl font-bold text-slate-800">Farmer Dashboard</h2>
            </div>
            <div class="flex items-center space-x-4">
                <button class="p-2 text-slate-400 hover:text-emerald-500 transition relative">
                    <i data-lucide="bell" class="w-6 h-6"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                <div class="w-10 h-10 rounded-full bg-emerald-100 border-2 border-emerald-500 overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=Farmer+John&background=10b981&color=fff" alt="Profile">
                </div>
            </div>
        </header>

        <div class="p-4 md:p-8 space-y-8">
            <section class="relative rounded-3xl overflow-hidden bg-slate-900 h-48 flex items-center px-8 shadow-2xl">
                <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&q=80&w=1000" class="absolute inset-0 w-full h-full object-cover opacity-40" alt="Farm">
                <div class="relative z-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-white">Welcome back, Farmer 👋</h1>
                    <p class="text-emerald-300 mt-2 font-medium">Your harvest is looking great this season!</p>
                </div>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-emerald-100 text-emerald-600 rounded-2xl"><i data-lucide="wallet"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Active Loans</span>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-800" id="totalLoan">₦0.00</h3>
                    <p class="text-slate-400 text-sm mt-1">Pending Repayment</p>
                </div>
                
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-orange-100 text-orange-600 rounded-2xl"><i data-lucide="package"></i></div>
                        <span class="text-xs font-bold text-slate-400 uppercase">Produce Listed</span>
                    </div>
                    <h3 class="text-3xl font-bold text-slate-800" id="produceCount">0</h3>
                    <p class="text-slate-400 text-sm mt-1">Active Listings</p>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm col-span-1">
                    <canvas id="miniChart" height="80"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex items-center mb-6">
                        <i data-lucide="badge-percent" class="w-6 h-6 text-emerald-500 mr-2"></i>
                        <h3 class="text-xl font-bold">Fast Credit Application</h3>
                    </div>
                    <form id="loanForm" class="space-y-4">
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-slate-400">₦</span>
                            <input type="number" id="loanAmount" class="w-full bg-slate-50 border-none rounded-2xl py-4 pl-8 pr-4 focus:ring-2 focus:ring-emerald-500 outline-none transition" placeholder="Enter amount">
                        </div>
                        <button class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl transition shadow-lg shadow-emerald-200">
                            Instant Application
                        </button>
                    </form>
                    <div id="loanMsg" class="mt-4 p-4 bg-emerald-50 text-emerald-700 rounded-xl hidden flex items-center">
                        <i data-lucide="check-circle" class="mr-2 w-5 h-5"></i> Loan submitted for review!
                    </div>
                </div>

                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
                    <div class="flex items-center mb-6">
                        <i data-lucide="plus-square" class="w-6 h-6 text-emerald-500 mr-2"></i>
                        <h3 class="text-xl font-bold">Inventory Management</h3>
                    </div>
                    <form id="produceForm" class="space-y-4">
                        <input type="text" id="pname" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Produce Name (e.g. Organic Maize)">
                        <input type="number" id="pprice" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-4 focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Target Price (₦)">
                        <button class="w-full bg-slate-900 hover:bg-black text-white font-bold py-4 rounded-2xl transition">Add to Marketplace</button>
                    </form>
                </div>
            </div>

            <section>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Marketplace Listings</h3>
                    <button onclick="payWithInterswitch()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl text-sm font-bold flex items-center transition shadow-lg shadow-blue-100">
                        <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i> Test Payout (₦2k)
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6" id="produceList">
                    </div>
            </section>
        </div>
    </main>

    <script src="https://sandbox.interswitchng.com/collections/api/v1/interswitch-inline.js"></script>
    <script>
        lucide.createIcons();

        // Responsive Sidebar
        const menuBtn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        menuBtn.onclick = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };
        overlay.onclick = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };

        // Mini Chart Logic
        const ctx = document.getElementById('miniChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Market Price',
                    data: [12, 19, 15, 25, 22],
                    borderColor: '#10b981',
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 0
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { x: { display: false }, y: { display: false } }
            }
        });

        // Loan Simulation
        document.getElementById("loanForm").addEventListener("submit", function(e){
            e.preventDefault();
            let amt = document.getElementById("loanAmount").value;
            if(!amt) return;
            document.getElementById("totalLoan").innerText = "₦" + Number(amt).toLocaleString();
            document.getElementById("loanMsg").classList.remove("hidden");
        });

        // Produce Listing
        let count = 0;
        document.getElementById("produceForm").addEventListener("submit", function(e){
            e.preventDefault();
            let name = document.getElementById("pname").value;
            let price = document.getElementById("pprice").value;
            if(!name || !price) return;

            count++;
            document.getElementById("produceCount").innerText = count;

            const card = `
                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all duration-300">
                    <div class="h-48 overflow-hidden relative">
                        <img src="https://images.unsplash.com/photo-1567306226416-28f0efdc88ce?auto=format&fit=crop&q=80&w=400" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <span class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-xs font-bold text-emerald-600 uppercase shadow-sm">Available</span>
                    </div>
                    <div class="p-6">
                        <h4 class="font-bold text-lg text-slate-800">${name}</h4>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-xl font-bold text-slate-900">₦${Number(price).toLocaleString()}</span>
                            <button class="p-2 bg-slate-100 rounded-lg text-slate-400 hover:text-emerald-500 hover:bg-emerald-50 transition"><i data-lucide="more-horizontal" class="w-5 h-5"></i></button>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById("produceList").insertAdjacentHTML('afterbegin', card);
            lucide.createIcons();
            document.getElementById("produceForm").reset();
        });

        // Interswitch Payment
        function payWithInterswitch(){
            window.webpayCheckout({
                merchant_code: "MX12345",
                pay_item_id: "Default_Payable_MX12345",
                txn_ref: "TXN_" + Date.now(),
                amount: 200000, 
                currency: 566,
                site_redirect_url: "#",
                onComplete: (r) => alert("Payment Successful!"),
                onClose: () => alert("Cancelled")
            });
        }
    </script>
</body>
</html>