<?php
// 공통변수선언
define('_WEBCLAP_', true);
define('BASEDIR', dirname(__DIR__) . '/');
define('DBCONFIG', BASEDIR . 'common/dbconfig.php');

// 공통 라이브러리
include_once BASEDIR . 'libs/function.php';
include_once BASEDIR . 'libs/jwt/token.php';

// 공통 헤더
header('Content-Type: application/json; charset=UTF-8');
