<?php
include('header.php');
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Patient Login</h1>
        <p class="lead">Login to access your dashboard and view your details.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center">Login</h2>
            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include('db_connection.php');

                // Collect and sanitize user input
                $email = trim($_POST['email']);
                $password = trim($_POST['password']);

                // Basic validation
                if (empty($email) || empty($password)) {
                    echo '<div class="alert alert-danger text-center">Please fill in all fields.</div>';
                } else {
                    // Check if email exists in the user_login table
                    $query = "SELECT * FROM user_login WHERE username = ? AND role = 'patient'";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param('s', $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();

                        // Verify password
                        if (password_verify($password, $row['password'])) {
                            // Start session and set session variables
                            session_start();
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['username'] = $row['username'];
                            $_SESSION['role'] = $row['role'];

                            // Redirect to the dashboard
                            header('Location: patient_dashboard.php');
                            exit();
                        } else {
                            echo '<div class="alert alert-danger text-center">Invalid email or password.</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger text-center">Invalid email or password.</div>';
                    }
                }
            }
            ?>

            <form action="patient_login.php" method="post">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>
        </div>
    </div>
</main>

<?php
include('footer.php');
?>
