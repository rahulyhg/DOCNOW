<?php
	include_once 'custom_modules/common.php';
	include_once 'modules/connect.php';
	include_once "modules/profile.php";

	global $Profile_ID;
	global $Session_ID;

	if (!isset($_POST['booking-submit']) || empty($_POST)) {
		redirectToPage(ThisURL, 'Cannot find booking', 'alert-error');
	}	

	extract($_POST);

	$paymentMethod = getPaymentMethodById($payment_method);
?>

<div class="container">
	<div class="row">
		<div class="col-sm-10 col-sm-offset-1 col-xs-12">
			<div class="tg-theme-heading">
				<h2>Booking Confirmation</h2>
				<span class="tg-roundbox"></span>
				<div class="tg-description">
					<p>Please ensure that the details captured below are correct.</p>
				</div>
			</div>
		</div>
		<div class="col-md-12 col-sm-12 col-xs-12 tg-packageswidth">
			<table class="table table-striped">
				<tbody>
				  <tr>
					<td><b>Payment Method:</b></td>
					<td><?=$paymentMethod['name']?></td>
				  </tr>
				  
				  <tr>
					<td><b>Patient Name:</b></td>
					<td><?=$first_name . ' ' . $last_name?></td>
				  </tr>
				  
				  <tr>
					<td><b>Email address:</b></td>
					<td><?=$email?></td>
				  </tr>
				  
				  <tr>
					<td><b>Cellphone Number:</b></td>
					<td><?=$cell_phone?></td>
				  </tr>
				  
				  <tr>
					<td><b>Appointment Time:</b></td>
					<td><strong>Start:</strong> <?=date('d-F-Y H:i', strtotime($start_date)) ?>
						<br><strong>End:</strong> <?=date('d-F-Y H:i', strtotime($end_date))?>
					</td>
				  </tr>
				  
				  <tr>
					<td><b>Doctor:</b></td>
					<td><?=$doctor_name?></td>
				  </tr>
				  
				  <tr>
					<td><b>Specialty:</b></td>
					<td><?=$doctor_speciality?></td>
				  </tr>
				  
				  <tr>
					<td><b>Location:</b></td>
					<td><?=$doctor_address?></td>
				  </tr>
				  
				</tbody>
			</table>

			<form action="/booking/save-booking.html" method="post">
				<input type="hidden" name="title" value="<?=$title?>"/>
				<input type="hidden" name="start_date" value="<?=$start_date?>"/>
				<input type="hidden" name="end_date" value="<?=$end_date?>"/>
				<?php
					$patient_profile_id = $patient_exist ? $patient_profile_id : '';
				?>
				<input type="hidden" name="doctor_profile_id" value="<?=$doctor_profile_id?>"/>
				<input type="hidden" name="patient_profile_id" value="<?=$patient_profile_id ?>"/>

				<input type="hidden" name="payment_method" value="<?=$payment_method?>"/>
				<input type="hidden" name="first_name"  value="<?=$first_name?>" >
				<input type="hidden" name="last_name" value="<?=$last_name?>" >
				<input type="hidden" name="email" value="<?=$email?>">
				<input type="hidden" name="cell_phone" value="<?=$cell_phone?>" >
				<input type="submit" class="btn btn-success" name="confirm-booking" value="Next" />
			</form>
			<a href="#" class="pull-right">Get Directions</a>
		</div>
	</div>
</div>