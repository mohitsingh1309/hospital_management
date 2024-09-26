<?php include('header.php'); ?>

<header class="bg-primary text-white text-center py-5">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Add New Pharmacy Item</h1>
    </div>
</header>

<main class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="process_add_pharmacy_item.php" method="post">
                <div class="form-group">
                    <label for="name">Item Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="form-group">
                    <label for="quantity">Quantity in Stock</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Item</button>
            </form>
        </div>
    </div>
</main>

<?php include('footer.php'); ?>
