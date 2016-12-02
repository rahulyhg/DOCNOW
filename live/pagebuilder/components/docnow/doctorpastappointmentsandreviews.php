<?php	
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
	require_once ('modules/profile.php');
	include_once 'modules/connect.php';
	include_once 'custom_modules/common.php';

	global $Profile_ID;
	global $Session_ID;

	if (isset($_GET['doctor_profile_id']))  {
		$doctorProfileId = $_GET['doctor_profile_id'];
	}

	$pastAppointments = getDoctorPastAppointments($Profile_ID);

?>
<script type="text/javascript">
$(document).ready(function() {

	$(document).on("click","a.btn-lg", function () {
		var id = $(this).attr("id");
		alert(id)
		var appointment_id = $('#appointment_id'+id).val();
		jQuery.ajax({
	      url: '/live/pagebuilder/components/docnow/sendreviewnotification.php?appointment_id=' + appointment_id,
	      type: 'get',
	      dataType: 'html',
	      success: function (obj) {
	      
	       if(obj){
	        
	            $('#notification' + id).html(obj);
	         
	        }  
	      },
	      error: function () {
	        alert ('There was a problem. Please try again.');
	      }
	    
	    });
	});
});
</script>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth" style="margin-bottom: 20px;">
	<div class="row">
		<div class="col-md-3">
			<a href="/doctors/dashboard/?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Dashboard</a>
		</div>
		
		<div class="col-md-3">
			<a href="/doctors/notifications.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Notifications</a>
		</div>
		
		<div class="col-md-3">
			<a href="/doctors/past-appointments-and-reviews.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Reviews</a>
		</div>
		
		<div class="col-md-3">
			<a href="/doctors/settings.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Settings</a>
		</div>
	</div>
</div>

<div class="container">
	<?php if (!empty($pastAppointments)):?>
		<?php 
		$x =0;
		foreach($pastAppointments as $pastAppointment) :
			 //$doctorProfileId = $pastAppointment['profile_id']; 
			//debug($pastAppointment);
				$reviews = getReviews($pastAppointment['appointment_id']);

			//debug($reviews);
			?>

		<div class="row">
			<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 pull-right">
				<div class="tg-dashboard tg-haslayout">
					<div class="tg-docprofilechart tg-haslayout">
						<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth">
							<div class="row">
								<div class="tg-docinfo tg-haslayout">
									<div class="tg-box">
										<div class="tg-heading-border tg-small">
											<h3><?=$pastAppointment['first_name'] .' '. $pastAppointment['last_name'] ?></h3> <?=date('d F Y H:i:s', strtotime($pastAppointment['start_date'])).' - '.date('H:i:s', strtotime($pastAppointment['end_date']))?>
										</div>
										<div class="tg-description">
										<? if($reviews['comment'] != ''){?>
											<p><?=$reviews['comment']?></p>
										<? }elseif($pastAppointment['review_appointment_sent'] == 0){?>
											<div id="notification<?=$x?>">
											<input type="hidden" name="appointment_id<?=$x?>" id="appointment_id<?=$x?>" value="<?=$pastAppointment['appointment_id']?>">
											<a href="javascript:" class="pull-right btn-success btn-lg" id="<?=$x?>">Ask this patient to review this appointment</a>
											</div>
										<?}elseif($pastAppointment['review_appointment_sent'] == 1){?>
											<p>Review notification sent</p>
										<?}?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth">
						<div class="row">
							<div class="tg-support">
								<div class="tg-heading-border tg-small">
									<h3><a href="#">rating</a></h3>
								</div>
								<div class="tg-ratingbox">
									<div class="tg-stars">
										<?php 
										for($i =1; $i <= $reviews['star']; $i++) {
										
										?>

										<i class="fa fa-star"></i>
									
										<?php
										}
										?>									
										
										<!-- <i class="fa fa-star-o"></i> -->
									</div>
									<!-- <strong>very good</strong> -->
								</div>
							</div>
						</div>
					</div>
					</div>					
					
				</div>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
				<aside id="tg-sidebar">
					<div class="tg-widget tg-widget-doctor">
						<figure>
						<?php if ($pastAppointment['profilepic']) :?>
							<a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">
								<img src="<?=IMAGE_URL.$pastAppointment['profilepic']?>" style="height:270px; width:270px !important;">
							</a>
						<?php else: ?>
							
								<img src="/live/images/Patient_default.jpg" alt="image description">
							
						<?php endif; ?>
						</figure>
					</div>
				</aside>
			</div>
		</div>
	<?php 
	$x++;
	endforeach; ?>
	<?php else:?>
		<p>No past past appointments found.</p>
	<?php endif;?>
</div>