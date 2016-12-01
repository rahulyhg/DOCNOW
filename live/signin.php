<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once('modules/DB.php');
include_once('modules/connect.php');
include_once ("modules/profile.php");
include_once ("modules/catalog.php");
include_once 'custom_modules/common.php';

global $UserStatus, $Filename;
global $Error_NUM, $From, $Dest_URL;

if (!empty($_POST)) {
    signin($_POST);
}

function signin($data) {

    $registrationURL = ThisURL . ROOT_URL . "/LSM.php";
    $data['Src_URL'] = ($Filename ? ThisURL . ROOT_URL . '/' . $Filename : HOME) . '?From=signin';
    $data['APIKey'] = 'f0e8212b6bda3ced017c4659bd6ea90b';
    $data['Format'] = 'json';
    $responseJSON = httpPost($registrationURL, $data);

    $response = json_decode($responseJSON);

    $dataToShow = $responseJSON;
/*
    print_r($response);

    echo $response->Profile_ID;*/

    if($response->Profile_ID){
        
       $profileDetails = getProflieRegDetails($response->Profile_ID);
       
       unset($data);

       session_start();

       $_SESSION['proflieRegDetails'] = $profileDetails;

       if ($profileDetails['doctor'] == '1'){

            $_Item_ID = 18;
            $data['url'] = ThisURL.RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $_Item_ID, RetrieveCatalogItemCode($_Item_ID), DEVICE_PC)."&Session_ID=".$response->Session_ID;
       }else{

            $_Item_ID = ($profileDetails['finishedReg'] == '0' ? 35 : 31);
            $data['url'] = ThisURL.RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $_Item_ID, RetrieveCatalogItemCode($_Item_ID), DEVICE_PC)."&Session_ID=".$response->Session_ID;
       }

       $dataToShow = json_encode($data);

    }

    echo $dataToShow;
    
}