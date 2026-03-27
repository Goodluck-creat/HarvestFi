<?php
session_start();
include 'db.php';

// 1. Security & Identity
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. Fetch User Profile Details
$user_query = "SELECT full_name, buyer_code, role FROM buyers WHERE id = ?";
$stmt_user = $conn->prepare($user_query);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$user_data = $stmt_user->get_result()->fetch_assoc();

// 3. Fetch Payments (Joined with produce table for better UX)
// I'm assuming a 'produce' table exists with a 'name' column based on your previous work
$payment_query = "SELECT p.*, pr.name as produce_name 
                  FROM payments p 
                  LEFT JOIN produce pr ON p.produce_id = pr.id 
                  WHERE p.user_id = ? 
                  ORDER BY p.created_at DESC";
$stmt_pay = $conn->prepare($payment_query);
$stmt_pay->bind_param("i", $user_id);
$stmt_pay->execute();
$payments = $stmt_pay->get_result();

function getStatusBadge($status) {
    $status = strtolower($status);
    $classes = "px-3 py-1 rounded-full text-[10px] font-bold uppercase border ";
    if ($status == 'approved' || $status == 'success') return $classes . "bg-green-100 text-green-700 border-green-200";
    if ($status == 'pending') return $classes . "bg-amber-100 text-amber-700 border-amber-200";
    return $classes . "bg-gray-100 text-gray-500 border-gray-200";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-[#F3F4F6] min-h-screen font-sans">

    <nav class="bg-white border-b border-gray-200 px-4 py-3 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="h-8 w-8 bg-black rounded-lg flex items-center justify-center text-white font-bold">H</div>
                <span class="font-black text-xl tracking-tighter">HarvestFi</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-gray-900 leading-none"><?php echo $user_data['full_name']; ?></p>
                    <!--<p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1"><?php echo $user_data['role']; ?></p>-->
                </div>
                <div class="h-10 w-10 rounded-full bg-gray-200 border-2 border-white shadow-sm overflow-hidden">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user_data['full_name']); ?>&background=random" alt="Profile">
                </div>
            </div>
             <div class="space-y-3">
                        
                        <a href="buyer_dashboard.php" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 text-gray-700 hover:bg-gray-100 transition-colors">
                            <span class="text-xs font-bold">Dashboard</span>
                            <i class="fas fa-home text-xs"></i>
                        </a>
                    </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        
        <div class="bg-black rounded-3xl p-8 mb-10 text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">Welcome, <?php echo explode(' ', $user_data['full_name'])[0]; ?>!</h2>
                <p class="text-gray-400 text-sm max-w-md">Your personalized dashboard for tracking agricultural purchases on harvestfi</p>
                
                <div class="mt-6 flex gap-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl p-4 border border-white/10">
                        <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Your ID Code</p>
                        <p class="text-lg font-mono font-bold text-amber-400"><?php echo $user_data['buyer_code']; ?></p>
                    </div>
                </div>
            </div>
            <div class="absolute -right-20 -top-20 h-64 w-64 bg-amber-400/10 rounded-full blur-3xl"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Payment History</h3>
                    <a href="#" class="text-xs font-bold text-black border-b-2 border-black">View All</a>
                </div>

                <?php if($payments->num_rows > 0): ?>
                    <?php while($row = $payments->fetch_assoc()): ?>
                    <div class="bg-white rounded-2xl p-4 border border-gray-100 flex items-center justify-between shadow-sm hover:scale-[1.01] transition-transform">
                        <div class="flex items-center gap-4">
                            <div class="h-12 w-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400">
                                <i class="fas fa-leaf text-xl text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900"><?php echo $row['produce_name'] ?? 'Agricultural Produce'; ?></h4>
                                <p class="text-[10px] text-gray-400 font-mono italic"><?php echo $row['txn_ref']; ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-md font-black text-gray-900">₦<?php echo number_format($row['amount'], 2); ?></p>
                            <span class="<?php echo getStatusBadge($row['status']); ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </div>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="bg-white rounded-2xl p-10 text-center border-2 border-dashed border-gray-200">
                        <p class="text-gray-400 italic">No payments recorded yet.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-3xl p-6 border border-gray-100 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-4">Quick Links</h4>
                    <div class="space-y-3">
                        
                        <a href="marketplace.php" class="flex items-center justify-between p-3 rounded-xl bg-gray-50 text-gray-700 hover:bg-gray-100 transition-colors">
                            <span class="text-xs font-bold">Browse Produce</span>
                            <i class="fas fa-shopping-cart text-xs"></i>
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-3xl p-6 text-white shadow-lg">
                    <i class="fas fa-qrcode text-3xl mb-4"></i>
                    <h4 class="font-bold text-lg leading-tight mb-2">Share Buyer Code</h4>
                    <p class="text-xs text-white/80 mb-4">Use this code for your transactions.</p>
                    <div class="bg-white/20 rounded-xl p-3 text-center border border-white/30 backdrop-blur-sm">
                        <span class="text-xl font-black tracking-widest"><?php echo $user_data['buyer_code']; ?></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>
</html>