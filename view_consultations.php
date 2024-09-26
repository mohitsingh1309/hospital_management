<?php
session_start();
include('db_connection.php');

// Check if user is logged in and has the role of doctor
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'doctor') {
    header('Location: login.php');
    exit();
}

// Fetch consultations for the logged-in doctor
$doctor_id = $_SESSION['doctor_id'];

$query = "SELECT c.id, c.consultation_date, c.consultation_time, u1.username AS patient_name, c.request_status, c.notes
          FROM online_consultations c
          JOIN users u1 ON c.patient_id = u1.id
          WHERE c.doctor_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $doctor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Consultations - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Navigation Bar Content -->
    </nav>

    <header class="bg-primary text-white text-center py-5">
        <div class="container">
            <h1 class="display-4 font-weight-bold">View Consultations</h1>
        </div>
    </header>

    <main class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Patient Name</th>
                            <th>Consultation Date</th>
                            <th>Consultation Time</th>
                            <th>Status</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['patient_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['consultation_date']); ?></td>
                                <td><?php echo htmlspecialchars($row['consultation_time']); ?></td>
                                <td><?php echo htmlspecialchars($row['request_status']); ?></td>
                                <td><?php echo htmlspecialchars($row['notes']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Include Bootstrap and custom JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
