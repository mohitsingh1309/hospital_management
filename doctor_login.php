<?php
// Start session
session_start();

// Include database connection file
include 'db_connection.php';

// Initialize variables for error messages
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get username and password from form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare and execute SQL query to fetch user data
    $sql = "SELECT * FROM users WHERE username = ? AND role = 'doctor'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        // Fetch user data
        $row = $result->fetch_assoc();

        // Verify password
        if ($password === $row['password']) {
            // Get the doctor's ID from the doctors table
            $doctor_sql = "SELECT id FROM doctors WHERE email = ?";
            $doctor_stmt = $conn->prepare($doctor_sql);
            $doctor_stmt->bind_param("s", $row['username']); // Assuming username is the email
            $doctor_stmt->execute();
            $doctor_result = $doctor_stmt->get_result();

            if ($doctor_result->num_rows == 1) {
                $doctor_row = $doctor_result->fetch_assoc();

                // Set session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['doctor_id'] = $doctor_row['id']; // Set doctor ID for later use

                // Redirect to doctor dashboard
                header("Location: doctor_dashboard.php");
                exit();
            } else {
                $error = "Doctor not found.";
            }
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        .login-container {
            margin-top: 100px;
        }
        .login-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .login-card .card-header {
            background-color: #007bff;
            color: #fff;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .login-card .card-body {
            padding: 30px;
        }
    </style>
</head>
<body>

<!-- Header -->
<?php include 'header.php'; ?>

<div class="main-content">
    <div class="container login-container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="card-header">
                        Doctor Login
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="username">Username (Email)</label>
                                <input type="text" name="username" id="username" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
