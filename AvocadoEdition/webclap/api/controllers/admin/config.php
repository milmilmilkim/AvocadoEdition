<?php
if (!defined('_WEBCLAP_')) exit;

Token::protect();
include_once BASEDIR . 'models/config.php';

try {
	// DB접속 & 객체정의
	$config = new Config();

	// 파일처리
	$random_string = uniqid() . '_';
	if (array_key_exists('wc_main_img_data', $_FILES)) {
		$file = $_FILES['wc_main_img_data'];
		$tmp_file = $file['tmp_name'];
		if (!isValidFile($tmp_file)) {
			throw new Exception('이미지 파일이 아닙니다.');
		}
		$upload_dir = BASEDIR . (basename(BASEDIR) == 'server' ? '../public' : '..');
		$file_path = $upload_dir . '/' . $random_string . basename($file['name']);
		move_uploaded_file($tmp_file, $file_path);
		@chmod($file_path, 0644);
		@unlink($upload_dir . '/' . basename($_POST['wc_main_img_orig']));
	}

	// form data 추출
	$wc_title = trim($_POST['wc_title']);
	$wc_main_msg = trim($_POST['wc_main_msg']);
	$wc_return_msg = trim($_POST['wc_return_msg']);
	$wc_cnt_limit = intval($_POST['wc_cnt_limit']);

	// 설정 변경
	$field = array(
		'wc_title' => $wc_title,
		'wc_main_msg' => $wc_main_msg,
		'wc_return_msg' => $wc_return_msg,
		'wc_cnt_limit' => $wc_cnt_limit,
	);
	if (array_key_exists('wc_main_img_data', $_FILES)) {
		$field['wc_main_img'] = $random_string . basename($_FILES['wc_main_img_data']['name']);
	}
	$config->updateById(1, $field);

	// 업데이트 성공
	echo parseJson($field);
} catch (PDOException $e) {
	echo parseJsonError(503, $e->getMessage());
} catch (Exception $e) {
	echo parseJsonError(400, $e->getMessage());
}
