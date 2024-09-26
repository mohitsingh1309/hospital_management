<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Manage Consultations</h1>
        <p class="lead">Review and manage online consultation requests.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php
            include('db_connection.php');

            // Fetch consultation requests for the logged-in doctor
            $doctor_id = $_SESSION['doctor_id']; // Assume doctor ID is stored in session
            $query = "SELECT * FROM online_consultations WHERE doctor_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('i', $doctor_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Patient ID</th>';
                echo '<th>Consultation Date</th>';
                echo '<th>Consultation Time</th>';
                echo '<th>Notes</th>';
                echo '<th>Status</th>';
                echo '<th>Actions</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                while ($row = $result->fetch_assoc()) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($row['patient_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['consultation_date']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['consultation_time']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['notes']) . '</td>';
                    echo '<td>' . htmlspecialchars($row['request_status']) . '</td>';
                    echo '<td>';
                    echo '<a href="approve_consultation.php?id=' . $row['id'] . '" class="btn btn-success btn-sm">Approve</a> ';
                    echo '<a href="reject_consultation.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Reject</a>';
                    echo '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
            } else {
                echo '<p class="text-center">No consultation requests at the moment.</p>';
            }

            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
