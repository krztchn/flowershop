<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "flower_shop");

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed"]));
}

$data = json_decode(file_get_contents("php://input"), true);
$action = $_GET['action'] ?? $data['action'] ?? '';

switch ($action) {
  case 'read':
    $result = $conn->query("SELECT * FROM users");
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
    break;

  case 'create':
    $stmt = $conn->prepare("INSERT INTO users (name, email, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['name'], $data['email'], $data['role']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  case 'update':
    $stmt = $conn->prepare("UPDATE users SET name=?, email=?, role=? WHERE id=?");
    $stmt->bind_param("sssi", $data['name'], $data['email'], $data['role'], $data['id']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  case 'delete':
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $data['id']);
    $stmt->execute();
    echo json_encode(["success" => true]);
    break;

  default:
    echo json_encode(["error" => "Invalid action"]);
    break;
}

$conn->close();
