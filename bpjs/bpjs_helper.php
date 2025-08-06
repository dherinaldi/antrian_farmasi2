<?php
#perbaikan path relative path
include_once(__DIR__ . '/../lz/LZString.php');
include_once(__DIR__ . '/../lz/LZReverseDictionary.php');
include_once(__DIR__ . '/../lz/LZData.php');
include_once(__DIR__ . '/../lz/LZUtil.php');
include_once(__DIR__ . '/../lz/LZUtil16.php');
include_once(__DIR__ . '/../lz/LZContext.php');

function getTimestamp()
{
    date_default_timezone_set('UTC');
    return strval(time());
}

function getSignature($cid, $secretKey, $timestamp)
{
    $signature = hash_hmac('sha256', $cid . "&" . $timestamp, $secretKey, true);
    return base64_encode($signature);
}

function stringDecrypt($key, $string)
{
    $encrypt_method = 'AES-256-CBC';
    $key_hash       = hex2bin(hash('sha256', $key));
    $iv             = substr($key_hash, 0, 16);
    return openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
}

function decompressLZ($string)
{
    return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);
}

/**
 * Reusable function for BPJS request
 *
 * @param string $endpoint
 * @param array $config
 * @param string $method [GET|POST|PUT|DELETE]
 * @param array|null $bodyData - for POST, PUT
 * @return string|array
 */
function bpjsRequest($endpoint, $config, $method = 'GET', $bodyData = null, $type = "vclaim")
{
    $cid       = $config['cons_id'];
    $secretKey = $config['secret_key'];

    if ($type == 'vclaim') {
        $userKey = $config['user_key'];
        $baseUrl = $config['base_url'];
    } else {
        $userKey = $config['user_key_antrol'];
        $baseUrl = $config['base_url_antrol'];
    }

    $timestamp = getTimestamp();
    $signature = getSignature($cid, $secretKey, $timestamp);

    $headers = [
        'Content-Type: application/json',
        'X-cons-id: ' . $cid,
        'X-timestamp: ' . $timestamp,
        'X-signature: ' . $signature,
        'user_key: ' . $userKey,
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $baseUrl . $endpoint);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    // Jika POST atau PUT, kirimkan body data dalam bentuk JSON
    if (in_array(strtoupper($method), ['POST', 'PUT']) && $bodyData !== null) {
        //$jsonData = json_encode($bodyData);
        $jsonData = $bodyData;
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
    }

    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response, true);

    if (! isset($json['response'])) {
        // Gagal didekripsi atau tidak ada response terenkripsi
        return $response;
    }

    $kunci     = $cid . $secretKey . $timestamp;
    $decrypted = stringDecrypt($kunci, $json['response']);
    return decompressLZ($decrypted);
}
