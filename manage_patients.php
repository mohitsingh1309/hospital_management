<?php
include('header.php');
include('db_connection.php');

// Handle form submission for adding a new patient
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_patient'])) {
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $query = "INSERT INTO patients (name, phone, address) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $name, $phone, $address);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Patient added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }

        $stmt->close();
    }

    if (isset($_POST['update_patient'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $query = "UPDATE patients SET name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sssi', $name, $phone, $address, $id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Patient updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }

        $stmt->close();
    }

    if (isset($_POST['delete_patient'])) {
        $id = $_POST['id'];

        $query = "DELETE FROM patients WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Patient deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
        }

        $stmt->close();
    }
}

// Fetch patients to display
$query = "SELECT * FROM patients";
$result = $conn->query($query);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Manage Patients</h1>
        <p class="lead">Add, edit, or delete patients from the system.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Form to add a new patient -->
            <h2>Add New Patient</h2>
            <form action="manage_patients.php" method="post">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary" name="add_patient">Add Patient</button>
            </form>

            <hr>

            <!-- Display existing patients -->
            <h2>Existing Patients</h2>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['address']); ?></td>
                            <td>
                                <!-- Edit button -->
                                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" data-id="<?php echo htmlspecialchars($row['id']); ?>" data-name="<?php echo htmlspecialchars($row['name']); ?>" data-phone="<?php echo htmlspecialchars($row['phone']); ?>" data-address="<?php echo htmlspecialchars($row['address']); ?>">Edit</button>

                                <!-- Delete button -->
                                <form action="manage_patients.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete_patient">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Edit Patient Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Patient</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_patients.php" method="post">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="form-group">
                        <label for="edit_name">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone">Phone</label>
                        <input type="text" class="form-control" id="edit_phone" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_address">Address</label>
                        <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary" name="update_patient">Update Patient</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Populate edit modal with patient data
    $('#editModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var id = button.data('id');
        var name = button.data('name');
        var phone = button.data('phone');
        var address = button.data('address');

        var modal = $(this);
        modal.find('#edit_id').val(id);
        modal.find('#edit_name').val(name);
        modal.find('#edit_phone').val(phone);
        modal.find('#edit_address').val(address);
    });
</script>

<?php include('footer.php'); ?>
