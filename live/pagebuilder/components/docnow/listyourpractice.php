<?php
	include_once ("modules/profile.php");
	include_once 'custom_modules/common.php';

	global $Session_ID;
	$specialities = getSpecialities();
?>
<script type="text/javascript">
	
	function initialize() {


		var defaultBounds = new google.maps.LatLngBounds(
			new google.maps.LatLng(40.802089, -124.163751)
			);

		var input = document.getElementById('address');

		var options = {
			bounds: defaultBounds,
	/*		componentRestrictions: {country: 'sa'}*/	
	    };


		var autocomplete = new google.maps.places.Autocomplete(input, options);    

		google.maps.event.addListener(autocomplete, 'place_changed', function () {
	        var place = autocomplete.getPlace();
	        $('#lat').val(place.geometry.location.lat());
	        $('#lng').val(place.geometry.location.lng());
	        console.log(place.geometry.location.toJSON());

	    });
	}

	google.maps.event.addDomListener(window, 'load', initialize);
</script>
<div class="container">
	<div class="row">
		<div class="col-sm-12 col-xs-12">
			<div class="tg-theme-heading">
				<h2>List your practice</h2>
				<span class="tg-roundbox"></span>
				<div class="tg-description">
					<p>In just three simple steps, DocNow will help you find your nearest healthcare setting without having to signup. We aim to facilitate you in finding your right doctor with just three clicks without having to ask around or wander to find your nearest healthcare facility.</p>
				</div>
			</div>
		</div>
		<span class="home-url hidden"><?=ThisURL?></span>
		<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
		<div class="alert alert-danger sign-up-error-div hidden">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="sign-up-error-span"></span>
		</div>		
		<div class="col-md-12 col-sm-12 col-xs-12 tg-packageswidth ">
			<form id="doctor-signup-form" action="/live/signup.php" method="post">
				<?=PrintHiddenField ("Session_ID", $Session_ID);?>
				<input type="hidden" name="doctor" value="1">
				<fieldset>
					<div class="form-group">
							<input type="text" class="form-control" placeholder="Name" name="first_name" required="required" />
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Surname" name="last_name" required="required">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Practice Number" name="practice_number" required="required">
						</div>
						<div class="form-group">
							<span class="select">
								<select  name="speciality_id" required="required">
									<option  value="">--Choose speciality--</option>
									<?php foreach ($specialities as $id => $speciality) :?> 
										<option value="<?=$id?>"><?=$speciality?></option>
									<?php endforeach;?>
								</select>
							</span>
						</div>
						<div class="form-group">
							<input type="email" class="form-control" placeholder="Email" name="Eml" required="required">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Landline Number" name="land_line" required="required">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Cellphone Number" name="cell_phone" required="required">
						</div>
						<div class="form-group">

							<input type="text" class="form-control" alt="Start address" name="address" id="address" placeholder="Address" autocomplete="on" required  value="" />
							<input type="hidden" id="lat" name="lat" value="" />
							<input type="hidden" id="lng" name="lng" value="" /> 
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Physical Address" name="address_1">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Physical Address Line 2" name="address_2">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="City" name="city">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Province" name="province">
						</div>
						<div class="form-group">
							<input type="text" class="form-control" placeholder="Postal/ZIP Code" name="postal_code">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Password" name="Pwd" required="required">
						</div>
						<div class="form-group">
							<input type="password" class="form-control" placeholder="Confirm password" name="Pwd2" required="required">
						</div>			
						<div class="form-group tg-checkbox">
							<label>
								<input type="checkbox" class="form-control">
								I agree with the terms and conditions
							</label>
						</div>
						<button type="submit" class="btn btn-success">Create an Account</button>
				</fieldset>
			</form>
			  
		</div>
	</div>
</div>


<script>
	$(function() {
		'use strict';
		var 
			signupForm = $('#doctor-signup-form'),
			flashErrDiv = $('.sign-up-error-div'),
			flashErrSpan = $('.sign-up-error-span'),
			sessionWriteScript = $('.session-write-script').html(),
			homeUrl = $('.home-url').html();

		signupForm.on('submit',function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();

			var $this = $(this);

			$.ajax({
				type: 'post',
				url: $this.attr('action'),
				data: new FormData($(this)[0]),
				contentType : false,
				processData : false
			}).done(function (response) {
				var responseObj = $.parseJSON(response);
				if (responseObj.Error_NUM && responseObj.Error_NUM > 0) {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html(responseObj.Error_STRING);
				} else if(responseObj.Error_NUM == 0) {
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: "Registration successful. An email was sent to your email address with further instructions.",
					 		sessionMessageClass: "alert-success"
					 	},
						contentType : false,
						processData : false
					}).done(function (response) {
						console.log(response);
					});
					window.location.href = homeUrl;
				} else {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html('An error occured. Please try again');
				}

			});
		});

	});
</script>