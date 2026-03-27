<?php
session_start();
include "db.php";

// --- Pagination Logic ---
$limit = 10; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total count for pagination
$total_res = $conn->query("SELECT COUNT(*) as id FROM farmers")->fetch_assoc()['id'];
$total_pages = ceil($total_res / $limit);

// Handle deletion
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM farmers WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_farmers.php?page=$page");
    exit;
}

// Fetch farmers with pagination
$result = $conn->query("SELECT * FROM farmers ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer Registry | HarvestFi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(8px); }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <aside class="w-64 bg-slate-900 text-slate-400 hidden md:flex flex-col">
        <div class="p-8"><h1 class="text-white text-2xl font-bold">HarvestFi<span class="text-emerald-500">.</span></h1></div>
        <nav class="flex-1 px-4 space-y-2">
            <a href="dashboard.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i> Dashboard
            </a>
            <a href="admin_farmers.php" class="flex items-center p-3 text-white bg-emerald-600 rounded-lg shadow-lg">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i> Farmers
            </a>
            <a href="admin_payments.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Payments
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-slate-800 tracking-tight">Farmer Registry</h2>
                    <p class="text-slate-500 mt-1">Manage and verify registered agricultural partners.</p>
                </div>
                <div class="flex space-x-3">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-slate-400"></i>
                        <input type="text" placeholder="Search farmers..." class="pl-10 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none w-64 shadow-sm">
                    </div>
                    <button class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition shadow-md flex items-center">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Add Farmer
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Farmer Profile</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact Details</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Location</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Verification</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="py-4 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if($result->num_rows > 0): ?>
                            <?php while($farmer = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-xs mr-3">
                                            <?= strtoupper(substr($farmer['full_name'], 0, 2)) ?>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($farmer['full_name']) ?></div>
                                            <div class="text-xs text-slate-400 italic">ID: #<?= $farmer['id'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-slate-600"><?= htmlspecialchars($farmer['email']) ?></div>
                                    <div class="text-xs text-slate-400 mt-1"><?= htmlspecialchars($farmer['phone']) ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center text-sm text-slate-600">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1.5 text-slate-400"></i>
                                        <?= htmlspecialchars($farmer['farm_location']) ?>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($farmer['document']): ?>
                                        <a href="<?= htmlspecialchars($farmer['document']) ?>" target="_blank" class="inline-flex items-center text-xs font-medium text-blue-600 hover:text-blue-800 bg-blue-50 px-2 py-1 rounded">
                                            <i data-lucide="file-text" class="w-3 h-3 mr-1"></i> View KYC
                                        </a>
                                    <?php else: ?>
                                        <span class="text-xs text-slate-300">No Document</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($farmer['status'] == 'approved'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500 mr-1.5"></span> Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                            <span class="w-1 h-1 rounded-full bg-amber-500 mr-1.5"></span> Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <a href="admin_edit_farmer.php?id=<?= $farmer['id'] ?>" class="text-slate-400 hover:text-blue-600 transition">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <a href="admin_farmers.php?delete_id=<?= $farmer['id'] ?>&page=<?= $page ?>" 
                                           onclick="return confirm('Archive this farmer record?');" 
                                           class="text-slate-400 hover:text-red-600 transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                            <?php else: ?>
                            <tr><td colspan="6" class="py-12 text-center text-slate-400">No farmer records found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-between">
                    <span class="text-sm text-slate-500">Record <span class="font-semibold"><?= $offset + 1 ?></span> to <span class="font-semibold"><?= min($offset + $limit, $total_res) ?></span></span>
                    <div class="flex space-x-1">
                        <?php if($page > 1): ?>
                            <a href="?page=<?= $page-1 ?>" class="p-2 border border-slate-300 rounded-lg hover:bg-white text-slate-600"><i data-lucide="chevron-left" class="w-4 h-4"></i></a>
                        <?php endif; ?>
                        
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="px-3.5 py-1.5 border <?= $i==$page ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-300' ?> rounded-lg text-sm font-medium transition"><?= $i ?></a>
                        <?php endfor; ?>

                        <?php if($page < $total_pages): ?>
                            <a href="?page=<?= $page+1 ?>" class="p-2 border border-slate-300 rounded-lg hover:bg-white text-slate-600"><i data-lucide="chevron-right" class="w-4 h-4"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>