<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';
requireAgency();

$agency_id = getUserId();
$sql = "SELECT b.id, c.name, c.phone, car.model, car.vehicle_number, b.start_date, b.num_days, b.total_cost FROM bookings b JOIN customers c ON b.customer_id = c.id JOIN cars car ON b.car_id = car.id WHERE car.agency_id = ? ORDER BY b.booked_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$agency_id]);
$bookings = $stmt->fetchAll();
?>

<h3>View Booked Cars</h3>
<?php if(empty($bookings)): ?>
    <div class="alert alert-info mt-4">No bookings found for your cars yet.</div>
<?php else: ?>
    <div class="table-responsive mt-4">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr><th>Customer</th><th>Phone</th><th>Car Model</th><th>Vehicle No.</th><th>Start Date</th><th>Days</th><th>Expected Total Cost</th></tr>
            </thead>
            <tbody>
                <?php foreach($bookings as $b): ?>
                <tr>
                    <td><?php echo htmlspecialchars($b['name']); ?></td>
                    <td><?php echo htmlspecialchars($b['phone']); ?></td>
                    <td><?php echo htmlspecialchars($b['model']); ?></td>
                    <td><?php echo htmlspecialchars($b['vehicle_number']); ?></td>
                    <td><?php echo htmlspecialchars($b['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($b['num_days']); ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($b['total_cost'], 2)); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
