<?php
require_once 'config/db.php';

$message = '';

// Proses borang pendaftaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password']; // Kata laluan tidak di-hash
    $role = 'tenant';

    // Periksa sama ada email telah digunakan
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message = "Email has been registered!";
    } else {
        // Masukkan data pengguna baru ke dalam database
        $query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $name, $email, $password, $role);

        if ($stmt->execute()) {
            $message = "Registration Successful! You can log in now.";
        } else {
            $message = "There is an error. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/755/745/small_2x/3d-rendering-of-modern-cozy-house-with-garage-for-sale-or-rent-with-beautiful-landscaping-on-background-real-estate-concept-ai-generated-artwork-photo.jpg'); /* Gambar latar belakang */
            background-size: cover;
            background-position: center;
            color: white;
        }
        .form-container {
            background: rgba(15, 15, 15, 0.8); /* Latar belakang separa lut sinar */
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: 50px auto;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">User Register</h2>
    <?php if (!empty($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>
    <div class="form-container">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="text-center mt-3">Already Have Account? <a href="login.php">Login</a></p>
    </div>
</div>
</body>
</html>
