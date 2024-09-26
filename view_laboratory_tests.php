<?php
include('header.php');
include('db_connection.php');

// Pagination settings
$limit = 20; // Number of items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
$offset = $page * $limit;

// Fetch laboratory tests with pagination
$query = "SELECT * FROM laboratory_tests LIMIT $limit OFFSET $offset";
$result = $conn->query($query);

// Fetch total number of records
$total_query = "SELECT COUNT(*) AS total FROM laboratory_tests";
$total_result = $conn->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

?>
<header class="bg-primary text-white text-center py-5" style="background-image: url('laboratory.jpg'); background-size: cover; background-position: center; color: #fff;">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Laboratory Tests</h1>
        <p class="lead">View the laboratory tests available in the system.</p>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <?php if ($result->num_rows > 0): ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Test Name</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['test_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Pagination controls -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 0; $i < $total_pages; $i++): ?>
                            <li class="page-item<?php echo ($i == $page) ? ' active' : ''; ?>">
                                <a class="page-link" href="view_laboratory_tests.php?page=<?php echo $i; ?>"><?php echo $i + 1; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php else: ?>
                <div class="alert alert-info text-center" role="alert">No laboratory tests found.</div>
            <?php endif; ?>
            <?php $conn->close(); ?>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
