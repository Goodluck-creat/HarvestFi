<?php
session_start();
include "db.php";

function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    $seconds = $time_difference;
    
    $minutes = round($seconds / 60);
    $hours   = round($seconds / 3600);
    $days    = round($seconds / 8400);

    if ($seconds <= 60) return "Just now";
    else if ($minutes <= 60) return ($minutes == 1) ? "1m ago" : $minutes . "m ago";
    else if ($hours <= 24) return ($hours == 1) ? "1h ago" : $hours . "h ago";
    else return ($days == 1) ? "Yesterday" : $days . "d ago";
}

$query = "SELECT p.*, f.full_name as farmer_name, f.farm_location 
          FROM produce p 
          JOIN farmers f ON p.farmer_id = f.id 
          WHERE p.status = 'available' 
          ORDER BY p.created_at DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interswitch Secured Marketplace | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(16px); }
        .interswitch-gradient { background: linear-gradient(135deg, #004a99 0%, #0072bc 100%); }
    </style>
</head>
<body class="bg-slate-50 text-slate-900">

    <div class="interswitch-gradient text-white py-2 px-6 flex justify-center items-center gap-4 text-[10px] font-bold uppercase tracking-[0.2em]">
        <i data-lucide="shield-check" class="w-4 h-4"></i>
        Secured by Interswitch Webpay — HarvestFi Integrity Guaranteed
    </div>

    <nav class="glass-nav border-b border-slate-200 py-4 px-8 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-8">
            <h1 class="text-2xl font-black text-slate-900 tracking-tighter italic">HarvestFi<span class="text-blue-600">.</span></h1>
            <div class="hidden lg:flex items-center gap-2 border-l border-slate-200 pl-6">
                <p class="text-[10px] font-bold text-slate-400 uppercase leading-none">Official Payment<br>Partner</p>
                <span class="text-blue-800 font-extrabold text-sm tracking-tighter">interswitch</span>
            </div>
        </div>
        <div class="flex gap-6 items-center">
            <a href="buyer_dashboard.php" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition">Buyer Portal</a>
            <div class="flex items-center gap-3 bg-blue-50 px-3 py-1.5 rounded-2xl border border-blue-100">
                <i data-lucide="lock" class="w-4 h-4 text-blue-600"></i>
                <span class="text-xs font-bold text-blue-700">PCI-DSS Compliant</span>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-6 lg:p-12">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="text-5xl font-black tracking-tight text-slate-900 mb-2">Marketplace</h2>
                <p class="text-slate-500 max-w-md">Purchase directly from farmers with the confidence of bank-grade security provided by **Interswitch**.</p>
            </div>
            <div class="bg-white p-4 rounded-3xl border border-slate-200 shadow-sm flex items-center gap-4">
                <div class="bg-emerald-100 p-2 rounded-2xl"><i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-600"></i></div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase">Integrity Status</p>
                    <p class="text-sm font-black text-slate-800 tracking-tight">Escrow Protected</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): 
                    $images = json_decode($row['image'], true);
                    $display_image = (!empty($images) && isset($images[0])) ? $images[0] : 'assets/placeholder.jpg';
                ?>
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden hover:shadow-2xl transition-all duration-500 group">
                    <div class="relative h-52 overflow-hidden">
                        <img src="../farmer/<?php echo $display_image; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur px-3 py-1.5 rounded-2xl shadow-xl">
                            <span class="text-xs font-black text-slate-900">₦<?php echo number_format($row['price'], 0); ?></span>
                        </div>
                        <div class="absolute bottom-4 right-4 bg-blue-600/90 backdrop-blur px-2 py-1 rounded-lg flex items-center gap-1.5">
                            <i data-lucide="shield" class="w-3 h-3 text-white"></i>
                            <span class="text-[9px] font-black text-white uppercase tracking-tighter">Interswitch Secured</span>
                        </div>
                    </div>

                    <div class="p-7">
                        <h3 class="text-xl font-bold text-slate-800 mb-1 capitalize leading-tight"><?php echo $row['name']; ?></h3>
                        <p class="text-xs text-slate-400 flex items-center gap-2 mb-6">
                            <i data-lucide="map-pin" class="w-3 h-3 text-blue-500"></i>
                            <?php echo $row['farm_location']; ?>
                        </p>

                        <button onclick="openCheckout('<?php echo addslashes($row['name']); ?>', <?php echo $row['price']; ?>, <?php echo $row['id']; ?>)" 
                                class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold hover:bg-blue-600 transition-all flex items-center justify-center gap-2">
                            Buy Now <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="mt-20 border-t border-slate-200 bg-white py-12 px-8">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-4">
                <i data-lucide="landmark" class="w-10 h-10 text-slate-300"></i>
                <p class="text-sm text-slate-500 font-medium">HarvestFi Integrity System: Every kobo is routed through <br><strong>Interswitch’s reputable payment infrastructure</strong>.</p>
            </div>
            <div class="flex gap-10 grayscale opacity-50">
                <span class="font-black italic text-xl">interswitch</span>
                <span class="font-black italic text-xl">VERIFIED BY VISA</span>
                <span class="font-black italic text-xl">Mastercard</span>
            </div>
        </div>
    </footer>

    <div id="checkoutModal" class="hidden fixed inset-0 bg-slate-900/80 backdrop-blur-md z-[100] flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
            <div class="flex justify-between items-start mb-8">
                <div>
                    <span class="text-blue-600 font-black text-[10px] uppercase tracking-widest flex items-center gap-2 mb-1">
                        <i data-lucide="shield-check" class="w-3 h-3"></i> Interswitch Gateway
                    </span>
                    <h2 class="text-3xl font-black text-slate-900" id="modalTitle">Produce</h2>
                </div>
                <button onclick="closeModal()" class="bg-slate-100 p-2 rounded-full hover:bg-red-50 transition"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            
            <form action="initiate_payment.php" method="POST">
                <input type="hidden" name="produce_id" id="modalProduceId">
                <input type="hidden" name="total_price" id="modalPriceRaw">
                
                <div class="mb-8">
                    <label class="block text-[11px] font-black uppercase tracking-widest text-slate-400 mb-3">Buyer Authentication</label>
                    <input type="text" id="buyer_code_input" name="buyer_code" required 
                        class="w-full p-5 bg-slate-50 border-2 border-slate-100 rounded-3xl focus:border-blue-500 outline-none transition-all font-mono text-xl" 
                        placeholder="ENTER CODE" oninput="verifyBuyerCode(this.value)">
                    <p id="verifyStatus" class="mt-3 text-[10px] text-slate-400 font-bold uppercase px-1">Code is required for secure processing.</p>
                </div>

                <div class="bg-slate-900 rounded-3xl p-6 mb-8 text-center shadow-xl">
                    <span class="text-slate-400 text-[10px] font-bold uppercase tracking-widest block mb-1">Grand Total (Powered by Interswitch)</span>
                    <span id="modalPrice" class="text-3xl font-black text-white leading-none"></span>
                </div>

                <button type="submit" id="payButton" disabled 
                    class="w-full bg-slate-200 cursor-not-allowed text-slate-400 font-black py-5 rounded-3xl transition-all text-lg uppercase tracking-[0.1em]">
                    Pay with Interswitch
                </button>
                
                <p class="mt-6 text-center text-[10px] text-slate-400 font-medium">
                    Your payment information is never stored on HarvestFi. <br>Transaction handled exclusively by <strong>Interswitch Limited</strong>.
                </p>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openCheckout(name, price, id) {
            document.getElementById('modalTitle').innerText = name;
            document.getElementById('modalPrice').innerText = "₦" + price.toLocaleString();
            document.getElementById('modalPriceRaw').value = price;
            document.getElementById('modalProduceId').value = id;
            document.getElementById('checkoutModal').classList.remove('hidden');
            document.getElementById('checkoutModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('checkoutModal').classList.add('hidden');
        }

        function verifyBuyerCode(code) {
            const status = document.getElementById('verifyStatus');
            const btn = document.getElementById('payButton');
            
            if (code.length > 2) {
                // This simulates the check you have in your verify_buyer.php
                fetch('verify_buyer.php?code=' + encodeURIComponent(code))
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            btn.disabled = false;
                            btn.className = "w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-5 rounded-3xl transition-all shadow-xl shadow-blue-200 text-lg uppercase tracking-[0.1em]";
                            status.innerText = "AUTHENTICATED: " + data.name;
                            status.className = "mt-3 text-[10px] text-blue-600 font-bold uppercase px-1";
                        } else {
                            btn.disabled = true;
                            status.innerText = "INVALID ACCOUNT CODE";
                            status.className = "mt-3 text-[10px] text-red-500 font-bold uppercase px-1";
                        }
                    });
            }
        }
    </script>
</body>
</html>