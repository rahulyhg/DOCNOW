<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';

print_r($_POST);

$SQL = "UPDATE Users SET first_name='{$_POST['first_name']}',second_name='{$_POST['second_name']}', last_name='{$_POST['last_name']}',email='{$_POST['email']}',phone='{$_POST['phone']}',fax='{$_POST['fax']}',skype='{$_POST['skype']}',websiteurl='{$_POST['websiteurl']}',address='{$_POST['address']}',profilepic='{$_POST['profilepic']}', finishedReg=1 WHERE profile_id={$_POST['Profile_ID']}";

UpdateDB ($SQL);

foreach ($_POST['medical_history'] as $medical_history) {
	
	$SQL = "INSERT INTO tPatientMedicalHistory(medical_history_data, profile_id) VALUES ('{$medical_history}',{$_POST['Profile_ID']})";
	$NewRecord = InsertDB ($SQL);
}


?>