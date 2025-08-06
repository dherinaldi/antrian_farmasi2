# Monitoring API VClaim BPJS

Aplikasi monitoring koneksi endpoint BPJS Kesehatan berbasis web ( VCLAIM DAN ANTROL) untuk FKRTL

## Fitur
- Cek koneksi API (Peserta, Rujukan, SEP, dsb)
- Menampilkan response time
- Log update terakhir

## Cara Deploy
1. Download / Clone file dari sumber github terdahulu (https://github.com/dherinaldi/antrian_farmasi2/) 
2. Aktif di folder bpjs 
3. Duplikasi atau bisa rename config.php.dist menjadi config.php 
    ```php
<?php
//Konfigurasi disini
return [
    'cons_id' => '{cons_id_faskes}',
    'secret_key' => '{secret_key_faskes}',
    'user_key' => '{user_key_vclaim}',
    'base_url' => '{base_url_vclaim}',#https://apijkn.bpjs-kesehatan.go.id/vclaim-rest/
    'user_key_antrol' => '{user_key_antrol}',
    'base_url_antrol' => '{base_url_antrol}'#https://apijkn.bpjs-kesehatan.go.id/antreanrs/    
];

```
Note : Perhatikan cara penulisan base_url dan base_url_antrol 

4. Jalankan Aplikasi untuk dashboard monitoring: `http://{ip_server}/antrian_farmasi2/bpjs/`

5. Jalankan Aplikasi untuk dashboard monitoring chart: `http://{ip_server}/antrian_farmasi2/bpjs/chart.php`

## Teknologi
- PHP
- jQuery
- Bootstrap 5
