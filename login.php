<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Login - Hospital Management System</h1>
        <p class="lead">Access your account and manage your activities.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row justify-content-center">
        <!-- Patient Login Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-success text-white d-flex align-items-center">
                    <img src="patient_login.jpg" class="card-header-img mr-3" alt="Patient Login">
                    <h5 class="card-title mb-0">Patient Login</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Access your medical records and appointments.</p>
                    <a href="patient_login.php" class="btn btn-success">Login</a>
                    <p class="card-text mt-2">New here? <a href="patient_signup.php">Sign Up</a></p>
                </div>
            </div>
        </div>

        <!-- Doctor Login Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-info text-white d-flex align-items-center">
                    <img src="doctor_login.jpg" class="card-header-img mr-3" alt="Doctor Login">
                    <h5 class="card-title mb-0">Doctor Login</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Manage your consultations and patient records.</p>
                    <a href="doctor_login.php" class="btn btn-info">Login</a>
                </div>
            </div>
        </div>

        <!-- Staff Login Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-warning text-dark d-flex align-items-center">
                    <img src="staff_login.jpg" class="card-header-img mr-3" alt="Staff Login">
                    <h5 class="card-title mb-0">Staff Login</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Manage hospital operations and patient information.</p>
                    <a href="staff_login.php" class="btn btn-warning">Login</a>
                </div>
            </div>
        </div>

        <!-- Admin Login Card -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-danger text-white d-flex align-items-center">
                    <img src="admin_login.jpg" class="card-header-img mr-3" alt="Admin Login">
                    <h5 class="card-title mb-0">Admin Login</h5>
                </div>
                <div class="card-body text-center">
                    <p class="card-text">Manage the entire hospital system.</p>
                    <a href="admin_login.php" class="btn btn-danger">Login</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
