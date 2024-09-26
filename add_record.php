<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $patient_id = $_POST['patient_id'];
    $record_date = $_POST['record_date'];
    $diagnosis = $_POST['diagnosis'];
    $treatment = $_POST['treatment'];
    $notes = $_POST['notes'];

    // Insert the new record into the database
    $query = "INSERT INTO medical_records (patient_id, record_date, diagnosis, treatment, notes) 
              VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issss', $patient_id, $record_date, $diagnosis, $treatment, $notes);

    if ($stmt->execute()) {
        // Redirect to view_records.php with patient_id as a query parameter
        header('Location: view_records.php?patient_id=' . urlencode($patient_id) . '&appointment_success=1');
        exit();
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medical Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include('header.php'); ?>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 font-weight-bold">Add Medical Record</h1>
        </div>
    </header>

    <main class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h2 class="text-center">Add Record for Patient ID: <?php echo htmlspecialchars($_GET['patient_id']); ?></h2>

                <form action="add_record.php" method="post">
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($_GET['patient_id']); ?>">
                    <div class="form-group">
                        <label for="record_date">Record Date</label>
                        <input type="date" class="form-control" id="record_date" name="record_date" required>
                    </div>
                    <div class="form-group">
                        <label for="diagnosis">Diagnosis</label>
                        <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="treatment">Treatment</label>
                        <textarea class="form-control" id="treatment" name="treatment" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Add Record</button>
                </form>
            </div>
        </div>
    </main>

    <?php include('footer.php'); ?>
</body>
</html>
