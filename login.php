<?php
require_once 'includes/db.php';
require_once 'includes/navbar.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role === 'customer') {
        $stmt = $pdo->prepare("SELECT id, name, password_hash FROM customers WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['role'] = 'customer';
            header("Location: cars.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    } else if ($role === 'agency') {
        $stmt = $pdo->prepare("SELECT id, agency_name, password_hash FROM agencies WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['agency_name'];
            $_SESSION['role'] = 'agency';
            header("Location: add_car.php");
            exit;
        } else {
            $error = 'Invalid email or password.';
        }
    }
}
?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card p-4">
    <h3 class="text-center mb-4">Sign In</h3>
    <?php if($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
    <form method="POST" action="" class="needs-validation" novalidate>
        <div class="mb-3">
            <label class="form-label">Login As</label>
            <select name="role" class="form-select" required>
                <option value="customer">Customer</option>
                <option value="agency">Agency</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Email address</label>
            <input type="email" name="email" class="form-control" required>
            <div class="invalid-feedback">Please provide a valid email.</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <div class="invalid-feedback">Please provide a password.</div>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>
</div></div></div>
</div>   
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/validate.js"></script>
</body></html>
