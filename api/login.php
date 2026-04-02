<?php
require_once 'config/db.php';
require_once 'config/auth.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';
    $role = $data['role'] ?? '';

    if ($role === 'customer') {
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = 'customer';
            echo json_encode(["success" => true, "user" => ["id" => $user['id'], "name" => $user['name'], "role" => "customer"]]);
            exit;
        }
    } else if ($role === 'agency') {
        $stmt = $pdo->prepare("SELECT id, agency_name, password_hash FROM agencies WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['agency_name'];
            $_SESSION['role'] = 'agency';
            echo json_encode(["success" => true, "user" => ["id" => $user['id'], "name" => $user['agency_name'], "role" => "agency"]]);
            exit;
        }
    }
    
    http_response_code(401);
    echo json_encode(["error" => "Invalid email or password"]);
} else {
    // Check session
    if (isLoggedIn()) {
        echo json_encode(["success" => true, "user" => ["id" => $_SESSION['user_id'], "name" => $_SESSION['user_name'], "role" => $_SESSION['role']]]);
    } else {
         echo json_encode(["success" => false]);
    }
}
?>
