<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php"); // Redirect to staff login if not authorized
    exit();
}

// Check if ID is provided
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute delete query
        $query = "DELETE FROM billing WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        // Redirect with success message
        header('Location: staff_dashboard.php?message=Billing record deleted successfully.');
        exit();
    } catch (Exception $e) {
        // Rollback transaction and display error message
        $conn->rollback();
        echo "Error deleting record: " . $e->getMessage();
    }
} else {
    echo "No billing record ID provided.";
}

$conn->close();
?>
