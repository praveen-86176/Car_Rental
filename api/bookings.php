<?php
require_once 'config/db.php';
require_once 'config/auth.php';
requireAgency();

$agency_id = getUserId();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $sql = "
    SELECT 
        b.id as booking_id, c.name as customer_name, c.phone as customer_phone, car.model as car_model, car.vehicle_number, b.start_date, b.num_days, b.total_cost, b.booked_at
    FROM bookings b
    JOIN customers c ON b.customer_id = c.id
    JOIN cars car ON b.car_id = car.id
    WHERE car.agency_id = ? ORDER BY b.booked_at DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$agency_id]);
    $bookings = $stmt->fetchAll();
    
    echo json_encode(["success" => true, "bookings" => $bookings]);
}
?>
