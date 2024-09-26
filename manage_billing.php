<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the right role
if (!isset($_SESSION['username']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'staff')) {
    header("Location: staff_login.php");
    exit();
}

// Fetch all billing records from the database
$billing_query = "SELECT b.*, p.name AS patient_name 
                  FROM billing b
                  JOIN patients p ON b.patient_id = p.id";
$billing_result = $conn->query($billing_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Billing - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Manage Billing</h1>
        
        <!-- Table displaying billing records -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Invoice Number</th>
                    <th>Patient Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($billing = $billing_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($billing['id']); ?></td>
                        <td><?php echo htmlspecialchars($billing['invoice_number']); ?></td>
                        <td><?php echo htmlspecialchars($billing['patient_name']); ?></td>
                        <td><?php echo htmlspecialchars($billing['amount']); ?></td>
                        <td><?php echo htmlspecialchars($billing['payment_date']); ?></td>
                        <td><?php echo htmlspecialchars($billing['payment_method']); ?></td>
                        <td><?php echo htmlspecialchars($billing['status']); ?></td>
                        <td>
                            <a href="edit_billing.php?id=<?php echo htmlspecialchars($billing['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_billing.php?id=<?php echo htmlspecialchars($billing['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this billing record?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
