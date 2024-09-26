<?php
session_start();
include('db_connection.php');

// Check if the admin is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: admin_login.php");
    exit();
}

// Fetch necessary data for the dashboard
$patients_count = $conn->query("SELECT COUNT(*) AS total FROM patients")->fetch_assoc()['total'];
$appointments_count = $conn->query("SELECT COUNT(*) AS total FROM appointments")->fetch_assoc()['total'];
$doctors_count = $conn->query("SELECT COUNT(*) AS total FROM doctors")->fetch_assoc()['total'];
$billing_count = $conn->query("SELECT COUNT(*) AS total FROM billing")->fetch_assoc()['total'];
$staff_count = $conn->query("SELECT COUNT(*) AS total FROM users WHERE role = 'staff'")->fetch_assoc()['total'];
$pharmacy_count = $conn->query("SELECT COUNT(*) AS total FROM pharmacy_items")->fetch_assoc()['total'];
$laboratory_count = $conn->query("SELECT COUNT(*) AS total FROM laboratory_tests")->fetch_assoc()['total'];
$medical_records_count = $conn->query("SELECT COUNT(*) AS total FROM medical_records")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
   
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>
        
        <!-- Logout Button in the Top-Right Corner -->
        <div class="text-right mb-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Card Layout for Management Sections -->
        <div class="row">

            <!-- Patients Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-patients">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Patients</h4>
                        <p class="card-text">Total Patients: <?php echo $patients_count; ?></p>
                        <a href="manage_patients.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Appointments Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-appointments">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Appointments</h4>
                        <p class="card-text">Total Appointments: <?php echo $appointments_count; ?></p>
                        <a href="manage_appointments.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Doctors Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-doctors">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Doctors</h4>
                        <p class="card-text">Total Doctors: <?php echo $doctors_count; ?></p>
                        <a href="manage_doctors.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Billing Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-billing">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Billing</h4>
                        <p class="card-text">Total Billing Records: <?php echo $billing_count; ?></p>
                        <a href="manage_billing.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Staff Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-staff">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Staff</h4>
                        <p class="card-text">Total Staff Members: <?php echo $staff_count; ?></p>
                        <a href="manage_staff.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Pharmacy Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-pharmacy">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Pharmacy</h4>
                        <p class="card-text">Total Pharmacy Records: <?php echo $pharmacy_count; ?></p>
                        <a href="manage_pharmacy.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Laboratory Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-laboratory">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Laboratory</h4>
                        <p class="card-text">Total Laboratory Records: <?php echo $laboratory_count; ?></p>
                        <a href="manage_laboratory_tests.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- Medical Records Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-medical-records">
                    <div class="card-body text-center">
                        <h4 class="card-title">Manage Medical Records</h4>
                        <p class="card-text">Total Medical Records: <?php echo $medical_records_count; ?></p>
                        <a href="view_records.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

            <!-- System Settings Card -->
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card card-system-settings">
                    <div class="card-body text-center">
                        <h4 class="card-title">System Settings</h4>
                        <p class="card-text">Manage System Settings</p>
                        <a href="system_settings.php" class="btn btn-primary manage-btn">Manage</a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <footer class="footer mt-5">
        <p>&copy; 2024 Hospital Management System. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
