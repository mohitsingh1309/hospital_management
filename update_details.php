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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $medical_history = $_POST['medical_history'];
    $current_medications = $_POST['current_medications'];
    $allergies = $_POST['allergies'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_phone = $_POST['emergency_contact_phone'];

    // Update patient details
    $query_update = "UPDATE patients SET name = ?, email = ?, phone = ?, address = ?, dob = ?, gender = ?, medical_history = ?, current_medications = ?, allergies = ?, emergency_contact_name = ?, emergency_contact_phone = ? WHERE id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param('sssssssssssi', $name, $email, $phone, $address, $dob, $gender, $medical_history, $current_medications, $allergies, $emergency_contact_name, $emergency_contact_phone, $patient_id);

    if ($stmt_update->execute()) {
        // Close the statement and connection
        $stmt_update->close();
        $conn->close();

        // Redirect to the dashboard
        header('Location: patient_dashboard.php');
        exit();
    } else {
        echo '<div class="alert alert-danger text-center">Error updating details. Please try again.</div>';
    }
}
?>

<?php include('header.php'); ?>

<main class="container mt-5">
    <h2 class="text-center">Update Your Details</h2>

    <form action="update_details.php" method="POST">
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
        </div>
        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Address:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($patient['dob']); ?>" required>
        </div>
        <div class="form-group">
            <label for="gender">Gender:</label>
            <select class="form-control" id="gender" name="gender" required>
                <option value="Male" <?php echo ($patient['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                <option value="Female" <?php echo ($patient['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                <option value="Other" <?php echo ($patient['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="medical_history">Medical History:</label>
            <textarea class="form-control" id="medical_history" name="medical_history" rows="3" required><?php echo htmlspecialchars($patient['medical_history']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="current_medications">Current Medications:</label>
            <textarea class="form-control" id="current_medications" name="current_medications" rows="3" required><?php echo htmlspecialchars($patient['current_medications']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="allergies">Allergies:</label>
            <textarea class="form-control" id="allergies" name="allergies" rows="3" required><?php echo htmlspecialchars($patient['allergies']); ?></textarea>
        </div>
        <div class="form-group">
            <label for="emergency_contact_name">Emergency Contact Name:</label>
            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="<?php echo htmlspecialchars($patient['emergency_contact_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="emergency_contact_phone">Emergency Contact Phone:</label>
            <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone" value="<?php echo htmlspecialchars($patient['emergency_contact_phone']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Details</button>
    </form>
</main>

<?php include('footer.php'); ?>
