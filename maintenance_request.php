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
    header('Location: login.php'); // Redirect to login page if not logged in
    exit();
}

$tenant_id = $_SESSION['user_id'];

// Retrieve tenant rental and contact information
$query = "SELECT ra.id AS application_id, p.id AS property_id, p.name AS property_name, 
                 ra.tenant_name, ra.phone_number
          FROM rental_applications ra
          JOIN properties p ON ra.property_id = p.id
          WHERE ra.user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $tenant_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No registered properties rented by you.";
    exit();
}

$rental_details = $result->fetch_assoc();
$property_id = $rental_details['property_id'];
$property_name = $rental_details['property_name'];
$tenant_name = $rental_details['tenant_name'];
$tenant_phone = $rental_details['phone_number'];

// Handle maintenance request form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = $_POST['description'];

    if (!empty($description)) {
        // Insert maintenance request with 'pending' status
        $insert_query = "INSERT INTO maintenance_requests (user_id, property_id, description, status) 
                         VALUES (?, ?, ?, 'pending')";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param('iis', $tenant_id, $property_id, $description);

        if ($insert_stmt->execute()) {
            $success_message = "Maintenance request submitted successfully!";
        } else {
            $error_message = "Failed to submit maintenance request.";
        }
    } else {
        $error_message = "Description cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('https://www.westportproperty.co.uk/wp-content/uploads/2021/06/Maintenance-Page-Main-Pic-1200x600.jpg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            overflow-x: hidden;
        }
        .container {
            margin-top: 100px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1.5s ease-in-out;
        }
        .container h2 {
            font-weight: bold;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 10px;
            color: black;
        }
        .card h5 {
            font-weight: bold;
        }
        .btn-primary, .btn-secondary {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
        .btn-secondary:hover {
            background-color: #333333;
            transform: scale(1.05);
        }
        textarea {
            resize: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @keyframes slideInFromLeft {
            from {
                transform: translateX(-100%);
            }
            to {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center">Maintenance Request</h2>

    <!-- Display messages -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>
    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <div class="card p-4">
        <h5>Property: <?= htmlspecialchars($property_name) ?></h5>
        
        <!-- Tenant Information -->
        <p><strong>Tenant Name:</strong> <?= htmlspecialchars($tenant_name) ?></p>
        <p><strong>Phone Number:</strong> <?= htmlspecialchars($tenant_phone) ?></p>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="description" class="form-label">Request Description</label>
                <textarea name="description" id="description" rows="5" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Submit Request</button>
        </form>
        <a href="tenant_dashboard.php" class="btn btn-secondary w-100">Back to Dashboard</a>
    </div>
</div>
</body>
</html>
