<?php
// Start session and include database connection
session_start();
include('db_connect.php');

// Check if user is logged in and is a patient
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    header("Location: patient_login.php");
    exit();
}

// Get patient information
$user_id = $_SESSION['user_id'];

// Query to fetch patient appointments
$query = "SELECT * FROM appointments WHERE patient_id = ? ORDER BY appointment_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include('header.php');
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">View Appointments</h1>
        <p class="lead">Here are your upcoming and past appointments.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-lg border-light">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Your Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if ($result->num_rows > 0): ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Doctor</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                                        <td><?php echo htmlspecialchars($row['appointment_time']); ?></td>
                                        <td>
                                            <?php
                                            // Fetch doctor name
                                            $doctor_query = "SELECT name FROM doctors WHERE id = ?";
                                            $doctor_stmt = $conn->prepare($doctor_query);
                                            $doctor_stmt->bind_param("i", $row['doctor_id']);
                                            $doctor_stmt->execute();
                                            $doctor_result = $doctor_stmt->get_result();
                                            $doctor = $doctor_result->fetch_assoc();
                                            echo htmlspecialchars($doctor['name']);
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p class="text-center">You have no appointments scheduled.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
