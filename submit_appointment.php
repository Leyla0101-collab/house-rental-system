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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $appointment_date = $conn->real_escape_string($_POST['appointment_date']);
    $appointment_time = $conn->real_escape_string($_POST['appointment_time']);

    $sql = "INSERT INTO appointments (property_id, name, phone, appointment_date, appointment_time) 
            VALUES ('$property_id', '$name', '$phone', '$appointment_date', '$appointment_time')";

    if ($conn->query($sql) === TRUE) {
        $success_message = "Your appointment has been submitted successfully!";
    } else {
        $error_message = "Error: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: url('https://ca-times.brightspotcdn.com/dims4/default/a517b34/2147483647/strip/false/crop/2000x1125+0+35/resize/1200x675!/quality/75/?url=https%3A%2F%2Fcalifornia-times-brightspot.s3.amazonaws.com%2Fad%2Ff4%2F1f1b2193479eafb7cbba65691184%2F10480-sunset-fullres-01.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Arial', sans-serif;
            color: #333;
            padding-top: 50px;
        }

        .appointment-card {
            margin: 0 auto;
            max-width: 600px;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(248, 247, 247, 0.3);
            background: rgba(78, 73, 73, 0.9);
            animation: fadeIn 1s ease-out;
            transition: transform 0.3s ease-in-out;
        }

        .appointment-card:hover {
            transform: translateY(-5px);
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .btn-custom {
            background-color: #007bff;
            color: white;
            font-size: 16px;
            padding: 12px 20px;
            border-radius: 5px;
            border: none;
            transition: all 0.3s ease-in-out;
        }

        .btn-custom:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .form-control {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            color: #007bff;
        }

        .alert {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .icon {
            font-size: 40px;
            color: #007bff;
            margin-bottom: 20px;
        }

        .d-flex {
            justify-content: space-between;
            gap: 10px;
        }

        /* Media Queries for mobile responsiveness */
        @media (max-width: 768px) {
            .appointment-card {
                padding: 20px;
                margin: 20px;
            }
            .btn-custom {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="appointment-card">
            <div class="text-center">
                <i class="fas fa-calendar-check icon"></i>
                <h2 class="text-primary mb-4">Submit Appointment</h2>
            </div>

            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" id="phone" name="phone" placeholder="0123456789" pattern="[0-9]{10}" required>
                </div>
                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Appointment Date</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                </div>
                <div class="mb-3">
                    <label for="appointment_time" class="form-label">Appointment Time</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                </div>
                <button type="submit" class="btn btn-custom w-100">Submit Appointment</button>
            </form>
            
            <!-- Button to return to tenant dashboard -->
            <div class="d-flex mt-4">
                <a href="tenant_dashboard.php" class="btn btn-secondary w-100"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
