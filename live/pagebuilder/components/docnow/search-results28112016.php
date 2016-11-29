<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once 'custom_modulues/common.php';

require_once 'Haversine.php';

global $Session_ID;


$StartLat = $_POST['lat'];
$StartLon = $_POST['lng'];
$Distance = "";

if($_POST['speciality']){
  
  $SQL = "SELECT profile_id, lat, lng FROM `tUsers` WHERE doctor=1";
  $SQL .= $_POST['speciality'] ? " AND speciality_id=".$_POST['speciality'] : "";
  $SQL .= $_POST['gender'] ? " AND gender='".$_POST['gender']."'" : "";

  //echo $SQL;
  $Query = QueryDB($SQL);
  while ($Result = ReadFromDB($Query)){

    // debug($Result);

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


  // debug($Distance);
  $profiles = array_keys($Distance);
  $featuredDoctors = getFeaturedDoctors($_POST['speciality']);
}
  
?>

<script>

  $(document).ready(function() {

    $(document).on('click', '.next', function (e) {
     
     var id = $(this).attr('id');
     var startdate = $('#startdate_'+id).val();
     var nextdate = $('#nextdate_'+id).val();
     var profile_id = $('#profile_id_' + id).val();
      jQuery.ajax({
        url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + nextdate + '&profile_id=' + profile_id + '&id=' + id + '&action=next',
        type: 'get',
        dataType: 'html',
        success: function (obj) {
        
         if(obj){
            
            $('#data'+id).html(obj);
            
          }  
        },
        error: function () {
          alert ('There was a problem. Please try again.');
        }
      
      });
    });

     $(document).on('click', '.back', function (e) {
     
     var id = $(this).attr('id');
     var startdate = $('#startdate_'+id).val();
     var nextdate = $('#nextdate_'+id).val();
     var profile_id = $('#profile_id_' + id).val();
      jQuery.ajax({
        url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + startdate + '&profile_id=' + profile_id + '&id=' + id + '&action=back',
        type: 'get',
        dataType: 'html',
        success: function (obj) {
        
         if(obj){
            
            $('#data'+id).html(obj);
            
          }  
        },
        error: function () {
          alert ('There was a problem. Please try again.');
        }
      
      });
    });

  });

</script>
<style>
  td:hover{
    background-color: #7dbb00;
  }
  
  td:hover a{
    color: #fff;
    display: block;
  }
  
</style>

<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
  <div class="tg-doctors-list tg-haslayout">
    <div class="tg-view tg-grid-view">
      <div class="row">
      <?php 
      $i = 1;

      foreach ($profiles as $profile_id):

        $profileDetails = getProflieRegDetails($profile_id);

      //echo "test".$i;
      ?>

      <script type="text/javascript">

        var trs = $("#internalActivities<?=$i?> tr");
        var btnMore = $("#seeMoreRecords");
        var btnLess = $("#seeLessRecords");
        var trsLength = trs.length;
        var currentIndex = 10;

        trs.hide();
        trs.slice(0, 10).show(); 
        checkButton();

        btnMore.click(function (e) { 
            e.preventDefault();
            $("#internalActivities<?=$i?> tr").slice(currentIndex, currentIndex + 10).show();
            currentIndex += 10;
            checkButton();
        });

        btnLess.click(function (e) { 
            e.preventDefault();
            $("#internalActivities<?=$i?> tr").slice(currentIndex - 10, currentIndex).hide();          
            currentIndex -= 10;
            checkButton();
        });

        function checkButton() {
            var currentLength = $("#internalActivities<?=$i?> tr:visible").length;
            
            if (currentLength >= trsLength) {
                btnMore.hide();            
            } else {
                btnMore.show();   
            }
            
            if (trsLength > 10 && currentLength > 10) {
                btnLess.show();
            } else {
                btnLess.hide();
            }
            
        }
      </script>
        <article class="tg-doctor-profile">
            <div class="tg-box">
            <?php if($profileDetails['profilepic']){?>
              <figure class="tg-docprofile-img"><a href="/doctor/doctor-details.html?doctor_profile_id=<?=$profile_id?>"><img src="<?=IMAGE_URL.$profileDetails['profilepic']?>" alt="<?php echo $profileDetails['first_name'].' '.$profileDetails['last_name']; ?>"></a></figure>
            <?}?>
                <span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
              <div class="tg-docprofile-content">
                <div class="tg-heading-border tg-small">
                  <h3><a href="/doctor/doctor-details.html?doctor_profile_id=<?=$profile_id?>">Dr. <?php echo $profileDetails['first_name'].' '.$profileDetails['last_name']; ?>  </a></h3> <br><a href="#"><?php echo getSpecialityName($profileDetails['speciality_id']); ?></a>
                </div>                          
                <div class="tg-description"> 
                  <p><?=$profileDetails['address']?></p>
                  
                </div>
                <?php
                date_default_timezone_set('Africa/Johannesburg');

                $date = new DateTime();
                $startdate = $date->format('Y-m-d');
                $date->add(new DateInterval('P2D'));
                $enddate = $date->format('Y-m-d');
                $date->add(new DateInterval('P1D'));
                $nextdate = $date->format('Y-m-d');

                $starttime=strtotime('08:00');
                $endtime=strtotime('16:30');

                $days = daysBetween(strtotime($startdate),strtotime($enddate));
                $bookedDates = getDoctorAppointmentsdates($profile_id, $startdate, $enddate);

                //echo $startdate.":".$days[0];
                ?>
                <div id="data<?=$i?>">
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
                    <a id="show_more<?=$i?>" class="tg-btn tg-btn-lg" href="">Show More</a>
                  </div>
                  <div class="form-group">
                    <a id="show_less<?=$i?>" class="tg-btn tg-btn-lg" href="">Show Less</a>
                  </div>
                </div>
            </div>
        </article>
      <? $i++; 
      endforeach?>  
      </div>
    </div>
    <div class="tg-btnarea">
      <a class="tg-btn" href="#">load more</a>
    </div>
  </div>
</div>

<script>

 function calculateRoute(start, destination) {
    var div_data = '<input type="image" src="/live/images/ajax-loader.gif" border="0" style=\"margin:20px auto;\" />';
    
    if (start=="" && destination == "") {
        $('#mainMarkerHeading').html('FIND BRANCH <strong class="red"> ON MAP</strong>');
            initialize();
    } else {
        //var routeArray = 
        
        if (!destination) {
            geocoder.geocode( { 'address': route}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    map.setCenter(results[0].geometry.location);
                    map.setZoom(9);
                    var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                    });
                } else {
                    alert('Geocode was not successful for the following reason: ' + status);
                }
            });
        }
        waypts = [];
        $('#mainMarkerHeading').html(start + ' to ' + destination);
        
        if (!destination) {
            return;
        }
        from = start;
        to = destination;
        var directionsService = new google.maps.DirectionsService();
        directionsRequest = {
            origin: from,
            destination: to,
            waypoints: waypts,
            region: "za",
            travelMode: google.maps.DirectionsTravelMode.DRIVING,
            unitSystem: google.maps.UnitSystem.METRIC
        };
        directionsService.route(
          directionsRequest,
          function(response, status)
          {
            if (status == google.maps.DirectionsStatus.OK){
                directionsDisplay.setDirections(response);
                $("#error").append('');
            }else{
              $("#error").append("Unable to retrieve your route<br />");
             }
          }
        );
    }
}

function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };

      request.open('GET', url, true);
      request.send(null);
}
function doNothing() {}

var customIcons = {
           
      orange: {
        icon: '/live/images/orangemarker.png',
        shadow: 'http://labs.google.com/ridefinder/images/mm_20_shadow.png'
      }
      
};
    
function getMarkers(){
    var infoWindow = new google.maps.InfoWindow({maxWidth: 270});
    function bindInfoWindow(marker, map, infoWindow, html) {
      google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });
    }


  // Change this depending on the name of your PHP file
  downloadUrl("/live/pagebuilder/components/docnow/read-in-xml.php?speciality=<?=$_POST['speciality']?>", function(data) {
    var xml = data.responseXML;
    var markers = xml.documentElement.getElementsByTagName("marker");
    for (var i = 0; i < markers.length; i++) {
        var profile_id = markers[i].getAttribute("profile_id");
        var name = markers[i].getAttribute("name");
        var address = markers[i].getAttribute("address");
        var speciality = markers[i].getAttribute("speciality");
        var type = "orange";
        var point = new google.maps.LatLng(parseFloat(markers[i].getAttribute("lat")), parseFloat(markers[i].getAttribute("lng")));
        var html = "<strong>" + name +  "(" + speciality + ") </strong><br />Address:" + address + "<br /> <a style='position: static;' href='/booking/&profile_id="+profile_id+"'>Book Now</a></div>";
        var icon = customIcons[type] || {};
        var marker = new google.maps.Marker({
            map: map,
            position: point,
            icon: icon.icon,
            shadow: icon.shadow,
            title: 'Doctor : ' + name + '(' + speciality + ')'      
      });
      bindInfoWindow(marker, map, infoWindow, html);
    }
  });
}

function initialize(latitude, longitude) {

    var zoom = '';
    if(latitude && longitude){

        zoom = 13;

    }else{

        latitude = -29.573457;
        longitude = 25.305175;
         var zoom = 5;
    }
    geocoder = new google.maps.Geocoder();
    directionsDisplay = new google.maps.DirectionsRenderer();
    directionsService = new google.maps.DirectionsService();
    var myOptions = {
          zoom: zoom,
          center: new google.maps.LatLng(latitude, longitude),
          mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    // Draw the map
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsDisplay.setMap(map);
/*    $('#mainMarkerHeading').html('FIND BRANCH <strong class="red"> ON MAP</strong>');
*/    getMarkers();

    var input = /** @type {HTMLInputElement} */(document.getElementById('location'));

      var types = document.getElementById('type-selector');
      //map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
      //map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

      var autocomplete = new google.maps.places.Autocomplete(input);
      autocomplete.bindTo('bounds', map);

      var infowindow = new google.maps.InfoWindow();
      var marker = new google.maps.Marker({
        map: map,
        anchorPoint: new google.maps.Point(0, -29)
      });

      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        infowindow.close();
        marker.setVisible(false);
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          window.alert("Autocomplete's returned place contains no geometry");
          return;
        }
        //console.log();
        get_closest_branchGPSC(place.geometry.location.lat(), place.geometry.location.lng())
        // If the place has a geometry, then present it on a map.
        
      });

  // Sets a listener on a radio button to change the filter type on Places
  // Autocomplete.
  function setupClickListener(id, types) {
    var radioButton = document.getElementById(id);
    google.maps.event.addDomListener(radioButton, 'click', function() {
      autocomplete.setTypes(types);
    });
  }

  setupClickListener('changetype-all', []);
    
}
 function initiate_geolocation() {
                
    navigator.geolocation.getCurrentPosition(handle_geolocation_query);
}

function handle_geolocation_query(position){

    $('#latitude').val(position.coords.latitude);
    $('#longitude').val(position.coords.longitude);
     get_closest_branch($('#latitude').val(), $('#longitude').val());    
                  
}
function get_closest_branch(StartLat, StartLon, Speciality){
    //alert(StartLat + ':' + StartLon);
  jQuery.ajax({
  url: '/live/pagebuilder/components/docnow/get-closest-doctor-ajax.php?StartLat=' + StartLat + '&StartLon=' + StartLon + '&speciality' + Sspeciality,
  type: 'get',
  dataType: 'html',
  success: function (obj) {
  
   if(obj){
    
        window.location = "/live/branchdetail.php?id=" + obj;
     
    }  
  },
  error: function () {
    alert ('There was a problem. Please try again.');
  }
  
  });

}

function get_closest_branchGPSC(StartLat, StartLon){
    //alert(StartLat + ':' + StartLon);
  jQuery.ajax({
  url: '/live/pagebuilder/components/autopedigree/get-closest-store-gps-ajax.php?StartLat=' + StartLat + '&StartLon=' + StartLon,
  type: 'get',
  dataType: 'html',
  success: function (obj) {
  
   if(obj){
    
      var str = obj;
      var res = str.split("|");

      var infowindow = new google.maps.InfoWindow();

      var myLatlng = new google.maps.LatLng(res[0],res[1]);
      var mapOptions = {
        zoom: 17,
        center: myLatlng
      }
      var map = new google.maps.Map(document.getElementById("map"), mapOptions);

      var marker = new google.maps.Marker({
          position: myLatlng,
          title:res[2]
      });

      // To add the marker to the map, call setMap();
      marker.setVisible(true);
      marker.setMap(map);

      infowindow.setContent('<div><strong>' + res[2] + '</strong><br>' + res[3] + '<br>' + res[4] + '<br>' + res[5]);
      infowindow.open(map, marker);


     
    }  
  },
  error: function () {
    alert ('There was a problem. Please try again.');
  }
  
  });

}

var map;
var latitude = '<?php echo $_POST['lat']; ?>';
var longitude = '<?php echo $_POST['lng']; ?>';

$(document).ready(function() {
    initialize(latitude, longitude); 

    $('#get_closest_branch').click(function(){
        initiate_geolocation();      
    });   
              
});
</script>
<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
  <aside id="tg-sidebar">
    <div class="tg-widget tg-widget-map">
      <h3>on map</h3>
      <div id="map" class="tg-location-map tg-haslayout"></div>
    </div>
    <div class="tg-widget tg-tab-widget">
        <h3>Featured doctors</h3>
        <div class="tg-tabwidet-content">
          <div class="tab-content">
            <div role="tg-tabpanel tabpanel" class="tg-tab-pane tab-pane active" id="recent">
              <ul>
                <?php foreach ($featuredDoctors as $featuredDoctor) :?>
                  <?php
                    $featuredocProfileId = $featuredDoctor['profile_id'];
                  ?>
                  <li>
                    <figure>
                    <?php if ($featuredDoctor['profilepic']) :?>
                      <a href="/doctor/doctor-details.html?doctor_profile_id=<?=$featuredocProfileId?>">
                        <img src="<?=IMAGE_URL.$featuredDoctor['profilepic']?>" style="height:63px; width:63px !important;">
                      </a>
                    <?php else: ?>
                      <a href="/doctor/doctor-details.html?doctor_profile_id=<?=$featuredocProfileId?>">
                        <img src="/live/images/blog/img-12.jpg" alt="image description">
                      </a>
                    <?php endif; ?>
                    </figure>
                    <div class="tg-description">
                      <h3><a href="/doctor/doctor-details.html?doctor_profile_id=<?=$featuredocProfileId?>">Dr. <?=$featuredDoctor['first_name'] . ' ' . $featuredDoctor['last_name']?></a></h3>
                      <?php
                        $reviewsStarAverage = getReviewsStarAverage($featuredocProfileId);

                        //echo $reviewsStarAverage;
                      ?>
                      <span class="tg-stars">
                        <?php 
                        if ($reviewsStarAverage > 0){
                          for($i =1; $i <= round($reviewsStarAverage); $i++) {
                        
                          ?>

                          <i class="fa fa-star"></i>
                        
                          <?php
                          }
                        }
                        ?>
                        <!-- <i class="fa fa-star-half-empty"></i>-->
                      </span>
                    </div>
                  </li>
                <?php endforeach;?>
              </ul>
            </div>
            
          </div>
        </div>
    </div>
  </aside>
</div>