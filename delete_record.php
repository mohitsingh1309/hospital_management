<?php
include('db_connection.php');

if (isset($_GET['id']) && isset($_GET['patient_id'])) {
    $id = $_GET['id'];
    $patient_id = $_GET['patient_id'];

    $query = "DELETE FROM medical_records WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo '<div class="alert alert-success text-center" role="alert">Record deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger text-center" role="alert">Error: ' . $conn->error . '</div>';
    }

    $stmt->close();
    $conn->close();

    echo '<a href="view_records.php?patient_id=' . htmlspecialchars($patient_id) . '" class="btn btn-primary">Back to Records</a>';
} else {
    echo '<p class="text-center">Invalid request.</p>';
}
?>
