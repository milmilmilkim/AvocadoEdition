<?php
include_once 'common/core.php';

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
	case 'POST':
		include_once BASEDIR . 'controllers/install/install.php';
		break;
	default:
		echo parseJsonError(405, 'Wrong HTTP Method');
		break;
}
