<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';


function daysInWeek($weekNum){

    $result = array();
    $datetime = new DateTime('Africa/Johannesburg');
    $datetime->setISODate((int)$datetime->format('o'), $weekNum, 7);
    $interval = new DateInterval('P1D');
    $week = new DatePeriod($datetime, $interval, 1);

    foreach($week as $day){
        $result[] = $day->format('Y-m-d');
    }
    return $result;
}

function getDoctorAppointmentsdates($profileId, $start_date) {
    $SQL = "SELECT tAppointments.start_date FROM tAppointments 
        LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
        WHERE tAppointments.doctor_profile_id = '" . $profileId . "'
        AND tAppointments.start_date BETWEEN '".$start_date." 00:00:00' AND '".$start_date." 23:59:59'";

    $Query = QueryDB($SQL);
    $appointments = array();
    while ($Result = ReadFromDB($Query)){
        $appointments[] = $Result['start_date'];
    }

    return $appointments;   
}

// $weekStart = $_GET['start'];
// $ts = strtotime($weekStart);
// // Find the year and the current week
// $year = date('o', $ts);
// $week = date('W', $ts);
$starttime=strtotime('08:00');
$endtime=strtotime('16:30');

// echo $week;
// $weekdays = daysInWeek($week); 
// echo "<pre>";print_r($weekdays);echo "</pre>";

$bookedDates = getDoctorAppointmentsdates($_POST['profile_id'], $_POST['start']);
$weekdays = array($_POST['start']);
// echo "<pre>";print_r($bookedDates);echo "</pre>";
foreach ($weekdays as $weekday) {

    for ($halfhour=$starttime;$halfhour<=$endtime;$halfhour=$halfhour+30*60) {

        $start = $weekday." ".date('H:i:s',$halfhour);
        $end = date("Y-m-d H:i:s", strtotime($start . "+30 minutes"));

        if(!in_array($start, $bookedDates, true)){
            $dates [] = array('start' => $start, 'end' => $end, 'url' => '/booking?profile_id='.$_POST['profile_id'].'&start_date='.$start.'&end_date='.$end);
        }
    }
}


/*echo "<pre>";print_r($dates);echo "</pre>";
*/
echo json_encode($dates);
