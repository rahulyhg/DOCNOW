<?php
include_once ("../../../modules/DB.php");
include_once ("../../../modules/connect.php");

require_once 'Haversine.php';

$StartLat = $_GET['StartLat'];
$StartLon = $_GET['StartLon'];
$Distance = "";

$SQL = "SELECT profile_id, lat, lng FROM `tUsers`";
$SQL .= $_GET['speciality'] ? " AND specialty_id=".$_GET['speciality'] : "";

$Query = QueryDB($SQL);
while ($Result = ReadFromDB($Query)){

     $Haversine = new Haversine(
        
        array(
            'lat' => $StartLat,
            'lon' => $StartLon
        ),
        array(
            'lat' => $Result['lat'],
            'lon' => $Result['lng']
        )
    );
    $Haversine->showSuffix(false);
    $Distance [$Result['profile_id']]= str_replace(".", "", $Haversine);
}

//$Distance = explode("|", $Distance);
$Distance = array_filter($Distance);
asort($Distance, SORT_NUMERIC);

$BranchID = array_keys($Distance);

echo $BranchID[0];


?>