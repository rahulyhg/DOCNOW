<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once '../../../modules/DB.php';
include_once '../../../modules/connect.php';

function getDoctorAppointmentsdates($profileId, $start_date, $end_date) {
    $SQL = "SELECT tAppointments.start_date FROM tAppointments 
        LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
        WHERE tAppointments.doctor_profile_id = '" . $profileId . "'
        AND tAppointments.start_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'";

    $Query = QueryDB($SQL);
    $appointments = array();
    while ($Result = ReadFromDB($Query)){
        $appointments[] = $Result['start_date'];
    }

    return $appointments;   
}

function daysBetween($start, $end){
   $dates = array();
   while($start <= $end){
       array_push(
           $dates,
           date(
            'Y-m-d',
            $start
           )
       );
       $start += 86400;
   }
   return $dates;
}
date_default_timezone_set('Africa/Johannesburg');

if($_GET['action'] == 'next'){

  $date = new DateTime($_GET['startdate']);
  $startdate = $date->format('Y-m-d');
  $date->add(new DateInterval('P2D'));
  $enddate = $date->format('Y-m-d');
  $date->add(new DateInterval('P1D'));
  $nextdate = $date->format('Y-m-d');

}else{

  $date = new DateTime($_GET['startdate']);
  $date->sub(new DateInterval('P1D'));
  $enddate = $date->format('Y-m-d');
  $date->sub(new DateInterval('P2D'));
  $startdate = $date->format('Y-m-d');
  $date->add(new DateInterval('P3D'));
  $nextdate = $date->format('Y-m-d');
}

$starttime=strtotime('08:00');
$endtime=strtotime('16:30');

$profile_id = $_GET['profile_id'];
$i = $_GET['id'];

$days = daysBetween(strtotime($startdate),strtotime($enddate));
$bookedDates = getDoctorAppointmentsdates($profile_id, $startdate, $enddate);

//echo $startdate.":".$days[0];
?>
 <script type="text/javascript">          
         
  $(document).ready(function() {

    var trs = $("#internalActivities<?=$i?> tr");
    var btnMore = $("#show_more<?=$i?>");
    var btnLess = $("#show_less<?=$i?>");
    var trsLength = trs.length;
    var currentIndex = 5;

    trs.hide();
    trs.slice(0, 5).show(); 
    checkButton<?=$i?>(trsLength, btnMore, btnLess);

    btnMore.click(function (e) { 
        e.preventDefault();
        $("#internalActivities<?=$i?> tr").slice(currentIndex, currentIndex + 5).show();
        currentIndex += 5;
        checkButton<?=$i?>(trsLength,btnMore, btnLess);
    });

    btnLess.click(function (e) { 
        e.preventDefault();
        $("#internalActivities<?=$i?> tr").slice(currentIndex - 5, currentIndex).hide();          
        currentIndex -= 5;
        checkButton<?=$i?>(trsLength, btnMore, btnLess);
    });

  });

  function checkButton<?=$i?>(trsLength, btnMore, btnLess) {

      var currentLength = $("#internalActivities<?=$i?> tr:visible").length;
      
      if (currentLength >= trsLength) {
          btnMore.hide();            
      } else {
          btnMore.show();   
      }
      
      if (trsLength > 5 && currentLength > 5) {
          btnLess.show();
      } else {
          btnLess.hide();
      }
      
  }
</script>
<input type="hidden" name="nextdate" id="nextdate_<?=$i?>" value="<?=$nextdate?>"> 
<input type="hidden" name="startdate" id="startdate_<?=$i?>" value="<?=$startdate?>"> 
<input type="hidden" name="profile_id" id="profile_id_<?=$i?>" value="<?=$profile_id?>"> 
<table style="width:100%" id="internalActivities<?=$i?>">
  <tr>
  <?
  if(date('Y-m-d') != $days[0]){
  ?>
    <th rowspan="19"><a href="javascript:" style=" color: #062e4c !important;" class="back" id="<?=$i?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></i></th>
    <? 
   }
    foreach ($days as $day):?>
    <th><?=date('d M Y', strtotime($day))?></th>
    <? endforeach?>               
   <th rowspan="19"><a href="javascript:" style=" color: #062e4c !important;" class="next" id="<?=$i?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></th>
  </tr>
  
  <?
    for ($halfhour=$starttime;$halfhour<=$endtime;$halfhour=$halfhour+30*60) {
      echo "<tr>";

      foreach ($days as $day){

        $start = $day." ".date('H:i:s',$halfhour);
        $end = date("Y-m-d H:i:s", strtotime($start . "+30 minutes"));  

        if(!in_array($start, $bookedDates, true)){    

          echo '<td><a href="/booking?profile_id='.$profile_id.'&start_date='.$start.'&end_date='.$end.'">'.date('H:i',strtotime($start)).'</a></td>';

        }else{

          echo '<td><a href="javascript:">-</a></td>';

        }

      }
      echo "</tr>";
    }
    ?>

 
</table>
<div class="form-group">
  <button id="show_more<?=$i?>" class="tg-btn tg-btn-lg" >Show More</button>
</div>
<div class="form-group">
  <button id="show_less<?=$i?>" class="tg-btn tg-btn-lg" >Show Less</button>
</div>