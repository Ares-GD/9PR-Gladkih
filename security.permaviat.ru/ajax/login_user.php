<?php
	session_start();

	$login = $_POST['login'] ?? '';
	$password = $_POST['password'] ?? '';

	$url = 'http://security.permaviat.ru/9PR-Gladkih/index.php';
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$login:$password"); 
	curl_setopt($ch, CURLOPT_HEADER, true); 
	$response = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$token = '';
	if ($http_code == 200) {
		preg_match('/token: (.*)/', $response, $matches);
		if (isset($matches[1])) {
			$token = $matches[1];
			$_SESSION['token'] = $token;
			header('Content-Type: application/json');
			echo json_encode(['token' => $token]);
		} else {
			header('HTTP/1.0 500 Internal Server Error');
			echo json_encode(['error' => 'Токен не получен']);
		}
	} else {
		header('HTTP/1.0 401 Unauthorized');
		echo json_encode(['error' => 'Неверный логин или пароль']);
	}
?>