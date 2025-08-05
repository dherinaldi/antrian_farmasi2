<?php
$config = include('config.php');
include('bpjs_helper.php');
#endpoint untuk vclaim
#$endpoint = "Peserta/nokartu/{$noka}/tglSEP/{$tanggal}";

$response = bpjsRequest('Peserta/nik/3507251902890001/tglSEP/2025-08-05', $config);

echo $response;


#post Data untuk antrol
$postData = '{
   "kodebooking": "2211210013",
   "jenispasien": "JKN",
   "nomorkartu": "0002073663292",
   "nik": "1118010107560020",
   "nohp": "085635228888",
   "kodepoli": "INT",
   "namapoli": "DALAM",
   "pasienbaru": 0,
   "norm": "123345",
   "tanggalperiksa": "2025-08-09",
   "kodedokter": 16308,
   "namadokter": "Dr Rahadian",
   "jampraktek": "09:00-13:00",
   "jeniskunjungan": 1,
   "nomorreferensi": "132418010922P000020",
   "nomorantrean": "A-12",
   "angkaantrean": 12,
   "estimasidilayani": 1615869169000,
   "sisakuotajkn": 5,
   "kuotajkn": 30,
   "sisakuotanonjkn": 5,
   "kuotanonjkn": 30,
   "keterangan": "Peserta harap 30 menit lebih awal guna pencatatan administrasi."
}';
$response1 = bpjsRequest('antrean/add', $config, 'POST', $postData,'antrol');
echo $response1;
