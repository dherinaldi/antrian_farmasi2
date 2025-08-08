<?php
include_once __DIR__ . '/../connect.php';
$jenis = isset($_REQUEST['jenis']) && $_REQUEST['jenis'];

if ($jenis == "poli") {

    $jenis_referensi = isset($_REQUEST['jenis_referensi']) && $_REQUEST['jenis_referensi'] != 0
    ? " AND ref.JENIS = " . $_REQUEST['jenis_referensi'] . " " : " ";

    $s_where = " WHERE 0 =0 ";

    $s_where .= $jenis_referensi;

    $s_query = "SELECT ru.ID POLI , ru.DESKRIPSI NAMA_POLI
FROM `master`.ruangan ru
WHERE ru.JENIS IN (5) AND ru.JENIS_KUNJUNGAN IN (1)";

    #   echo $s_query;

    $query = mysqli_query($koneksi, $s_query)
    or die(json_encode([
        "metaData" => [
            "code"    => "500",
            "message" => "Query Error: " . mysqli_error($koneksi),
        ],
        "response" => null,
    ]));

    $rows = mysqli_num_rows($query);

    if ($rows > 0) {
        while ($data = mysqli_fetch_assoc($query)) {
            $hasil[] = [
                "ID"        => $data['POLI'],
                "POLI"      => $data['POLI'],
                "NAMA_POLI" => $data['NAMA_POLI'],
                "DESKRIPSI" => $data['NAMA_POLI'],
            ];
        }
    } else {
        $hasil[] = [
            "ID"        => '',
            "POLI"      => '',
            "NAMA_POLI" => '',
            "DESKRIPSI" => '',
        ];
    }

    // Tambahkan ini untuk response khusus Select2
      if (isset($_GET['select2']) && $_GET['select2'] == '1') {
        $select2_data = [];
        foreach ($hasil as $row) {
            $select2_data[] = [
                "id"   => $row['ID'],
                "text" => $row['DESKRIPSI'],
            ];
        }
        echo json_encode($select2_data);
        exit;
    }

    if ($_GET['select2'] == '0') {
        echo json_encode($hasil); 
        exit;
    } 

    $output = [
        "metaData" => [
            "code"    => "200",
            "message" => "Sukses",
        ],
        "response" => [
            "hasil" => $hasil,
        ],
    ];
    header('Content-Type: application/json');
    echo json_encode($output, JSON_PRETTY_PRINT);
} else {
    echo 'tidak ada';
}
