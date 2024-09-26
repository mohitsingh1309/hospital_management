<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the right role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: staff_login.php");
    exit();
}

// Get the staff ID from the URL
if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    // Fetch staff details from the database
    $staff_query = "SELECT * FROM staff WHERE id = ?";
    $stmt = $conn->prepare($staff_query);
    $stmt->bind_param("i", $staff_id);
    $stmt->execute();
    $staff_result = $stmt->get_result();
    $staff = $staff_result->fetch_assoc();

    // Check if the form is submitted for updating
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $position = $_POST['position'];

        // Update the staff record in the database
        $update_query = "UPDATE staff SET name = ?, email = ?, phone = ?, position = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $name, $email, $phone, $position, $staff_id);

        if ($stmt->execute()) {
            echo "<script>alert('Staff details updated successfully.');</script>";
            echo "<script>window.location.href='manage_staff.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
} else {
    echo "<script>alert('Invalid staff ID.');</script>";
    echo "<script>window.location.href='manage_staff.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Staff</h1>

        <!-- Form to edit staff details -->
        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($staff['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($staff['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($staff['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="position">Role/Position</label>
                <input type="text" class="form-control" id="position" name="position" value="<?php echo htmlspecialchars($staff['position']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Staff</button>
            <a href="manage_staff.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
