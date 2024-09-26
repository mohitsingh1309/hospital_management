<?php
include('db_connection.php');

$id = $_GET['id'];

$query = "UPDATE online_consultations SET request_status = 'Approved' WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header("Location: manage_consultations.php");
    exit();
} else {
    echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
}

$stmt->close();
$conn->close();
?>
