<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'patient') {
    header('Location: patient_login.php');
    exit();
}

include('db_connection.php');

// Fetch patient details
$patient_id = $_SESSION['user_id'];
$query_patient = "SELECT * FROM patients WHERE id = ?";
$stmt_patient = $conn->prepare($query_patient);
$stmt_patient->bind_param('i', $patient_id);
$stmt_patient->execute();
$result_patient = $stmt_patient->get_result();

if ($result_patient->num_rows == 1) {
    $patient = $result_patient->fetch_assoc();
} else {
    echo '<div class="alert alert-danger text-center">Patient details not found.</div>';
    exit();
}

// Fetch appointments
$query_appointments = "SELECT * FROM appointments WHERE patient_id = ?";
$stmt_appointments = $conn->prepare($query_appointments);
$stmt_appointments->bind_param('i', $patient_id);
$stmt_appointments->execute();
$result_appointments = $stmt_appointments->get_result();

// Fetch consultations
$query_consultations = "SELECT * FROM online_consultations WHERE patient_id = ?";
$stmt_consultations = $conn->prepare($query_consultations);
$stmt_consultations->bind_param('i', $patient_id);
$stmt_consultations->execute();
$result_consultations = $stmt_consultations->get_result();

// Fetch billing details
$query_billing = "SELECT * FROM billing WHERE patient_id = ?";
$stmt_billing = $conn->prepare($query_billing);
$stmt_billing->bind_param('i', $patient_id);
$stmt_billing->execute();
$result_billing = $stmt_billing->get_result();
?>

<?php include('header.php'); ?>

<header class="bg-success text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Patient Dashboard</h1>
        <p class="lead">Welcome, <?php echo htmlspecialchars($patient['name']); ?>!</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <!-- Your Details Card -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="card-title">Your Details</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></li>
                        <li class="list-group-item"><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></li>
                        <li class="list-group-item"><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></li>
                        <li class="list-group-item"><strong>Address:</strong> <?php echo htmlspecialchars($patient['address']); ?></li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo htmlspecialchars($patient['dob']); ?></li>
                        <li class="list-group-item"><strong>Gender:</strong> <?php echo htmlspecialchars($patient['gender']); ?></li>
                        <li class="list-group-item"><strong>Medical History:</strong> <?php echo htmlspecialchars($patient['medical_history']); ?></li>
                        <li class="list-group-item"><strong>Current Medications:</strong> <?php echo htmlspecialchars($patient['current_medications']); ?></li>
                        <li class="list-group-item"><strong>Allergies:</strong> <?php echo htmlspecialchars($patient['allergies']); ?></li>
                        <li class="list-group-item"><strong>Emergency Contact Name:</strong> <?php echo htmlspecialchars($patient['emergency_contact_name']); ?></li>
                        <li class="list-group-item"><strong>Emergency Contact Phone:</strong> <?php echo htmlspecialchars($patient['emergency_contact_phone']); ?></li>
                    </ul>
                    <a href="update_details.php" class="btn btn-primary btn-block mt-4">Update Details</a>
                </div>
            </div>
        </div>

        <!-- Book Appointment Card -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title">Book Appointment</h4>
                </div>
                <div class="card-body text-center">
                    <a href="book_appointment.php" class="btn btn-success btn-lg">Book Now</a>
                </div>
            </div>
        </div>

        <!-- View Appointments Card -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h4 class="card-title">View Appointments</h4>
                </div>
                <div class="card-body">
                    <?php if ($result_appointments->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($appointment = $result_appointments->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong>Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?><br>
                                    <strong>Time:</strong> <?php echo htmlspecialchars($appointment['appointment_time']); ?><br>
                                    <strong>Doctor:</strong> <?php echo htmlspecialchars($appointment['doctor_id']); ?><br>
                                    <strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No appointments found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Consultation Status Card -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="card-title">Consultation Status</h4>
                </div>
                <div class="card-body">
                    <?php if ($result_consultations->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($consultation = $result_consultations->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong>Date:</strong> <?php echo htmlspecialchars($consultation['consultation_date']); ?><br>
                                    <strong>Time:</strong> <?php echo htmlspecialchars($consultation['consultation_time']); ?><br>
                                    <strong>Notes:</strong> <?php echo htmlspecialchars($consultation['notes']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No consultations found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Billing Details Card -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="card-title">Billing Details</h4>
                </div>
                <div class="card-body">
                    <?php if ($result_billing->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($bill = $result_billing->fetch_assoc()): ?>
                                <li class="list-group-item">
                                    <strong>Invoice:</strong> <?php echo htmlspecialchars($bill['invoice_number']); ?><br>
                                    <strong>Amount:</strong> <?php echo htmlspecialchars($bill['amount']); ?><br>
                                    <strong>Status:</strong> <?php echo htmlspecialchars($bill['status']); ?>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No billing records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <a href="logout.php" class="btn btn-danger btn-block mt-4">Logout</a>
</main>

<?php include('footer.php'); ?>

<?php
// Close statements and connection
$stmt_patient->close();
$stmt_appointments->close();
$stmt_consultations->close();
$stmt_billing->close();
$conn->close();
?>
