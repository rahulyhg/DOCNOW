<?php

include_once 'custom_modules/common.php';

if (!empty($_POST) || !isset($_POST['email']) || !isset($_POST['name']) || !isset($_POST['id'])) {

	$data = $_POST;
	$name =  explode(' ', $_POST['name']);
	$data['first_name'] = $name[0];
	$data['last_name'] = isset($name[1]) ? $name[1] : '';
	// debug($data);
	$response = createGoogleProfile($data);
// debug($response);
	echo json_encode($response);
	exit;
}