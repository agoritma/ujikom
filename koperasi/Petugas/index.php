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
$id = $nama = $username = $password = $level = "";
$isEditing = false;

// Handle form submission for adding or updating a petugas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $level = $_POST['level'];

    if (isset($_POST['update_petugas'])) {
        $sql = "UPDATE petugas SET nama_user='$nama', username='$username', password='$password', level='$level' WHERE id_petugas='$id'";
    } else {
        $sql = "INSERT INTO petugas (id_petugas, nama_user, username, password, level) VALUES ('$id', '$nama', '$username', '$password', '$level')";
    }
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle delete petugas
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM petugas WHERE id_petugas = $id";
    $conn->query($sql);
    header("location: ./");
    exit();
}

// Handle edit petugas
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = $conn->query("SELECT * FROM petugas WHERE id_petugas = $id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id = $row['id_petugas'];
        $nama = $row['nama_user'];
        $username = $row['username'];
        $password = $row['password'];
        $level = $row['level'];
        $isEditing = true;
    }
}

// Fetch all petugas
$sql = "SELECT * FROM petugas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Koperasi Pegawai</title>
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
                    <h1 class="h2">Petugas</h1>
                </div>
                <div id="content">
                    <!-- Petugas Form -->
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="id" class="form-label">ID Petugas</label>
                            <input type="number" class="form-control" id="id" name="id" value="<?php echo $id; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama User</label>
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="<?php echo $password; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="level" class="form-label">Level</label>
                            <input type="text" class="form-control" id="level" name="level" value="<?php echo $level; ?>" required>
                        </div>
                        <?php if ($isEditing): ?>
                            <button type="submit" class="btn btn-primary" name="update_petugas">Update Petugas</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-primary" name="add_petugas">Add Petugas</button>
                        <?php endif; ?>
                    </form>
                    <!-- Petugas Table -->
                    <div id="petugas" class="table-responsive mt-4">
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>ID Petugas</th>
                                    <th>Nama User</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id_petugas']; ?></td>
                                    <td><?php echo $row['nama_user']; ?></td>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['password']; ?></td>
                                    <td><?php echo $row['level']; ?></td>
                                    <td>
                                        <a href="?edit=<?php echo $row['id_petugas']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="?delete=<?php echo $row['id_petugas']; ?>" class="btn btn-danger btn-sm">Delete</a>
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