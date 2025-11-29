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

// Initialize variables
$selected_state = isset($_GET['state']) ? $_GET['state'] : '';

// Fetch properties data with filter
$sql = "
    SELECT p.id, p.name, p.location, p.price, p.image, 
           CASE 
               WHEN EXISTS (
                   SELECT 1 
                   FROM rental_applications ra 
                   WHERE ra.property_id = p.id
               ) THEN 'rented'
               ELSE 'available'
           END AS status
    FROM properties p
";
if (!empty($selected_state)) {
    $sql .= " WHERE TRIM(SUBSTRING_INDEX(p.location, ',', -1)) = ?";
}
$stmt = $conn->prepare($sql);
if (!empty($selected_state)) {
    $stmt->bind_param('s', $selected_state);
}
$stmt->execute();
$result = $stmt->get_result();

// Fetch unique states for filter dropdown
$states_sql = "SELECT DISTINCT TRIM(SUBSTRING_INDEX(location, ',', -1)) AS state FROM properties";
$states_result = $conn->query($states_sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #8e9eab, #eef2f3);
            color: #333;
            min-height: 100vh;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-weight: bold;
        }
        .property-card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .property-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .property-card img {
            border-radius: 15px 15px 0 0;
            object-fit: cover;
            width: 100%;
            height: 200px;
        }
        .card-title {
            color: #2c3e50;
            font-weight: bold;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 40px;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
        .btn-success {
            background-color: #2ecc71;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            height: 40px;
        }
        .btn-success:hover {
            background-color: #27ae60;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            gap: 10px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .filter-container {
            background: rgb(142, 192, 238);
            border-radius: 10px;
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s ease;
        }
        .filter-container:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .filter-container .form-select {
            border: 2px solid #3498db;
            transition: border-color 0.3s ease;
        }
        .filter-container .form-select:focus {
            border-color: #2980b9;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.5);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Properties List</h1>
        
        <!-- Filter Form -->
        <div class="filter-container">
            <form method="GET" action="">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="state" class="form-label fw-bold text-primary">
                            <i class="bi bi-geo-alt-fill"></i> Filter by State:
                        </label>
                    </div>
                    <div class="col-md-6">
                        <select name="state" id="state" class="form-select border-primary shadow-sm" style="height: 45px; font-size: 16px;">
                            <option value="">All States</option>
                            <?php if ($states_result->num_rows > 0): ?>
                                <?php while ($state_row = $states_result->fetch_assoc()): ?>
                                    <option value="<?php echo $state_row['state']; ?>" 
                                        <?php echo $selected_state == $state_row['state'] ? 'selected' : ''; ?> >
                                        <?php echo $state_row['state']; ?>
                                    </option>
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-gradient shadow-sm px-4 py-2 fw-bold text-white">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Properties Listing -->
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card property-card">
                            <img src="<?php echo '../' . $row['image']; ?>" alt="Property Image">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['name']; ?></h5>
                                <p class="card-text">
                                    <strong>State:</strong> <?php echo trim(substr($row['location'], strrpos($row['location'], ',') + 1)); ?><br>
                                    <strong>Location:</strong> <?php echo $row['location']; ?><br>
                                    <strong>Price:</strong> RM<?php echo $row['price']; ?><br>
                                    <strong>Status:</strong> <?php echo ucfirst($row['status']); ?>
                                </p>
                                <?php if ($row['status'] === 'available'): ?>
                                    <div class="button-container">
                                        <a href="property_details.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">
                                            <i class="bi bi-eye"></i> View Details
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <p class="text-danger">This property is currently rented.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-danger text-center">No properties found in the selected state.</p>
            <?php endif; ?>
        </div>

        <!-- Button to go back to Dashboard -->
        <div class="text-center mt-4">
            <a href="admin_dashboard.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>
    <?php $conn->close(); ?>
</body>
</html>
