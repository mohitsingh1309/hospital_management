<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">My Consultation Requests</h1>
        <p class="lead">View and manage your consultation requests.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php
            include('db_connection.php');

            // Assume patient_id is retrieved from the session or authentication
            if (isset($_SESSION['patient_id'])) {
                $patient_id = $_SESSION['patient_id'];

                // Fetch consultation requests for the logged-in patient
                $query = "SELECT oc.id, d.name AS doctor_name, oc.consultation_date, oc.consultation_time, oc.notes
                          FROM online_consultations oc
                          JOIN doctors d ON oc.doctor_id = d.id
                          WHERE oc.patient_id = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('i', $patient_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo '<table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Consultation Date</th>
                                    <th>Consultation Time</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>';
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . htmlspecialchars($row['doctor_name']) . '</td>
                                <td>' . htmlspecialchars($row['consultation_date']) . '</td>
                                <td>' . htmlspecialchars($row['consultation_time']) . '</td>
                                <td>' . htmlspecialchars($row['notes']) . '</td>
                            </tr>';
                    }
                    echo '</tbody></table>';
                } else {
                    echo '<div class="alert alert-info text-center" role="alert">No consultation requests found.</div>';
                }

                $stmt->close();
            } else {
                echo '<div class="alert alert-danger text-center" role="alert">Session expired. Please log in again.</div>';
            }

            $conn->close();
            ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
