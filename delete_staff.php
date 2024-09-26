<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the right role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: staff_login.php");
    exit();
}

// Check if a staff ID is provided in the URL
if (isset($_GET['id'])) {
    $staff_id = $_GET['id'];

    // Delete the staff record from the database
    $delete_query = "DELETE FROM staff WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $staff_id);

    if ($stmt->execute()) {
        echo "<script>alert('Staff member deleted successfully.');</script>";
    } else {
        echo "<script>alert('Error deleting staff member: " . $conn->error . "');</script>";
    }

    // Redirect back to the manage staff page
    echo "<script>window.location.href='manage_staff.php';</script>";
} else {
    echo "<script>alert('Invalid staff ID.');</script>";
    echo "<script>window.location.href='manage_staff.php';</script>";
}

$conn->close();
?>
