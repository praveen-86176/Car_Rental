<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';
requireAgency();

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = trim($_POST['model']);
    $vehicle_number = trim($_POST['vehicle_number']);
    $seating_capacity = (int) $_POST['seating_capacity'];
    $rent_per_day = (float) $_POST['rent_per_day'];
    $agency_id = getUserId();

    if (empty($model) || empty($vehicle_number) || $seating_capacity <= 0 || $rent_per_day <= 0) {
        $error = "Please fill all fields correctly.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO cars (agency_id, model, vehicle_number, seating_capacity, rent_per_day) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$agency_id, $model, $vehicle_number, $seating_capacity, $rent_per_day]);
            $success = "Car added successfully!";
        } catch (PDOException $e) { if ($e->getCode() == 23000) { $error = "Vehicle number already exists."; } else { $error = "Error: " . $e->getMessage(); } }
    }
}
?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card p-4">
    <h3 class="mb-4 text-center">Add New Car</h3>
    <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3"><label class="form-label">Vehicle Model</label><input type="text" name="model" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Vehicle Number</label><input type="text" name="vehicle_number" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Seating Capacity</label><input type="number" name="seating_capacity" min="1" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Rent Per Day ($)</label><input type="number" step="0.01" name="rent_per_day" min="1" class="form-control" required></div>
        <button type="submit" class="btn btn-primary w-100">Add Car</button>
    </form>
</div></div></div>
</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
