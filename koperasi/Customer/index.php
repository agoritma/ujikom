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
$id = $nama = $alamat = $telpon = $tax = $email = "";
$isEditing = false;

// Handle form submission for adding or updating a customer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $telpon = $_POST['telpon'];
    $tax = $_POST['tax'];
    $email = $_POST['email'];

    if (isset($_POST['update_customer'])) {
        $sql = "UPDATE customer SET nama_customer='$nama', alamat='$alamat', telp='$telpon', fax='$tax', email='$email' WHERE id_customer='$id'";
    } else {
        $sql = "INSERT INTO customer (id_customer, nama_customer, alamat, telp, fax, email) VALUES ('$id', '$nama', '$alamat', '$telpon', '$tax', '$email')";
    }
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle delete customer
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM customer WHERE id_customer = $id";
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle edit customer
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM customer WHERE id_customer = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id_customer'];
        $nama = $row['nama_customer'];
        $alamat = $row['alamat'];
        $telpon = $row['telp'];
        $tax = $row['fax'];
        $email = $row['email'];
        $isEditing = true;
    }
}

// Fetch all customers
$sql = "SELECT * FROM customer";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Koperasi Pegawai | Customer</title>
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
                    <h1 class="h2">Customer</h1>
                </div>
                <div id="content">
                    <!-- Customer Form -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID Customer</label>
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $id; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Customer</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telpon" class="form-label">Telpon</label>
                            <input type="text" class="form-control" id="telpon" name="telpon" value="<?php echo $telpon; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="tax" class="form-label">Tax</label>
                            <input type="text" class="form-control" id="tax" name="tax" value="<?php echo $tax; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
                        </div>
                        <?php if ($isEditing): ?>
                            <button type="submit" class="btn btn-primary" name="update_customer">Update Customer</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="add_customer">Add Customer</button>
                        <?php endif; ?>
                    </form>
                    <!-- Customer Table -->
                    <div id="customer" class="table-responsive mt-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID Customer</th>
                                    <th>Nama Customer</th>
                                    <th>Alamat</th>
                                    <th>Telpon</th>
                                    <th>Tax</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_customer']; ?></td>
                                    <td><?php echo $row['nama_customer']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>
                                    <td><?php echo $row['telp']; ?></td>
                                    <td><?php echo $row['fax']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id_customer']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id_customer']; ?>" class="btn btn-danger btn-sm">Delete</a>
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