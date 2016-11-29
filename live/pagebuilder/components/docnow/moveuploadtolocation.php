<?php

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';
include_once '../../../custom_modules/common.php';

if($_FILES['file']['name']){

	$valid_file = true;
	//if no errors...
	if(!$_FILES['file']['error']){
		//now is the time to modify the future file name and validate the file
		$new_file_name = strtolower($_FILES['file']['name']); //rename file

		if($_FILES['file']['size'] > (1024000)){
			$valid_file = false;
			$message = 'Oops!  Your file\'s size is to large.';
		}
		
		//if the file has passed the test
		if($valid_file){
			//move it to where we want it to be
			if(move_uploaded_file($_FILES['file']['tmp_name'], IMAGE_FILES.'/profilepics/'.$new_file_name)){
				$message = 'Congratulations!  Your file was accepted.';
			}else{
				$valid_file = false;
				$message = 'Sorry the file could not be uploaded.';
			}
		
			
		}
	}else{
		//set that to be the returned message
		$valid_file = false;
		$message = 'Ooops!  Your upload triggered the following error:  '.$_FILES['file']['error'];
	}
// echo json_encode($arr = array('' => , );)
	if ($valid_file) {
		// saveUserProfilePic($_POST['Profile_ID'] , '/profilepics/'. $_FILES['file']['name']);
		$directory = '/profilepics/'. strtolower($_FILES['file']['name']);
		$sql = 'Update tUsers set profilepic = "' . $directory . '" WHERE Profile_ID = "' . $_POST['Profile_ID'] . '"';
    	UpdateDB($sql);
	}

	echo json_encode(array('valid' => $valid_file, 'message' => $message, 'file' => '/profilepics/'.$_FILES['file']['name']));
}
?>