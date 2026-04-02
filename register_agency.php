<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

$error = ''; $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agency_name = trim($_POST['agency_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];

    if (empty($agency_name) || empty($email) || empty($address) || empty($password)) {
        $error = "All fields are required.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO agencies (agency_name, email, address, password_hash) VALUES (?, ?, ?, ?)");
            $stmt->execute([$agency_name, $email, $address, $hash]);
            $success = "Registration successful! You can now <a href='login.php'>log in</a>.";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) { $error = "Email already exists."; } else { $error = "Error: " . $e->getMessage(); }
        }
    }
}
?>
<div class="row justify-content-center"><div class="col-md-6"><div class="card p-4">
    <h3 class="text-center mb-4">Agency Registration</h3>
    <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <?php if($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3"><label class="form-label">Agency Name</label><input type="text" name="agency_name" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Address</label><input type="text" name="address" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" minlength="6" class="form-control" required></div>
        <button type="submit" class="btn btn-primary w-100">Register</button>
    </form>
</div></div></div>
</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
