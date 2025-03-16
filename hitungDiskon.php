<?php
function hitungDiskon($harga) {
    if ($harga >= 100000) {
        $persenDiskon = 10;
    } elseif ($harga > 50000 && $harga < 100000) {
        $persenDiskon = 5;
    } else {
        $persenDiskon = 0;
    }
    $diskon = ($harga * $persenDiskon) / 100;
    echo "Mendapat Diskon: " . $persenDiskon . "%";
    return $diskon;
}

$harga = 120000; // Contoh harga
$hasilDiskon = hitungDiskon($harga);
$hargaAkhir = $harga - $hasilDiskon;
echo "<br>Diskon: Rp " . number_format($hasilDiskon, 0, ',', '.');
echo "<br>Harga yang harus di bayar: Rp " . number_format($hargaAkhir, 0, ',', '.');
?>