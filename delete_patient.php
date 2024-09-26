<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the 'staff' role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
    header("Location: staff_login.php"); // Redirect to staff login if not authorized
    exit();
}

if (isset($_GET['id'])) {
    $patient_id = $_GET['id'];

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Delete related online consultations records
        $delete_online_consultations_query = "DELETE FROM online_consultations WHERE patient_id = ?";
        $stmt = $conn->prepare($delete_online_consultations_query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $stmt->close();

        // Delete related billing records
        $delete_billing_query = "DELETE FROM billing WHERE patient_id = ?";
        $stmt = $conn->prepare($delete_billing_query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $stmt->close();

        // Delete related user login records
        $delete_user_login_query = "DELETE FROM user_login WHERE user_id = ?";
        $stmt = $conn->prepare($delete_user_login_query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $stmt->close();

        // Delete patient record
        $delete_patient_query = "DELETE FROM patients WHERE id = ?";
        $stmt = $conn->prepare($delete_patient_query);
        $stmt->bind_param('i', $patient_id);
        $stmt->execute();
        $stmt->close();

        // Commit transaction
        $conn->commit();

        echo "Patient record and related records deleted successfully.";
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    $conn->close();
}
?>
