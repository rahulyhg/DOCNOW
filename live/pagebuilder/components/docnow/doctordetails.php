<style>

	body.modal-open div.modal-backdrop { 
	    z-index: 0; 
	}

	/*.modal-backdrop {
	  z-index: -1;
	}*/

</style>
<?php	
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
	require_once ('modules/profile.php');
	include_once 'modules/connect.php';
	include_once 'custom_modules/common.php';

	global $Profile_ID;
	global $Session_ID;
	global $_PAGE_TITLE;

	if (isset($_GET['doctor_profile_id']))  {
		$doctorProfileId = $_GET['doctor_profile_id'];
	}

	$userHasActiveAppointment = false;

	if ($Profile_ID) {
		$userHasActiveAppointment = checkActiveAppointment($doctorProfileId, $Profile_ID);
	}

	$profileDetails = getProflieRegDetails($doctorProfileId);
	$specialities = getSpecialities();
	$doctorSpeciality = isset($specialities[$profileDetails['speciality_id']]) ? $specialities[$profileDetails['speciality_id']] : 'none';

	$featuredDoctors = getSimilarDoctors($profileDetails['speciality_id'], $doctorProfileId);
	$profDetails = RetrieveProfileDetails ($doctorProfileId);
	/*$upcomingAppointments = getUpcomingAppointments($Profile_ID);
	$appointments = getDoctorAppointments($Profile_ID);*/
	//debug($profileDetails);
	$doctorName = "Dr.".str_replace('Dr', '', $profileDetails['first_name'])." ".$profileDetails['last_name']."(".$doctorSpeciality .")";
	$_PAGE_TITLE = $_PAGE_TITLE." - ".$doctorName;
	$profileDetails['profilepic'] = ($profileDetails['profilepic'] ? $profileDetails['profilepic'] : '/profilepics/dr default picture.jpg');


?>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
 <script>

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


        var profile_id = '<?=$profileDetails['profile_id']?>';
        var name = '<?=$profileDetails['first_name']." ".$profileDetails['last_name']?>';
        var address = '<?=$profileDetails['address']?>';
        var speciality = '<?php echo $specialities [$profileDetails['speciality_id']];?>';
        var type = "orange";
        var point = new google.maps.LatLng(parseFloat('<?=$profileDetails['lat']?>'), parseFloat('<?=$profileDetails['lng']?>'));
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

    function initMap(latitude, longitude) {

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
  		getMarkers();
      }

      var latitude = parseFloat('<?=$profileDetails['lat']?>');
      var longitude = parseFloat('<?=$profileDetails['lng']?>')	;

      $(document).ready(function() {
		    initMap(latitude, longitude); 
     
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
<div class="container">
	<div class="row">
		<div class="col-lg-9 col-md-8 col-sm-8 col-xs-12">
			<div class="tg-doctor-detail tg-doctor-detail2 tg-haslayout">
				<article class="tg-doctor-profile">
					<div class="tg-box">
						<?php if($profileDetails['profilepic']){?>
							<figure class="tg-docprofile-img"><a href="#"><img src="<?=IMAGE_URL.$profileDetails['profilepic']?>" alt="<?php echo $profileDetails['first_name'].' '.$profileDetails['last_name']; ?>"></a></figure>
						<?}?>
						<span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
						
						<div class="tg-docprofile-content">
							<div class="tg-heading-border tg-small">
								<h2><?=$doctorName?></h2>
							</div>
							<div class="tg-description">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliquat enim ad minim veniam. Eascxcepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.</p>
								<br>
								<ul class="tg-doccontactinfo">
									<li>
										<i class="fa fa-map-marker"></i>
										<address><?=$profileDetails['address'];?></address>
									</li>
									<li>
										<i class="fa fa-phone"></i>
										<a href="tel:<?=$profileDetails['work_number'];?>"><?=$profileDetails['work_number'];?></a>
									</li>
									<li>
										<i class="fa fa-envelope-o"></i>
										<a href="mailto:<?=$profDetails['Login_STRING'];?>"><?=$profDetails['Login_STRING'];?></a>
									</li>
									<li>
										<i class="fa fa-fax"></i>
										<span><?=$profileDetails['fax_number'];?></span>
									</li>
									
								</ul>
								<!-- <a href="/booking/&Session_ID=<?=$Session_ID?>&profile_id=<?=$profileDetails['profile_id']?>" class="pull-right btn-success btn-lg" id="book">Book Dr <?=$profileDetails['first_name']." ".$profileDetails['last_name'];?></a> -->
								<?php if($userHasActiveAppointment) :?>
									<?php $fullName = $profileDetails['first_name'] . " " . $profileDetails['last_name']; ?>
									<button type="button" class="btn btn-primary btn-lg"  id="load-notification-modal" data-profile-id="<?=$profileDetails['profile_id']?>" data-doctor-name="<?=$fullName?>">
									  Send message to DR. <?=$profileDetails['first_name'] . " " . $profileDetails['last_name'];?>
									</button>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</article>
				<script type="text/javascript">
				$(document).ready(function() {

				  $(document).on("click","a.next", function () {
				    var id = $(this).attr("id");
				    var nextdate = $('#nextdate_' + id).val();
				    var profile_id = $('#profile_id_' + id).val();

				          //alert(StartLat + ':' + StartLon);
				    jQuery.ajax({
				      url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + nextdate + '&profile_id=' + profile_id + '&id=' + id + '&action=next',
				      type: 'get',
				      dataType: 'html',
				      success: function (obj) {
				      
				       if(obj){
				        
				            $('#calendar_' + id).html(obj);
				         
				        }  
				      },
				      error: function () {
				        alert ('There was a problem. Please try again.');
				      }
				    
				    });
				  });

				  $(document).on("click","a.back", function () {
				    var id = $(this).attr("id");
				    var startdate = $('#startdate_' + id).val();
				    var profile_id = $('#profile_id_' + id).val();

				    console.log('test:'+startdate)
				    jQuery.ajax({
				      url: '/live/pagebuilder/components/docnow/freetimes.php?startdate=' + startdate + '&profile_id=' + profile_id + '&id=' + id + '&action=back',
				      type: 'get',
				      dataType: 'html',
				      success: function (obj) {
				      
				       if(obj){
				        
				            $('#calendar_' + id).html(obj);
				         
				        }  
				      },
				      error: function () {
				        alert ('There was a problem. Please try again.');
				      }
				    
				    });
				  });

				});
				</script>

				<?
					$i =1;
      
					$date = new DateTime('Africa/Johannesburg');
					$startdate = $date->format('Y-m-d');
					$date->add(new DateInterval('P2D'));
					$enddate = $date->format('Y-m-d');
					$date->add(new DateInterval('P1D'));
					$nextdate = $date->format('Y-m-d');

					$starttime=strtotime('08:00');
					$endtime=strtotime('16:30');

					$days = daysBetween(strtotime($startdate),strtotime($enddate));      
					$bookedDates = getDoctorAppointmentsdates($doctorProfileId, $startdate, $enddate);


				?>
		
                <input type="hidden" name="profile_id_<?=$i?>" id="profile_id_<?=$i?>" value="<?=$doctorProfileId?>">
				<div class="col-md-12" id="booking">
					<h4>Book Dr <?=$profileDetails['first_name']." ".$profileDetails['last_name'];?></h4>
					<div id="calendar_<?=$i?>"> 
						<input type="hidden" name="nextdate" id="nextdate_<?=$i?>" value="<?=$nextdate?>">    
                		<input type="hidden" name="startdate" id="startdate_<?=$i?>" value="<?=$startdate?>"> 
						<table style="width:100%">
						  <tr>
							  <?
							  if(date('Y-m-d') != $days[0]){
							  ?>
							    <th rowspan="19"><a href="javascript:" style=" color: #062e4c !important;" class="back" id="<?=$i?>"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></i></th>
							    <? 
							   }foreach ($days as $day):?>
	                      <th><?=date('d M Y', strtotime($day))?></th>
	                      <? endforeach?>  
							<th rowspan="19"> <a href="javascript:" style=" color: #062e4c !important;" class="next" id="<?=$i?>"><i class="fa fa-arrow-right" aria-hidden="true"></i></a></th>
						  </tr>
							<?
						    for ($halfhour=$starttime;$halfhour<=$endtime;$halfhour=$halfhour+30*60) {
						      echo "<tr>";

						      foreach ($days as $day){

						        $start = $day." ".date('H:i:s',$halfhour);
						        $end = date("Y-m-d H:i:s", strtotime($start . "+30 minutes"));  

						        if(!in_array($start, $bookedDates, true)){    

						          echo '<td><a href="/booking?profile_id='.$doctorProfileId.'&start_date='.$start.'&end_date='.$end.'">'.date('H:i',strtotime($start)).'-'.date('H:i', strtotime($end)).'</a></td>';

						        }else{

						          echo '<td><a href="javascript:">-</a></td>';

						        }

						      }
						      echo "</tr>";
						    }
						    ?>

						</table>
					</div>
				</div>
				<div class="tg-doc-feature">
					<div class="tg-heading-border tg-small">
						<h3>Speciality</h3>
					</div>
					<div class="tg-description">
						<p><?=$doctorSpeciality?></p>
					</div>
				</div>
				<div class="tg-doc-feature">
					<div class="tg-heading-border tg-small">
						<h3>Languages</h3>
					</div>
					<div class="tg-description">
						<p><?=$profileDetails['language']?></p>
					</div>
				</div>
				<div class="tg-doc-feature">
					<div class="tg-heading-border tg-small">
						<h3>About Dr. <?=$profileDetails['first_name']." ".$profileDetails['last_name'];?></h3>
					</div>
					<h3>University of South Africa</h3>
					<div class="tg-description">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
					</div>
					<h3>College Of Education</h3>
					<div class="tg-description">
						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
			<aside id="tg-sidebar">
				<div class="tg-widget tg-widget-map">
					<h3>on map</h3>
					<div id="map" class="tg-location-map tg-haslayout"></div>
					<?
					if($profileDetails['lat']){
					?>
					<a class="tg-btn" href="https://maps.google.com?saddr=Current+Location&daddr=<?=$profileDetails['lat']?>,<?=$profileDetails['lng']?>" target="_blank" style="bottom: -31px">get direction</a>
					<?
					}
					?>
				</div>
				<div class="tg-widget tg-tab-widget">
					<h3>Similar doctors</h3>
					<div class="tg-tabwidet-content">
						<div class="tab-content">
							<div role="tg-tabpanel tabpanel" class="tg-tab-pane tab-pane active" id="recent">
								<ul>
									<?php foreach ($featuredDoctors as $featuredDoctor) :?>
										<?php
											$featuredocProfileId = $featuredDoctor['profile_id'];
											$reviewsStarAverage = getReviewsStarAverage($featuredDoctor['profile_id']);
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
	</div>
</div>

<!-- <div class="modal fade" id="notify-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="notify-modal-content">
    </div>
  </div>
</div> -->

<div class="modal fade" id="notify-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
      	<form class="notifiy-doctor-form" action="">
        	<textarea class="form-control" rows="4"></textarea>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Send message</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#load-notification-modal').on('click', function() {
			var $this = $(this);
				doctorName = $this.data('doctorName'),
				doctorProfileId = $this.data('profileId');

			$('#notify-modal').modal('show', {backdrop: 'static', keyboard: false});
			$('.modal-title').html('Send message to Dr. ' + doctorName);
		});
	});
</script>