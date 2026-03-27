<?php
session_start();
include "db.php";

// --- Pagination Logic ---
$limit = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$total_res = $conn->query("SELECT COUNT(*) as id FROM buyers")->fetch_assoc()['id'];
$total_pages = ceil($total_res / $limit);

// ✅ Handle Delete
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM buyers WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: admin_buyers.php?page=$page");
    exit;
}

// ✅ Handle Edit/Update Logic
$edit_buyer = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $conn->prepare("SELECT * FROM buyers WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_buyer = $stmt->get_result()->fetch_assoc();
}

if (isset($_POST['update_buyer'])) {
    $id = intval($_POST['id']);
    $full_name = htmlspecialchars($_POST['full_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $role = htmlspecialchars($_POST['role']);
    $buyer_code = htmlspecialchars($_POST['buyer_code']);

    $stmt = $conn->prepare("UPDATE buyers SET full_name=?, email=?, phone=?, role=?, buyer_code=? WHERE id=?");
    $stmt->bind_param("sssssi", $full_name, $email, $phone, $role, $buyer_code, $id);
    $stmt->execute();
    header("Location: admin_buyers.php?page=$page");
    exit;
}

// ✅ Fetch with Pagination
$result = $conn->query("SELECT * FROM buyers ORDER BY created_at DESC LIMIT $limit OFFSET $offset");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buyer Management | HarvestFi</title>
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
            <h1 class="text-white text-2xl font-bold italic">HarvestFi<span class="text-indigo-500">.</span></h1>
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
            <a href="admin_buyers.php" class="flex items-center p-3 text-white bg-indigo-600 rounded-lg shadow-lg">
                <i data-lucide="shopping-cart" class="w-5 h-5 mr-3"></i> Buyers
            </a>
            <a href="admin_loans.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="landmark" class="w-5 h-5 mr-3"></i> Loans
            </a>
            <a href="admin_payments.php" class="flex items-center p-3 rounded-lg hover:bg-slate-800 transition">
                <i data-lucide="credit-card" class="w-5 h-5 mr-3"></i> Payments
            </a>
        </nav>
    </aside>

    <main class="flex-1 p-4 md:p-8 w-full">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center">
                    <button id="open-sidebar" class="mr-4 md:hidden text-slate-600 bg-white p-2 rounded-lg border border-slate-200 shadow-sm">
                        <i data-lucide="menu"></i>
                    </button>
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold text-slate-800">Buyer Directory</h2>
                        <p class="text-slate-500 mt-1 text-sm">Manage wholesale partners and procurement agents.</p>
                    </div>
                </div>
                <div class="hidden sm:block">
                    <span class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl font-semibold border border-indigo-100 text-sm">
                        Total: <?= $total_res ?> Buyers
                    </span>
                </div>
            </div>

            <?php if($edit_buyer): ?>
            <div class="bg-white border-2 border-indigo-100 p-6 md:p-8 rounded-2xl shadow-xl mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-500"></div>
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center">
                        <i data-lucide="user-cog" class="w-5 h-5 mr-2 text-indigo-600"></i> Modify Account: <?= htmlspecialchars($edit_buyer['full_name']) ?>
                    </h3>
                    <a href="admin_buyers.php" class="bg-slate-100 p-1.5 rounded-lg text-slate-400 hover:text-slate-600 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </a>
                </div>
                <form method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <input type="hidden" name="id" value="<?= $edit_buyer['id'] ?>">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Full Name</label>
                        <input type="text" name="full_name" value="<?= htmlspecialchars($edit_buyer['full_name']) ?>" class="w-full border border-slate-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Email Address</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($edit_buyer['email']) ?>" class="w-full border border-slate-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Access Level</label>
                        <div class="relative">
                            <select name="role" class="w-full border border-slate-200 px-4 py-2.5 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none appearance-none bg-white transition cursor-pointer">
                                <option value="buyer" <?= $edit_buyer['role']=='buyer'?'selected':'' ?>>Standard Buyer</option>
                                <option value="admin" <?= $edit_buyer['role']=='admin'?'selected':'' ?>>System Admin</option>
                            </select>
                            <i data-lucide="chevron-down" class="absolute right-3 top-3 w-4 h-4 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Phone</label>
                        <input type="text" name="phone" value="<?= htmlspecialchars($edit_buyer['phone']) ?>" class="w-full border border-slate-200 px-4 py-2.5 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Unique Buyer Code</label>
                        <input type="text" name="buyer_code" value="<?= htmlspecialchars($edit_buyer['buyer_code']) ?>" class="w-full border border-slate-200 px-4 py-2.5 rounded-xl font-mono text-indigo-600 bg-slate-50">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" name="update_buyer" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl transition shadow-lg shadow-indigo-200 flex items-center justify-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[900px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Identity</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Contact</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Identifier</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Privileges</th>
                                <th class="py-4 px-6 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while($buyer = $result->fetch_assoc()): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-4 shrink-0">
                                            <?= strtoupper(substr($buyer['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900"><?= htmlspecialchars($buyer['full_name']) ?></p>
                                            <p class="text-xs text-slate-400 italic">Joined <?= date('M Y', strtotime($buyer['created_at'])) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-600">
                                    <div class="flex items-center"><i data-lucide="mail" class="w-3.5 h-3.5 mr-2 opacity-50"></i> <?= htmlspecialchars($buyer['email']) ?></div>
                                    <div class="flex items-center mt-1"><i data-lucide="phone" class="w-3.5 h-3.5 mr-2 opacity-50"></i> <?= htmlspecialchars($buyer['phone']) ?></div>
                                </td>
                                <td class="py-4 px-6">
                                    <code class="text-xs bg-indigo-50 px-2 py-1 rounded text-indigo-700 font-bold border border-indigo-100">
                                        <?= htmlspecialchars($buyer['buyer_code']) ?>
                                    </code>
                                </td>
                                <td class="py-4 px-6">
                                    <?php if($buyer['role'] == 'admin'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 ring-1 ring-inset ring-purple-600/20">
                                            Administrator
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700 ring-1 ring-inset ring-slate-600/10">
                                            Buyer Account
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <a href="admin_buyers.php?edit_id=<?= $buyer['id'] ?>&page=<?= $page ?>" class="p-2 bg-slate-50 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
                                            <i data-lucide="pencil-line" class="w-4 h-4"></i>
                                        </a>
                                        <a href="admin_buyers.php?delete_id=<?= $buyer['id'] ?>&page=<?= $page ?>" onclick="return confirm('Confirm buyer removal?');" class="p-2 bg-slate-50 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-slate-500 order-2 sm:order-1">Showing entries <span class="font-semibold"><?= $offset+1 ?></span> - <span class="font-semibold"><?= min($offset + $limit, $total_res) ?></span></p>
                    <div class="flex space-x-1 order-1 sm:order-2">
                        <?php for($i=1; $i<=$total_pages; $i++): ?>
                            <a href="?page=<?= $i ?>" class="px-3.5 py-2 rounded-xl text-sm font-bold transition <?= $i==$page ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
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