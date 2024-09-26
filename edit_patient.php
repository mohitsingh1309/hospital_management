<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the appropriate role (staff or admin)
if (!isset($_SESSION['username']) || !in_array($_SESSION['role'], ['staff', 'admin'])) {
    header("Location: staff_login.php");
    exit();
}

// Fetch the patient details based on the ID from the URL
if (isset($_GET['id'])) {
    $patient_id = intval($_GET['id']);
    $query = "SELECT * FROM patients WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "Patient not found.";
        exit();
    }
    $stmt->close();
} else {
    echo "No patient ID provided.";
    exit();
}

// Update the patient details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];

    $update_query = "UPDATE patients SET name = ?, email = ?, phone = ?, address = ?, dob = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssssi", $name, $email, $phone, $address, $dob, $patient_id);

    if ($stmt->execute()) {
        header("Location: staff_dashboard.php");
        exit();
    } else {
        echo "Error updating patient record.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Patient</h1>

        <!-- Edit Patient Form -->
        <form method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($patient['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($patient['address']); ?>" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($patient['dob']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Patient</button>
            <a href="staff_dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
