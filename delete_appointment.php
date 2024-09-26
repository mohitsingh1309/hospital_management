<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php"); // Redirect to staff login if not authorized
    exit();
}

if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete appointment record
        $delete_appointment_query = "DELETE FROM appointments WHERE id = ?";
        $stmt = $conn->prepare($delete_appointment_query);
        $stmt->bind_param('i', $appointment_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Redirect with a success message or other action
        header("Location: staff_dashboard.php?message=Appointment deleted successfully.");
        exit();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "No appointment ID provided.";
}
?>
