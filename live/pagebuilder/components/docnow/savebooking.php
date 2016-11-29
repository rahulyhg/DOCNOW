<?php
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;

	if (!isset($_POST['confirm-booking']) || empty($_POST)) {
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot find booking data.', 'alert-danger');
	}	

	if (saveBooking($_POST)) {
		sendDoctorBookingEmail($_POST);
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Booking saved successfully.', 'alert-success');
	} else {
		redirectToPage(ThisURL . '?Session_ID=' . $Session_ID, 'Cannot save booking data.', 'alert-danger');
	}

	exit;