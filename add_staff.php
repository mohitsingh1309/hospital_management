<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the right role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: staff_login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $department = $_POST['department'];
    $hire_date = $_POST['hire_date'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

    // Insert new staff into the database
    $insert_query = "INSERT INTO staff (name, email, phone, position, department, hire_date, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sssssss", $name, $email, $phone, $position, $department, $hire_date, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Staff member added successfully.');</script>";
        echo "<script>window.location.href='manage_staff.php';</script>";
    } else {
        echo "<script>alert('Error adding staff member: " . $conn->error . "');</script>";
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
    <title>Add Staff - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Add Staff Member</h1>

        <form action="add_staff.php" method="post">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="position">Role</label>
                <input type="text" id="position" name="position" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="department">Department</label>
                <input type="text" id="department" name="department" class="form-control">
            </div>
            <div class="form-group">
                <label for="hire_date">Hire Date</label>
                <input type="date" id="hire_date" name="hire_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Staff</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
