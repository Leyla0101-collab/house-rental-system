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

// Process notice notifications
if (isset($_GET['notice_id'])) {
    $notice_id = $_GET['notice_id'];

    // Update request status to 'noticed'
    $update_query = "UPDATE maintenance_requests SET status = 'noticed' WHERE id = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param('i', $notice_id);

    if ($update_stmt->execute()) {
        // Get tenant's user_id
        $tenant_query = "SELECT user_id FROM maintenance_requests WHERE id = ?";
        $tenant_stmt = $conn->prepare($tenant_query);
        $tenant_stmt->bind_param('i', $notice_id);
        $tenant_stmt->execute();
        $tenant_result = $tenant_stmt->get_result();
        $tenant_data = $tenant_result->fetch_assoc();
        $tenant_user_id = $tenant_data['user_id'];

        // Add notification for tenant
        $notification_query = "INSERT INTO notifications (user_id, message) 
                               VALUES (?, 'Your maintenance request has been noticed.')";
        $notif_stmt = $conn->prepare($notification_query);
        $notif_stmt->bind_param('i', $tenant_user_id);
        $notif_stmt->execute();

        // Redirect to the same page with success message
        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit();
    } else {
        // Redirect to the same page with error message
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=1");
        exit();
    }
}

// Retrieve all maintenance requests for admin
$admin_query = "SELECT mr.id, p.name AS property_name, ra.tenant_name, ra.phone_number, mr.description, mr.status 
                FROM maintenance_requests mr
                JOIN rental_applications ra ON mr.user_id = ra.user_id
                JOIN properties p ON mr.property_id = p.id";
$admin_result = $conn->query($admin_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Maintenance Requests</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            background: linear-gradient(to right, #ff7e5f, #feb47b);
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

        table {
            background-color: rgba(255, 255, 255, 0.8);
            color: #333;
            text-align: center;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            vertical-align: middle;
        }

        th {
            background-color: #ff6f61;
            color: #fff;
            text-align: center;
        }

        .badge {
            font-size: 0.9rem;
            padding: 0.5em;
            border-radius: 10px;
        }

        .btn-primary {
            transition: transform 0.3s, background 0.3s;
        }

        .btn-primary:hover {
            transform: scale(1.05);
            background-color: #ff4b3e !important;
        }

        .animate-fade {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5 animate-fade">
    <h2><i class="fas fa-tools"></i> Maintenance Requests List</h2>

    <!-- Display messages -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success mt-3 animate__animated animate__fadeInDown">
            <i class="fas fa-check-circle"></i> Maintenance notice sent successfully and status updated!
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger mt-3 animate__animated animate__shakeX">
            <i class="fas fa-exclamation-circle"></i> Failed to send notice or update status.
        </div>
    <?php endif; ?>

    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>#</th>
                <th><i class="fas fa-user"></i> Tenant Name</th>
                <th><i class="fas fa-home"></i> Property</th>
                <th><i class="fas fa-file-alt"></i> Description</th>
                <th><i class="fas fa-flag"></i> Status</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $admin_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                    <td><?= htmlspecialchars($row['property_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'noticed'): ?>
                            <span class="badge bg-success"><i class="fas fa-check"></i> Noticed</span>
                        <?php else: ?>
                            <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] !== 'noticed'): ?>
                            <a href="?notice_id=<?= $row['id'] ?>" class="btn btn-primary">
                                <i class="fas fa-bell"></i> Notice
                            </a>
                        <?php else: ?>
                            <span class="text-muted"><i class="fas fa-info-circle"></i> Already Noticed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="back-btn">
        <a href="admin_dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
</body>
</html>
