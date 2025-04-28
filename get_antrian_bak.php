<?php
// get_antrian.php

$host = "192.168.10.236";
$user = "root";
$pass = "S!MRSGos2";
$db = "regonline";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$query = "SELECT * FROM antrian_farmasi ORDER BY waktu ASC";
$result = $koneksi->query($query);

$data = [
    'belum_diterima' => [],
    'dilayani' => [],
    'selesai' => []
];

while ($row = $result->fetch_assoc()) {
    if ($row['status'] == 'Belum Diterima') {
        $data['belum_diterima'][] = $row;
    } elseif ($row['status'] == 'Dilayani') {
        $data['dilayani'][] = $row;
    } elseif ($row['status'] == 'Selesai') {
        $data['selesai'][] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
