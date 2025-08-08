<?php
#require '../connect.php';
include_once(__DIR__ .'/../connect.php');

/* $data = [
  ['id' => 1, 'no_antrian' => 1, 'nama_pasien' => 'Aliyah', 'no_rm' => '0001', 'waktu_kunjungan' => '2025-08-07 17:36:11', 'status' => 'menunggu', 'poli' => 'ANAK'],
  ['id' => 2, 'no_antrian' => 2, 'nama_pasien' => 'Budi',   'no_rm' => '0002', 'waktu_kunjungan' => '2025-08-07 17:38:20', 'status' => 'menunggu', 'poli' => 'SARAF'],
  ['id' => 3, 'no_antrian' => 3, 'nama_pasien' => 'Clara',  'no_rm' => '0003', 'waktu_kunjungan' => '2025-08-07 17:40:10', 'status' => 'dipanggil', 'poli' => 'DALAM'],
  ['id' => 4, 'no_antrian' => 4, 'nama_pasien' => 'Dian',   'no_rm' => '0004', 'waktu_kunjungan' => '2025-08-07 17:42:00', 'status' => 'dipanggil', 'poli' => 'ANAK']
]; */

$query = "SELECT antri_ru.RUANGAN POLI,ru.DESKRIPSI NAMA_POLI, antri_ru.TANGGAL TANGGAL_ANTRIAN, antri_ru.NOMOR, antri_ru.JENIS, antri_ru.REF AS NOPEN, antri_ru.`STATUS`,pen.NORM,pen.TANGGAL,pas.NAMA NAMA_PASIEN, kun.NOMOR NOKUN, kun.MASUK, kun.KELUAR, kun.`STATUS` STATUS_KUNJUNGAN , kun.DPJP
FROM pendaftaran.antrian_ruangan antri_ru
LEFT JOIN pendaftaran.pendaftaran  pen ON pen.NOMOR = antri_ru.REF
LEFT JOIN `master`.pasien pas ON pas.NORM = pen.NORM
LEFT JOIN pendaftaran.kunjungan kun ON kun.NOPEN = antri_ru.REF
LEFT JOIN `master`.ruangan ru ON ru.ID = antri_ru.RUANGAN AND ru.JENIS_KUNJUNGAN IN (1) AND ru.JENIS IN (5)
WHERE 0 = 0 
#AND antri_ru.REF IN (2412050003,2506030003,2303290224)
AND ru.DESKRIPSI IS NOT NULL 
AND antri_ru.`STATUS` IN (1,2)
AND pen.TANGGAL BETWEEN '2025-08-08 00:00:00' AND '2025-08-08 23:59:59'
GROUP BY antri_ru.REF
ORDER BY pen.TANGGAL DESC
#LIMIT 10
";

$result = $koneksi->query($query);

$output = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $poli             = $row['NAMA_POLI'];
        $status           = $row['STATUS'];
        $status_kunjungan = $row['STATUS_KUNJUNGAN'];

        // Pastikan struktur awal sudah ada
        if (! isset($output[$poli])) {
            $output[$poli] = [
                'dipanggil' => [],
                'menunggu'  => [],
                'sudah'     => [],
            ];
        }

        // Tentukan kategori berdasarkan status
        if ($status == 1 && $status_kunjungan == 0) {
            $kategori = 'dipanggil';
        } elseif ($status == 2 && $status_kunjungan == 1) {
            $kategori = 'menunggu';
        } elseif ($status == 2 && $status_kunjungan == 2) {
            $kategori = 'sudah';
        } else {
            continue; // skip kalau status tidak dikenali
        }

        $output[$poli][$kategori][] = [
            'no_antrian'      => $row['NOMOR'],
            'nama_pasien'     => $row['NAMA_PASIEN'],
            'no_rm'           => $row['NORM'],
            'waktu_kunjungan' => $row['TANGGAL'],
        ];
    }
}

/* foreach ($data as $row) {
    $poli = $row['poli'];
    $status = $row['status'];

    if (!isset($output[$poli])) {
        $output[$poli] = ['menunggu' => [], 'dipanggil' => []];
    }

    $output[$poli][$status][] = [
        'no_antrian' => $row['no_antrian'],
        'nama_pasien' => $row['nama_pasien'],
        'no_rm' => $row['no_rm'],
        'waktu_kunjungan' => $row['waktu_kunjungan']
    ];
} */

echo json_encode($output);
