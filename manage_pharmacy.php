<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Pharmacy Management</h1>
        <p class="lead">Manage pharmacy items and stock levels.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php
            include('db_connection.php');

            // Fetch pharmacy items
            $query = "SELECT * FROM pharmacy_items";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                echo '<table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity in Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
                while ($row = $result->fetch_assoc()) {
                    echo '<tr>
                            <td>' . htmlspecialchars($row['name']) . '</td>
                            <td>' . htmlspecialchars($row['description']) . '</td>
                            <td>' . htmlspecialchars($row['price']) . '</td>
                            <td>' . htmlspecialchars($row['quantity_in_stock']) . '</td>
                            <td>
                                <a href="edit_pharmacy_item.php?id=' . $row['id'] . '" class="btn btn-warning btn-sm">Edit</a>
                                <a href="delete_pharmacy_item.php?id=' . $row['id'] . '" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>';
                }
                echo '</tbody></table>';
            } else {
                echo '<div class="alert alert-info text-center" role="alert">No pharmacy items found.</div>';
            }

            $conn->close();
            ?>
            <a href="add_pharmacy_item.php" class="btn btn-primary">Add New Item</a>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
