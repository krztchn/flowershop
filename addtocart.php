<?php
header('Content-Type: application/json');

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['price'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit();
}

$name = $data['name'];
$price = $data['price'];

// Database credentials
$host = 'localhost';
$dbname = 'flower_shop';
$username = 'root';     // change if your DB user is different
$password = '';         // change if your DB has a password

// Connect to MySQL
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'DB connection failed']);
    exit();
}

// Insert into cart table
$stmt = $conn->prepare("INSERT INTO cart (product_name, product_price) VALUES (?, ?)");
$stmt->bind_param("sd", $name, $price);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Insert failed']);
}

$stmt->close();
$conn->close();
?>
