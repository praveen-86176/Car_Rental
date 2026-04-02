<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn() && isCustomer()) {
    $car_id = (int) $_POST['car_id'];
    $num_days = (int) $_POST['num_days'];
    $start_date = $_POST['start_date'];
    
    if($num_days <= 0 || empty($start_date)){
        $error = "Please provide valid booking details.";
    } else {
        $stmt = $pdo->prepare("SELECT rent_per_day FROM cars WHERE id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch();
        if($car){
            $total_cost = $car['rent_per_day'] * $num_days;
            $customer_id = getUserId();
            try {
                $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, car_id, start_date, num_days, total_cost) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$customer_id, $car_id, $start_date, $num_days, $total_cost]);
                $success = "Car booked successfully!";
            } catch(PDOException $e){ $error = "Booking failed: " . $e->getMessage(); }
        } else {
            $error = "Invalid car selection.";
        }
    }
}

// Fetch cars and bookings if customer
$stmt = $pdo->query("SELECT c.*, a.agency_name FROM cars c JOIN agencies a ON c.agency_id = a.id ORDER BY c.created_at DESC");
$cars = $stmt->fetchAll();

$booked_cars = [];
if (isCustomer()) {
    $stmt_b = $pdo->prepare("SELECT car_id FROM bookings WHERE customer_id = ?");
    $stmt_b->execute([getUserId()]);
    $booked_cars = $stmt_b->fetchAll(PDO::FETCH_COLUMN);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Available Cars</h3>
    <?php if(isAgency()): ?><a href="add_car.php" class="btn btn-primary">Add New Car</a><?php endif; ?>
</div>

<?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
<?php if($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

<div class="row">
    <?php if(empty($cars)): ?><div class="col-12"><div class="alert alert-info">No cars available.</div></div><?php else: ?>
        <?php foreach($cars as $car): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($car['model']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted">Posted by <?php echo htmlspecialchars($car['agency_name']); ?></h6>
                    <p class="card-text">
                        <strong>Vehicle No:</strong> <?php echo htmlspecialchars($car['vehicle_number']); ?><br>
                        <strong>Capacity:</strong> <?php echo htmlspecialchars($car['seating_capacity']); ?> persons<br>
                        <strong>Rent Per Day:</strong> $<?php echo htmlspecialchars(number_format($car['rent_per_day'], 2)); ?>
                    </p>
                    
                    <?php if(!isLoggedIn()): ?>
                        <a href="login.php" class="btn btn-outline-primary w-100">Login to Rent</a>
                    <?php elseif(isAgency()): ?>
                        <?php if($car['agency_id'] == getUserId()): ?>
                            <a href="edit_car.php?id=<?php echo $car['id']; ?>" class="btn btn-sm btn-warning mb-2 w-100">Edit Details</a>
                        <?php else: ?>
                            <div class="alert alert-secondary py-2 text-center mb-0" style="font-size:0.9rem;">Agencies cannot book cars.</div>
                        <?php endif; ?>
                    <?php elseif(isCustomer()): ?>
                        <?php if (in_array($car['id'], $booked_cars)): ?>
                            <div class="alert alert-success py-2 text-center mb-0" style="font-size:0.9rem;">Booked by you</div>
                        <?php else: ?>
                            <form method="POST" action="" class="needs-validation mt-3" novalidate>
                                <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                <div class="row mb-2">
                                    <div class="col"><input type="date" name="start_date" class="form-control form-control-sm" required min="<?php echo date('Y-m-d'); ?>"></div>
                                    <div class="col">
                                        <select name="num_days" class="form-select form-select-sm" required>
                                            <option value="">Days</option>
                                            <?php for($i=1; $i<=30; $i++): ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Rent Car</button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
