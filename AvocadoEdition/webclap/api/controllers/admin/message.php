<?php
if (!defined('_WEBCLAP_')) exit;

Token::protect();
include_once BASEDIR . 'models/message.php';
include_once BASEDIR . 'models/config.php';

try {
	// DB접속 & 객체정의
	$message = new Message();

	// form data에서 조회날짜와 조회타입 지정
	$date = $_GET['date'];
	$val = explode(":", $_GET['val']);

	$result = array();
	foreach ($val as $v) {
		if ($v == 'enable') {
			// 년월로 찾아서 일별 박수(+메시지) 존재하는 날 체크
			$result[$v] = $message->getDaysByYearAndMonth($date);
		} else if ($v == 'count') {
			// 날짜기준 시간별 박수(+메시지) 개수
			$result[$v] = $message->getHoursByDate($date);
		} else if ($v == 'messages') {
			// 날짜기준 메시지(박수x) 목록
			$result[$v] = $message->getDaysByDate($date);
		}
	}

	echo parseJson($result);
} catch (PDOException $e) {
	echo parseJsonError(503, $e->getMessage());
} catch (Exception $e) {
	echo parseJsonError(400, $e->getMessage());
}
