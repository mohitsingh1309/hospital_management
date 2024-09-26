<?php
include('header.php');
include('db_connection.php');

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $contact_number = $_POST['contact_number'];
    $availability = $_POST['availability'];

    $query = "UPDATE emergency_services SET service_name = ?, description = ?, contact_number = ?, availability = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssi', $service_name, $description, $contact_number, $availability, $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center" role="alert">Emergency service updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}

// Fetch existing data for editing
$query = "SELECT * FROM emergency_services WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Edit Emergency Service</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="edit_emergency_service.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label for="service_name">Service Name</label>
                    <input type="text" class="form-control" id="service_name" name="service_name" value="<?php echo htmlspecialchars($row['service_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="<?php echo htmlspecialchars($row['contact_number']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="availability">Availability</label>
                    <select class="form-control" id="availability" name="availability">
                        <option value="24/7" <?php echo ($row['availability'] == '24/7') ? 'selected' : ''; ?>>24/7</option>
                        <option value="Weekdays" <?php echo ($row['availability'] == 'Weekdays') ? 'selected' : ''; ?>>Weekdays</option>
                        <option value="Weekends" <?php echo ($row['availability'] == 'Weekends') ? 'selected' : ''; ?>>Weekends</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Service</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
