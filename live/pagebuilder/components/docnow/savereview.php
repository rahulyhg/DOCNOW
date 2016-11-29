<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';

extract($_POST);

$SQL = "SELECT * FROM tDocReview WHERE appointment_id={$appointment_id}";
$Query = QueryDB($SQL);
$RecordCount = CountRowsDB ($Query);;

if($RecordCount == 0){

	$SQL = "INSERT INTO tDocReview(comment,star,appointment_id) VALUES ('".mysql_real_escape_string($comment)."', ".$star.", ".$appointment_id.")";
	$NewRecord = InsertDB ($SQL);

	
}else{
	$SQL = "UPDATE tDocReview SET comment='".mysql_real_escape_string($comment)."', star=".$star." WHERE appointment_id={$appointment_id}";
	$NewRecord = UpdateDB ($SQL);

}
if($NewRecord > 0){

		$message ['error'] = false;
		$message ['message'] = "Your reveiw was saved successfully";

}else{

	$message ['error'] = true;
	$message ['message'] = "Sorry could not save your review, please try again";
}

echo json_encode($message);

?>