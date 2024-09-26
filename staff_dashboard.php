<?php
session_start();
include('db_connection.php');

// Check if the staff is logged in
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php");
    exit();
}

// Fetch necessary data for the dashboard
$patients_query = "SELECT * FROM patients";
$patients_result = $conn->query($patients_query);

$appointments_query = "SELECT a.id, p.name AS patient_name, d.name AS doctor_name, 
                       a.appointment_date, a.appointment_time, a.status 
                       FROM appointments a
                       JOIN patients p ON a.patient_id = p.id
                       JOIN doctors d ON a.doctor_id = d.id";
$appointments_result = $conn->query($appointments_query);

$billing_query = "SELECT b.id, b.invoice_number, p.name AS patient_name, 
                  b.amount, b.payment_date, b.payment_method, b.status
                  FROM billing b
                  JOIN patients p ON b.patient_id = p.id";
$billing_result = $conn->query($billing_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Staff Dashboard</h1>
        
        <!-- Logout Button in the Top-Right Corner -->
        <div class="text-right mb-4">
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <!-- Patient Management Section -->
        <h3>Manage Patients</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>DOB</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($patient = $patients_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($patient['id']); ?></td>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                        <td><?php echo htmlspecialchars($patient['email']); ?></td>
                        <td><?php echo htmlspecialchars($patient['phone']); ?></td>
                        <td><?php echo htmlspecialchars($patient['address']); ?></td>
                        <td><?php echo htmlspecialchars($patient['dob']); ?></td>
                        <td>
                            <a href="edit_patient.php?id=<?php echo htmlspecialchars($patient['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Appointment Management Section -->
        <h3>Manage Appointments</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($appointment = $appointments_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['id']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_time']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['status']); ?></td>
                        <td>
                            <a href="edit_appointment.php?id=<?php echo htmlspecialchars($appointment['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_appointment.php?id=<?php echo htmlspecialchars($appointment['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this appointment?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Billing Management Section -->
        <h3>Manage Billing</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Patient Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($billing = $billing_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($billing['id']); ?></td>
                        <td><?php echo htmlspecialchars($billing['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($billing['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($billing['amount']); ?></td>
                        <td><?php echo htmlspecialchars($billing['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($billing['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($billing['status']); ?></td>
                        <td>
                            <a href="edit_billing.php?id=<?php echo htmlspecialchars($billing['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_billing.php?id=<?php echo htmlspecialchars($billing['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this billing record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
