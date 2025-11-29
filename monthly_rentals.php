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

// Ambil user_id daripada sesi
$user_id = $_SESSION['user_id'];

// Dapatkan senarai sewa bulanan pengguna dengan status approved
$query = "
    SELECT mr.id, mr.monthly_rent, mr.start_date, p.name AS property_name 
    FROM monthly_rentals mr 
    JOIN properties p ON mr.property_id = p.id 
    WHERE mr.user_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Rentals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            font-family: Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: fadeIn 1.5s ease-in-out;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        table {
            border-radius: 10px;
            overflow: hidden;
        }
        table th, table td {
            text-align: center;
            vertical-align: middle;
        }
        .btn-primary {
            background-color: #2575fc;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #1a5dc7;
            transform: scale(1.05);
        }
        .btn-secondary {
            background-color: #333;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #555;
            transform: scale(1.05);
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
<div class="container">
    <h2><i class="fas fa-home"></i> Monthly Rentals</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-hover table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th><i class="fas fa-building"></i> Property Name</th>
                    <th><i class="fas fa-money-bill-wave"></i> Monthly Rent</th>
                    <th><i class="fas fa-calendar-alt"></i> Start Date</th>
                    <th><i class="fas fa-cogs"></i> Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['property_name']) ?></td>
                        <td>RM <?= number_format($row['monthly_rent'], 2) ?></td>
                        <td><?= htmlspecialchars($row['start_date']) ?></td>
                        <td>
                            <a href="monthly_rental_payment.php?monthly_rental_id=<?= $row['id'] ?>" class="btn btn-primary"><i class="fas fa-credit-card"></i> Pay Now</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> No approved monthly rentals found.</p>
    <?php endif; ?>

    <a href="tenant_dashboard.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
