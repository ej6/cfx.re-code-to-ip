<?php

if (!isset($_GET['code'])) {
    die("no code provided");
}

$code = $_GET['code'];

$url = "https://cfx.re/join/{$code}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (platform; rv:geckoversion) Gecko/geckotrail Firefox/firefoxversion");

$headers = curl_exec($ch);

curl_close($ch);

$headerName = "X-Citizenfx-Url";
$headerValue = "";
$headerLines = explode("\r\n", trim($headers));
foreach ($headerLines as $headerLine) {
    $headerParts = explode(": ", $headerLine);
    if (count($headerParts) === 2 && strtolower($headerParts[0]) === strtolower($headerName)) {
        $headerValue = $headerParts[1];
        break;
    }
}

if (empty($headerValue)) {
    die("Invalid Code");
}

$ipPort = preg_replace('#^https?://#', '', $headerValue);
$ipPort = rtrim($ipPort, '/');
if (filter_var($ipPort, FILTER_VALIDATE_IP)) {
    list($ip, $port) = explode(":", $ipPort);
    print("ip={$ip}\nport={$port}");
} else {
    $domain = str_replace("https://", "", $ipPort);
    print("IP= {$domain}");
}

?>
