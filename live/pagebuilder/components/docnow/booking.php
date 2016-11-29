<?php
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/

	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;

	$referer = isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ThisURL;
	// debug($referer);exit;

	if (isset($_GET['profile_id']) && $_GET['profile_id'] > '') {
		$doctorProfileId = $_GET['profile_id'];
		$doctorDetails = getProflieRegDetails($doctorProfileId);
	} else {
		redirectToPage($referer, 'Unable to find doctor details', 'alert-danger');
	}

	if (empty($doctorDetails)) {
		redirectToPage($referer, 'Unable to find doctor details', 'alert-danger');
	}

	if (!isset($_GET['start_date']) || !isset($_GET['end_date'])) {
		redirectToPage($referer, 'Unable to find the booking date and time', 'alert-danger');
	}
	

	$doctorFulname = 'Dr. ' . $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];
	if ($Profile_ID) {
		$patientDetails = getProflieRegDetails($Profile_ID);
	}

	$patientExist = '1';

	if (empty($patientDetails)) {
		$patientExist = '0';
	}
	$specialities = getSpecialities();
	$paymentMethods = getPaymentMethods();
	$appointments = getDoctorAppointments($doctorProfileId);
	$doctorSpeciality = isset($specialities[$doctorDetails['speciality_id']]) ? $specialities[$doctorDetails['speciality_id']] : 'none';

	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
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
						title : 'Booked',
						start : value.start_date,
						end: value.end_date,
						color: value.confirmed == '1' ? '#51c5d7' : '#f58320'
					};
				eventData.push(temp);
			});
		}

		/*calendarDiv.fullCalendar({
			minTime: "08:00:00",
            maxTime: "17:00:00",
			defaultView: 'agendaWeek',
			editable: true,
			eventLimit: true,
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				// var title = prompt('Event Title:');
				var title = $('#first_name').val() + ' ' + $('#last_name').val();
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					$('#start_date').val(start);
					$('#end_date').val(end);
					$('#title').val(title);
					$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
				}
				$('#calendar').fullCalendar('unselect');
			},
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'agendaWeek,agendaDay !important'
			},
			 eventSources: [
	        	{
	        		events: eventData,
	        		color: '#B9CEF0',   // an option!
					backgroundColor: '#B9CEF0 !important',
			    	textColor: '#fff !important'
	        	}
	        ]
		});*/
		
	});

</script>
<style>
	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}
</style>

<div class="container">
	<div class="row">
		<div class="col-sm-12 col-xs-12">
			<div class="tg-theme-heading">
				<h2>Make a booking with <?=$doctorFulname?></h2>
				<span class="tg-roundbox"></span>
				<div class="tg-description">
					<p>In just three simple steps, DocNow will help you find your nearest healthcare setting without having to signup. We aim to facilitate you in finding your right doctor with just three clicks without having to ask around or wander to find your nearest healthcare facility.</p>
				</div>
			</div>
		</div>

		<div class="tg-graph tg-haslayout centered-block">
			<div class="tg-profilehits">
				<div class="tg-heading-border tg-small">
					<h3>Appointment date and time</h3>
				</div>
				<div>
					<p><strong>Start:</strong> <?=$start_date?></p>
					<p><strong>End:</strong> <?=$end_date?></p>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 tg-packageswidth pull-left">
			<form action="/booking/booking-confirmation.html?Session_ID=<?=$Session_ID?>" method="post" id="booking-form">
				<input type="hidden" id="title" name="title" value=""/>
				<input type="hidden" id="start_date" name="start_date" value="<?=$start_date?>"/>
				<input type="hidden" id="end_date" name="end_date" value="<?=$end_date?>"/>
				<input type="hidden" name="doctor_address" value="<?=$doctorDetails['address']?>"/>
				<input type="hidden" name="doctor_speciality" value="<?=$doctorSpeciality?>"/>
				<input type="hidden" id="doctor_name" name="doctor_name" value="<?=$doctorFulname?>"/>
				<input type="hidden" id="patient_exist" name="patient_exist" value="<?=$patientExist?>"/>
				<input type="hidden" id="doctor_profile_id" name="doctor_profile_id" value="<?=$doctorProfileId?>"/>
				<input type="hidden" id="patient_profile_id" name="patient_profile_id" value="<?=$Profile_ID?>"/>
			  <div class="form-group">
				<label for="exampleSelect1">Payment Method</label>
				<select class="form-control" id="payment_method" name="payment_method" required="required">
					<option value="">Select Payment Method</option>
					<?php foreach ($paymentMethods as $paymentMethod) :?>
						<option value="<?=$paymentMethod['id']?>"><?=$paymentMethod['name']?></option>
					<?php endforeach; ?>
				</select>
			  </div>
			  
			  <div class="form-group">
				<label for="first_name">Enter patient's first name</label>
				<input type="text" id="first_name" name="first_name" class="form-control" placeholder="Enter patient's first name" value="<?=$patientDetails['first_name']?>" required="required">
			  </div>

			  <div class="form-group">
				<label for="last_name">Enter patient's last name</label>
				<input type="text" id="last_name" name="last_name" class="form-control" placeholder="Enter patient's last name" value="<?=$patientDetails['last_name']?>" required="required">
			  </div>
			  
			  <div class="form-group">
				<label for="email">Email address</label>
				<input type="email" name="email" class="form-control" id="email" placeholder="Enter email" value="<?=$patientDetails['email']?>" required="required">
			  </div>
			  
			  <div class="form-group">
				<label for="Cellphone">Cellphone</label>
				<input type="text" name="cell_phone" class="form-control" id="Cellphone" placeholder="Enter cellphone" value="<?=$patientDetails['cell_phone']?>" required="required">
			  </div>
				<!-- <div class="tg-graph tg-haslayout">
					<div class="tg-profilehits">
						<div class="tg-heading-border tg-small">
							<h3>Doctor Calendar</h3>
						</div>
						<div id="calendar"></div>
					</div>
				</div> -->
			  <input type="submit" class="btn btn-success" name="booking-submit" value="Next" />
			</form>
			<br>			
		</div>
		
	</div>
</div>

<script type="text/javascript">
	$(function() {
		var bookingForm = $('#booking-form');	

		bookingForm.on('submit' ,function(e) {
			var titleEvent = $('#first_name').val() + ' ' + $('#last_name').val()
				$('#title').val(titleEvent);

			if (!$('#start_date').val() || !$('#end_date').val()) {
				e.preventDefault();
				e.stopImmediatePropagation();
				alert('Please make a booking by selecting the date from the Calendar.');
			}
		});
	});
</script>