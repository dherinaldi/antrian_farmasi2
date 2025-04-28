<?php
// get_antrian.php

//ubah disini untuk koneksinya 
$host = "192.168.10.236";
$user = "root";
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
    AND DATE(or2.TANGGAL) between '2025-04-20 00:00:00' and '2025-04-28 23:59:59'     
	ORDER BY k.MASUK ASC";
$result_order = $koneksi->query($query_order); 

#getKunjunganFarmasi status 1
 $query_kunjungan = "SELECT k.NOPEN, DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL, p.NORM, p2.NAMA
	FROM pendaftaran.kunjungan k
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE k.RUANGAN = 103010201 AND k.STATUS = 1
    AND DATE(k.MASUK) between '2025-04-20 00:00:00' and '2025-04-28 23:59:59'   
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
AND DATE(k.MASUK) between '2025-04-20 00:00:00' and '2025-04-28 23:59:59'   
ORDER BY k.MASUK DESC limit 10";

$result_kunjungan_sel = $koneksi->query($query_kunjungan_sel); 

$data = [
'belum_diterima' => [],
'dilayani' => [],
'selesai' => []
];

$data['belum_diterima'][] = $result_order->fetch_assoc();
$data['dilayani'][] = $result_kunjungan->fetch_assoc();
$data['selesai'][] = $result_kunjungan_sel->fetch_assoc();


header('Content-Type: application/json');
echo json_encode($data);
?>
