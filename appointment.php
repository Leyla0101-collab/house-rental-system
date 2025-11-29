<?php
require_once 'config/db.php';
$propertyId = $_GET['id'] ?? 0;

// Dapatkan maklumat hartanah dan butiran tambahan
$sql = "SELECT p.*, pd.bedrooms, pd.bathrooms, pd.size, pd.amenities
        FROM properties p
        LEFT JOIN property_details pd ON p.id = pd.property_id
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $propertyId);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['name']) ?> - Property Details</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2><?= htmlspecialchars($property['name']) ?></h2>
    <p><strong>Location:</strong> <?= htmlspecialchars($property['location']) ?></p>
    <p><strong>Price:</strong> RM <?= number_format($property['price'], 2) ?></p>
    <p><strong>Bedrooms:</strong> <?= htmlspecialchars($property['bedrooms']) ?></p>
    <p><strong>Bathrooms:</strong> <?= htmlspecialchars($property['bathrooms']) ?></p>
    <p><strong>Size:</strong> <?= htmlspecialchars($property['size']) ?> sqft</p>
    <p><strong>Amenities:</strong> <?= htmlspecialchars($property['amenities']) ?></p>
    <a href="appointment.php?property_id=<?= $property['id'] ?>" class="btn btn-primary">Submit Appointment</a>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
