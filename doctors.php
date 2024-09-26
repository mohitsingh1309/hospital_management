<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Our Doctors</h1>
        <p class="lead">Meet our experienced medical professionals.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <?php
        include('db_connection.php');

        // Fetch doctors from the database
        $query = "SELECT * FROM doctors";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            // Display each doctor
            while ($row = $result->fetch_assoc()) {
                echo '<div class="col-md-4 mb-4">';
                echo '    <div class="card shadow-sm">';
                echo '        <div class="card-header d-flex align-items-center">';
                if (!empty($row['profile_picture'])) {
                    echo '            <img src="uploads/' . htmlspecialchars($row['profile_picture']) . '" alt="Profile Picture" class="img-fluid rounded-circle mr-3">';
                } else {
                    echo '            <img src="default-profile.png" alt="Default Profile Picture" class="img-fluid rounded-circle mr-3">';
                }
                echo '            <h5 class="card-title">' . htmlspecialchars($row['name']) . '</h5>';
                echo '        </div>';
                echo '        <div class="card-body">';
                echo '            <p class="card-text"><strong>Specialization:</strong> ' . htmlspecialchars($row['specialization']) . '</p>';
                echo '            <p class="card-text"><strong>Email:</strong> ' . htmlspecialchars($row['email']) . '</p>';
                echo '            <p class="card-text"><strong>Phone:</strong> ' . htmlspecialchars($row['phone']) . '</p>';
                echo '            <p class="card-text"><strong>Address:</strong> ' . htmlspecialchars($row['address']) . '</p>';
                echo '            <p class="card-text"><strong>Availability:</strong> ' . htmlspecialchars($row['availability']) . '</p>';
                echo '        </div>';
                echo '    </div>';
                echo '</div>';
            }
        } else {
            echo '<div class="col-md-12 text-center">';
            echo '    <p>No doctors available at the moment.</p>';
            echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</main>

<?php include('footer.php'); ?>
