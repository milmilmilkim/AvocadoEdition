<?php
if (!defined('_WEBCLAP_')) exit;

include_once BASEDIR . 'models/config.php';

try {
	$config = new Config();

	// form 데이터 가져오기
	$data = json_decode(file_get_contents("php://input"));
	$username = trim($data->username);
	$password = trim($data->password);

	// 로그인
	$user = $config->findOne(array('username' => $username));
	if (!$user) {
		echo parseJsonError(401, '아이디가 존재하지 않습니다.', 'username');
		exit;
	}
	if (!$config->verify($password, $user['password'])) {
		echo parseJsonError(401, '비밀번호가 틀립니다.', 'password');
		exit;
	}

	// 토큰 생성
	Token::generate();

	// 로그인 성공
	echo parseJsonSuccess();
} catch (Exception $e) {
	echo parseJsonError(401, $e->getMessage());
}
