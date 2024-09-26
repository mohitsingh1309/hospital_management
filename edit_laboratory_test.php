<?php
include('header.php');
include('db_connection.php');

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name = $_POST['test_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "UPDATE laboratory_tests SET test_name = ?, description = ?, price = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdi', $test_name, $description, $price, $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center" role="alert">Laboratory test updated successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}

// Fetch existing data for editing
$query = "SELECT * FROM laboratory_tests WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Edit Laboratory Test</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="edit_laboratory_test.php?id=<?php echo $id; ?>" method="post">
                <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <input type="text" class="form-control" id="test_name" name="test_name" value="<?php echo htmlspecialchars($row['test_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($row['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($row['price']); ?>" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Test</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
