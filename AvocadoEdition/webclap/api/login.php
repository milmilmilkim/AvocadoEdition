<?php
include_once 'common/common.php';

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
	case 'POST':
		include_once BASEDIR . 'controllers/config/login.php';
		break;
	default:
		echo parseJsonError(405, 'Wrong HTTP Method');
		break;
}
