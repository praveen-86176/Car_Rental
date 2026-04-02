<?php require_once 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental Agency</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="cars.php">CarRental</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-toggle="target" aria-controls="navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link" href="cars.php">Available Cars</a>
        </li>
        <?php if (isAgency()): ?>
        <li class="nav-item">
          <a class="nav-link" href="add_car.php">Add Car</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="bookings.php">View Bookings</a>
        </li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav ms-auto">
        <?php if (isLoggedIn()): ?>
            <li class="nav-item p-2 text-light">Welcome, <?php echo htmlspecialchars(getUserName()); ?></li>
            <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register_customer.php">Customer Signup</a></li>
            <li class="nav-item"><a class="nav-link" href="register_agency.php">Agency Signup</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
