<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';
include_once '../../../modules/MIME.php';

function RetrieveMessage ($Mail_ID) {
    $SQL = "SELECT * FROM Mails WHERE Mail_ID = '$Mail_ID'";
    $Query = QueryDB($SQL);
    return ReadFromDB($Query);
}

$SQL = "SELECT tAppointments.first_name, tAppointments.last_name, tAppointments.title, tAppointments.start_date, tAppointments.end_date, CONCAT(tUsers.first_name, ' ', tUsers.last_name) AS doctor, tAppointments.email, tAppointments.doctor_profile_id 
FROM tAppointments LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.doctor_profile_id 
WHERE tAppointments.id=".$_GET['appointment_id'];

$Query = QueryDB($SQL);
$Result = ReadFromDB($Query);

$mailId = 5;
$MessageDetails = RetrieveMessage($mailId);
$nameFrom = $MessageDetails['From_STRING'];
$emailFrom = $MessageDetails['FromEmail_STRING'];
$ccTo = $MessageDetails['CCTo_STRING'];
$replyTo = $MessageDetails['ReplyTo_STRING'];
$subject = stripslashes($MessageDetails['MailSubject_STRING']);
$textMessage = stripslashes($MessageDetails['MailText_STRING']);
$htmlMessage = stripslashes($MessageDetails['MailHTML_STRING']);
$priority = $MessageDetails['Priority_NUM'];
$fullname = $Result['first_name'] . ' ' . $Result['last_name'];
$patientCellphone = $data['cell_phone'];
$bookingDate = date('d-F-Y H:i', strtotime($Result['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($Result['end_date']));

$fullDoctorName = 'Dr. '.$Result['doctor'];
$emailTo = $Result['email'];
$link = '<a target="_blank" href="'.ThisURL.'/review-a-doctor.html?a='.$_GET['appointment_id'].'&d='.$Result['doctor_profile_id'].'" class="link2" style="color:#ffffff;">Review appointment here</a>';

$htmlMessage = str_replace("**user**", $fullname, $htmlMessage);
$htmlMessage = str_replace("**date**", $bookingDate, $htmlMessage);
$htmlMessage = str_replace("**doctor**", $fullDoctorName, $htmlMessage);
$htmlMessage = str_replace("**link**", $link, $htmlMessage);

//SendMultipartMIMEMail ($emailTo, $emailFrom, $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority, MailBot);
SendMultipartMIMEMail ($emailTo, $nameFrom.' <'.$emailFrom.'>', $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority, MailBot);

$SQL = "UPDATE tAppointments SET review_appointment_sent = 1 WHERE id=".$_GET['appointment_id'];
UpdateDB ($SQL);

echo "Review notification sent";

?>