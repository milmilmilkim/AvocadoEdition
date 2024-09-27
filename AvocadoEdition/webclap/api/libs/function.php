<?php
if (!defined('_WEBCLAP_')) exit;

function parseJson($data)
{
	return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function parseJsonSuccess()
{
	return parseJson(array('error' => false));
}

function parseJsonError($code, $message, $name = NULL)
{
	http_response_code($code);
	$error = array('error' => true, 'message' => $message);
	if ($name) {
		$error['name'] = $name;
	}
	return parseJson($error);
}

function isValidFile($file)
{
	$size = getimagesize($file);
	if (!$size) {
		return false;
	}

	$valid_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);
	if (in_array($size[2],  $valid_types)) {
		return true;
	} else {
		return false;
	}
}
