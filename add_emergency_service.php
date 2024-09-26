<?php
include('header.php');
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $service_name = $_POST['service_name'];
    $description = $_POST['description'];
    $contact_number = $_POST['contact_number'];
    $availability = $_POST['availability'];

    $query = "INSERT INTO emergency_services (service_name, description, contact_number, availability) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $service_name, $description, $contact_number, $availability);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center" role="alert">Emergency service added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Add Emergency Service</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="add_emergency_service.php" method="post">
                <div class="form-group">
                    <label for="service_name">Service Name</label>
                    <input type="text" class="form-control" id="service_name" name="service_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="contact_number">Contact Number</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                </div>
                <div class="form-group">
                    <label for="availability">Availability</label>
                    <select class="form-control" id="availability" name="availability">
                        <option value="24/7">24/7</option>
                        <option value="Weekdays">Weekdays</option>
                        <option value="Weekends">Weekends</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Add Service</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
