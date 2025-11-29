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

// Ambil parameter dari URL
$application_id = isset($_GET['application_id']) ? $_GET['application_id'] : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';

if (empty($application_id) || empty($price)) {
    die("Invalid request. Missing required parameters.");
}

// Simpan mesej pembayaran
$payment_status_message = "";

// Semak jika borang dihantar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_method = $_POST['payment_method'];
    $payment_date = date("Y-m-d H:i:s");

    // Simpan maklumat pembayaran ke dalam jadual payments
    $stmt = $conn->prepare("INSERT INTO payments (application_id, amount, payment_method, payment_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $application_id, $price, $payment_method, $payment_date);

    if ($stmt->execute()) {
        // Kemaskini status permohonan sewa ke "paid"
        $update_stmt = $conn->prepare("UPDATE rental_applications SET status = 'paid' WHERE id = ?");
        $update_stmt->bind_param("i", $application_id);
        $update_stmt->execute();
        $update_stmt->close();

        $payment_status_message = "<div class='alert alert-success'>Payment successful!</div>";
    } else {
        $payment_status_message = "<div class='alert alert-danger'>Failed to process payment. Please try again.</div>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Make Payment</h2>
    <?php echo $payment_status_message; ?>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Payment Details</h5>
            <p><strong>Application ID:</strong> <?php echo $application_id; ?></p>
            <p><strong>Amount to Pay:</strong> RM <?php echo number_format($price, 2); ?></p>

            <form method="POST">
                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method:</label>
                    <select name="payment_method" id="payment_method" class="form-control" required>
                        <option value="Credit Card">Credit Card</option>
                        <option value="Debit Card">Debit Card</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Cash">Cash</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Confirm Payment</button>
                <a href="tenant_view_application_status.php" class="btn btn-secondary">Cancel</a>
                <a href="tenant_dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </form>
        </div>
    </div>
</div>
</body>
</html>
