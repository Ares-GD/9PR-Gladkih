<?php
include("connect_datebase.php");

if (!isset($_SERVER['PHP_AUTH_USER']) || empty($_SERVER['PHP_AUTH_USER'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

if (!isset($_SERVER['PHP_AUTH_PW']) || empty($_SERVER['PHP_AUTH_PW'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$login = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];

$query_user = $mysqli->query("SELECT * FROM `users` WHERE `login` = '$login' AND `password` = '$password'");

if ($read_user = $query_user->fetch_assoc()) {
    // Формирование JWT токена
    $header = [
        "typ" => "JWT",
        "alg" => "sha256"
    ];

    $payload = [
        "userId" => $read_user['id'],
        "role" => $read_user['roll']
    ];

    $SECRET_KEY = 'cAtwa1kkEy';

    $unsignedToken = base64_encode(json_encode($header)) . '.' .
                     base64_encode(json_encode($payload));

    $signature = hash_hmac($header['alg'], $unsignedToken, $SECRET_KEY);

    $token = base64_encode(json_encode($header)) . '.' .
             base64_encode(json_encode($payload)) . '.' .
             base64_encode($signature);

    header("token: " . $token);
    header('HTTP/1.0 200 OK');
} else {
    header('HTTP/1.0 401 Unauthorized');
}
?>