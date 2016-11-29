<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';
include_once '../../../modules/profile.php';
/*echo "<pre>";print_r($_POST);echo "</pre>";die;*/

extract($_POST);
/*
if($whichform == 'profiledata'){

	
}elseif ($whichform == 'password') {

		
}elseif (condition) {
	# code...
}*/

switch ($whichform) {

	case 'profiledata':
		UpdateProfile ($Profile_ID, $email, None);

		$SQL1 = "UPDATE tUsers SET first_name='".mysql_real_escape_string($first_name)."',last_name='".mysql_real_escape_string($last_name)."',cell_phone='".mysql_real_escape_string($cell_phone)."',address_1='".mysql_real_escape_string($address_1)."',address_2='".mysql_real_escape_string($address_2)."',city='".mysql_real_escape_string($city)."',province='".mysql_real_escape_string($province)."',birth_date='".mysql_real_escape_string($birth_date)."', nickname='".mysql_real_escape_string($nickname)."',guarduian='".mysql_real_escape_string($guarduian)."',country_id='".mysql_real_escape_string($country_id)."',work_number='".mysql_real_escape_string($work_number)."',home_number='".mysql_real_escape_string($home_number)."',prefered_number='".mysql_real_escape_string($prefered_number)."',gender='".mysql_real_escape_string($gender)."',marital_status='".mysql_real_escape_string($marital_status)."',postal_code='".mysql_real_escape_string($postal_code)."', finishedReg =1, address='".mysql_real_escape_string($address)."', lat='{$lat}', lng='{$lng}', speciality_id = '{$specialty}', language ='".join(',', $language)."' WHERE profile_id=".mysql_real_escape_string($Profile_ID);


		$Result = UpdateDB ($SQL1,None);
		$SQL2 = "DELETE FROM tUserEmergencyDetails WHERE profile_id={$Profile_ID}";
		DeleteFromDB ($SQL2);


		$SQL3 = "INSERT INTO tUserEmergencyDetails(profile_id, emergency_contact_name, emergency_contact_surname, emergency_contact_relationship, emergency_contact_number) VALUES ('".mysql_real_escape_string($Profile_ID)."', '".mysql_real_escape_string($emergency_contact_name)."', '".mysql_real_escape_string($emergency_contact_surname)."', '".mysql_real_escape_string($emergency_contact_relationship)."', '".mysql_real_escape_string($emergency_contact_number)."')";

		$NewRecord = InsertDB ($SQL3);

		$SQL4 = "DELETE FROM tUserEmployer WHERE profile_id={$Profile_ID}";

		DeleteFromDB ($SQL4);

		$SQL5 = "INSERT INTO tUserEmployer(profile_id, employer_name, employer_address, employer_contact_number) VALUES ('".mysql_real_escape_string($Profile_ID)."', '".mysql_real_escape_string($employer_name)."', '".mysql_real_escape_string($employer_address)."', '".mysql_real_escape_string($employer_contact_number)."')";

		$NewRecord = InsertDB ($SQL5);

		$response = array('error' => false, 'message' => 'Your setting have been saved.');

		break;
	case 'password':
		
		$ProfileDetails = RetrieveProfileDetails ($Profile_ID);
		
		if(trim($pwdOld) == $ProfileDetails['Password_STRING']){

			if(trim($pwd1) == trim($pwd2)) {

				if(UpdateProfile ($Profile_ID, None, $pwd1)){

					$response ['error'] = false;
					$response ['message'] = "Your password has been updated.";
				}else{

					$response ['error'] = true;
					$response ['message'] = "Your password could not be updated.";
				}
			}else{

				$response ['error'] = true;
				$response ['message'] = "Your password does not match with the confirmation password.";	
			}
		}else{

			$response ['error'] = true;
			$response ['message'] = "Your old password does not match the one on our system.";
		}

		
		break;
	
	case 'preferences':
		
/*		echo "<pre>";print_r($_POST);echo "</pre>";die;*/

		$SQL = "DELETE FROM tUserPreferences WHERE profile_id={$Profile_ID}";

		//echo $SQL;
		DeleteFromDB ($SQL);

		$SQL = "INSERT INTO tUserPreferences(profile_id, wellness_reminder, push_appointment_reminder, push_appointment_reschedule, push_wellness_reminder) VALUES ('".mysql_real_escape_string($Profile_ID)."','".mysql_real_escape_string($wellness_reminder)."','".mysql_real_escape_string($push_appointment_reminder)."','".mysql_real_escape_string($push_appointment_reschedule)."','".mysql_real_escape_string($push_wellness_reminder)."')";
		$NewRecord = InsertDB ($SQL);

		$response ['error'] = false;
		$response ['message'] = "Your notifications settings have been updated.";

		break;
}

echo json_encode($response);
?>