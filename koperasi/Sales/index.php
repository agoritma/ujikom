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
$id_sales = $tanggal_sales = $id_customer = $do_number = $status = "";
$isEditing = false;

// Handle form submission for adding or updating a sale
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_sales = $_POST['id_sales'];
    $tanggal_sales = $_POST['tanggal_sales'];
    $id_customer = $_POST['id_customer'];
    $do_number = $_POST['do_number'];
    $status = $_POST['status'];

    if (isset($_POST['update_sales'])) {
        $sql = "UPDATE sales SET tgl_sales='$tanggal_sales', id_customer='$id_customer', do_number='$do_number', status='$status' WHERE id_sales='$id_sales'";
    } else {
        $sql = "INSERT INTO sales (id_sales, tgl_sales, id_customer, do_number, status) VALUES ('$id_sales', '$tanggal_sales', '$id_customer', '$do_number', '$status')";
    }
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle delete sale
if (isset($_GET['delete'])) {
    $id_sales = $_GET['delete'];
    $sql = "DELETE FROM sales WHERE id_sales = $id_sales";
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle edit sale
if (isset($_GET['edit'])) {
    $id_sales = $_GET['edit'];
    $result = $conn->query("SELECT * FROM sales WHERE id_sales = $id_sales");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_sales = $row['id_sales'];
        $tanggal_sales = $row['tgl_sales'];
        $id_customer = $row['id_customer'];
        $do_number = $row['do_number'];
        $status = $row['status'];
        $isEditing = true;
    }
}

// Fetch all sales
$sql = "SELECT sales.*, customer.nama_customer FROM sales JOIN customer ON sales.id_customer = customer.id_customer";
$result = $conn->query($sql);

// Fetch all customers for the dropdown
$customers = $conn->query("SELECT id_customer, nama_customer FROM customer");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Koperasi Pegawai | Sales</title>
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
                    <h1 class="h2">Sales</h1>
                </div>
                <div id="content">
                    <!-- Sales Form -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id_sales" class="form-label">ID Sales</label>
                            <input type="number" class="form-control" id="id_sales" name="id_sales" value="<?php echo $id_sales; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_sales" class="form-label">Tanggal Sales</label>
                            <input type="date" class="form-control" id="tanggal_sales" name="tanggal_sales" value="<?php echo $tanggal_sales; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="id_customer" class="form-label">Customer</label>
                            <select class="form-control" id="id_customer" name="id_customer" required>
                                <option value="">Select Customer</option>
                                <?php while($customer = $customers->fetch_assoc()): ?>
                                    <option value="<?php echo $customer['id_customer']; ?>" <?php echo ($customer['id_customer'] == $id_customer) ? 'selected' : ''; ?>>
                                        <?php echo $customer['nama_customer']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="do_number" class="form-label">DO Number</label>
                            <input type="text" class="form-control" id="do_number" name="do_number" value="<?php echo $do_number; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <input type="text" class="form-control" id="status" name="status" value="<?php echo $status; ?>" required>
                        </div>
                        <?php if ($isEditing): ?>
                            <button type="submit" class="btn btn-primary" name="update_sales">Update Sales</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="add_sales">Add Sales</button>
                        <?php endif; ?>
                    </form>
                    <!-- Sales Table -->
                    <div id="sales" class="table-responsive mt-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID Sales</th>
                                    <th>Tanggal Sales</th>
                                    <th>ID Customer</th>
                                    <th>Nama Customer</th>
                                    <th>DO Number</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_sales']; ?></td>
                                    <td><?php echo $row['tgl_sales']; ?></td>
                                    <td><?php echo $row['id_customer']; ?></td>
                                    <td><?php echo $row['nama_customer']; ?></td>
                                    <td><?php echo $row['do_number']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id_sales']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id_sales']; ?>" class="btn btn-danger btn-sm">Delete</a>
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