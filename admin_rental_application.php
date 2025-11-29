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

// Ambil data aplikasi sewa
$sql = "SELECT ra.id, ra.tenant_name, ra.phone_number, ra.current_address, ra.income_proof, ra.supporting_docs, 
        ra.start_date, ra.status, p.name AS property_name 
        FROM rental_applications ra
        JOIN properties p ON ra.property_id = p.id
        ORDER BY ra.created_at DESC";
$result = $conn->query($sql);

// Kira jumlah aplikasi tertunda
$pending_count = $conn->query("SELECT COUNT(*) AS count FROM rental_applications WHERE status = 'pending'")->fetch_assoc()['count'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Applications - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: url('https://ca-times.brightspotcdn.com/dims4/default/a517b34/2147483647/strip/false/crop/2000x1125+0+35/resize/1200x675!/quality/75/?url=https%3A%2F%2Fcalifornia-times-brightspot.s3.amazonaws.com%2Fad%2Ff4%2F1f1b2193479eafb7cbba65691184%2F10480-sunset-fullres-01.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
        }

        .navbar {
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .btn-warning {
            background-color: #ff9800;
            border: none;
        }

        .btn-warning:hover {
            background-color: #f57c00;
        }

        .btn-status {
            margin-right: 5px;
            width: 150px;
            height: 35px;
            text-align: center;
            font-size: 1rem;
        }

        .table-container {
            background: rgba(94, 85, 85, 0.8);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(255, 255, 255, 0.1);
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: white;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .table th {
            background-color: rgb(40, 92, 128);
            color: white;
            text-align: center;
            vertical-align: middle;
        }

        .badge-warning {
            background-color: #ff9800;
        }

        .badge-success {
            background-color: #4caf50;
        }

        .badge-danger {
            background-color: #f44336;
        }

        .btn-back {
            background-color: #2980b9;
            color: white;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 20px;
            font-size: 1.2rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-back:hover {
            background-color: #1f6f92;
            transform: scale(1.05);
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Dashboard</a>
            <div class="ml-auto">
                <button class="btn btn-warning">
                    <i class="fas fa-exclamation-circle"></i> Pending Applications 
                    <span class="badge bg-danger"><?php echo $pending_count; ?></span>
                </button>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="table-container">
            <h2>Rental Applications</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tenant Name</th>
                        <th>Phone Number</th>
                        <th>Current Address</th>
                        <th>Property</th>
                        <th>Start Date</th>
                        <th>Income Proof</th>
                        <th>Supporting Documents</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['tenant_name']; ?></td>
                                <td><?php echo $row['phone_number']; ?></td>
                                <td><?php echo $row['current_address']; ?></td>
                                <td><?php echo $row['property_name']; ?></td>
                                <td><?php echo $row['start_date']; ?></td>
                                <td>
                                    <?php if (!empty($row['income_proof'])): ?>
                                        <a href="uploads/<?php echo basename($row['income_proof']); ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-file-pdf"></i> View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not Uploaded</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($row['supporting_docs'])): ?>
                                        <a href="uploads/<?php echo basename($row['supporting_docs']); ?>" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fas fa-file-alt"></i> View
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not Uploaded</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'pending'): ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php elseif ($row['status'] === 'approved'): ?>
                                        <span class="badge badge-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Rejected</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="approve_application.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-status">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </a>
                                    <a href="reject_application.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-status">
                                        <i class="fas fa-times-circle"></i> Reject
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="10" class="text-center">No applications found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <a href="admin_dashboard.php" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>