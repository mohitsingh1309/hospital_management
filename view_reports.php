<?php
include('header.php');
include('db_connection.php');

// Initialize variables
$report_type = '';
$filter = '';
$query = '';
$result = null;

// Handle report filtering
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['filter_reports'])) {
    $report_type = $_POST['report_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    
    if ($report_type == 'consultations') {
        $filter = "WHERE consultation_date BETWEEN '$start_date' AND '$end_date'";
    } elseif ($report_type == 'billing') {
        $filter = "WHERE payment_date BETWEEN '$start_date' AND '$end_date'";
    }
}

// Fetch filtered reports
if ($report_type == 'consultations') {
    $query = "SELECT * FROM online_consultations $filter";
} elseif ($report_type == 'billing') {
    $query = "SELECT * FROM billing $filter";
}

if (!empty($query)) {
    $result = $conn->query($query);
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">View Reports</h1>
        <p class="lead">Filter and view reports based on the selected criteria.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Filter Reports</h2>
            <form action="view_reports.php" method="post">
                <div class="form-group">
                    <label for="report_type">Report Type</label>
                    <select class="form-control" id="report_type" name="report_type" required>
                        <option value="">Select Report Type</option>
                        <option value="consultations" <?php if ($report_type == 'consultations') echo 'selected'; ?>>Consultations</option>
                        <option value="billing" <?php if ($report_type == 'billing') echo 'selected'; ?>>Billing</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
                <button type="submit" class="btn btn-primary" name="filter_reports">Generate Report</button>
            </form>

            <?php if ($report_type): ?>
                <h2 class="mt-5">Report Results</h2>
                <?php if ($result && $result->num_rows > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <?php if ($report_type == 'consultations'): ?>
                                    <th>ID</th>
                                    <th>Patient ID</th>
                                    <th>Doctor ID</th>
                                    <th>Consultation Date</th>
                                    <th>Consultation Time</th>
                                    <th>Request Status</th>
                                    <th>Notes</th>
                                <?php elseif ($report_type == 'billing'): ?>
                                    <th>ID</th>
                                    <th>Invoice Number</th>
                                    <th>Patient ID</th>
                                    <th>Amount</th>
                                    <th>Payment Date</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <?php if ($report_type == 'consultations'): ?>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['doctor_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['consultation_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['consultation_time']); ?></td>
                                        <td><?php echo htmlspecialchars($row['request_status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['notes']); ?></td>
                                    <?php elseif ($report_type == 'billing'): ?>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['invoice_number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['patient_id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['payment_method']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>No records found for the selected criteria.</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
