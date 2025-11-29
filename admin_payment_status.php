<?php
session_start();

// Semak jika sesi admin wujud
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>
        alert('Session expired or you do not have admin privileges. Please login again.');
        window.location.href='login.php';
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

// Dapatkan semua pembayaran dari jadual payments dengan maklumat tenant dan property
$query = "
    SELECT 
        p.id AS payment_id,
        ra.id AS application_id,
        ra.property_id,
        ra.tenant_name,
        ra.current_address,
        p.amount,
        p.payment_date,
        p.payment_method,
        IF(p.amount IS NOT NULL, 'Paid', 'Pending') AS payment_status
    FROM rental_applications ra
    LEFT JOIN payments p ON p.application_id = ra.id
    ORDER BY p.payment_date DESC
";

$result = $conn->query($query);

if (!$result) {
    die("Error fetching payment records: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Payment Status</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Arial', sans-serif;
            color: #fff;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            max-width: 900px;
            width: 100%;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-in-out;
        }

        h2 {
            color: #6a11cb;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .card {
            border: none;
            border-radius: 15px;
            background: linear-gradient(to right, #e0eafc, #cfdef3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-top: 15px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #2575fc;
            color: #fff;
            text-align: center;
        }

        td {
            text-align: center;
        }

        .btn {
            border-radius: 50px;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary {
            background: #6a11cb;
            border: none;
        }

        .btn-primary:hover {
            background: #2575fc;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
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
    <h2><i class="fas fa-money-check-alt"></i> Payment Status Overview</h2>

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title text-center"><i class="fas fa-list"></i> Payments Overview</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Payment ID</th>
                        <th>Application ID</th>
                        <th>Tenant Name</th>
                        <th>Current Address</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['payment_id']) ?></td>
                            <td><?= htmlspecialchars($row['application_id']) ?></td>
                            <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                            <td><?= htmlspecialchars($row['current_address']) ?></td>
                            <td>RM <?= number_format($row['amount'], 2) ?></td>
                            <td><?= $row['payment_date'] ? date("d-m-Y", strtotime($row['payment_date'])) : '-' ?></td>
                            <td><?= htmlspecialchars($row['payment_method']) ?></td>
                            <td><span class="badge bg-<?= $row['payment_status'] === 'Paid' ? 'success' : 'warning' ?>">
                                <?= htmlspecialchars($row['payment_status']) ?>
                            </span></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-primary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
</body>
</html>

<?php
// Tutup sambungan pangkalan data
$conn->close();
?>
