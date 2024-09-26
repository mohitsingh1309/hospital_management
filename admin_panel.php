<?php
session_start();
if ($_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <?php include('header.php'); ?>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 font-weight-bold">Admin Panel</h1>
            <p class="lead">Manage the hospital system from here.</p>
        </div>
    </header>

    <main class="container mt-5">
        <div class="row">
            <!-- Admin functionalities go here -->
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
