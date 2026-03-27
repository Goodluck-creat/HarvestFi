<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HarvestFi Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            background: #f4f6f9;
        }
        .register-card {
            max-width: 450px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .nav-tabs .nav-link.active {
            background-color: rgb(52,173,84);
            color: #fff;
            border-radius: 5px;
        }
        .btn-register {
            background: rgb(52,173,84);
            border: none;
        }
        .btn-register:hover {
            background: rgb(255,153,51);
        }
    </style>
</head>
<body>

<div class="register-card">
    <div class="text-center mb-4">
        <h2 style="color: rgb(52,173,84);">HarvestFi</h2>
        <p class="text-muted">Create a new account</p>
    </div>

    <!-- User Type Tabs -->
    <ul class="nav nav-tabs mb-4" id="registerTab" role="tablist">
        <li class="nav-item w-50" role="presentation">
            <button class="nav-link active w-100" id="farmer-tab" data-bs-toggle="tab" data-bs-target="#farmer" type="button" role="tab">Farmer</button>
        </li>
        <li class="nav-item w-50" role="presentation">
            <button class="nav-link w-100" id="buyer-tab" data-bs-toggle="tab" data-bs-target="#buyer" type="button" role="tab">Buyer</button>
        </li>
    </ul>

    <div class="tab-content" id="registerTabContent">

        <!-- Farmer Registration -->
        <div class="tab-pane fade show active" id="farmer" role="tabpanel">
            <form action="farmer_register.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="farmerName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="farmerName" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="farmerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="farmerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="farmerPhone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="farmerPhone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="farmerAddress" class="form-label">Farm Location</label>
                    <input type="text" class="form-control" id="farmerAddress" name="address" required>
                </div>
                <div class="mb-3">
                    <label for="farmerDocs" class="form-label">Upload Identification / Farm Proof</label>
                    <input type="file" class="form-control" id="farmerDocs" name="documents" required>
                </div>
                <div class="mb-3">
                    <label for="farmerPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="farmerPassword" name="password" required>
                </div>
                <button type="submit" class="btn btn-register w-100">Register as Farmer</button>
                <div class="text-center mt-3">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>

        <!-- Buyer Registration -->
        <div class="tab-pane fade" id="buyer" role="tabpanel">
            <form action="../buyer/buyer_register.php" method="POST">
                <div class="mb-3">
                    <label for="buyerName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="buyerName" name="full_name" required>
                </div>
                <div class="mb-3">
                    <label for="buyerEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="buyerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="buyerPhone" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="buyerPhone" name="phone" required>
                </div>
                <div class="mb-3">
                    <label for="buyerPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="buyerPassword" name="password" required>
                </div>
                <button type="submit" class="btn btn-register w-100">Register as Buyer</button>
                <div class="text-center mt-3">
                    Already have an account? <a href="login.php">Login</a>
                </div>
            </form>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>