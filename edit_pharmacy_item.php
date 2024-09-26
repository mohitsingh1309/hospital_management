<?php
include('header.php');
include('db_connection.php');

$id = $_GET['id'];

$query = "SELECT * FROM pharmacy_items WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo '<div class="alert alert-danger text-center" role="alert">Item not found.</div>';
    exit();
}
?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Edit Pharmacy Item</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="process_edit_pharmacy_item.php" method="post">
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($item['id']); ?>">
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo htmlspecialchars($item['quantity_in_stock']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Item</button>
            </form>
        </div>
    </div>
</main>

<?php
$stmt->close();
$conn->close();
include('footer.php');
?>
