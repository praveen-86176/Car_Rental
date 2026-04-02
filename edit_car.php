<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';
requireAgency();
$agency_id = getUserId();
$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ? AND agency_id = ?");
$stmt->execute([$car_id, $agency_id]);
$car = $stmt->fetch();
if (!$car) { echo "<div class='alert alert-danger'>Car not found or no permission.</div></div></body></html>"; exit; }
$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = trim($_POST['model']);
    $vehicle_number = trim($_POST['vehicle_number']);
    $seating_capacity = (int) $_POST['seating_capacity'];
    $rent_per_day = (float) $_POST['rent_per_day'];
    if (empty($model) || empty($vehicle_number) || $seating_capacity <= 0 || $rent_per_day <= 0) {
        $error = "Please fill all fields correctly.";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE cars SET model = ?, vehicle_number = ?, seating_capacity = ?, rent_per_day = ? WHERE id = ? AND agency_id = ?");
            $stmt->execute([$model, $vehicle_number, $seating_capacity, $rent_per_day, $car_id, $agency_id]);
            $success = "Car updated successfully!";
            $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?"); $stmt->execute([$car_id]); $car = $stmt->fetch();
        } catch (PDOException $e) { if ($e->getCode() == 23000) { $error = "Vehicle number already exists."; } else { $error = "Error: " . $e->getMessage(); } }
    }
}
?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card p-4">
    <h3 class="mb-4 text-center">Edit Car</h3>
    <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3"><label class="form-label">Vehicle Model</label><input type="text" name="model" value="<?php echo htmlspecialchars($car['model']);?>" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Vehicle Number</label><input type="text" name="vehicle_number" value="<?php echo htmlspecialchars($car['vehicle_number']);?>" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Seating Capacity</label><input type="number" name="seating_capacity" min="1" value="<?php echo htmlspecialchars($car['seating_capacity']);?>" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Rent Per Day ($)</label><input type="number" step="0.01" name="rent_per_day" min="1" value="<?php echo htmlspecialchars($car['rent_per_day']);?>" class="form-control" required></div>
        <button type="submit" class="btn btn-primary w-100">Update Car</button>
    </form>
</div></div></div>
</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
