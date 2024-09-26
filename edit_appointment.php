<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];
    
    // Prepare the update query
    $query = "UPDATE appointments SET 
                patient_id = ?, 
                doctor_id = ?, 
                appointment_date = ?, 
                appointment_time = ?, 
                status = ? 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iisssi', $patient_id, $doctor_id, $appointment_date, $appointment_time, $status, $id);

    if ($stmt->execute()) {
        header('Location: staff_dashboard.php'); // Redirect back to dashboard on success
        exit();
    } else {
        $error_message = 'Error updating appointment: ' . $conn->error;
    }
}

// Fetch record to edit
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $appointment = $result->fetch_assoc();
    
    if (!$appointment) {
        die('Appointment not found.');
    }

    // Fetch patients and doctors for the dropdowns
    $patients_query = "SELECT id, name FROM patients";
    $patients_result = $conn->query($patients_query);

    $doctors_query = "SELECT id, name FROM doctors";
    $doctors_result = $conn->query($doctors_query);
} else {
    die('Invalid ID.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Appointment</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="edit_appointment.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($appointment['id']); ?>">
            <div class="form-group">
                <label for="patient_id">Patient</label>
                <select class="form-control" id="patient_id" name="patient_id" required>
                    <?php while ($patient = $patients_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($patient['id']); ?>" <?php echo $appointment['patient_id'] == $patient['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($patient['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="doctor_id">Doctor</label>
                <select class="form-control" id="doctor_id" name="doctor_id" required>
                    <?php while ($doctor = $doctors_result->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($doctor['id']); ?>" <?php echo $appointment['doctor_id'] == $doctor['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($doctor['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="appointment_date">Date</label>
                <input type="date" class="form-control" id="appointment_date" name="appointment_date" value="<?php echo htmlspecialchars($appointment['appointment_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="appointment_time">Time</label>
                <input type="time" class="form-control" id="appointment_time" name="appointment_time" value="<?php echo htmlspecialchars($appointment['appointment_time']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Scheduled" <?php echo $appointment['status'] == 'Scheduled' ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="Completed" <?php echo $appointment['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="Cancelled" <?php echo $appointment['status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Update Appointment</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
