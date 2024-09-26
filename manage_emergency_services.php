<?php
include('header.php');
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $query = "DELETE FROM emergency_services WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $delete_id);
    $stmt->execute();
    $stmt->close();
}

// Fetch all emergency services
$query = "SELECT * FROM emergency_services";
$result = $conn->query($query);

?>
<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Emergency Services</h1>
        <p class="lead">Manage the emergency services available in the system.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <a href="add_emergency_service.php" class="btn btn-primary mb-3">Add New Service</a>
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Contact Number</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['service_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['availability']); ?></td>
                                <td>
                                    <a href="edit_emergency_service.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="manage_emergency_services.php" method="post" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">No emergency services found.</div>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
