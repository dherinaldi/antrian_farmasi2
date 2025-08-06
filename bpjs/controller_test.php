<?php
$config = include 'config.php';
include 'bpjs_helper.php';

$endpoint = "Peserta/nokartu/3507251902890001/tglSEP/2025-08-25";
$response = bpjsRequest($endpoint, $config);
echo $response;
