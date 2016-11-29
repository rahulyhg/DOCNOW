<?php

	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';

	global $Profile_ID;

	$profileDetails = getProflieRegDetails($Profile_ID);
	$upcomingAppointments = getUpcomingAppointments($Profile_ID);
	$appointments = getDoctorAppointments($Profile_ID);

?>
<div class="encoded-appointments-data hidden">
    <?=json_encode($appointments)?>
</div>

<link href='/live/css/fullcalendar.css' rel='stylesheet' />
<link href='/live/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/live/js/moment.min.js'></script>
<script src='/live/js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
		var calendarDiv = $('#calendar'),
			calendarDataHtml = $.trim($('.encoded-appointments-data').html()),
			calendarDataJson = calendarDataHtml > '' ? $.parseJSON(calendarDataHtml) : '',
			eventData = [];

		if (calendarDataJson > '') {
			$.each(calendarDataJson, function(key, value) {
				var temp = {};
				temp = {
						title : value.title,
						start : value.start_date,
						end: value.end_date,
						url: '/booking/view/' + value.id
					};
				eventData.push(temp);
			});
		}

		calendarDiv.fullCalendar({
			editable: true,
			eventLimit: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay !important'
			},
			 eventSources: [
	        	{
	        		events: eventData,
	        		color: '#B9CEF0',   // an option!
					backgroundColor: '#B9CEF0 !important',
			    	textColor: '#fff !important'
	        	}
	        ]
		});
		
	});

</script>
<style>

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
<div class="tg-dashboard tg-haslayout">
	<div class="tg-docprofilechart tg-haslayout">
		<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth">
			<div class="row">
				<div class="tg-docinfo tg-haslayout">
					<div class="tg-box">
						<div class="tg-heading-border tg-small">
							<h3>Welcome, Dr. <?=$profileDetails['first_name']." ".$profileDetails['last_name'];?></h3>
						</div>
						<div class="tg-description">
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodporia incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisti nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consuatag.</p>
							<br>
							<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodporia incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisti nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consuatag.</p>
						</div>
						<div class="tg-bottominfo tg-haslayout">
							
							<div class="tg-regardsright">
								<button type="button" class="btn btn-success">Find A Doctor</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth">
		<div class="row">
			<div class="tg-support">
				<div class="tg-costumerreview">
					<div class="tg-heading-border tg-small">
						<h3>Upcoming Appointments</h3>
					</div>
					<?php if (empty($upcomingAppointments)) : ?>
						<p>No upcoming appointments.</p>
					<?php else : ?>
					<div id="tg-reviewscrol" class="tg-reviewscrol">
						<ul class="tg-reviews">
							<?php foreach ($upcomingAppointments as $upcomingAppointment) :?>
								<li>
									<div class="tg-review">
										<div class="tg-reviewcontet">
											<div class="comment-head">
												<div class="pull-left">
													<h3><a href="#"><?=$upcomingAppointment['first_name'] . ' ' . $upcomingAppointment['last_name']?></a></h3>
												</div>
												
											</div>
											<div class="tg-description">
												<p>
													<?=date('d-F-Y', strtotime($upcomingAppointment['start_date']))?><br>
													<?=date("H:i",strtotime($upcomingAppointment['start_date']));?>
													<a href="#" class="btn btn-warning pull-right">Edit</a>
												</p>
											</div>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
						</ul>
					</div>
					`<?php endif;?>
				</div>
			</div>
		</div>
	</div>
	</div>
	
	<div class="tg-graph tg-haslayout">
		<div class="tg-profilehits">
			<div class="tg-heading-border tg-small">
				<h3>Calendar</h3>
			</div>
			<div id="calendar"></div>
		</div>
	</div>
	
</div>