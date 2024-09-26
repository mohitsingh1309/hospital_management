<?php include('header.php'); ?>

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

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                include('db_connection.php');

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
                
                // Insert data into the database
                $query = "INSERT INTO patients (name, email, phone, address, dob, gender, medical_history, current_medications, allergies, emergency_contact_name, emergency_contact_phone) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);

                // Correct bind_param with 'sssssssssss' for 11 string parameters
                $stmt->bind_param('sssssssssss', $name, $email, $phone, $address, $dob, $gender, $medical_history, $current_medications, $allergies, $emergency_contact_name, $emergency_contact_phone);
                
                if ($stmt->execute()) {
                    $patient_id = $stmt->insert_id;
                    $stmt->close();
                    $conn->close();
                    
                    // Redirect to the home page with a success message
                    header('Location: index.php?registration_success=1&patient_id=' . $patient_id);
                    exit(); // Ensure no further code is executed
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error: ' . $conn->error . '</div>';
                }
            }
            ?>

            <form action="register.php" method="post">
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
                <button type="submit" class="btn btn-success">Register</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
