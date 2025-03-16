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
$id = $nama = $uom = $harga_beli = $harga_jual = "";
$isEditing = false;

// Handle form submission for adding or updating a sales
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $uom = $_POST['uom'];
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];

    if (isset($_POST['update_item'])) {
        $sql = "UPDATE item SET nama_item='$nama', uom='$uom', harga_beli='$harga_beli', harga_jual='$harga_jual' WHERE id_item='$id'";
    } else {
        $sql = "INSERT INTO item (id_item, nama_item, uom, harga_beli, harga_jual) VALUES ('$id', '$nama', '$uom', '$harga_beli', '$harga_jual')";
    }
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle delete sales
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM item WHERE id_item = $id";
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle edit sales
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM item WHERE id_item = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id_item'];
        $nama = $row['nama_item'];
        $uom = $row['uom'];
        $harga_beli = $row['harga_beli'];
        $harga_jual = $row['harga_jual'];
        $isEditing = true;
    }
}

// Fetch all sales
$sql = "SELECT * FROM item";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Koperasi Pegawai | Item</title>
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
                    <h1 class="h2">Item</h1>
                </div>
                <div id="content">
                    <!-- Sales Form -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID Item</label>
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $id; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Item</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="uom" class="form-label">UOM</label>
                            <input type="text" class="form-control" id="uom" name="uom" value="<?php echo $uom; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli</label>
                            <input type="number" class="form-control" id="harga_beli" name="harga_beli" value="<?php echo $harga_beli; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga_jual" class="form-label">Harga Jual</label>
                            <input type="number" class="form-control" id="harga_jual" name="harga_jual" value="<?php echo $harga_jual; ?>" required>
                        </div>
                        <?php if ($isEditing): ?>
                            <button type="submit" class="btn btn-primary" name="update_item">Update Item</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="add_item">Add Item</button>
                        <?php endif; ?>
                    </form>
                    <!-- Sales Table -->
                    <div id="sales" class="table-responsive mt-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID Item</th>
                                    <th>Nama Item</th>
                                    <th>UOM</th>
                                    <th>Harga Beli</th>
                                    <th>Harga Jual</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_item']; ?></td>
                                    <td><?php echo $row['nama_item']; ?></td>
                                    <td><?php echo $row['uom']; ?></td>
                                    <td><?php echo $row['harga_beli']; ?></td>
                                    <td><?php echo $row['harga_jual']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id_item']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id_item']; ?>" class="btn btn-danger btn-sm">Delete</a>
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