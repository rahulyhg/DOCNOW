<?php
	/*error_reporting(E_ALL);
	ini_set('display_errors', 1);*/
	include_once 'custom_modules/common.php';

	if (!$_GET['appointmentId']) {
		echo 'Appointment reference not found.';
		exit;
	}
	$appointment_id = $_GET['appointmentId'];

	$appointmentDetails = getAppointmentById($appointment_id);

	if (empty($appointmentDetails)) {
		echo 'Appointment details not found.';
		exit;
	}
?>

<script type="text/javascript" src="/live/js/jquery.blockUI.js"></script>
<script type="text/javascript" src="/live/js/moment.min.js"></script>
<script type="text/javascript" src="/live/js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="/live/css/bootstrap-datetimepicker.min.css"/>

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Appointment details</h4>
</div>
<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
<form id="confirm-appointment-form" action="/live/confirmappointment.php" method="post">
<div class="modal-body">
	<div class="table-container booking-details">
	<table class="table-responsive">
		<tr>
			<th>Patient</th>
			<td class="patient-name"><?=$appointmentDetails['first_name'] . ' ' . $appointmentDetails['last_name']?></td>
		</tr>
		<tr>
			<th>Email address:</th>
			<td class="patient-email"><?=$appointmentDetails['email']?></td>
		</tr>

		<tr>
			<th>Cellphone Number:</th>
			<td class="patient-phone"><?=$appointmentDetails['cell_phone']?></td>
		</tr>
		<tr>
			<th>Payment method</th>
			<td class="payment-method"><?=$appointmentDetails['payment_method']?></td>
		</tr>	
		<tr>
			<th>Appointment time</th>
			<td class="patient-name">
				<strong>Start:</strong> <?=date('d-F-Y H:i', strtotime($appointmentDetails['start_date'])) ?>
				<br><strong>End:</strong> <?=date('d-F-Y H:i', strtotime($appointmentDetails['end_date']))?>
			</td>
		</tr>
	</table>

		<!-- <div class="radio">
		  <label>
		    <input type="radio" name="confirmation" class="confirmation-radio" value="confirmed" checked>
		    Confirmed
		  </label>
		</div> -->
		<div class="radio">
		  <label>
		    <input type="radio" name="confirmation" class="confirmation-radio" value="reschedule">
		    Reschedule
		  </label>
		</div>
		<input type="hidden" name="appointment_id" value="<?=$appointment_id?>">

		<!-- <div id="reschedule-times-div" class="reschedule-times hidden">
			<div class="form-group">
				<label for="reschedule_start_date">Reschedule start date and time:</label>
				<input type="text" class="form-control" name="reschedule_start_date" id="reschedule_start_date">
				<span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
			</div>
			<div class="form-group">
				<label for="reschedule_start_date">Reschedule end date and time:</label>
				<input type="text" class="form-control" name="reschedule_end_date" id="reschedule_end_date">
				<span class="input-group-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                </span>
			</div>
		</div> -->
		<div id="reschedule-times-div" class="container reschedule-times hidden">
		    <div class='col-md-3'>
		        <div class="form-group">
		        	<label for="reschedule_start_date">Reschedule start time:</label>
		            <div class='input-group date' id='reschedule_start_date'>
		                <input type='text' class="form-control" name="reschedule_start_date"/>
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		    </div>
		    <div class='col-md-3'>
		        <div class="form-group">
		        	<label for="reschedule_start_date">Reschedule end time:</label>
		            <div class='input-group date' id='reschedule_end_date'>
		                <input type='text' class="form-control" name="reschedule_end_date" />
		                <span class="input-group-addon">
		                    <span class="glyphicon glyphicon-calendar"></span>
		                </span>
		            </div>
		        </div>
		    </div>
		</div>		
	</div>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-success" id="confirm-appointment-btn">Save changes</button>
	</div>

	</form>
<script type="text/javascript">
	$(document).ready(function() {
		var confirmAppointmentForm = $('#confirm-appointment-form'),
			sessionWriteScript = $('.session-write-script').html();

		$('#reschedule_start_date').datetimepicker();
		$('#reschedule_end_date').datetimepicker({
			useCurrent: false //Important! See issue #1075
		});
		$("#reschedule_start_date").on("dp.change", function (e) {
		$('#reschedule_end_date').data("DateTimePicker").minDate(e.date);
		});
		$("#reschedule_end_date").on("dp.change", function (e) {
		$('#reschedule_start_date').data("DateTimePicker").maxDate(e.date);
		});

		$('.confirmation-radio')
			.change(function() {
				console.log($(this).val());
				$('#reschedule-times-div').toggleClass('hidden');
			});

		confirmAppointmentForm.on('submit',function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var $this = $(this);

			$.ajax({
				type: 'post',
				url: $this.attr('action'),
				data: new FormData($this[0]),
				contentType : false,
				processData : false
				}).done(function(data){
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: "Booking changes saved successfully.",
					 		sessionMessageClass: "alert-success"
					 	}
					}).done(function (response) {
						console.log(response);
						window.location.reload();
					});
				});
			
			});	

	});
</script>