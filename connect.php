<?php
// get_antrian.php

//ubah disini untuk koneksinya 
 $host = "192.168.10.236";
$user = "admin";
$pass = "S!MRSGos2";
$db   = "regonline"; 

//environment production
/*   $host = "192.168.100.110";
$user = "admin";
$pass = "S!MRSGos2";
$db   = "regonline"; */ 

/* $host = "192.168.3.99";
$user = "admin";
$pass = "S!MGos2@kemkes.go.id";
$db   = "regonline";  */

$koneksi = new mysqli($host, $user, $pass, $db);

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}