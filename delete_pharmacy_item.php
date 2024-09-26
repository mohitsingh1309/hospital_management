<?php
include('db_connection.php');

$id = $_GET['id'];

$query = "DELETE FROM pharmacy_items WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: manage_pharmacy.php');
    exit();
} else {
    echo 'Error: ' . $conn->error;
}

$stmt->close();
$conn->close();
?>
