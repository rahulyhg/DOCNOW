<?php
	include_once 'custom_modules/common.php';

	$data = $_POST;
	$sent = saveNotification($data);

	return json_encode($sent);