<?php
session_start();

// Sambungan ke pangkalan data
$host = 'localhost'; // Tukar kepada hos pangkalan data anda
$user = 'root'; // Tukar kepada nama pengguna pangkalan data anda
$password = ''; // Tukar kepada kata laluan pangkalan data anda
$dbname = 'house_rental_system'; // Tukar kepada nama pangkalan data anda

$conn = new mysqli($host, $user, $password, $dbname);

// Semak sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pemprosesan borang Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        echo "<script>alert('Login successful! Welcome, " . $user['name'] . "');</script>";
    } else {
        echo "<script>alert('Invalid email or password!');</script>";
    }
}

// Pemprosesan borang Register
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful! You can now log in.');</script>";
    } else {
        echo "<script>alert('Registration failed. Email may already be used.');</script>";
    }
}

// Hasil carian property
$location = $_GET['location'] ?? '';
$minBudget = $_GET['minBudget'] ?? 0;
$maxBudget = $_GET['maxBudget'] ?? PHP_INT_MAX;

$sql = "SELECT * FROM properties WHERE location LIKE ? AND price BETWEEN ? AND ?";
$stmt = $conn->prepare($sql);
$likeLocation = "%$location%";
$stmt->bind_param('sdd', $likeLocation, $minBudget, $maxBudget);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Property</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/755/745/small_2x/3d-rendering-of-modern-cozy-house-with-garage-for-sale-or-rent-with-beautiful-landscaping-on-background-real-estate-concept-ai-generated-artwork-photo.jpg');
            background-size: cover;
            background-position: center;
            color: rgb(0, 0, 0);
        }
        .navbar {
            background-color: rgba(0, 0, 0, 0.8);
        }
        .navbar-brand {
            color: rgb(255, 255, 255);
        }
        .navbar-brand:hover {
            color: rgb(200, 200, 200);
        }
        .form-container {
            background: rgba(250, 250, 250, 0.9);
            padding: 20px;
            border-radius: 10px;
            max-width: 600px;
            margin: 50px auto;
        }
        footer {
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            color: #fff;
            text-align: center;
        }
        /* Gaya butang */
        .btn-primary {
            background-color: #4CAF50;
            border-color: #45A049;
            color: white;
            font-weight: bold;
            padding: 10px 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-primary:hover {
            background-color: #45A049;
            border-color: #3E8E41;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
        .btn-primary i {
            margin-right: 8px;
            animation: slide-left 1s infinite;
        }
        @keyframes slide-left {
            0% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(-5px);
            }
            100% {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="#">Optima View</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
        </ul>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="accountDropdown" role="button" data-toggle="dropdown">Account</a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="login.php">Login</a>
                    <a class="dropdown-item" href="register.php">Register</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<!-- Search Results -->
<div class="form-container">
    <h2 class="text-center text-dark">Search Results</h2>
    <div class="row">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='col-md-4'>";
                echo "<div class='card mb-4'>";
                echo "<img src='" . htmlspecialchars($row['image']) . "' class='card-img-top' alt='Property Image'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($row['name']) . "</h5>";
                echo "<p class='card-text'>Location: " . htmlspecialchars($row['location']) . "</p>";
                echo "<p class='card-text'>Price: RM " . number_format($row['price'], 2) . "</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='text-center'>No properties found.</p>";
        }
        ?>
        <!-- Button to go back to Home -->
        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary btn-lg shadow rounded-pill back-to-home-btn">
                <i class="bi bi-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>
</div>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Optima View | About Us | Contact Us</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
