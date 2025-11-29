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

// Check if tenant is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'tenant') {
    header('Location: login.php');
    exit();
}

$tenant_id = $_SESSION['user_id'];

// Fetch tenant's maintenance requests
$query = "SELECT mr.id, p.name AS property_name, mr.description, mr.status, mr.created_at 
          FROM maintenance_requests mr
          JOIN properties p ON mr.property_id = p.id
          WHERE mr.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $tenant_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant - Maintenance Request Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('https://i0.wp.com/valleypm.com/wp-content/uploads/2022/03/maintenance.jpg?fit=1188%2C531&ssl=1') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            margin-top: 40px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            margin-top: 20px;
            background-color: #fff;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        table thead th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            padding: 10px;
        }
        table tbody tr {
            border-bottom: 1px solid #ddd;
        }
        table tbody tr:hover {
            background-color: #f1f1f1;
        }
        table td {
            padding: 15px;
            font-size: 14px;
        }
        table .badge {
            font-size: 13px;
        }
        .btn-back {
            margin-top: 20px;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-tools"></i> Maintenance Request Status</h2>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Property</th>
                <th>Description</th>
                <th>Status</th>
                <th>Request Date</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['property_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <?php if ($row['status'] === 'noticed'): ?>
                            <span class="badge bg-success"><i class="fas fa-check-circle"></i> Noticed</span>
                        <?php elseif ($row['status'] === 'in_progress'): ?>
                            <span class="badge bg-info"><i class="fas fa-spinner fa-spin"></i> In Progress</span>
                        <?php elseif ($row['status'] === 'resolved'): ?>
                            <span class="badge bg-secondary"><i class="fas fa-check"></i> Resolved</span>
                        <?php else: ?>
                            <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <div class="text-center">
        <a href="tenant_dashboard.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
</div>
</body>
</html>
