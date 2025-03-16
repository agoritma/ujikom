<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan Makanan</title>
</head>
<body>
    <div>
        <h1>Form Pemesanan Makanan</h1>
        <form method="post" action="">
            <table>
                <tr>
                    <th>Nama Makanan</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                </tr>
                <tr>
                    <td>Nasi Goreng</td>
                    <td>Rp 10.000</td>
                    <td><input type="number" name="nasi_goreng" value="0"></td>
                </tr>
                <tr>
                    <td>Ayam Goreng</td>
                    <td>Rp 12.000</td>
                    <td><input type="number" name="ayam_goreng" value="0"></td>
                </tr>
                <tr>
                    <td>Es Teh</td>
                    <td>Rp 2.000</td>
                    <td><input type="number" name="es_teh" value="0"></td>
                </tr>
                <tr>
                    <td>Kopi</td>
                    <td>Rp 3.000</td>
                    <td><input type="number" name="kopi" value="0"></td>
                </tr>
            </table>
            <button type="submit" name="hitung">Hitung Total</button>
        </form>
        <?php
        if (isset($_POST['hitung'])) {
            $harga_ayam_goreng = 12000;
            $harga_es_teh = 2000;
            $harga_kopi = 3000;
            $harga_nasi_goreng=10000;

            $jumlah_ayam_goreng = $_POST['ayam_goreng'];
            $jumlah_es_teh = $_POST['es_teh'];
            $jumlah_kopi = $_POST['kopi'];
            $jumlah_nasi_goreng = $_POST['nasi_goreng'];

            $total_harga = ($harga_ayam_goreng * $jumlah_ayam_goreng) + ($harga_es_teh * $jumlah_es_teh) + ($harga_kopi * $jumlah_kopi) + ($harga_nasi_goreng * $jumlah_nasi_goreng);

            echo "<span>Total Harga: Rp " . number_format($total_harga, 0, ',', '.') . "</span>";
        }
        ?>
    </div>
</body>
</html>