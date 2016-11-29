<?php
include_once ("../../../modules/DB.php");
include_once ("../../../modules/connect.php");
include_once '../../../custom_modules/common.php';

// debug($_GET);
function parseToXML($htmlStr){ 
	$xmlStr=str_replace('<','&lt;',$htmlStr); 
	$xmlStr=str_replace('>','&gt;',$xmlStr); 
	$xmlStr=str_replace('"','&quot;',$xmlStr); 
	$xmlStr=str_replace("'",'&apos;',$xmlStr); 
	$xmlStr=str_replace("&",'&amp;',$xmlStr); 
	return $xmlStr; 
} 


header("Content-type: text/xml");
// Start XML file, echo parent node
echo '<markers>';
$SQL = "SELECT * FROM tUsers WHERE doctor=1";

$SQL .= $_GET['speciality'] ? " AND speciality_id=".$_GET['speciality'] : "";

$Query = QueryDB($SQL);
while ($Result = ReadFromDB($Query)){

	echo '<marker ';
	echo 'profile_id="' . $Result['profile_id'] . '" ';
	echo 'name="Dr. ' . $Result['first_name'] . ' '.$Result['last_name'].'" ';
	echo 'address="' . parseToXML($Result['address']) . '" ';
	echo 'speciality="'.getSpecialityName($Result['speciality_id']).'" ';
	echo 'lat="' . $Result['lat']. '" ';
	echo 'lng="' . $Result['lng']. '" ';
	echo '/>';
}
// End XML file
echo '</markers>';
?>