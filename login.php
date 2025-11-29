<?php
session_start();
require_once 'config/db.php';

$message = '';

// Proses borang login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Semak email dalam database
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Semak kata laluan secara langsung
        if ($password === $user['password']) {
            // Tetapkan sesi
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['name'] = $user['name'];

            // Halakan pengguna berdasarkan peranan
            if ($user['role'] === 'admin') {
                header('Location: dashboard/admin_dashboard.php');
            } else {
                header('Location: dashboard/tenant_dashboard.php');
            }
            exit;
        } else {
            $message = "Invalid Password!";
        }
    } else {
        $message = "User Not Found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Masuk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/755/745/small_2x/3d-rendering-of-modern-cozy-house-with-garage-for-sale-or-rent-with-beautiful-landscaping-on-background-real-estate-concept-ai-generated-artwork-photo.jpg');
            background-size: cover;
            background-position: center;
            color: white;
        }
        .form-container {
            background: rgba(29, 28, 28, 0.8);
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Login</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif; ?>
    <div class="form-container">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <p class="text-center mt-3">Do Not Have An Account? <a href="register.php">Register Now</a></p>
    </div>
</div>
</body>
</html>
