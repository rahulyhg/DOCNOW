<?php	
	require_once ('modules/profile.php');
	include_once 'modules/connect.php';
	include_once 'custom_modules/common.php';

	global $Profile_ID;
	global $Session_ID;

	if (isset($_GET['doctor_profile_id']))  {
		$doctorProfileId = $_GET['doctor_profile_id'];
	}

	$pastAppointments = getPatientPastAppointments($Profile_ID);

?>

<script type="text/javascript">
	
	$(document).ready(function(){

		$.getJSON("http://ip-api.com/json/<?=$_SERVER['REMOTE_ADDR']?>", function(data) {
        	//console.log(data)
        	$('#lat1').val(data.lat);
        	$('#lng1').val(data.lon);
        });

		$('#book_dr').click(function () {

	     	var value = $(this).attr("id");
	     	
           	$('#specialty-form').submit();
        	
    	});

	});

</script>
<form action="/search/&Session_ID=<?=$Session_ID?>" method="post" id="specialty-form">
	
	<input type="hidden" name="speciality" value="1" id="speciality1">
	<input type="hidden" name="lat" id="lat1" value="">
	<input type="hidden" name="lng" id="lng1" value="">
</form>

<div class="tg-description" style="padding-bottom: 10px;">
	<div class="col-md-3">
		<a href="javascript:" class="tg-btn" style="width: 100%;" id="book_dr"><span style="font-size: 14px;">Book Dr</span></a>
	</div>
	
	<div class="col-md-3">
		<a href="Pateints settings.html" class="tg-btn" style="width: 100%;">Notifications</a>
	</div>
	
	<div class="col-md-3">
		<a href="/patients/past-appointments-and-reviews.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Reviews</a>
	</div>
	
	<div class="col-md-3">
		<a href="/patients/settings.html?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Settings</a>
	</div>
</div>   


<div class="container">
	<?php if (!empty($pastAppointments)):?>
		<?php foreach($pastAppointments as $pastAppointment) :?>
			<?php $doctorProfileId = $pastAppointment['profile_id']; ?>
		<div class="row">
			<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 pull-right">
				<div class="tg-dashboard tg-haslayout">
					<div class="tg-docprofilechart tg-haslayout">
						<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth">
							<div class="row">
								<div class="tg-docinfo tg-haslayout">
									<div class="tg-box">
										<div class="tg-heading-border tg-small">
											<h3><a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">Dr. <?=$pastAppointment['first_name'] . ' ' . $pastAppointment['last_name'] ?></a></h3>
										</div>
										<div class="tg-description">
											<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodporia incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisti nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consuatag.</p>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth">
						<div class="row">
							<div class="tg-support">
								<div class="tg-heading-border tg-small">
									<h3><a href="#">overall rating</a></h3>
								</div>
								<div class="tg-ratingbox">
									<div class="tg-stars">
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star-o"></i>
									</div>
									<strong>very good</strong>
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
							<a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">
								<img src="/live/images/dashboard/img-01.jpg" alt="image description">
							</a>
						<?php endif; ?>
						</figure>
					</div>
				</aside>
			</div>
		</div>
	<?php endforeach; ?>
	<?php else:?>
		<p>No past past appointments found.</p>
	<?php endif;?>
</div>