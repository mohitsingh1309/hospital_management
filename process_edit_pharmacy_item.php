<?php
include('db_connection.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $query = "UPDATE pharmacy_items SET name = ?, description = ?, price = ?, quantity_in_stock = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssdii', $name, $description, $price, $quantity, $id);

    if ($stmt->execute()) {
        header('Location: manage_pharmacy.php');
        exit();
    } else {
        echo 'Error: ' . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
