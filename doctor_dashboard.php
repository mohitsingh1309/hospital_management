<?php
// Start session
session_start();

// Include database connection file
include 'db_connection.php';

// Check if the user is logged in and has the 'doctor' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: doctor_login.php");
    exit();
}

// Get doctor's ID from session
$doctor_id = $_SESSION['doctor_id'];

// Fetch doctor's details from doctors table
$doctor_sql = "SELECT * FROM doctors WHERE id = ?";
$doctor_stmt = $conn->prepare($doctor_sql);
$doctor_stmt->bind_param("i", $doctor_id);
$doctor_stmt->execute();
$doctor_result = $doctor_stmt->get_result();
$doctor = $doctor_result->fetch_assoc();

// Fetch appointments, consultations, and billing details
$appointments_sql = "SELECT * FROM appointments WHERE doctor_id = ?";
$appointments_stmt = $conn->prepare($appointments_sql);
$appointments_stmt->bind_param("i", $doctor_id);
$appointments_stmt->execute();
$appointments_result = $appointments_stmt->get_result();

$consultations_sql = "SELECT * FROM online_consultations WHERE doctor_id = ?";
$consultations_stmt = $conn->prepare($consultations_sql);
$consultations_stmt->bind_param("i", $doctor_id);
$consultations_stmt->execute();
$consultations_result = $consultations_stmt->get_result();

$billing_sql = "SELECT * FROM billing WHERE doctor_id = ?";
$billing_stmt = $conn->prepare($billing_sql);
$billing_stmt->bind_param("i", $doctor_id);
$billing_stmt->execute();
$billing_result = $billing_stmt->get_result();

// Handle appointment status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    
    // Validate input
    if (!empty($appointment_id) && !empty($status)) {
        $update_appointment_sql = "UPDATE appointments SET status = ? WHERE id = ?";
        $update_appointment_stmt = $conn->prepare($update_appointment_sql);
        $update_appointment_stmt->bind_param("si", $status, $appointment_id);
        $update_appointment_stmt->execute();
        
        // Check if update was successful
        if ($update_appointment_stmt->affected_rows > 0) {
            echo '<p class="alert alert-success">Appointment status updated successfully.</p>';
        } else {
            echo '<p class="alert alert-danger">Failed to update appointment status.</p>';
        }
    } else {
        echo '<p class="alert alert-warning">Invalid data provided for appointment update.</p>';
    }
}

// Handle billing generation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_billing'])) {
    // Ensure that the request method is POST and that the form fields are set
    if (isset($_POST['patient_id'], $_POST['amount'], $_POST['description'])) {
        $patient_id = $_POST['patient_id'];
        $amount = $_POST['amount'];
        $description = $_POST['description'];
        $admission_id = $_POST['admission_id'] ?? null; // Optional

        // Validate input
        if (!empty($patient_id) && !empty($amount) && !empty($description)) {
            $invoice_number = uniqid(); // Generate a unique invoice number
            $payment_date = date('Y-m-d');
            $status = 'Pending'; // Default status
            
            // Insert billing record
            $insert_billing_sql = "INSERT INTO billing (invoice_number, patient_id, amount, payment_date, payment_method, status, description, admission_id, total_amount, billing_description, doctor_id) VALUES (?, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?)";
            $insert_billing_stmt = $conn->prepare($insert_billing_sql);
            $insert_billing_stmt->bind_param("siissssssi", $invoice_number, $patient_id, $amount, $payment_date, $status, $description, $admission_id, $amount, $description, $doctor_id);
            $insert_billing_stmt->execute();
            
            // Check if insertion was successful
            if ($insert_billing_stmt->affected_rows > 0) {
                echo '<p class="alert alert-success">Billing record created successfully.</p>';
            } else {
                echo '<p class="alert alert-danger">Failed to create billing record.</p>';
            }
        } else {
            echo '<p class="alert alert-warning">Invalid data provided for billing generation.</p>';
        }
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-container {
            margin-top: 50px;
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            font-size: 18px;
        }
        .card-body {
            padding: 20px;
        }
        .list-group-item {
            border: none;
        }
        .form-group {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>

<div class="container dashboard-container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Doctor Dashboard
                </div>
                <div class="card-body">
                    <h4>Your Details</h4>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($doctor['name']); ?></p>
                    <p><strong>Specialization:</strong> <?php echo htmlspecialchars($doctor['specialization']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
                    <p><strong>Address:</strong> <?php echo htmlspecialchars($doctor['address']); ?></p>
                    <p><strong>Availability:</strong> <?php echo htmlspecialchars($doctor['availability']); ?></p>
                    <p><strong>Qualification:</strong> <?php echo htmlspecialchars($doctor['qualification']); ?></p>

                    <h4 class="mt-4">Appointments</h4>
                    <?php if ($appointments_result->num_rows > 0): ?>
                        <form method="post" action="">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Appointment ID</th>
                                        <th>Patient ID</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['patient_id']); ?></td>
                                            <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                            <td>
                                                <select name="status" class="form-control">
                                                    <option value="Scheduled" <?php echo $appointment['status'] == 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                                                    <option value="Completed" <?php echo $appointment['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="Cancelled" <?php echo $appointment['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment['id']); ?>">
                                                <button type="submit" name="update_appointment" class="btn btn-primary">Update Status</button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </form>
                    <?php else: ?>
                        <p>No appointments found.</p>
                    <?php endif; ?>

                    <h4 class="mt-4">Consultations</h4>
                    <?php if ($consultations_result->num_rows > 0): ?>
                        <ul class="list-group">
                            <?php while ($consultation = $consultations_result->fetch_assoc()): ?>
                                <li class="list-group-item">Consultation ID: <?php echo htmlspecialchars($consultation['id']); ?> - Patient ID: <?php echo htmlspecialchars($consultation['patient_id']); ?> - Date: <?php echo htmlspecialchars($consultation['consultation_date']); ?> - Time: <?php echo htmlspecialchars($consultation['consultation_time']); ?></li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>No consultations found.</p>
                    <?php endif; ?>

                    <h4 class="mt-4">Billing Details</h4>
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="patient_id">Patient ID</label>
                            <input type="text" class="form-control" id="patient_id" name="patient_id" required>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="admission_id">Admission ID (Optional)</label>
                            <input type="text" class="form-control" id="admission_id" name="admission_id">
                        </div>
                        <button type="submit" name="generate_billing" class="btn btn-success">Generate Billing Record</button>
                    </form>

                    <?php if ($billing_result->num_rows > 0): ?>
                        <h4 class="mt-4">Generated Billings</h4>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Invoice Number</th>
                                    <th>Patient ID</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($billing = $billing_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($billing['invoice_number']); ?></td>
                                        <td><?php echo htmlspecialchars($billing['patient_id']); ?></td>
                                        <td><?php echo htmlspecialchars($billing['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($billing['payment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($billing['status']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No billing records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
