<?php
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
	case 'GET':
		include_once 'common/core.php';
		include_once BASEDIR . 'controllers/config/config.php';
		break;
	case 'POST':
		include_once 'common/common.php';
		include_once BASEDIR . 'controllers/admin/config.php';
		break;
	default:
		echo parseJsonError(405, 'Wrong HTTP Method');
		break;
}
