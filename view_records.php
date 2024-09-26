<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">View Medical Records</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['patient_id'])) {
                $patient_id = $_POST['patient_id'];

                if (is_numeric($patient_id)) {
                    include('db_connection.php');

                    // Fetch patient name
                    $patient_query = "SELECT name FROM patients WHERE id = ?";
                    $patient_stmt = $conn->prepare($patient_query);
                    $patient_stmt->bind_param('i', $patient_id);
                    $patient_stmt->execute();
                    $patient_result = $patient_stmt->get_result();

                    if ($patient_result->num_rows > 0) {
                        $patient = $patient_result->fetch_assoc();
                        echo '<h3 class="text-center">Patient ID: ' . htmlspecialchars($patient_id) . ' - ' . htmlspecialchars($patient['name']) . '</h3>';
                        echo '<a href="add_record.php?patient_id=' . htmlspecialchars($patient_id) . '" class="btn btn-primary mb-3">Add New Record</a>';

                        // Fetch and display medical records
                        $record_query = "SELECT * FROM medical_records WHERE patient_id = ?";
                        $record_stmt = $conn->prepare($record_query);
                        $record_stmt->bind_param('i', $patient_id);
                        $record_stmt->execute();
                        $result = $record_stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo '<table class="table table-bordered">';
                            echo '<thead>';
                            echo '<tr>';
                            echo '<th>Record Date</th>';
                            echo '<th>Diagnosis</th>';
                            echo '<th>Treatment</th>';
                            echo '<th>Notes</th>';
                            echo '<th>Actions</th>';
                            echo '</tr>';
                            echo '</thead>';
                            echo '<tbody>';

                            while ($row = $result->fetch_assoc()) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['record_date']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['diagnosis']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['treatment']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['notes']) . '</td>';
                                echo '<td>';
                                echo '<a href="edit_record.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a> ';
                                echo '<a href="delete_record.php?id=' . $row['id'] . '&patient_id=' . htmlspecialchars($patient_id) . '" class="btn btn-danger btn-sm">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                            }

                            echo '</tbody>';
                            echo '</table>';
                        } else {
                            echo '<p class="text-center">No medical records found for this patient.</p>';
                        }

                        $record_stmt->close();
                    } else {
                        echo '<div class="alert alert-danger text-center" role="alert">Patient not found. Please enter a valid Patient ID.</div>';
                    }

                    $patient_stmt->close();
                    $conn->close();
                } else {
                    echo '<div class="alert alert-danger text-center" role="alert">Invalid Patient ID. Please enter a valid number.</div>';
                }
            }
            ?>

            <form action="view_records.php" method="post" class="mb-4">
                <div class="form-group">
                    <label for="patient_id">Enter Patient ID</label>
                    <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
