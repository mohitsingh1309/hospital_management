// Include the database connection file
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);  // Assuming you have a gender field
    $date_of_birth = trim($_POST['date_of_birth']);  // Assuming you have a DOB field

    // Basic validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($password) || empty($phone) || empty($address) || empty($gender) || empty($date_of_birth)) {
        echo '<div class="alert alert-danger text-center">Please fill in all fields.</div>';
    } else {
        // Check if the email is already registered
        $check_email_query = "SELECT * FROM patient_signup WHERE email = ?";
        $stmt = $conn->prepare($check_email_query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo '<div class="alert alert-danger text-center">Email is already registered. Please use a different email.</div>';
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert patient signup data into the patient_signup table
            $insert_signup_query = "INSERT INTO patient_signup (patient_name, email, contact_number, address, gender, date_of_birth, registration_date) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insert_signup_query);
            $patient_name = $first_name . ' ' . $last_name;
            $stmt->bind_param("ssssss", $patient_name, $email, $phone, $address, $gender, $date_of_birth);

            if ($stmt->execute()) {
                // Get the inserted patient's ID
                $patient_id = $stmt->insert_id;

                // Insert login data into the user_login table
                $insert_login_query = "INSERT INTO user_login (user_id, username, password, role, created_at) VALUES (?, ?, ?, 'patient', NOW())";
                $stmt = $conn->prepare($insert_login_query);
                $stmt->bind_param("iss", $patient_id, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Redirect to login page with a success message
                    header("Location: patient_login.php?registration_success=1&patient_id=$patient_id");
                    exit();
                } else {
                    echo '<div class="alert alert-danger text-center">Registration failed. Please try again later.</div>';
                }
            } else {
                echo '<div class="alert alert-danger text-center">Registration failed. Please try again later.</div>';
            }
        }
    }
}
?>
