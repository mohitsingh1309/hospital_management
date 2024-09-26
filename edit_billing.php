<?php
session_start();
include('db_connection.php');

// // Check if the user is logged in and has the 'staff' role
// if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'staff') {
//     header("Location: staff_login.php"); // Redirect to staff login if not authorized
//     exit();
// }

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $invoice_number = $_POST['invoice_number'];
    $patient_id = $_POST['patient_id'];
    $amount = $_POST['amount'];
    $payment_date = $_POST['payment_date'];
    $payment_method = $_POST['payment_method'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    $query = "UPDATE billing SET 
                invoice_number = ?, 
                patient_id = ?, 
                amount = ?, 
                payment_date = ?, 
                payment_method = ?, 
                status = ?, 
                description = ? 
              WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sissssss', $invoice_number, $patient_id, $amount, $payment_date, $payment_method, $status, $description, $id);

    if ($stmt->execute()) {
        header('Location: staff_dashboard.php?message=Billing record updated successfully.');
        exit();
    } else {
        $error_message = 'Error updating record: ' . $conn->error;
    }
}

// Fetch record to edit
$id = $_GET['id'];
$query = "SELECT * FROM billing WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$billing = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Billing - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Edit Billing Information</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>
        <form action="edit_billing.php" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($billing['id']); ?>">
            <div class="form-group">
                <label for="invoice_number">Invoice Number</label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="<?php echo htmlspecialchars($billing['invoice_number']); ?>" required>
            </div>
            <div class="form-group">
                <label for="patient_id">Patient ID</label>
                <input type="number" class="form-control" id="patient_id" name="patient_id" value="<?php echo htmlspecialchars($billing['patient_id']); ?>" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" class="form-control" id="amount" name="amount" value="<?php echo htmlspecialchars($billing['amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="payment_date">Payment Date</label>
                <input type="date" class="form-control" id="payment_date" name="payment_date" value="<?php echo htmlspecialchars($billing['payment_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="payment_method">Payment Method</label>
                <input type="text" class="form-control" id="payment_method" name="payment_method" value="<?php echo htmlspecialchars($billing['payment_method']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Paid" <?php echo $billing['status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="Pending" <?php echo $billing['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Failed" <?php echo $billing['status'] == 'Failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($billing['description']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Billing</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
