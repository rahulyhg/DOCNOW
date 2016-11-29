<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';
include_once '../../../custom_modules/common.php';
/*debug($_FILES);
debug($_POST);
die;*/

if($_FILES[$_POST['Card_face']]['name']){

	$valid_file = true;
	//if no errors...
	if(!$_FILES[$_POST['Card_face']]['error']){
		//now is the time to modify the future file name and validate the file
		$new_file_name = strtolower($_FILES[$_POST['Card_face']]['name']); //rename file

		if($_FILES[$_POST['Card_face']]['size'] > (1024000)){
			$valid_file = false;
			$message = 'Oops!  Your file\'s size is to large.';
		}
		
		//if the file has passed the test
		if($valid_file){
			//move it to where we want it to be
			if(move_uploaded_file($_FILES[$_POST['Card_face']]['tmp_name'], IMAGE_FILES.'/medicalaidcards/'.$new_file_name)){
				$message = 'Congratulations!  Your file was accepted.';
				$file = '/medicalaidcards/'.$_FILES[$_POST['Card_face']]['name'];
			}else{
				$valid_file = false;
				$message = 'Sorry the file could not be uploaded.';
			}
		
			
		}
	}else{
		//set that to be the returned message
		$valid_file = false;
		$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES[$_POST['Card_face']]['error'];
	}

	if($valid_file){

		$SQL = "SELECT * FROM tPatientMedicalAidCards WHERE profile_id=".$_POST['Profile_ID'];
		$Query = QueryDB($SQL);
		$RecordCount = CountRowsDB ($Query);

		if($RecordCount == 1){

			$SQL = "UPDATE tPatientMedicalAidCards SET ".$_POST['Card_face']."= '{$file}' WHERE profile_id=".$_POST['Profile_ID'];
			UpdateDB ($SQL);

		}elseif ($RecordCount == 0){

			$SQL = "INSERT INTO tPatientMedicalAidCards (profile_id, ".$_POST['Card_face'].") VALUES ('".$_POST['Profile_ID']."', '{$file}')";
			InsertDB ($SQL, None);
		}

	}
	echo json_encode(array('valid' => $valid_file, 'message' => $message, 'file' =>$file, 'Card_face' => $_POST['Card_face']));
}
?>