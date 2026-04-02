<?php
require_once 'config/db.php';
require_once 'config/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->query("SELECT c.*, a.agency_name FROM cars c JOIN agencies a ON c.agency_id = a.id ORDER BY c.created_at DESC");
    $cars = $stmt->fetchAll();
    
    $booked_cars = [];
    if (isCustomer()) {
        $stmt_b = $pdo->prepare("SELECT car_id FROM bookings WHERE customer_id = ?");
        $stmt_b->execute([getUserId()]);
        $booked_cars = $stmt_b->fetchAll(PDO::FETCH_COLUMN);
    }

    echo json_encode(["success" => true, "cars" => $cars, "booked_cars" => $booked_cars]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCustomer();
    $data = json_decode(file_get_contents("php://input"), true);
    
    $car_id = (int) ($data['car_id'] ?? 0);
    $num_days = (int) ($data['num_days'] ?? 0);
    $start_date = $data['start_date'] ?? '';
    
    if($num_days <= 0 || empty($start_date)){
        http_response_code(400);
        echo json_encode(["error" => "Please provide valid booking details."]);
        exit;
    }
    
    // Fetch rent_per_day
    $stmt = $pdo->prepare("SELECT rent_per_day FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    
    if($car){
        $total_cost = $car['rent_per_day'] * $num_days;
        $customer_id = getUserId();
        
        try{
            $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, car_id, start_date, num_days, total_cost) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$customer_id, $car_id, $start_date, $num_days, $total_cost]);
            echo json_encode(["success" => true, "message" => "Car booked successfully!"]);
        } catch(PDOException $e){
             http_response_code(500);
             echo json_encode(["error" => "Booking failed: " . $e->getMessage()]);
        }
    } else {
        http_response_code(404);
        echo json_encode(["error" => "Invalid car selection."]);
    }
}
?>
