<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HarvestFi Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
    <style>
        body {
            background: #f4f6f9;
        }
        .login-card {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: #fff;
        }
        .nav-tabs .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <h2 class="text-primary">HarvestFi</h2>
        <p class="text-muted">Login to your account</p>
    </div>

    <!-- User Type Tabs -->
    <ul class="nav nav-tabs mb-4" id="loginTab" role="tablist">
        <li class="nav-item w-50" role="presentation">
            <button class="nav-link active w-100" id="farmer-tab" data-bs-toggle="tab" data-bs-target="#farmer" type="button" role="tab">Farmer</button>
        </li>
        <li class="nav-item w-50" role="presentation">
            <button class="nav-link w-100" id="buyer-tab" data-bs-toggle="tab" data-bs-target="#buyer" type="button" role="tab">Buyer</button>
        </li>
    </ul>

    <div class="tab-content" id="loginTabContent">
        <!-- Farmer Login -->
        <div class="tab-pane fade show active" id="farmer" role="tabpanel">
            <form action="farmer_login.php" method="POST">
                <div class="mb-3">
                    <label for="farmerEmail" class="form-label">Email or Phone</label>
                    <input type="text" class="form-control" id="farmerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="farmerPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="farmerPassword" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="farmerRemember" name="remember">
                    <label class="form-check-label" for="farmerRemember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <div class="text-center mt-3">
                    <a href="#">Forgot Password?</a> | <a href="#">Sign Up</a>
                </div>
            </form>
        </div>

        <!-- Buyer Login -->
        <div class="tab-pane fade" id="buyer" role="tabpanel">
            <form action="../buyer/buyer_login.php" method="POST">
                <div class="mb-3">
                    <label for="buyerEmail" class="form-label">Email or Phone</label>
                    <input type="text" class="form-control" id="buyerEmail" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="buyerPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="buyerPassword" name="password" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="buyerRemember" name="remember">
                    <label class="form-check-label" for="buyerRemember">Remember me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
                <div class="text-center mt-3">
                    <a href="#">Forgot Password?</a> | <a href="#">Sign Up</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>