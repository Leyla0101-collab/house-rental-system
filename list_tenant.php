<?php
// Konfigurasi sambungan ke pangkalan data
$host = "localhost"; // Tukar kepada nama host anda
$username = "root";  // Tukar kepada nama pengguna database anda
$password = "";      // Tukar kepada kata laluan database anda
$dbname = "house_rental_system"; // Nama pangkalan data anda

// Sambungkan ke pangkalan data
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa sambungan
if ($conn->connect_error) {
    die("Sambungan gagal: " . $conn->connect_error);
}

// Dapatkan data dari jadual rental_application
$sql = "SELECT property_id, id, tenant_name, phone_number, current_address, job_details FROM rental_applications";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant List</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('https://propcheck.in/wp-content/uploads/2024/06/2151004031-1000x500.jpg');
            background-size: cover;
            background-position: center;
        }
        .container {
            max-width: 900px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #343a40;
            margin-bottom: 20px;
            animation: fadeInDown 1s;
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }
        td i {
            color: #007bff;
            margin-right: 5px;
        }
        tr:hover {
            background-color: #f1f1f1;
            transform: scale(1.01);
            transition: all 0.3s ease;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
        }
        .back-button {
            display: block;
            margin: 20px auto;
            text-align: center;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .back-button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-users"></i> List of Tenants</h1>
        <table>
            <thead>
                <tr>
                    <th> Property ID</th>
                    <th> ID</th>
                    <th> Tenant Name</th>
                    <th> Phone Number</th>
                    <th> Current Address</th>
                    <th> Job Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Semak jika data wujud
                if ($result->num_rows > 0) {
                    // Paparkan setiap baris data
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><i class='fas fa-home'></i>" . $row['property_id'] . "</td>";
                        echo "<td><i class='fas fa-id-badge'></i>" . $row['id'] . "</td>";
                        echo "<td><i class='fas fa-user'></i>" . $row['tenant_name'] . "</td>";
                        echo "<td><i class='fas fa-phone'></i>" . $row['phone_number'] . "</td>";
                        echo "<td><i class='fas fa-map-marker-alt'></i>" . $row['current_address'] . "</td>";
                        echo "<td><i class='fas fa-briefcase'></i>" . $row['job_details'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='no-data'>Data Not Found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="admin_dashboard.php" class="back-button"><i class="fas fa-arrow-left"></i> Back To Dashboard</a>
    </div>
</body>
</html>

<?php
// Tutup sambungan
$conn->close();
?>
