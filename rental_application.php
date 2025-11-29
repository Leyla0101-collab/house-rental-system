<?php
session_start(); // Mulakan sesi untuk mendapatkan user_id

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sambungan ke pangkalan data
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "house_rental_system";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Semak jika user_id wujud dalam sesi
    if (!isset($_SESSION['user_id'])) {
        die("Error: User not logged in.");
    }

    $user_id = $_SESSION['user_id']; // Ambil user_id dari sesi

    // Ambil maklumat daripada borang
    $property_id = $_POST['property_id'];
    $tenant_name = $_POST['tenant_name'];
    $phone_number = $_POST['phone_number'];
    $current_address = $_POST['current_address'];
    $start_date = $_POST['start_date'];
    $job_details = $_POST['job_details']; // Maklumat pekerjaan
    $status = 'pending';

    // Tetapan untuk muat naik
    $upload_dir = __DIR__ . '/uploads/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Cipta direktori jika tiada
    }

    $allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
    $max_file_size = 5 * 1024 * 1024; // 5MB

    $income_proof = $_FILES['income_proof'];
    $supporting_docs = $_FILES['supporting_docs'];

    $income_proof_path = '';
    $supporting_docs_path = '';

    // Validasi dan simpan dokumen sokongan pendapatan
    if ($income_proof['error'] === UPLOAD_ERR_OK) {
        if (in_array($income_proof['type'], $allowed_types) && $income_proof['size'] <= $max_file_size) {
            $income_proof_path = $upload_dir . uniqid() . '_' . basename($income_proof['name']);
            if (!move_uploaded_file($income_proof['tmp_name'], $income_proof_path)) {
                die("Failed to upload income proof.");
            }
        } else {
            die("Invalid income proof file.");
        }
    } else {
        die("Error uploading income proof: " . $income_proof['error']);
    }

    // Validasi dan simpan dokumen sokongan lain
    if ($supporting_docs['error'] === UPLOAD_ERR_OK) {
        if (in_array($supporting_docs['type'], $allowed_types) && $supporting_docs['size'] <= $max_file_size) {
            $supporting_docs_path = $upload_dir . uniqid() . '_' . basename($supporting_docs['name']);
            if (!move_uploaded_file($supporting_docs['tmp_name'], $supporting_docs_path)) {
                die("Failed to upload supporting document.");
            }
        } else {
            die("Invalid supporting document file.");
        }
    } else {
        die("Error uploading supporting document: " . $supporting_docs['error']);
    }

    // Simpan data ke pangkalan data
    $stmt = $conn->prepare("INSERT INTO rental_applications (user_id, property_id, tenant_name, phone_number, current_address, start_date, job_details, income_proof, supporting_docs, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssssssss", $user_id, $property_id, $tenant_name, $phone_number, $current_address, $start_date, $job_details, $income_proof_path, $supporting_docs_path, $status);

    if ($stmt->execute()) {
        echo "Application submitted successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.7s ease-in-out;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            text-align: center;
            color: #6a11cb;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #333;
        }
        .form-control {
            border-radius: 8px;
            border: 2px solid #ddd;
            transition: border-color 0.3s;
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: none;
        }
        .btn-primary {
            background: #2575fc;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1rem;
            transition: transform 0.3s, background 0.3s;
        }
        .btn-primary:hover {
            background: #6a11cb;
            transform: scale(1.05);
        }
        .btn-secondary {
            background: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 1rem;
            transition: transform 0.3s, background 0.3s;
        }
        .btn-secondary:hover {
            background: #555;
            transform: scale(1.05);
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
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
    <h1><i class="fas fa-file-alt"></i> Rental Application</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="property_id" value="<?php echo isset($_GET['property_id']) ? htmlspecialchars($_GET['property_id']) : ''; ?>">
        
        <div class="form-group mb-3">
            <label for="tenant_name"><i class="fas fa-user"></i> Tenant Name:</label>
            <input type="text" name="tenant_name" id="tenant_name" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="phone_number"><i class="fas fa-phone-alt"></i> Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="current_address"><i class="fas fa-map-marker-alt"></i> Current Address:</label>
            <input type="text" name="current_address" id="current_address" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="start_date"><i class="fas fa-calendar-alt"></i> Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="job_details"><i class="fas fa-briefcase"></i> Job Details:</label>
            <input type="text" name="job_details" id="job_details" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="income_proof"><i class="fas fa-file-upload"></i> Income Proof (PDF/JPG/PNG):</label>
            <input type="file" name="income_proof" id="income_proof" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="supporting_docs"><i class="fas fa-folder-open"></i> Supporting Documents (PDF/JPG/PNG):</label>
            <input type="file" name="supporting_docs" id="supporting_docs" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-paper-plane"></i> Submit Application</button>
    </form>

    <a href="tenant_dashboard.php" class="btn btn-secondary mt-3 w-100"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
</div>
</body>
</html>
