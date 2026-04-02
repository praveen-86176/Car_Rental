<?php
require_once 'config/db.php';
require_once 'config/auth.php';
requireAgency();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $model = trim($data['model'] ?? '');
    $vehicle_number = trim($data['vehicle_number'] ?? '');
    $seating_capacity = (int) ($data['seating_capacity'] ?? 0);
    $rent_per_day = (float) ($data['rent_per_day'] ?? 0);
    $agency_id = getUserId();

    if (empty($model) || empty($vehicle_number) || $seating_capacity <= 0 || $rent_per_day <= 0) {
        http_response_code(400);
        echo json_encode(["error" => "Please fill all fields correctly."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO cars (agency_id, model, vehicle_number, seating_capacity, rent_per_day) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$agency_id, $model, $vehicle_number, $seating_capacity, $rent_per_day]);
        echo json_encode(["success" => true, "message" => "Car added successfully!"]);
    } catch (PDOException $e) {
         http_response_code(400);
         if ($e->getCode() == 23000) {
            echo json_encode(["error" => "Vehicle number already exists."]);
         } else {
            echo json_encode(["error" => "Database Error: " . $e->getMessage()]);
         }
    }
}
?>
