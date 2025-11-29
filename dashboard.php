<?php
session_start();

// Database connection configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Verify if the user is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

// Fetch statistics for the dashboard
$requests_query = "SELECT COUNT(*) as total_requests FROM maintenance_requests";
$requests_result = $conn->query($requests_query);
$requests_count = $requests_result->fetch_assoc()['total_requests'];

$pending_requests_query = "SELECT COUNT(*) as pending_requests FROM maintenance_requests WHERE status = 'pending'";
$pending_requests_result = $conn->query($pending_requests_query);
$pending_requests_count = $pending_requests_result->fetch_assoc()['pending_requests'];

$tenants_query = "SELECT COUNT(*) as total_tenants FROM rental_applications";
$tenants_result = $conn->query($tenants_query);
$tenants_count = $tenants_result->fetch_assoc()['total_tenants'];

$properties_query = "SELECT COUNT(*) as total_properties FROM properties";
$properties_result = $conn->query($properties_query);
$properties_count = $properties_result->fetch_assoc()['total_properties'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(to right, #42a5f5, #478ed1);
            font-family: 'Poppins', sans-serif;
            color: #fff;
        }

        h2 {
            margin-top: 20px;
            font-weight: bold;
            color: #fff;
            text-align: center;
            position: relative;
        }

        h2::after {
            content: "";
            width: 80px;
            height: 4px;
            background: #fff;
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 5px;
        }

        .card {
            margin-bottom: 30px;
        }

        .card-header {
            background-color: #ff6f61;
            color: white;
            font-weight: bold;
        }

        .card-body {
            font-size: 1.2rem;
        }

        .icon {
            font-size: 3rem;
        }

        .back-btn {
            text-align: center;
            margin-top: 40px;
        }

        .btn-secondary {
            background-color: #757575;
            border-color: #616161;
        }

        .btn-secondary:hover {
            background-color: #616161;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

    <div class="row">
        <!-- Total Maintenance Requests -->
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-header">
                    <i class="fas fa-tools icon"></i> Total Maintenance Requests
                </div>
                <div class="card-body">
                    <h5><?= $requests_count ?> Requests</h5>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-header">
                    <i class="fas fa-hourglass-half icon"></i> Pending Requests
                </div>
                <div class="card-body">
                    <h5><?= $pending_requests_count ?> Pending</h5>
                </div>
            </div>
        </div>

        <!-- Total Tenants -->
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-header">
                    <i class="fas fa-users icon"></i> Total Tenants
                </div>
                <div class="card-body">
                    <h5><?= $tenants_count ?> Tenants</h5>
                </div>
            </div>
        </div>

        <!-- Total Properties -->
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-header">
                    <i class="fas fa-home icon"></i> Total Properties
                </div>
                <div class="card-body">
                    <h5><?= $properties_count ?> Properties</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="back-btn">
        <a href="admin_dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

</body>
</html>
