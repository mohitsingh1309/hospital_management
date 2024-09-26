<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $admission_date = $_POST['admission_date'];
    $room_number = $_POST['room_number'];
    $bed_number = $_POST['bed_number'];
    $diagnosis = $_POST['diagnosis'];
    $discharge_date = $_POST['discharge_date']; // Add discharge date field

    // Calculate the total billing (example logic)
    $bed_charge_per_day = 100; // Example rate
    $days_of_stay = (strtotime($discharge_date) - strtotime($admission_date)) / (60 * 60 * 24);
    $total_billing = $bed_charge_per_day * $days_of_stay;

    // Insert admission record
    $query = "INSERT INTO admissions (patient_id, doctor_id, admission_date, room_number, bed_number, diagnosis, discharge_date, total_billing) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iissssis', $patient_id, $doctor_id, $admission_date, $room_number, $bed_number, $diagnosis, $discharge_date, $total_billing);
    $stmt->execute();
    $admission_id = $stmt->insert_id;
    $stmt->close();

    // Insert initial billing record
    $query = "INSERT INTO billing (admission_id, amount, payment_date, payment_method, description) VALUES (?, ?, NOW(), 'Cash', 'Initial billing')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('id', $admission_id, $total_billing);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    echo 'Patient admitted successfully.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Patient - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include('header.php'); ?>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Admit Patient</h1>
        </div>
    </header>

    <main class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <form action="admit_patient.php" method="post">
                    <div class="form-group">
                        <label for="patient_id">Patient ID</label>
                        <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                    </div>
                    <div class="form-group">
                        <label for="doctor_id">Doctor ID</label>
                        <input type="number" class="form-control" id="doctor_id" name="doctor_id" required>
                    </div>
                    <div class="form-group">
                        <label for="admission_date">Admission Date</label>
                        <input type="date" class="form-control" id="admission_date" name="admission_date" required>
                    </div>
                    <div class="form-group">
                        <label for="room_number">Room Number</label>
                        <input type="text" class="form-control" id="room_number" name="room_number" required>
                    </div>
                    <div class="form-group">
                        <label for="bed_number">Bed Number</label>
                        <input type="text" class="form-control" id="bed_number" name="bed_number" required>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="discharge_date">Expected Discharge Date</label>
                        <input type="date" class="form-control" id="discharge_date" name="discharge_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Admit Patient</button>
                </form>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
