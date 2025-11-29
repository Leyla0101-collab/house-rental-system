<?php
session_start();

// Semak jika sesi pengguna wujud
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Session expired or user not logged in. Please login again.');
        window.location.href='../login.php'; // Pastikan laluan ke login.php betul
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

// Ambil ID sewa bulanan daripada URL
$monthly_rental_id = isset($_GET['monthly_rental_id']) ? $_GET['monthly_rental_id'] : '';

if (!$monthly_rental_id) {
    echo "<script>
        alert('Invalid request. Please try again.');
        window.location.href='monthly_rentals.php';
    </script>";
    exit();
}

// Dapatkan butiran sewa bulanan berdasarkan ID
$query = "
    SELECT mr.id, mr.monthly_rent, p.name AS property_name, ra.id AS application_id 
    FROM monthly_rentals mr 
    JOIN properties p ON mr.property_id = p.id 
    JOIN rental_applications ra ON mr.property_id = ra.property_id 
    WHERE mr.id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $monthly_rental_id);
$stmt->execute();
$result = $stmt->get_result();
$rental_details = $result->fetch_assoc();

if (!$rental_details) {
    echo "<script>
        alert('Rental details not found.');
        window.location.href='monthly_rentals.php';
    </script>";
    exit();
}

// Proses pembayaran
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_amount = $rental_details['monthly_rent'];
    $payment_date = date('Y-m-d');
    $application_id = $rental_details['application_id'];
    $payment_method = $_POST['payment_method'];

    $payment_query = "
        INSERT INTO payments (application_id, amount, payment_date, payment_method) 
        VALUES (?, ?, ?, ?)
    ";

    $payment_stmt = $conn->prepare($payment_query);
    $payment_stmt->bind_param("idss", $application_id, $payment_amount, $payment_date, $payment_method);

    if ($payment_stmt->execute()) {
        $payment_id = $conn->insert_id;
        echo "<script>
            alert('Payment successful!');
            window.location.href='payment_receipt.php?payment_id=$payment_id';
        </script>";
    } else {
        echo "<script>
            alert('Payment failed. Please try again.');
        </script>";
    }

    $payment_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #6dd5fa, #2980b9);
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            animation: slideIn 0.7s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #2980b9;
            font-weight: bold;
            margin-bottom: 20px;
        }
        p {
            font-size: 1rem;
            color: #333;
        }
        .form-label {
            font-weight: bold;
            color: #555;
        }
        .payment-method {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 20px;
        }
        .payment-option {
            text-align: center;
            border: 2px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            transition: transform 0.3s, border-color 0.3s;
            cursor: pointer;
        }
        .payment-option:hover {
            transform: scale(1.1);
            border-color: #2980b9;
        }
        .payment-option.active {
            border-color: #2980b9;
            background-color: #eaf6ff;
        }
        .payment-option i {
            font-size: 2rem;
            color: #2980b9;
            margin-bottom: 10px;
        }
        .payment-option p {
            margin: 0;
            font-weight: bold;
            color: #555;
        }
        .btn-submit {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-submit:hover {
            background: #1a5276;
        }
        .btn-secondary {
            display: block;
            margin: 20px auto 0;
            background-color: #555;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            text-align: center;
            transition: background 0.3s;
        }
        .btn-secondary:hover {
            background-color: #333;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fas fa-wallet"></i> Confirm Your Payment</h2>
    <p><strong>Property Name:</strong> <?= htmlspecialchars($rental_details['property_name']) ?></p>
    <p><strong>Monthly Rent:</strong> RM<?= htmlspecialchars($rental_details['monthly_rent']) ?></p>
    
    <form method="POST" action="">
        <div class="form-label">Choose Your Payment Method:</div>
        <div class="payment-method">
            <div class="payment-option" onclick="selectOption('Credit Card')">
                <i class="fas fa-credit-card"></i>
                <p>Credit Card</p>
            </div>
            <div class="payment-option" onclick="selectOption('Bank Transfer')">
                <i class="fas fa-university"></i>
                <p>Bank Transfer</p>
            </div>
            <div class="payment-option" onclick="selectOption('Cash')">
                <i class="fas fa-hand-holding-usd"></i>
                <p>Cash</p>
            </div>
        </div>
        <input type="hidden" name="payment_method" id="payment_method" required>
        <button type="submit" class="btn-submit"><i class="fas fa-check"></i> Confirm Payment</button>
    </form>

    <a href="tenant_dashboard.php" class="btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>

<script>
    function selectOption(method) {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('active');
        });
        const selectedOption = [...document.querySelectorAll('.payment-option')]
            .find(option => option.textContent.trim() === method);
        selectedOption.classList.add('active');
        document.getElementById('payment_method').value = method;
    }
</script>
</body>
</html>
