<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="hospital-logo.jpg" alt="Hospital Logo" style="width: 150px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="about.php">About Us</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="servicesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Services
                </a>
                <div class="dropdown-menu" aria-labelledby="servicesDropdown">
                    <!-- Uncomment if needed -->
                    <!-- <?php if (isset($_SESSION['user_id']) && $_SESSION['role'] == 'admin'): ?>
                        <a class="dropdown-item" href="view_records.php">Medical Records</a>
                    <?php endif; ?> -->
                    <!-- <a class="dropdown-item" href="billing.php">Billing and Payments</a> -->
                    <a class="dropdown-item" href="view_pharmacy_items.php">Pharmacy</a>
                    <a class="dropdown-item" href="view_laboratory_tests.php">Laboratory</a>
                    <a class="dropdown-item" href="view_emergency_services.php">Emergency Services</a>
                </div>
            </li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['role'] == 'doctor'): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="doctorsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Doctors
                        </a>
                        <div class="dropdown-menu" aria-labelledby="doctorsDropdown">
                            <a class="dropdown-item" href="view_consultations.php">View Consultations</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
</body>
</html>
