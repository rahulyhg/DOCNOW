<?php

include_once('modules/DB.php');
include_once('modules/connect.php');
include_once("modules/profile.php");
include_once("modules/MIME.php");
include_once("modules/catalog.php");


global $UserStatus, $Filename;
global $Error_NUM, $From, $Dest_URL;

if (!empty($_POST)) {
    signup($_POST);
}

function signup($data) {
    $registrationURL = ROOT_URL . "/CPPM.php";
    $data['Src_URL'] = ($Filename ? ROOT_URL . '/' . $Filename : HOME) . '?From=signup';
    $data['APIKey'] = 'f0e8212b6bda3ced017c4659bd6ea90b';
    $data['Format'] = 'json';
    $response = httpPost($registrationURL, $data);
    $responseObj = json_decode($response, true);

    if (isset($responseObj['Error_NUM']) && $responseObj['Error_NUM'] == 0) {
        $data['profile_id'] = $responseObj['Profile_ID'];
        if (saveUser($data)){
            // $loginURL = ThisURL . ROOT_URL . "/LSM.php";
            makeUserInactive($data['profile_id']);
            sendEmailVerification($data);
            // echo httpPost($loginURL, $data);
            echo $response;
        }
    } else {
        echo $response;
    }
}

function makeUserInactive($profileId) {
    $sql = 'Update Profiles set Status_NUM = "-2" WHERE Profile_ID = "' . $profileId . '"';
    return UpdateDB($sql);
}

function saveUser($data) {
    $profileId = $data['profile_id'];
    $doctor = $data['doctor'];
    $firstName = isset($data['first_name']) ? addslashes($data['first_name']) : null;
    $lastName = isset($data['last_name']) ? addslashes($data['last_name']) : null;
    $email = isset($data['Eml']) ? addslashes($data['Eml']) : null;
    $landLine = isset($data['land_line']) ? addslashes($data['land_line']) : null;
    $cellPhone = isset($data['cell_phone']) ? addslashes($data['cell_phone']) : null;
    $birthDate = isset($data['birth_date'])  ? addslashes($data['birth_date']) : null;
    $address1 = isset($data['address_1']) ? addslashes($data['address_1']) : null;
    $address2 = isset($data['address_2']) ? addslashes($data['address_2']) : null;
    $city = isset($data['city']) ? addslashes($data['city']) : null;
    $postalCode = isset($data['postal_code']) ? addslashes($data['postal_code']) : null;
    $province = isset($data['province']) ? addslashes($data['province']) : null;
    $practiceNumber = isset($data['practice_number']) ? addslashes($data['practice_number']) : null;

    $specialityId = isset($data['speciality_id']) ? $data['speciality_id'] : null;

    $SQL = "INSERT INTO tUsers (profile_id, doctor, first_name, last_name, email, land_line, cell_phone, birth_date, practice_number, address_1, address_2, city, postal_code, province, speciality_id) VALUES ('$profileId', '$doctor', '$firstName', '$lastName', '$email','$landLine', '$cellPhone', '$birthDate', '$practiceNumber', '$address1' , '$address2', '$city', '$postalCode', '$province', '$specialityId')";
    return InsertDB($SQL, 'id');
}

function RetrieveMessage ($Mail_ID) {
    $SQL = "SELECT * FROM Mails WHERE Mail_ID = '$Mail_ID'";
    $Query = QueryDB($SQL);
    return ReadFromDB($Query);
}

function sendEmailVerification($data) {
    $mailId = $data['doctor'] ? 1 : 2;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = $welcomeMessageDetails['MailSubject_STRING'];
    $textMessage = $welcomeMessageDetails['MailText_STRING'];
    $htmlMessage = $welcomeMessageDetails['MailHTML_STRING'];
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $fullname = $data['first_name'] . ' ' . $data['last_name'];
    $emailTo = $data['Eml'];
    $itemId = 36;
    //$data['doctor'] == '1' ? 18 : 31;
    $url = ThisURL . RetrieveCatalogContentURL(CATALOG_ITEM, CPE, $itemId, RetrieveCatalogItemCode($itemId), DEVICE_PC) . '&Profile_ID=' . $data['profile_id'];
    $htmlMessage = str_replace("*||user||*", $fullname, $htmlMessage);
    $htmlMessage = str_replace("*||url||*", $url, $htmlMessage);
    // $htmlMessage = str_replace("*||docnowurl||*", ThisURL, $htmlMessage);

    SendMultipartMIMEMail ($emailTo, $emailFrom, $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function debug($data){ 
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}


function httpPost($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    if($response === false) {
	    return 'Curl error: ' . curl_error($curl);
	} 
    curl_close($curl);
    return $response;
}