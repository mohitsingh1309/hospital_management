<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Request Online Consultation</h1>
        <p class="lead">Fill in the form below to request an online consultation with one of our doctors.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="text-center">Consultation Request Form</h2>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include('db_connection.php');

                // Collect and sanitize user input
                $patient_id = $_POST['patient_id'];
                $doctor_id = $_POST['doctor_id'];
                $consultation_date = $_POST['consultation_date'];
                $consultation_time = $_POST['consultation_time'];
                $notes = $_POST['notes'];

                // Insert data into the database
                $query = "INSERT INTO online_consultations (patient_id, doctor_id, consultation_date, consultation_time, notes) 
                          VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('issss', $patient_id, $doctor_id, $consultation_date, $consultation_time, $notes);

                if ($stmt->execute()) {
                    // Redirect to home page with success message
                    header("Location: index.php?consultation_success=1");
                    exit();
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                }

                $stmt->close();
                $conn->close();
            }
            ?>

            <form action="request_consultation.php" method="post">
                <div class="form-group">
                    <label for="patient_id">Patient ID</label>
                    <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor</label>
                    <select class="form-control" id="doctor_id" name="doctor_id" required>
                        <?php
                        include('db_connection.php');
                        $query = "SELECT id, name FROM doctors";
                        $result = $conn->query($query);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No doctors available</option>';
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="consultation_date">Consultation Date</label>
                    <input type="date" class="form-control" id="consultation_date" name="consultation_date" required>
                </div>
                <div class="form-group">
                    <label for="consultation_time">Consultation Time</label>
                    <input type="time" class="form-control" id="consultation_time" name="consultation_time" required>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Request Consultation</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
