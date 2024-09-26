<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the appropriate login page based on the user's role
if (isset($_SESSION['role'])) {
    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: admin_login.php");
            break;
        case 'doctor':
            header("Location: doctor_login.php");
            break;
        case 'staff':
            header("Location: staff_login.php");
            break;
        case 'patient':
            header("Location: patient_login.php");
            break;
        default:
            header("Location: index.php"); // Default to homepage or index page
            break;
    }
} else {
    header("Location: index.php"); // If role is not set, redirect to the homepage
}
exit();
?>
