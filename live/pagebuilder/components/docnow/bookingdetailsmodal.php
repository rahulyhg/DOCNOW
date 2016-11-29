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

<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<h4 class="modal-title" id="myModalLabel">Appointment details</h4>
</div>
<div class="modal-body">
	<div class="table-container booking-details">
	<table class="table-responsive">
		<tr>
			<th>Patient</th>
			<td class="patient-name"><?=$appointmentDetails['firt_name']?></td>
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
	<input type="radio" name="confirmation" value="confirmed" checked> Confirmed<br>
	<input type="radio" name="confirmation" value="reshedule"> Reshedule<br><br>
	<div class="reshedule-times hidden">
		<div class="form-group">
			<label for="reschedule_start_date">Reschedule start date and time:</label>
			<input type="text" class="form-control" name="reschedule_start_date" id="reschedule_start_date">
		</div>
		<div class="form-group">
			<label for="reschedule_start_date">Reschedule end date and time:</label>
			<input type="text" class="form-control" name="reschedule_end_date" id="reschedule_end_date">
		</div>
	</div>
</div>
</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="submit" class="btn btn-primary">Save changes</button>
</div>