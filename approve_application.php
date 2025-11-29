<?php
// Sambung ke pangkalan data
$conn = new mysqli("localhost", "root", "", "house_rental_system");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Dapatkan ID permohonan
$application_id = $_GET['id'];

// Kemas kini status aplikasi kepada 'approved'
$sql = "UPDATE rental_applications SET status = 'approved' WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $application_id);
$stmt->execute();

// Redirect ke halaman admin selepas kemas kini
header("Location: admin_rental_application.php");
exit();
?>
