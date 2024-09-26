<?php
include('header.php');
include('db_connection.php');

// Handle form submission for adding a new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_appointment'])) {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('iisss', $patient_id, $doctor_id, $appointment_date, $appointment_time, $status);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Appointment added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Handle form submission for updating appointment details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_appointment'])) {
    $id = $_POST['id'];
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $status = $_POST['status'];

    $query = "UPDATE appointments SET patient_id = ?, doctor_id = ?, appointment_date = ?, appointment_time = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('iisssi', $patient_id, $doctor_id, $appointment_date, $appointment_time, $status, $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Appointment updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Handle form submission for deleting an appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_appointment'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die('Prepare failed: ' . $conn->error);
    }
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Appointment deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Fetch appointments to display
$query = "SELECT a.id, a.patient_id, a.doctor_id, a.appointment_date, a.appointment_time, a.status, p.name as patient_name, d.name as doctor_name 
          FROM appointments a
          JOIN patients p ON a.patient_id = p.id
          JOIN doctors d ON a.doctor_id = d.id";
$result = $conn->query($query);
if (!$result) {
    die('Query failed: ' . $conn->error);
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Manage Appointments</h1>
        <p class="lead">Add, edit, or delete appointments in the system.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Form to add a new appointment -->
            <h2>Add New Appointment</h2>
            <form action="manage_appointments.php" method="post">
                <div class="form-group">
                    <label for="patient_id">Patient</label>
                    <select class="form-control" id="patient_id" name="patient_id" required>
                        <?php
                        // Fetch and display patients
                        $query = "SELECT id, name FROM patients";
                        $patients = $conn->query($query);
                        while ($row = $patients->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctor_id">Doctor</label>
                    <select class="form-control" id="doctor_id" name="doctor_id" required>
                        <?php
                        // Fetch and display doctors
                        $query = "SELECT id, name FROM doctors";
                        $doctors = $conn->query($query);
                        while ($row = $doctors->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                        }
                        ?>
                    </select>
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
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Scheduled">Scheduled</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" name="add_appointment">Add Appointment</button>
            </form>

            <hr>

            <!-- Display existing appointments -->
            <h2>Existing Appointments</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['doctor_name']; ?></td>
                            <td><?php echo $row['appointment_date']; ?></td>
                            <td><?php echo $row['appointment_time']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                                <!-- Edit button -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo $row['id']; ?>" data-patient_id="<?php echo $row['patient_id']; ?>" data-doctor_id="<?php echo $row['doctor_id']; ?>" data-appointment_date="<?php echo $row['appointment_date']; ?>" data-appointment_time="<?php echo $row['appointment_time']; ?>" data-status="<?php echo $row['status']; ?>">Edit</button>

                                <!-- Delete button -->
                                <form action="manage_appointments.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_appointment">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Edit Appointment Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Appointment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_appointments.php" method="post">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_patient_id">Patient</label>
                        <select class="form-control" id="edit_patient_id" name="patient_id" required>
                            <?php
                            // Fetch and display patients
                            $query = "SELECT id, name FROM patients";
                            $patients = $conn->query($query);
                            while ($row = $patients->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_doctor_id">Doctor</label>
                        <select class="form-control" id="edit_doctor_id" name="doctor_id" required>
                            <?php
                            // Fetch and display doctors
                            $query = "SELECT id, name FROM doctors";
                            $doctors = $conn->query($query);
                            while ($row = $doctors->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_appointment_date">Appointment Date</label>
                        <input type="date" class="form-control" id="edit_appointment_date" name="appointment_date" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_appointment_time">Appointment Time</label>
                        <input type="time" class="form-control" id="edit_appointment_time" name="appointment_time" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="Scheduled">Scheduled</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_appointment">Update Appointment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate edit modal with appointment data
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var patient_id = button.data('patient_id');
        var doctor_id = button.data('doctor_id');
        var appointment_date = button.data('appointment_date');
        var appointment_time = button.data('appointment_time');
        var status = button.data('status');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_patient_id').val(patient_id);
        modal.find('#edit_doctor_id').val(doctor_id);
        modal.find('#edit_appointment_date').val(appointment_date);
        modal.find('#edit_appointment_time').val(appointment_time);
        modal.find('#edit_status').val(status);
    });
</script>

<?php include('footer.php'); ?>
