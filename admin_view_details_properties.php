<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get property ID from URL
$property_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch property details
$sql = "SELECT pd.*, p.name, p.location, p.price, p.image 
        FROM property_details pd
        JOIN properties p ON pd.property_id = p.id
        WHERE pd.property_id = $property_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $property = $result->fetch_assoc();
} else {
    echo "Property details not found!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://static.vecteezy.com/system/resources/thumbnails/023/307/449/small_2x/ai-generative-exterior-of-modern-luxury-house-with-garden-and-beautiful-sky-photo.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        .property-details-card {
            margin: 50px auto;
            max-width: 700px;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            background: linear-gradient(to bottom, rgba(255, 255, 255, 0.95), rgba(250, 250, 250, 0.9));
            border: 2px solid transparent;
            border-image: linear-gradient(to right,rgb(93, 96, 99),rgb(57, 66, 68)) 1;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
        .property-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            border: 3px solid #f8f9fa;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .property-image:hover {
            transform: scale(1.05);
        }
        .btn-custom, .btn-secondary {
            margin-top: 20px;
            font-size: 16px;
            padding: 10px 18px;
            border-radius: 5px;
            transition: all 0.3s ease-in-out;
        }
        .btn-custom {
            background: linear-gradient(to right, #007bff, #00c6ff);
            color: white;
            border: none;
        }
        .btn-custom:hover {
            background: linear-gradient(to right, #0056b3, #0086d4);
            transform: scale(1.1);
        }
        .btn-secondary {
            background: linear-gradient(to right, #6c757d, #a3a3a3);
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background: linear-gradient(to right, #5a6268, #8c8c8c);
            transform: scale(1.1);
        }
        ul {
            padding: 0;
            list-style-type: none;
        }
        ul li {
            margin-bottom: 5px;
            font-size: 14px;
        }
        h2 {
            color: #007bff;
            font-weight: bold;
        }
        h4 {
            color: #333;
            margin-top: 20px;
        }
        .text-muted {
            color: #6c757d;
        }
        .property-details-card hr {
            border-top: 1px solid #e0e0e0;
        }
        .property-header {
            text-align: center;
            color: #007bff;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .property-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px 20px;
        }
        .property-details li {
            margin-bottom: 10px;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="property-details-card">
            <?php if (isset($property)): ?>
                <h2 class="property-header"><?php echo htmlspecialchars($property['name']); ?></h2>
                <img class="property-image" src="<?php echo '../' . htmlspecialchars($property['image']); ?>" alt="Property Image">
                <p class="text-muted text-center mt-3"><strong>Location:</strong> <?php echo htmlspecialchars($property['location']); ?></p>
                <p class="text-center"><strong>Price:</strong> $<?php echo htmlspecialchars(number_format($property['price'], 2)); ?></p>
                <hr>
                <h4>Property Details</h4>
                <ul class="property-details">
                    <li><strong>Size:</strong> <?php echo htmlspecialchars($property['size']); ?> sq. ft.</li>
                    <li><strong>Bedrooms:</strong> <?php echo htmlspecialchars($property['bedrooms']); ?></li>
                    <li><strong>Bathrooms:</strong> <?php echo htmlspecialchars($property['bathrooms']); ?></li>
                    <li><strong>Garage:</strong> <?php echo $property['garage'] ? 'Yes' : 'No'; ?></li>
                    <li><strong>Year Built:</strong> <?php echo htmlspecialchars($property['year_built']); ?></li>
                    <li><strong>Available From:</strong> <?php echo date('d M Y', strtotime($property['available_from'])); ?></li>
                    <li><strong>Parking Space:</strong> <?php echo htmlspecialchars($property['parking_space']); ?></li>
                    <li><strong>Floor:</strong> <?php echo $property['floor'] ? htmlspecialchars($property['floor']) : 'N/A'; ?></li>
                    <li><strong>Pet Friendly:</strong> <?php echo $property['pet_friendly'] ? 'Yes' : 'No'; ?></li>
                    <li><strong>Furniture:</strong> <?php echo htmlspecialchars($property['furniture']); ?></li>
                    <li><strong>Status:</strong> <?php echo htmlspecialchars($property['status']); ?></li>
                    <li><strong>Neighborhood:</strong> <?php echo htmlspecialchars($property['neighborhood']); ?></li>
                </ul>
                <div class="d-flex justify-content-between">
                    <a href="list_properties.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Properties</a>
                </div>
            <?php else: ?>
                <p class="text-danger text-center">Property details not found!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
