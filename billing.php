<?php
session_start();
include('db_connection.php');

// Initialize variables
$patient_id = isset($_POST['patient_id']) ? intval($_POST['patient_id']) : 0;
$billing_records = [];

// Fetch billing records if patient ID is provided
if ($patient_id > 0) {
    $query = "SELECT b.id, b.invoice_number, b.patient_id, p.name as patient_name, b.amount, b.payment_date, b.payment_method, b.status, b.description, 
                     a.admission_date, a.discharge_date, a.room_number, a.bed_number, a.doctor_id
              FROM billing b
              JOIN patients p ON b.patient_id = p.id
              LEFT JOIN admissions a ON a.id = b.admission_id
              WHERE b.patient_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $patient_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $billing_records = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    // Fetch all billing records if no patient ID is provided
    $query = "SELECT b.id, b.invoice_number, b.patient_id, p.name as patient_name, b.amount, b.payment_date, b.payment_method, b.status, b.description, 
                     a.admission_date, a.discharge_date, a.room_number, a.bed_number, a.doctor_id
              FROM billing b
              JOIN patients p ON b.patient_id = p.id
              LEFT JOIN admissions a ON a.id = b.admission_id";
    $result = $conn->query($query);
    $billing_records = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Billing Information</h1>

        <!-- Form to enter patient ID -->
        <form action="billing.php" method="post" class="mb-4">
            <div class="form-group">
                <label for="patient_id">Enter Patient ID</label>
                <input type="number" class="form-control" id="patient_id" name="patient_id" required>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <?php if ($billing_records): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Invoice Number</th>
                        <th>Patient ID</th>
                        <th>Patient Name</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Description</th>
                        <th>Admission Date</th>
                        <th>Discharge Date</th>
                        <th>Room Number</th>
                        <th>Bed Number</th>
                        <th>Doctor ID</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($billing_records as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['amount']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo htmlspecialchars($row['admission_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['discharge_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['room_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['bed_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['doctor_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['admission_id']); ?></td>
                            <td>
                                <a href="edit_billing.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_billing.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">No billing records found for this patient.</div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
