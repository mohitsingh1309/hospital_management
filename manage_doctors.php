<?php
include('header.php');
include('db_connection.php');

// Handle form submission for adding a new doctor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_doctor'])) {
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone']; // Updated to phone

    $query = "INSERT INTO doctors (name, specialization, phone) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sss', $name, $specialization, $phone);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Doctor added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

// Handle form submission for updating doctor details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_doctor'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $phone = $_POST['phone']; // Updated to phone

    $query = "UPDATE doctors SET name = ?, specialization = ?, phone = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $name, $specialization, $phone, $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Doctor updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

// Handle form submission for deleting a doctor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_doctor'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM doctors WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Doctor deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
}

// Fetch doctors to display
$query = "SELECT * FROM doctors";
$result = $conn->query($query);
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Manage Doctors</h1>
        <p class="lead">Add, edit, or delete doctors from the system.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Form to add a new doctor -->
            <h2>Add New Doctor</h2>
            <form action="manage_doctors.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" class="form-control" id="specialization" name="specialization" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label> <!-- Updated label -->
                    <input type="text" class="form-control" id="phone" name="phone" required> <!-- Updated field -->
                </div>
                <button type="submit" class="btn btn-primary" name="add_doctor">Add Doctor</button>
            </form>

            <hr>

            <!-- Display existing doctors -->
            <h2>Existing Doctors</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Phone</th> <!-- Updated column name -->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['specialization']; ?></td>
                            <td><?php echo $row['phone']; ?></td> <!-- Updated field -->
                            <td>
                                <!-- Edit button -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['name']; ?>" data-specialization="<?php echo $row['specialization']; ?>" data-phone="<?php echo $row['phone']; ?>">Edit</button>

                                <!-- Delete button -->
                                <form action="manage_doctors.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_doctor">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Edit Doctor Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Doctor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_doctors.php" method="post">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_specialization">Specialization</label>
                        <input type="text" class="form-control" id="edit_specialization" name="specialization" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone</label> <!-- Updated label -->
                        <input type="text" class="form-control" id="edit_phone" name="phone" required> <!-- Updated field -->
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_doctor">Update Doctor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Populate edit modal with doctor data
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var name = button.data('name');
        var specialization = button.data('specialization');
        var phone = button.data('phone'); // Updated to phone

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_name').val(name);
        modal.find('#edit_specialization').val(specialization);
        modal.find('#edit_phone').val(phone); // Updated to phone
    });
</script>

<?php include('footer.php'); ?>
