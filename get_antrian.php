<?php
require('connect.php');
// get_antrian.php

//ubah disini untuk koneksinya 
/* $host = "192.168.10.236";
$user = "admin";
$pass = "S!MRSGos2";
$db   = "regonline";  */

//environment production
/*  $host = "192.168.100.110";
$user = "admin";
$pass = "S!MRSGos2";
$db   = "regonline";  */

/* $host = "192.168.3.99";
$user = "admin";
$pass = "S!MGos2@kemkes.go.id";
$db   = "regonline";  */

/* $koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
 */
#getOrderFarmasi
$query_order = "SELECT k.NOPEN, DATE_FORMAT(or2.TANGGAL, '%d-%m-%Y %H:%i:%s') AS TANGGAL,LPAD(p.NORM, 6, '0') NORM, p2.NAMA,
    or2.CITO,
    MAX(CASE WHEN odr.RACIKAN = 1 THEN 1 ELSE 0 END) AS RACIKAN,
    r.DESKRIPSI AS ASAL_RUANGAN
	FROM layanan.order_resep or2
	LEFT JOIN `pendaftaran`.kunjungan k ON k.NOMOR = or2.KUNJUNGAN
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
    LEFT JOIN layanan.order_detil_resep odr ON odr.ORDER_ID = or2.NOMOR
	WHERE or2.TUJUAN = 103010201 AND or2.STATUS = 1
    AND r.ID in ('102010108') #set hanya poli JANTUNG disini 
     AND DATE(or2.TANGGAL) = DATE(NOW())
    -- AND DATE(or2.TANGGAL) between '2025-04-20 00:00:00' and NOW()
	GROUP BY k.NOPEN
	ORDER BY k.MASUK DESC";

$result_order = $koneksi->query($query_order);

#getKunjunganFarmasi status 1
$query_kunjungan = "SELECT k.NOPEN, DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL,LPAD(p.NORM, 6, '0') NORM, p2.NAMA
	FROM pendaftaran.kunjungan k
	LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
	LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
	LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
	WHERE k.RUANGAN = 103010201 AND k.STATUS = 1
    -- AND r.ID in ('102010108') #set hanya poli JANTUNG disini 
     AND DATE(k.MASUK) = DATE(NOW())
    -- AND DATE(k.MASUK) between '2025-04-20 00:00:00' and NOW()
	GROUP BY k.NOPEN
    ORDER BY k.MASUK DESC limit 10";

$result_kunjungan = $koneksi->query($query_kunjungan);

$data = [
    'belum_diterima' => [],
    'dilayani'       => [],
    'selesai'        => [],
];

#getKunjunganFarmasi status 2
$query_kunjungan_sel = "SELECT k.NOPEN, DATE_FORMAT(k.MASUK, '%d-%m-%Y %H:%i:%s') AS TANGGAL,LPAD(p.NORM, 6, '0') NORM, p2.NAMA
FROM pendaftaran.kunjungan k
LEFT JOIN `pendaftaran`.pendaftaran p ON p.NOMOR = k.NOPEN
LEFT JOIN `master`.pasien p2 ON p.NORM = p2.NORM
LEFT JOIN `master`.ruangan r ON r.ID = k.RUANGAN
LEFT JOIN (
    SELECT COALESCE(COUNT(tar.ID),0) AS C_TELAAH, tar.RESEP
    FROM layanan.telaah_awal_resep tar
    GROUP BY tar.RESEP
) AS c_telaah ON c_telaah.RESEP = k.NOMOR
WHERE k.RUANGAN = 103010201 AND k.STATUS = 2
-- AND r.ID in ('102010108') #set hanya poli JANTUNG disini 
-- AND DATE(k.MASUK) = DATE(NOW())
AND c_telaah.C_TELAAH < 3 # telaah akhir minimal centang 3 data
AND DATE(k.MASUK) = DATE(NOW())
-- AND DATE(k.MASUK) between '2025-04-20 00:00:00' and NOW()
GROUP BY k.NOPEN
ORDER BY k.MASUK DESC limit 10";

//echo $query_kunjungan_sel;

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