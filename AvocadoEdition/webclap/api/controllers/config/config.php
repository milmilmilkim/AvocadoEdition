<?php
if (!defined('_WEBCLAP_')) exit;

Token::refresh();
try {
	// 설치 여부 확인
	if (!file_exists(DBCONFIG)) {
		throw new Exception();
	}

	include_once(DBCONFIG);
	include_once BASEDIR . 'models/config.php';

	$config = new Config();
	$result = $config->findById(1);

	// 숫자 풀기
	$result['wc_cnt_limit'] = intval($result['wc_cnt_limit']);

	// 아이디, 비밀번호, 메시지 카운트는 삭제
	unset($result['id']);
	unset($result['username']);
	unset($result['password']);
	unset($result['wc_total_cnt']);

	// 설정값 전송
	echo parseJson($result);
} catch (Exception $e) {
	echo parseJson(array('error' => true));
}
