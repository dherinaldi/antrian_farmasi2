<?php
include_once('..\lz\LZString.php');
include_once('..\lz\LZReverseDictionary.php');
include_once('..\lz\LZData.php');
include_once('..\lz\LZUtil.php');
include_once('..\lz\LZUtil16.php');
include_once('..\lz\LZContext.php');

function getTimestamp() {
    date_default_timezone_set('UTC');
    return strval(time());
}

function getSignature($cid, $secretKey, $timestamp) {
    $signature = hash_hmac('sha256', $cid . "&" . $timestamp, $secretKey, true);
    return base64_encode($signature);
}

function stringDecrypt($key, $string) {
    $encrypt_method = 'AES-256-CBC';
    $key_hash = hex2bin(hash('sha256', $key));
    $iv = substr($key_hash, 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
}

function decompressLZ($string) {
    return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
}

function bpjsRequest($endpoint, $config) {
    $cid = $config['cons_id'];
    $secretKey = $config['secret_key'];
    $userKey = $config['user_key'];
    $baseUrl = $config['base_url'];

    $timestamp = getTimestamp();
    $signature = getSignature($cid, $secretKey, $timestamp);

    $headers = [
        'X-cons-id: ' . $cid,
        'X-timestamp: ' . $timestamp,
        'X-signature: ' . $signature,
        'user_key: ' . $userKey
    ];

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => $headers
    ));

    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response, true);
    if (!isset($json['response'])) {
        return $response; // error atau format tidak sesuai
    }

    $kunci = $cid . $secretKey . $timestamp;
    $decrypted = stringDecrypt($kunci, $json['response']);
    return decompressLZ($decrypted);
}
