<?php
if (!defined('_WEBCLAP_')) exit;

Token::refresh();
include_once BASEDIR . 'models/message.php';
include_once BASEDIR . 'models/config.php';

try {
	// 데이터베이스 접속 & 객체 정의
	$message = new Message();
	$config = new Config();
	$conf = $config->findById(1);

	// 카운트 제한 사용시 박수 카운트 제한 확인
	$wc_cnt_limit = $conf['wc_cnt_limit'];
	$wc_cnt = 1;
	if ($wc_cnt_limit > 0) {
		if (!isset($_COOKIE['wc_cnt'])) {
			$wc_cnt = 1;
		} else {
			$wc_cnt = $_COOKIE['wc_cnt'] + 1;
			if ($wc_cnt > $wc_cnt_limit) {
				throw new Exception("박수는 연속해서 " . $wc_cnt_limit . "번까지 보낼 수 있습니다.\n잠시 후에 부탁드립니다.");
			}
		}
	}

	// 박수 추가하기
	$field = array(
		'type' => 0,
		'content' => '',
		'ip' => sprintf('%u', ip2long($_SERVER['REMOTE_ADDR'])),
		'date' => date('Y-m-d H:i:s'),
	);
	$message->insert($field);

	// 박수 총 개수 증가시키기
	$config->increaseCount(1);

	// 카운트 제한 사용시 카운트 증가
	if ($wc_cnt_limit > 0) {
		setcookie('wc_cnt', $wc_cnt, time() + 3600, '/', $_SERVER['SERVER_NAME'], Token::isSecure(), true); // 1시간
	}

	// 성공 메시지 출력
	echo parseJson(array('message' => $conf['wc_return_msg']));
} catch (PDOException $e) {
	echo parseJsonError(503, $e->getMessage());
} catch (Exception $e) {
	echo parseJsonError(400, $e->getMessage());
}
