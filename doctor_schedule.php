<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Doctor Schedules</h1>
        <p class="lead">Find out the availability of our doctors.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <?php
        include('db_connection.php');

        // Fetch doctor schedules from the database
        $query = "SELECT id, name, specialization, availability FROM doctors";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Display each doctor's schedule
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">';
                echo '    <div class="card shadow-sm">';
                echo '        <div class="card-header bg-info text-white">';
                echo '            <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                echo '        </div>';
                echo '        <div class="card-body">';
                echo '            <p class="card-text"><strong>Specialization:</strong> ' . htmlspecialchars($row['specialization']) . '</p>';
                echo '            <p class="card-text"><strong>Availability:</strong> ' . htmlspecialchars($row['availability']) . '</p>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-md-12 text-center">';
            echo '    <p>No schedules available at the moment.</p>';
            echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</main>

<?php include('footer.php'); ?>
