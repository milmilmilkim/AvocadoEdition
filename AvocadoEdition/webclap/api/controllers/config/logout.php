<?php
if (!defined('_WEBCLAP_')) exit;

Token::protect();
try {
	// 토큰 삭제
	Token::destroy();

	// 로그아웃 성공
	echo parseJsonSuccess();
} catch (Exception $e) {
	echo parseJsonError(401, $e->getMessage());
}
