<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_mahasiswa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update data
if (isset($_POST['update'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $gender = $_POST['gender'];
    $jurusan = $_POST['jurusan'];

    $sql = "UPDATE table_mahasiswa SET nama='$nama', gender='$gender', jurusan='$jurusan' WHERE nim='$nim'";
    $conn->query($sql);

    // Redirect to home page after update
    header("Location: ./");
    exit();
}

// Delete data
if (isset($_GET['delete'])) {
    $nim = $_GET['delete'];
    $sql = "DELETE FROM table_mahasiswa WHERE nim='$nim'";
    $conn->query($sql);
    header("Location: ./");
    exit();
}

// Fetch data
$sql = "SELECT * FROM table_mahasiswa";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Data Mahasiswa</title>
    <style>
        body {
            padding: 2rem 4rem;
        }
        
        .action-col {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
    </style>
</head>
<body>
    <h1>Data Mahasiswa</h1>
    <table border="1" class="table table-striped-columns">
        <tr>
            <th>No</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Gender</th>
            <th>Jurusan</th>
            <th>Action</th>
        </tr>
        <?php
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$no}</td>
                <td>{$row['nim']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['gender']}</td>
                <td>{$row['jurusan']}</td>
                <td class='action-col'>
                    <a class='btn btn-warning' href='?edit={$row['nim']}'>Edit</a>
                    <a class='btn btn-danger' href='?delete={$row['nim']}'>Delete</a>
                </td>
            </tr>";
            $no++;
        }
        ?>
    </table>

    <?php
    if (isset($_GET['edit'])) {
        $nim = $_GET['edit'];
        $sql = "SELECT * FROM table_mahasiswa WHERE nim='$nim'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
    ?>
    <h2>Edit Data Mahasiswa</h2>
    <form method="post" action="">
        <label class="form-label">NIM:</label>
        <input class="form-control" type="text" name="nim" value="<?php echo $row['nim']; ?>"><br>
        <label class="form-label">Nama:</label>
        <input class="form-control" type="text" name="nama" value="<?php echo $row['nama']; ?>"><br>
        <label class="form-label">Gender:</label>
        <input class="form-control" type="text" name="gender" value="<?php echo $row['gender']; ?>"><br>
        <label class="form-label">Jurusan:</label>
        <input class="form-control" type="text" name="jurusan" value="<?php echo $row['jurusan']; ?>"><br>
        <input class="btn btn-primary" type="submit" name="update" value="Update">
    </form>
    <?php
    }
    ?>

</body>
</html>

<?php
$conn->close();
?>