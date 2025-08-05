<?php
$config = include('config.php');
include('bpjs_helper.php');



$response = bpjsRequest('/ref/poli', $config);

echo $response;
