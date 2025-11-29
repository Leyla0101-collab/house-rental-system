<?php
session_start();

// Pastikan tenant sudah log masuk
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
    header('Location: login.php'); // Redirect ke halaman login jika tidak log masuk
    exit();
}

$tenant_name = $_SESSION['name']; // Ambil nama tenant dari sesi
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://static.vecteezy.com/system/resources/thumbnails/022/903/410/small_2x/ai-generative-3d-modern-luxury-real-estate-house-for-sale-and-rent-luxury-property-concept-ai-generated-artwork-photo.jpg');
            background-size: cover;
            background-position: center;
            color: white;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            position: fixed;
            height: 100vh;
            padding: 30px 20px;
            transition: all 0.3s;
        }
        .sidebar h3 {
            font-size: 26px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 30px;
            color: #FFD700;
        }
        .sidebar h5 {
            margin-bottom: 20px;
            font-size: 18px;
            font-weight: normal;
            text-align: center;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin: 15px 0;
            display: flex;
            align-items: center;
            font-size: 16px;
            padding: 12px;
            border-radius: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 18px;
        }
        .sidebar a:hover {
            background-color: #006400;
            transform: scale(1.05);
            animation: bounce 1s ease-in-out;
        }

        /* Animation for icons on hover */
        @keyframes bounce {
            0%, 20%, 40%, 60%, 80%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
            50% {
                transform: translateY(-5px);
            }
            70% {
                transform: translateY(-3px);
            }
            90% {
                transform: translateY(-2px);
            }
        }

        .dashboard-content {
            margin-left: 270px;
            padding: 40px;
            transition: all 0.3s;
        }
        .dashboard-content h1 {
            font-size: 38px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #FFD700;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.5);
        }
        .card {
            margin-bottom: 20px;
            border-radius: 15px;
            background: rgba(0, 0, 0, 0.6);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }
        .card-body {
            padding: 30px;
            color: white;
            text-align: center;
        }
        .card-title {
            font-size: 22px;
            font-weight: bold;
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 10px 30px;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar animate__animated animate__fadeIn">
        <h3>Tenant Dashboard</h3>
        <h5>Welcome, <?= htmlspecialchars($tenant_name) ?></h5>
        <a href="available_properties.php"><i class="fas fa-home"></i> Search Properties</a>
        <a href="rental_application.php"><i class="fas fa-file-alt"></i> Rental Applications</a>
        <a href="tenant_view_application_status.php"><i class="fas fa-clipboard-check"></i> Application Status</a>
        <a href="monthly_rentals.php"><i class="fas fa-wallet"></i> Payments</a>
        <a href="maintenance_request.php"><i class="fas fa-tools"></i> Maintenance Requests</a>
        <a href="tenant_maintenance_status.php"><i class="fas fa-info-circle"></i> Maintenance Status</a>
        <form action="../logout.php" method="POST" class="mt-4">
            <button type="submit" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="dashboard-content animate__animated animate__fadeIn">
        <h1>Welcome to Your Dashboard</h1>

        <!-- Section: Property Search -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-search"></i> Search Properties</h5>
                <p>Find your perfect home by searching available properties.</p>
                <a href="available_properties.php" class="btn btn-custom">Search Now</a>
            </div>
        </div>

        <!-- Section: Rental Application Status -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-clipboard-check"></i> Rental Application Status</h5>
                <p>Check the status of your rental applications.</p>
                <a href="tenant_view_application_status.php" class="btn btn-custom">Check Status</a>
            </div>
        </div>

        <!-- Section: Monthly Rentals -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-wallet"></i> Payments</h5>
                <p>View your monthly payments.</p>
                <a href="monthly_rentals.php" class="btn btn-custom">View Payments</a>
            </div>
        </div>

        <!-- Section: Maintenance Requests -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-tools"></i> Maintenance Requests</h5>
                <p>View the status of your maintenance requests.</p>
                <a href="tenant_maintenance_status.php" class="btn btn-custom">View Status</a>
            </div>
        </div>
    </div>

</body>
</html>
