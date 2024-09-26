<?php
include('header.php');
include('db_connection.php');

// Handle form submission for updating settings
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_settings'])) {
    $site_name = $_POST['site_name'];
    $site_email = $_POST['site_email'];
    $timezone = $_POST['timezone'];
    $contact_number = $_POST['contact_number'];
    $address = $_POST['address'];

    // Update settings in the database
    $query = "UPDATE settings SET site_name = ?, site_email = ?, timezone = ?, contact_number = ?, address = ? WHERE id = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssss', $site_name, $site_email, $timezone, $contact_number, $address);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Settings updated successfully!</div>';
        header("Location: admin_dashboard.php"); // Redirect to the dashboard
        exit(); // Ensure that no further code is executed
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

// Fetch current settings
$query = "SELECT site_name, site_email, timezone, contact_number, address FROM settings WHERE id = 1";
$result = $conn->query($query);
if ($result) {
    $settings = $result->fetch_assoc();
} else {
    die("Error fetching settings: " . $conn->error);
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">System Settings</h1>
        <p class="lead">Update the system settings below.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2>Update Settings</h2>
            <form action="system_settings.php" method="post">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name" value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="site_email">Site Email</label>
                    <input type="email" class="form-control" id="site_email" name="site_email" value="<?php echo htmlspecialchars($settings['site_email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="timezone">Timezone</label>
                    <input type="text" class="form-control" id="timezone" name="timezone" value="<?php echo htmlspecialchars($settings['timezone']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($settings['contact_number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($settings['address']); ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="update_settings">Update Settings</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
