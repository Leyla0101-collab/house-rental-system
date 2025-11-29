<?php
// Fail: view_document.php

// Sambungan ke pangkalan data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house_rental_system";

$conn = new mysqli($servername, $username, $password, $dbname);

// Semak jika terdapat ralat sambungan
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validasi input GET parameter
if (!isset($_GET['id']) || !isset($_GET['type'])) {
    die("Invalid request.");
}

$doc_id = intval($_GET['id']); // Pastikan hanya nombor dibenarkan
$type = $_GET['type'];

// Jenis dokumen yang sah (income_proof atau supporting_docs)
$allowed_types = ['income_proof', 'supporting_docs'];
if (!in_array($type, $allowed_types)) {
    die("Invalid document type.");
}

// Semak dokumen yang diminta dalam pangkalan data
$sql = "SELECT $type FROM rental_applications WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $doc_id);
$stmt->execute();
$stmt->bind_result($file_path);
$stmt->fetch();
$stmt->close();
$conn->close();

// Semak jika dokumen wujud
if (!$file_path || !file_exists("uploads/" . $file_path)) {
    die("File not found.");
}

// Tetapkan header untuk memaparkan dokumen
$file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
$content_type = 'application/octet-stream';

// Tetapkan jenis kandungan berdasarkan sambungan fail
switch (strtolower($file_extension)) {
    case 'pdf':
        $content_type = 'application/pdf';
        break;
    case 'jpg':
    case 'jpeg':
        $content_type = 'image/jpeg';
        break;
    case 'png':
        $content_type = 'image/png';
        break;
}

// Paparkan fail
header("Content-Type: $content_type");
header("Content-Disposition: inline; filename=\"" . basename($file_path) . "\"");
header("Content-Length: " . filesize("uploads/" . $file_path));
readfile("uploads/" . $file_path);
exit;
?>
