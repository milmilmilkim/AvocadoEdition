<?php
include_once 'common/common.php';

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
	case 'GET':
		include_once BASEDIR . 'controllers/admin/message.php';
		break;
	case 'DELETE':
		include_once BASEDIR . 'controllers/admin/delete.php';
		break;
	case 'POST':
		$data = json_decode(file_get_contents("php://input"));
		$type = trim($data->type);
		if ($type == 'clap')
			include_once BASEDIR . 'controllers/message/clap.php';
		else if ($type == 'message')
			include_once BASEDIR . 'controllers/message/message.php';
		else
			echo parseJsonError(400, 'Wrong Type Access');
		break;
	default:
		echo parseJsonError(405, 'Wrong HTTP Method');
		break;
}
