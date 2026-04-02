<?php
require_once 'config/db.php';
require_once 'config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $password = $data['password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        http_response_code(400);
        echo json_encode(["error" => "All fields are required."]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $phone, $hash]);
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
