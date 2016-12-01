<?php

	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';
	include_once 'flash_message.php';

	global $Profile_ID;
	global $Session_ID;

	$profileDetails = getProflieRegDetails($Profile_ID);
	$appointments = getDoctorAppointments($Profile_ID);

?>

<div class="encoded-appointments-data hidden">
    <?=json_encode($appointments)?>
</div>
<span class="booking-details-modal-url hidden"><?='/live/bookingdetailsmodal.php'?></span>

<link href='/live/css/fullcalendar.css' rel='stylesheet' />
<link href='/live/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/live/js/moment.min.js'></script>
<script src='/live/js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
		'use strict';

		var calendarDiv = $('#calendar'),
			bookingDetailsUrl = $('.booking-details-modal-url').html(),
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
						eventId: value.id,
						// url: value.id,
						color: value.confirmed == '1' ? '#51c5d7' : '#f58320'
					};
				eventData.push(temp);
			});
		}

		calendarDiv.fullCalendar({
			minTime: "08:00:00",
            maxTime: "17:00:00",
            allDaySlot:false,
            defaultView: 'agendaWeek',
			editable: false,
			eventLimit: true,
			eventClick:  function(event, jsEvent, view) {
	            console.log(event.eventId);
	            $("#appointment-modal-content").load(bookingDetailsUrl + '?appointmentId=' + event.eventId, function() {});
	            $('#myModal').modal('show', {backdrop: 'static', keyboard: false});

	        },
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'agendaWeek !important'
			},
			 eventSources: [
	        	{
	        		events: eventData,
	        		//color: '#B9CEF0',   // an option!
					//backgroundColor: '#B9CEF0 !important',
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

	body.modal-open div.modal-backdrop { 
	    z-index: 0; 
	}

	/*.modal-backdrop {
	  z-index: -1;
	}*/

</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content" id="appointment-modal-content">
    </div>
  </div>
</div>

<div class="container">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
			<div class="tg-heading-border">
				<h3>Welcome, Dr. <?=$profileDetails['first_name']." ".$profileDetails['last_name'];?></h3>
			</div>
			<div class="tg-dashboard tg-haslayout">
				<div class="tg-docprofilechart tg-haslayout">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth">
						<div class="row">
							<div class="col-md-3">
								<a href="/doctors/dashboard/?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Calendar</a>
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
		</div>
	</div>
</div>
