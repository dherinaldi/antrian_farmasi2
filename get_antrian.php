<?php
// get_antrian.php

//ubah disini untuk koneksinya 
$host = "192.168.100.110";
$user = "admin";
$pass = "S!MRSGos2";
$db = "regonline";

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

#getOrderFarmasi
$query_order = "SELECT k.NOPEN, DATE_FORMAT(or2.TANGGAL, '%d-%m-%Y %H:%i:%s') AS TANGGAL, p.NORM, p2.NAMA
	FROM layanan.order_resep or2
	LEFT JOIN `pendaftaran`.kunjungan k ON k.NOMOR = or2.KUNJUNGAN
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE or2.TUJUAN = 103010201 AND or2.STATUS = 1
    AND DATE(or2.TANGGAL) = DATE(NOW())   
	ORDER BY k.MASUK DESC";
$result_order = $koneksi->query($query_order); 

#getKunjunganFarmasi status 1
 $query_kunjungan = "SELECT k.NOPEN, DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL, p.NORM, p2.NAMA
	FROM pendaftaran.kunjungan k
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE k.RUANGAN = 103010201 AND k.STATUS = 1
    AND DATE(k.MASUK) = DATE(NOW())
    ORDER BY k.MASUK DESC limit 10";

$result_kunjungan = $koneksi->query($query_kunjungan); 

$data = [
    'belum_diterima' => [],
    'dilayani' => [],
    'selesai' => []
];

#getKunjunganFarmasi status 2
$query_kunjungan_sel = "SELECT k.NOPEN, DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL, p.NORM, p2.NAMA
FROM pendaftaran.kunjungan k
LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
WHERE k.RUANGAN = 103010201 AND k.STATUS = 2
AND DATE(k.MASUK) = DATE(NOW())  
ORDER BY k.MASUK DESC limit 10";

$result_kunjungan_sel = $koneksi->query($query_kunjungan_sel); 

// Belum diterima (order resep)
if ($result_order->num_rows > 0) {
    while ($row = $result_order->fetch_assoc()) {
        $data['belum_diterima'][] = $row;
    }
}

// Dilayani (kunjungan status 1)
if ($result_kunjungan->num_rows > 0) {
    while ($row = $result_kunjungan->fetch_assoc()) {
        $data['dilayani'][] = $row;
    }
}

// Selesai (kunjungan status 2)
if ($result_kunjungan_sel->num_rows > 0) {
    while ($row = $result_kunjungan_sel->fetch_assoc()) {
        $data['selesai'][] = $row;
    }
}


header('Content-Type: application/json');
echo json_encode($data);
?>
