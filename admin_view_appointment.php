<?php
session_start();

// Semak jika sesi admin wujud
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    echo "<script>
        alert('Session expired or you do not have admin privileges. Please login again.');
        window.location.href='login.php'; // Pastikan laluan ke login.php betul
    </script>";
    exit();
}

// Sambungan ke pangkalan data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

// Membuat sambungan
$conn = new mysqli($servername, $username, $password, $dbname);

// Semak sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dapatkan semua janji temu
$sqlAppointments = "SELECT a.id, a.property_id, a.name, a.phone, a.appointment_date, a.appointment_time, p.name AS property_name 
                    FROM appointments a
                    JOIN properties p ON a.property_id = p.id
                    ORDER BY a.appointment_date ASC, a.appointment_time ASC";
$result = $conn->query($sqlAppointments);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin View Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            font-family: 'Arial', sans-serif;
            color: #fff;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 1100px;
            width: 90%;
        }

        h2 {
            color: #6a11cb;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
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

        .btn-secondary {
            background: #6a11cb;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }

        .btn-secondary:hover {
            background: #2575fc;
            transform: scale(1.05);
        }

        .text-danger {
            font-weight: bold;
        }

        .badge {
            padding: 5px 10px;
            font-size: 14px;
            border-radius: 10px;
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
    <h2><i class="fas fa-calendar-check"></i> All Appointments</h2>

    <?php if ($result->num_rows > 0): ?>
        <table class="table table-hover table-bordered mt-4">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Property</th>
                    <th>Tenant Name</th>
                    <th>Phone Number</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['property_name']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= date("d-m-Y", strtotime($row['appointment_date'])) ?></td>
                        <td><?= htmlspecialchars($row['appointment_time']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger">No appointments found.</p>
    <?php endif; ?>
    <div class="text-center mt-4">
        <a href="admin_dashboard.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup sambungan pangkalan data
$conn->close();
?>
