<?php
session_start();

// Semak jika sesi pengguna wujud
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Session expired or user not logged in. Please login again.');
        window.location.href='../login.php';
    </script>";
    exit();
}

// Sambungan ke pangkalan data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil ID pembayaran dari URL
$payment_id = isset($_GET['payment_id']) ? $_GET['payment_id'] : '';

if (!$payment_id) {
    echo "<script>
        alert('Invalid request. Please try again.');
        window.location.href='tenant_dashboard.php';
    </script>";
    exit();
}

// Dapatkan butiran pembayaran berdasarkan ID
$query = "
    SELECT 
        p.name AS property_name, 
        pay.amount, 
        pay.payment_date, 
        pay.payment_method 
    FROM payments pay
    JOIN rental_applications ra ON pay.application_id = ra.id
    JOIN properties p ON ra.property_id = p.id
    WHERE pay.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $payment_id);
$stmt->execute();
$result = $stmt->get_result();
$payment_details = $result->fetch_assoc();

if (!$payment_details) {
    echo "<script>
        alert('Payment details not found.');
        window.location.href='tenant_dashboard.php';
    </script>";
    exit();
}

// Formatkan tarikh sahaja (tanpa masa)
$payment_date = date("d-m-Y", strtotime($payment_details['payment_date']));

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to bottom, #6a11cb, #2575fc);
            color: #333;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .receipt-container {
            background-color: #fff;
            border-radius: 15px;
            padding: 30px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            position: relative;
            animation: fadeIn 1s ease-in-out;
        }
        .receipt-header {
            text-align: center;
            color: #2575fc;
            margin-bottom: 20px;
        }
        .receipt-header h2 {
            font-weight: bold;
        }
        .receipt-header i {
            font-size: 40px;
            color: #6a11cb;
        }
        .receipt-body {
            border: 2px dashed #ddd;
            padding: 20px;
            border-radius: 10px;
            background: #f9f9f9;
            margin-bottom: 20px;
        }
        .receipt-body p {
            font-size: 1.1rem;
            margin: 10px 0;
        }
        .receipt-body strong {
            color: #6a11cb;
        }
        .btn-primary {
            background: #2575fc;
            border: none;
            border-radius: 10px;
            transition: background 0.3s, transform 0.3s;
        }
        .btn-primary:hover {
            background: #6a11cb;
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="receipt-header">
            <i class="fas fa-receipt"></i>
            <h2>Payment Receipt</h2>
            <p>Thank you for your payment!</p>
        </div>

        <div class="receipt-body">
            <p><i class="fas fa-building"></i> <strong>Property Name:</strong> <?= htmlspecialchars($payment_details['property_name']) ?></p>
            <p><i class="fas fa-money-bill-wave"></i> <strong>Amount Paid:</strong> RM<?= number_format($payment_details['amount'], 2) ?></p>
            <p><i class="fas fa-calendar-alt"></i> <strong>Payment Date:</strong> <?= htmlspecialchars($payment_date) ?></p>
            <p><i class="fas fa-credit-card"></i> <strong>Payment Method:</strong> <?= htmlspecialchars($payment_details['payment_method']) ?></p>
        </div>

        <div class="text-center">
            <a href="tenant_dashboard.php" class="btn btn-primary mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        </div>
    </div>
</body>
</html>
