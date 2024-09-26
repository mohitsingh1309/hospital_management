<?php
// Include header file
include('header.php');

// Enable error reporting for debugging (you can disable this in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include('db_connection.php');

// Initialize error and success messages
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize user input
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $medical_history = $_POST['medical_history'];
    $current_medications = $_POST['current_medications'];
    $allergies = $_POST['allergies'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_phone = $_POST['emergency_contact_phone'];
    $password = $_POST['password'];

    // Insert data into the patients table
    $query = "INSERT INTO patients (name, email, phone, address, dob, gender, medical_history, current_medications, allergies, emergency_contact_name, emergency_contact_phone) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssssssssss', $name, $email, $phone, $address, $dob, $gender, $medical_history, $current_medications, $allergies, $emergency_contact_name, $emergency_contact_phone);

    if ($stmt->execute()) {
        $patient_id = $stmt->insert_id;  // Get the inserted patient ID

        // Insert into user_login table for login
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $login_query = "INSERT INTO user_login (user_id, username, password, role) VALUES (?, ?, ?, 'patient')";
        $login_stmt = $conn->prepare($login_query);
        $login_stmt->bind_param("iss", $patient_id, $email, $hashed_password);

        if ($login_stmt->execute()) {
            $success = "Registration successful! Your Patient ID is $patient_id. Please log in.";
        } else {
            $error = "Error creating login: " . $conn->error;
        }
    } else {
        $error = "Error in registration: " . $conn->error;
    }

    // Close statements and connection
    $stmt->close();
    $login_stmt->close();
    $conn->close();
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Patient Registration</h1>
        <p class="lead">Register as a new patient and get your unique ID.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="text-center">Registration Form</h2>

            <!-- Display error or success messages -->
            <?php if ($error): ?>
                <div class="alert alert-danger" role="alert"><?php echo $error; ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success" role="alert"><?php echo $success; ?></div>
            <?php endif; ?>

            <!-- Registration Form -->
            <form action="patient_signup.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
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
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address"></textarea>
                </div>
                <div class="form-group">
                    <label for="dob">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob">
                </div>
                <div class="form-group">
                    <label for="gender">Gender</label>
                    <select class="form-control" id="gender" name="gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="medical_history">Medical History</label>
                    <textarea class="form-control" id="medical_history" name="medical_history"></textarea>
                </div>
                <div class="form-group">
                    <label for="current_medications">Current Medications</label>
                    <textarea class="form-control" id="current_medications" name="current_medications"></textarea>
                </div>
                <div class="form-group">
                    <label for="allergies">Allergies</label>
                    <textarea class="form-control" id="allergies" name="allergies"></textarea>
                </div>
                <div class="form-group">
                    <label for="emergency_contact_name">Emergency Contact Name</label>
                    <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name">
                </div>
                <div class="form-group">
                    <label for="emergency_contact_phone">Emergency Contact Phone</label>
                    <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-success">Register</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
