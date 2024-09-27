<?php
if (!defined('_WEBCLAP_')) exit;

Token::protect();
include_once BASEDIR . 'models/message.php';

try {
	// DB접속 & 객체정의
	$message = new Message();

	// 삭제 실행
	$message->deleteAll();

	// 삭제 성공
	echo parseJsonSuccess();
} catch (PDOException $e) {
	echo parseJsonError(503, $e->getMessage());
} catch (Exception $e) {
	echo parseJsonError(400, $e->getMessage());
}
