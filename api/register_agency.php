<?php
require_once 'config/db.php';
require_once 'config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $agency_name = trim($data['agency_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $address = trim($data['address'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($agency_name) || empty($email) || empty($address) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO agencies (agency_name, email, address, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$agency_name, $email, $address, $hash]);
        echo json_encode(["success" => true, "message" => "Registration successful!"]);
    } catch (PDOException $e) {
        http_response_code(400);
         if ($e->getCode() == 23000) {
            echo json_encode(["error" => "Email address already exists."]);
        } else {
            echo json_encode(["error" => "Error: " . $e->getMessage()]);
        }
    }
}
?>
