<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Tambah Barang Ahmad Ghozali</title>
    <style>
        main {
            width: 100%;
            height: 80vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
    <main>
        <form action="" method="POST">
            <h1>Tambah Data Barang</h1>
            <div class="mb-3">
                <label for="inputNumber" class="form-label">No</label>
                <input type="number" class="form-control" id="inputNumber" name="no" required>
            </div>
            <div class="mb-3">
                <label for="inputName" class="form-label">Nama Merek</label>
                <input type="text" class="form-control" id="inputName" name="nama_merek" required>
            </div>
            <div class="mb-3">
                <label for="inputColor" class="form-label">Warna</label>
                <input type="text" class="form-control" id="inputColor" name="warna" required>
            </div>
            <div class="mb-3">
                <label for="inputQuantity" class="form-label">Jumlah</label>
                <input type="number" class="form-control" id="inputQuantity" name="jumlah" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
        </form>
    </main>

    <?php
    if (isset($_POST['submit'])) {
        $no = $_POST['no'];
        $nama_merek = $_POST['nama_merek'];
        $warna = $_POST['warna'];
        $jumlah = $_POST['jumlah'];

        // Database connection
        $conn = new mysqli("localhost", "root", "", "ujikom_data");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO printer (no, nama_merek, warna, jumlah) VALUES ('$no', '$nama_merek', '$warna', '$jumlah')";

        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success' role='alert'>New record created successfully</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $sql . "<br>" . $conn->error . "</div>";
        }

        $conn->close();
    }
    ?>
</body>
</html>