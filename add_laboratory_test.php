<?php
include('header.php');
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $test_name = $_POST['test_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $query = "INSERT INTO laboratory_tests (test_name, description, price) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssd', $test_name, $description, $price);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center" role="alert">Laboratory test added successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Add Laboratory Test</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="add_laboratory_test.php" method="post">
                <div class="form-group">
                    <label for="test_name">Test Name</label>
                    <input type="text" class="form-control" id="test_name" name="test_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Test</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
