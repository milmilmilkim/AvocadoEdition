<?php

/**
 * 이 파일에서는 설치여부를 확인합니다
 * config.php, install.php를 제외한 파일은 이 파일을 include 해야 함
 */

include_once 'core.php';
if (!defined('_WEBCLAP_')) exit;

// 설치여부확인
if (file_exists(DBCONFIG)) {
	include_once(DBCONFIG);
} else {
	echo parseJsonError(503, "웹박수가 설치되지 않았습니다.");
	exit;
}
