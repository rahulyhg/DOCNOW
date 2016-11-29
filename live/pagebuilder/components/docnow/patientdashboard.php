<?php

include_once 'modules/fusion.php';

include_once 'custom_modules/common.php';

global $Profile_ID, $Session_ID;

$profileDetails = getProflieRegDetails($Profile_ID);
$upcomingAppointments = getPatientUpcomingAppointments($Profile_ID);
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
<div class="container">
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
	<div class="tg-heading-border tg-small">
		<h3>welcome, Mr. <?=$profileDetails['first_name'] . ' ' . $profileDetails['last_name']?></h3>
	</div>
	<div class="tg-dashboard tg-haslayout">
		<div class="tg-docprofilechart tg-haslayout">
			<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 tg-findheatlhwidth">
				<div class="row">
					<div class="tg-docinfo tg-haslayout">
						<div class="tg-box">
							
							<div class="tg-description">
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
							
							<div class="tg-heading-border tg-small">
								<h3>MY FAVORITE DOCTORS</h3>
							</div>
							
							<div class="tg-costumerreview">
					
					<div id="tg-reviewscrol" class="tg-reviewscrol">
						<ul class="tg-reviews">
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/blog/img-12.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Dr Hannah Mathu</a></h3>
											</div>
											<div class="pull-right">
												<div class="tg-stars">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-half-empty"></i>
													<i class="fa fa-star-half-empty"></i>
												</div>
											</div>
											<div class="tg-description">
												<p>General Practisioner</p>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/blog/img-12.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Dr Romald Magondo</a></h3>
											</div>
											<div class="pull-right">
												<div class="tg-stars">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-half-empty"></i>
													<i class="fa fa-star-half-empty"></i>
												</div>
											</div>
											<div class="tg-description">
												<p>General Practisioner</p>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/blog/img-12.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">dr Gareth Slaven</a></h3>
											</div>
											<div class="pull-right">
												<div class="tg-stars">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-half-empty"></i>
													<i class="fa fa-star-half-empty"></i>
												</div>
											</div>
											<div class="tg-description">
												<p>General Practisioner</p>
											</div>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/blog/img-12.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Dr Jeje Hamici</a></h3>
											</div>
											<div class="pull-right">
												<div class="tg-stars">
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star"></i>
													<i class="fa fa-star-half-empty"></i>
													<i class="fa fa-star-half-empty"></i>
												</div>
											</div>
											<div class="tg-description">
												<p>General Practisioner</p>
											</div>
										</div>
										
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
							
						</div>
						
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 tg-expectwidth">
			<div class="row">
				<div class="tg-support">
					<div class="tg-costumerreview">
						<div class="tg-heading-border tg-small">
							<h3>Upcoming Appointments</h3>
						</div>
						
						<div class="tg-widget tg-tab-widget">
	<div class="tg-tabwidet-content">
		<div class="tab-content">
			<?php if (!empty($upcomingAppointments)) :?>
			<div role="tg-tabpanel tabpanel" class="tg-tab-pane tab-pane active" id="recent">
				<ul>
				<?php foreach ($upcomingAppointments as $upcomingAppointment): ?>
					<?php $doctorProfileId = $upcomingAppointment['profile_id'];?>
					<li>
						<figure>
						<?php if ($upcomingAppointment['profilepic']) :?>
							<a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">
								<img src="<?=IMAGE_URL.$upcomingAppointment['profilepic']?>" style="height:63px; width:63px !important;">
							</a>
						<?php else: ?>
							<a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">
								<img src="/live/images/blog/img-12.jpg" alt="image description">
							</a>
						<?php endif; ?>
						</figure>
						<div class="tg-description">
							<h3><a href="/doctor/doctor-details.html?doctor_profile_id=<?=$doctorProfileId?>">Dr. <?=$upcomingAppointment['first_name'] . ' ' . $upcomingAppointment['last_name']?></a></h3>
							<span class="tg-stars">
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star"></i>
								<i class="fa fa-star-half-empty"></i>
							</span>
							<p>
								<strong>Start:</strong> <?=date('d-F-Y H:i', strtotime($upcomingAppointment['start_date'])) ?>
								<br><strong>End:</strong> <?=date('d-F-Y H:i', strtotime($upcomingAppointment['end_date']))?>
							</p>
						</div>
						
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
			<?php else: ?>
				<p>No upcoming appointments</p>
			<?php endif; ?>
		</div>
	</div>
</div>
						
					</div>
				</div>
				
				
			</div>
		</div>
		</div>
		
		
		
	</div>
</div>
</div>
</div>