<?php
session_start();
include('db_connection.php');

// Check if the user is logged in and has the right role
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: staff_login.php");
    exit();
}

// Fetch all staff records from the database
$staff_query = "SELECT * FROM staff";
$staff_result = $conn->query($staff_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Staff - Hospital Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <!-- Header with Logout Option -->
    <?php include('header.php'); ?>

    <div class="container mt-5">
        <h1 class="mb-4">Manage Staff</h1>
        
        <!-- Button to Add Staff -->
        <a href="add_staff.php" class="btn btn-primary mb-4">Add New Staff</a>
        
        <!-- Table displaying staff records -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($staff = $staff_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($staff['id']); ?></td>
                        <td><?php echo htmlspecialchars($staff['name']); ?></td>
                        <td><?php echo htmlspecialchars($staff['email']); ?></td>
                        <td><?php echo htmlspecialchars($staff['phone']); ?></td>
                        <td><?php echo htmlspecialchars($staff['position']); ?></td>
                        <td>
                            <a href="edit_staff.php?id=<?php echo htmlspecialchars($staff['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_staff.php?id=<?php echo htmlspecialchars($staff['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this staff member?');">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
