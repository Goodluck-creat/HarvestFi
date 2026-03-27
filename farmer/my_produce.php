<?php
session_start();
include "db.php";
error_reporting(E_ALL);
ini_set('display_errors', 0); // hide from users
ini_set('log_errors', 1);
ini_set('error_log', 'error_log.txt');

// Replace with session ID in production
$farmer_id = $_SESSION['farmer_id'] ?? 1; // Example, replace with actual session

// --- 1. HANDLE DELETE ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM produce WHERE id = ? AND farmer_id = ?");
    $stmt->bind_param("ii", $id, $farmer_id);
    $stmt->execute();
    header("Location: my_produce.php?msg=deleted");
    exit();
}

// --- 2. HANDLE MULTI-IMAGE UPLOAD ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_produce'])) {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $price = $_POST['price'];
    $qty = $_POST['quantity'];

    $uploaded_images = [];
    $target_dir = "uploads/produce/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);

    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['name'] as $key => $val) {
            $ext = pathinfo($_FILES["images"]["name"][$key], PATHINFO_EXTENSION);
            $file_name = uniqid('prod_', true) . "." . $ext;
            $target_path = $target_dir . $file_name;

            if (move_uploaded_file($_FILES["images"]["tmp_name"][$key], $target_path)) {
                $uploaded_images[] = $target_path;
            }
        }
    }

    $image_json = json_encode($uploaded_images);
    $stmt = $conn->prepare("INSERT INTO produce (farmer_id, name, description, quantity, price, image, status) VALUES (?, ?, ?, ?, ?, ?, 'available')");
    $stmt->bind_param("isssds", $farmer_id, $name, $desc, $qty, $price, $image_json);
    $stmt->execute();

    header("Location: my_produce.php?msg=success");
    exit();
}

$result = $conn->query("SELECT * FROM produce WHERE farmer_id = $farmer_id ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Produce | HarvestFi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
:root { --harvest-green: #2d9447; }
body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
.sidebar { height: 100vh; width: 250px; position: fixed; background: var(--harvest-green); color: white; padding: 20px; z-index: 1000; }
.sidebar a { display: block; color: white; padding: 12px; text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: 0.3s; }
.sidebar a:hover, .sidebar a.active { background: rgba(255,255,255,0.2); }
.main-content { margin-left: 250px; padding: 40px; }

.produce-card { border: none; border-radius: 20px; background: white; transition: 0.3s; box-shadow: 0 10px 30px rgba(0,0,0,0.05); overflow: hidden; }
.card-img-container { height: 220px; position: relative; background: #eee; }
.card-img-container img { height: 220px; object-fit: cover; width: 100%; border-top-left-radius: 20px; border-top-right-radius: 20px; }
.img-badge { position: absolute; top: 15px; right: 15px; z-index: 10; background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); color: white; padding: 5px 12px; border-radius: 50px; font-size: 11px; font-weight: 600; pointer-events: none; }

@media (max-width: 768px) { .sidebar { display: none; } .main-content { margin-left: 0; padding: 20px; } }
</style>
</head>
<body>

<div class="sidebar">
    <h3 class="fw-bold mb-4"><i class="fa-solid fa-leaf me-2"></i>HarvestFi</h3>
    <nav>
        <a href="dashboard.php"><i class="fa-solid fa-chart-line me-2"></i> Dashboard</a>
        <a href="apply_loan.php"><i class="fa-solid fa-hand-holding-dollar me-2"></i> Apply Loan</a>
        <a href="my_produce.php" class="active"><i class="fa-solid fa-basket-shopping me-2"></i> My Produce</a>
    </nav>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold mb-1">My Produce Listing</h2>
            <p class="text-muted small">Manage and track your listed farm products.</p>
        </div>
        <button class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
            <i class="fa-solid fa-plus me-2"></i>List New Item
        </button>
    </div>

    <div class="row g-4">
        <?php while($row = $result->fetch_assoc()): 
            $images = json_decode($row['image'], true);
            if (!is_array($images)) $images = [];
            $cover_image = !empty($images[0]) ? $images[0] : "https://via.placeholder.com/400x220?text=No+Image+Available";
            $imageCount = count($images);
        ?>
        <div class="col-md-4">
            <div class="card produce-card h-100">
                <div class="card-img-container">
                    <?php if($imageCount > 1): ?>
                        <div class="img-badge">
                            <i class="fa-solid fa-images me-1"></i> +<?php echo $imageCount - 1; ?> more
                        </div>
                    <?php endif; ?>
                    <img src="<?php echo htmlspecialchars($cover_image); ?>" alt="Produce Cover">
                </div>

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-capitalize mb-0"><?php echo $row['name']; ?></h5>
                        <span class="text-success fw-bold">₦<?php echo number_format($row['price'], 2); ?></span>
                    </div>
                    <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;"><?php echo htmlspecialchars($row['description']); ?></p>
                    
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="small">
                            <span class="text-muted">Available Stock:</span><br>
                            <span class="fw-bold text-dark"><?php echo $row['quantity']; ?></span>
                        </div>
                        <a href="my_produce.php?delete=<?php echo $row['id']; ?>" 
                           class="btn btn-sm btn-outline-danger border-0 fw-bold" 
                           onclick="return confirm('Delete this listing permanently?')">
                           <i class="fa-solid fa-trash-can me-1"></i> Remove
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header border-0 p-4 pb-0">
                <h5 class="fw-bold">List New Produce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="my_produce.php" method="POST" enctype="multipart/form-data" class="p-4">
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold uppercase">Produce Name</label>
                    <input type="text" name="name" class="form-control bg-light border-0 py-2" placeholder="e.g. Yellow Garri" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold uppercase">Description</label>
                    <textarea name="description" class="form-control bg-light border-0" placeholder="Details about quality, weight, etc." rows="3"></textarea>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label text-muted small fw-bold uppercase">Price (₦)</label>
                        <input type="number" name="price" class="form-control bg-light border-0 py-2" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label text-muted small fw-bold uppercase">Quantity</label>
                        <input type="text" name="quantity" class="form-control bg-light border-0 py-2" placeholder="e.g. 50 Bags" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold uppercase">Product Images</label>
                    <input type="file" name="images[]" class="form-control border-0 bg-light" multiple required>
                    <div class="form-text text-xs italic">You can select multiple photos at once.</div>
                </div>
                <button type="submit" name="add_produce" class="btn btn-success w-100 py-3 rounded-pill fw-bold shadow">
                    Publish to Marketplace
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>