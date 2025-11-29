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

$pending_requests_query = "SELECT COUNT(*) as pending_requests FROM rental_applications WHERE status = 'pending'";
$pending_requests_result = $conn->query($pending_requests_query);
$pending_requests_count = $pending_requests_result->fetch_assoc()['pending_requests'];

$tenants_query = "SELECT COUNT(*) as total_tenants FROM rental_applications";
$tenants_result = $conn->query($tenants_query);
$tenants_count = $tenants_result->fetch_assoc()['total_tenants'];

$properties_query = "SELECT COUNT(*) as total_properties FROM properties";
$properties_result = $conn->query($properties_query);
$properties_count = $properties_result->fetch_assoc()['total_properties'];

// Fetch total payments
$total_payments_query = "SELECT SUM(amount) as total_payments FROM payments";
$total_payments_result = $conn->query($total_payments_query);
$total_payments = $total_payments_result->fetch_assoc()['total_payments'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('https://static.vecteezy.com/system/resources/thumbnails/022/756/628/small/3d-rendering-of-modern-cozy-house-with-garage-for-sale-or-rent-with-beautiful-landscaping-on-background-real-estate-concept-ai-generated-artwork-photo.jpg') no-repeat center center fixed;
            background-size: cover;
            overflow-x: hidden;
            color: #fff;
        }
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            height: 100vh;
            padding: 20px;
            z-index: 1000;
            transition: 0.3s ease;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            margin: 20px 0;
            display: flex;
            align-items: center;
            font-size: 1.1rem;
            transition: 0.3s ease;
            padding-left: 20px;
        }
        .sidebar a i {
            margin-right: 10px;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        .sidebar a:hover {
            text-decoration: underline;
            background-color: #34495e;
            border-radius: 5px;
            padding-left: 15px;
        }
        .sidebar a:hover i {
            transform: rotate(15deg);
            color: #f39c12;
        }
        .sidebar h3, .sidebar h5 {
            text-align: center;
        }
        .dashboard-content {
            margin-left: 270px;
            padding: 30px;
            transition: 0.3s ease;
        }
        .card {
            margin-bottom: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .icon {
            font-size: 2.5rem;
        }
        .card-body {
            text-align: center;
            padding: 30px;
        }
        .card-body h5 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .card-body p {
            font-size: 1.2rem;
        }
        .card.bg-info {
            background-color: #3498db !important;
        }
        .card.bg-warning {
            background-color: #f39c12 !important;
        }
        .card.bg-success {
            background-color: #2ecc71 !important;
        }
        .card.bg-danger {
            background-color: #e74c3c !important;
        }
        
        /* Responsive layout for mobile devices */
        @media (max-width: 768px) {
            .dashboard-content {
                margin-left: 0;
            }
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .sidebar a {
                margin: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>OPTIMA VIEW</h3>
        <h5>Fasya</h5>
        <a href="admin_dashboard.php">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="list_properties.php">
            <i class="fas fa-building"></i> Properties
        </a>
        <a href="list_tenant.php">
            <i class="fas fa-users"></i> Tenants
        </a>
        <a href="admin_payment_status.php">
            <i class="fas fa-file-invoice-dollar"></i> Invoices
        </a>
        <a href="admin_rental_application.php">
            <i class="fas fa-file-signature"></i> Lease Agreement
        </a>
        <a href="admin_view_appointment.php">
            <i class="fas fa-calendar-check"></i> View Appointment
        </a>
        <a href="view_maintenance_request.php">
            <i class="fas fa-tools"></i> Maintenance
        </a>

        <!-- Logout Form -->
        <form action="../logout.php" method="POST" class="mt-4">
            <button type="submit" class="btn btn-danger w-100">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>

    <div class="dashboard-content">
        <h1 class="mb-4 text-center">Admin Dashboard</h1>

        <div class="row">
            <!-- Total Payments -->
            <div class="col-md-3 col-sm-6">
                <a href="admin_payment_status.php">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <h5><i class="fas fa-credit-card icon"></i> Total Payments</h5>
                            <p>RM <?= number_format($total_payments, 2) ?></p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Pending Requests -->
            <div class="col-md-3 col-sm-6">
                <a href="admin_rental_application.php">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h5><i class="fas fa-hourglass-half icon"></i> Pending Rental Applications</h5>
                            <p><?= $pending_requests_count ?> Pending</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Tenants -->
            <div class="col-md-3 col-sm-6">
                <a href="list_tenant.php">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h5><i class="fas fa-users icon"></i> Total Tenants</h5>
                            <p><?= $tenants_count ?> Tenants</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Properties -->
            <div class="col-md-3 col-sm-6">
                <a href="list_properties.php">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h5><i class="fas fa-home icon"></i> Total Properties</h5>
                            <p><?= $properties_count ?> Properties</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
