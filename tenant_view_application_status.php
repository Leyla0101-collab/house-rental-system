<?php
// Sambungan ke pangkalan data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak jika tenant_name atau phone_number disediakan
$tenant_name = isset($_GET['tenant_name']) ? $_GET['tenant_name'] : '';
$phone_number = isset($_GET['phone_number']) ? $_GET['phone_number'] : '';

if (empty($tenant_name) || empty($phone_number)) {
    // Paparkan borang jika input tiada
    echo '
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Application</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
        <style>
            body {
                background: linear-gradient(to right, #6a11cb, #2575fc);
                color: white;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: Arial, sans-serif;
                margin: 0;
            }
            .container {
                background-color: rgba(255, 255, 255, 0.95);
                border-radius: 15px;
                padding: 30px;
                box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
                animation: fadeIn 1.5s ease-in-out;
            }
            h1 {
                text-align: center;
                color: #333;
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
            .form-label {
                font-weight: bold;
                color: #333;
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
            <h1><i class="fas fa-search"></i> Search Application</h1>
            <form action="tenant_view_application_status.php" method="GET" class="mt-4">
                <div class="mb-3">
                    <label for="tenant_name" class="form-label">Tenant Name:</label>
                    <input type="text" name="tenant_name" id="tenant_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number:</label>
                    <input type="text" name="phone_number" id="phone_number" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-eye"></i> View Status</button>
            </form>
        </div>
    </body>
    </html>
    ';
    exit;
}

// Ambil data aplikasi sewa berdasarkan tenant_name dan phone_number
$stmt = $conn->prepare("SELECT ra.id, ra.property_id, ra.start_date, ra.status, p.name AS property_name, p.price AS property_price
                        FROM rental_applications ra
                        JOIN properties p ON ra.property_id = p.id
                        WHERE ra.tenant_name = ? AND ra.phone_number = ?
                        ORDER BY ra.created_at DESC");
$stmt->bind_param("ss", $tenant_name, $phone_number);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application Status</title>
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
        .btn-secondary {
            background-color: #333;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background-color: #555;
            transform: scale(1.05);
        }
        .fa {
            margin-right: 5px;
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
    <h2><i class="fas fa-list"></i> Application Status</h2>
    <?php if ($result->num_rows > 0): ?>
        <table class="table table-hover table-bordered mt-4">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th><i class="fas fa-building"></i> Property</th>
                    <th><i class="fas fa-calendar-alt"></i> Start Date</th>
                    <th><i class="fas fa-info-circle"></i> Status</th>
                    <th><i class="fas fa-money-bill-wave"></i> Price</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['property_name']; ?></td>
                        <td><?php echo $row['start_date']; ?></td>
                        <td><?php echo ucfirst($row['status']); ?></td>
                        <td>RM <?php echo number_format($row['property_price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger"><i class="fas fa-exclamation-circle"></i> No applications found for the provided details.</p>
    <?php endif; ?>
    <a href="tenant_dashboard.php" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
