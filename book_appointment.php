<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Book an Appointment</h1>
        <p class="lead">Fill in the form below to book your appointment.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="text-center">Appointment Booking Form</h2>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include('db_connection.php');

                // Collect and sanitize user input
                $patient_id = $_POST['patient_id'];
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $appointment_date = $_POST['appointment_date'];
                $appointment_time = $_POST['appointment_time'];
                $doctor_id = $_POST['doctor_id'];
                $reason = $_POST['reason'];

                // Fetch doctor availability
                $query = "SELECT availability FROM doctors WHERE id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $doctor_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $doctor = $result->fetch_assoc();
                    $availability = $doctor['availability']; // Example format: "Mon, Tue, Wed, Thu, Fri"

                    // Convert appointment date to day of the week
                    $appointment_day = date('D', strtotime($appointment_date)); // Example: "Mon"

                    // Check if the appointment day is within doctor's availability
                    if (stripos($availability, $appointment_day) === false) {
                        echo '<div class="alert alert-danger" role="alert">The selected date is not available for the chosen doctor. Please select a different date.</div>';
                    } else {
                        // Insert data into the database
                        $query = "INSERT INTO appointments (patient_id, email, phone, appointment_date, appointment_time, doctor_id, reason) 
                                  VALUES (?, ?, ?, ?, ?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        $stmt->bind_param('issssis', $patient_id, $email, $phone, $appointment_date, $appointment_time, $doctor_id, $reason);
                        
                        if ($stmt->execute()) {
                            // Redirect to home page with success message
                            header("Location: patient_dashboard.php?appointment_success=1");
                            exit();
                        } else {
                            echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                        }
                    }
                } else {
                    echo '<div class="alert alert-danger" role="alert">Invalid Doctor ID. Please select a valid doctor.</div>';
                }
                
                $stmt->close();
                $conn->close();
            }
            ?>

            <form action="book_appointment.php" method="post">
                <div class="form-group">
                    <label for="patient_id">Patient ID</label>
                    <input type="number" class="form-control" id="patient_id" name="patient_id" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="appointment_date">Appointment Date</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required>
                </div>
                <div class="form-group">
                    <label for="appointment_time">Appointment Time</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
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
                    <label for="reason">Reason for Appointment</label>
                    <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Book Appointment</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
