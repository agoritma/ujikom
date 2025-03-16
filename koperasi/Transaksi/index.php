<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_koperasi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$id_transaksi = $id_item = $quantity = $price = $amount = "";
$isEditing = false;

// Handle form submission for adding or updating a transaction
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_transaksi = $_POST['id_transaksi'];
    $id_item = $_POST['id_item'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];

    if (isset($_POST['update_transaksi'])) {
        $sql = "UPDATE transaction SET id_item='$id_item', quantity='$quantity', price='$price', amount='$amount' WHERE id_transaction='$id_transaksi'";
    } else {
        $sql = "INSERT INTO transaction (id_transaction, id_item, quantity, price, amount) VALUES ('$id_transaksi', '$id_item', '$quantity', '$price', '$amount')";
    }
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle delete transaction
if (isset($_GET['delete'])) {
    $id_transaksi = $_GET['delete'];
    $sql = "DELETE FROM transaction WHERE id_transaction = $id_transaksi";
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle edit transaction
if (isset($_GET['edit'])) {
    $id_transaksi = $_GET['edit'];
    $result = $conn->query("SELECT * FROM transaction WHERE id_transaction = $id_transaksi");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_transaksi = $row['id_transaction'];
        $id_item = $row['id_item'];
        $quantity = $row['quantity'];
        $price = $row['price'];
        $amount = $row['amount'];
        $isEditing = true;
    }
}

// Fetch all transactions
$sql = "SELECT transaction.*, item.nama_item FROM transaction JOIN item ON transaction.id_item = item.id_item";
$result = $conn->query($sql);

// Fetch all items for the dropdown
$items = $conn->query("SELECT id_item, nama_item FROM item");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Koperasi Pegawai | Transaksi</title>
    <link rel="stylesheet" href="../main.css">
</head>
<body>
    <nav class="px-4 navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="../">Koperasi Pegawai</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="../transaksi">
                                Transaksi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../item">
                                Item
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../customer">
                                Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../sales">
                                Sales
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../petugas">
                                Petugas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../manager">
                                Manager
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Transaksi</h1>
                </div>
                <div id="content">
                    <!-- Transaksi Form -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_transaksi" class="form-label">ID Transaksi</label>
                            <input type="number" class="form-control" id="id_transaksi" name="id_transaksi" value="<?php echo $id_transaksi; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_item" class="form-label">Item</label>
                            <select class="form-control" id="id_item" name="id_item" required>
                                <option value="">Select Item</option>
                                <?php while($item = $items->fetch_assoc()): ?>
                                    <option value="<?php echo $item['id_item']; ?>" <?php echo ($item['id_item'] == $id_item) ? 'selected' : ''; ?>>
                                        <?php echo $item['nama_item']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price" value="<?php echo $price; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" value="<?php echo $amount; ?>" required>
                        </div>
                        <?php if ($isEditing): ?>
                            <button type="submit" class="btn btn-primary" name="update_transaksi">Update Transaksi</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="add_transaksi">Add Transaksi</button>
                        <?php endif; ?>
                    </form>
                    <!-- Transaksi Table -->
                    <div id="transaksi" class="table-responsive mt-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID Transaksi</th>
                                    <th>ID Item</th>
                                    <th>Nama Item</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_transaction']; ?></td>
                                    <td><?php echo $row['id_item']; ?></td>
                                    <td><?php echo $row['nama_item']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['price']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id_transaction']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id_transaction']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>