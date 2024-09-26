<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Edit Medical Record</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="text-center">Edit Medical Record</h2>

            <?php
            include('db_connection.php');

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $patient_id = $_POST['patient_id'];
                $record_date = $_POST['record_date'];
                $diagnosis = $_POST['diagnosis'];
                $treatment = $_POST['treatment'];
                $notes = $_POST['notes'];

                $query = "UPDATE medical_records SET record_date = ?, diagnosis = ?, treatment = ?, notes = ? WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ssssi', $record_date, $diagnosis, $treatment, $notes, $id);

                if ($stmt->execute()) {
                    echo '<div class="alert alert-success" role="alert">Record updated successfully!</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                }

                $stmt->close();
                $conn->close();

                echo '<a href="view_records.php?patient_id=' . htmlspecialchars($patient_id) . '" class="btn btn-primary">Back to Records</a>';
            } else {
                $id = $_GET['id'];
                $query = "SELECT * FROM medical_records WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $record = $result->fetch_assoc();
                $stmt->close();
            ?>
            <form action="edit_record.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($record['id']); ?>">
                <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($record['patient_id']); ?>">
                <div class="form-group">
                    <label for="record_date">Record Date</label>
                    <input type="date" class="form-control" id="record_date" name="record_date" value="<?php echo htmlspecialchars($record['record_date']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="diagnosis">Diagnosis</label>
                    <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required><?php echo htmlspecialchars($record['diagnosis']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="treatment">Treatment</label>
                    <textarea class="form-control" id="treatment" name="treatment" rows="3" required><?php echo htmlspecialchars($record['treatment']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" required><?php echo htmlspecialchars($record['notes']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-success">Update Record</button>
            </form>
            <?php
            }
            ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
