<?php
	include_once ("modules/profile.php");
	include_once 'custom_modules/common.php';
	global $Session_ID;
	$specialities = getSpecialities();
?>

<div id='div-session-write'> </div>
<span class="home-url hidden"><?=ThisURL?></span>
<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
<div class="alert alert-danger sign-up-error-div hidden">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<span class="sign-up-error-span"></span>
</div>

<div role="tabpanel" class="tab-pane tg-haslayout" id="tg-signup-formarea">
	<div class="col-md-6"><a href="#" class="pull-left" id="doc-link" style="text-align: center;"><i class="fa fa-user-md" style="font-size: 60px;" aria-hidden="true"></i><br><span style="font-size: 18px; color:#062e4c;">Doctor Sign Up</span></a></div>
	<div class="col-md-6"><a href="#" class="pull-right" id="pat-link" style="text-align: center;"><i class="fa fa-user" style="font-size: 60px;" aria-hidden="true"></i><br><span style="font-size: 18px; color: #062e4c;">Patient Sign Up</span></a></div>
	
	
	<form class="tg-form-modal tg-form-signup" id="doctor" action="/live/signup.php" method="post">
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
			<button type="submit" class="tg-btn tg-btn-lg">Create an Account</button>
		</fieldset>
	</form>
	
	<form class="tg-form-modal tg-form-signup" id="patient" action="/live/signup.php" method="post">
		<fieldset>
			<?=PrintHiddenField ("Session_ID", $Session_ID);?>
			<input type="hidden" name="doctor" value="0">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Name" name="first_name">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Surname" name="last_name">
			</div>
			<div class="form-group">
				<input type="date" class="form-control" placeholder="Date Of Birth" name="birth_date">
			</div>
			<div class="form-group">
				<input type="email" class="form-control" placeholder="Email" name="Eml">
			</div>
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Cellphone Number" name="cell_phone">
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
			<button type="submit" class="tg-btn tg-btn-lg ">Create an Account</button>
		</fieldset>
	</form>
</div>

<script>
	$(function() {
		'use strict';
		var 
			signupForm = $('.tg-form-signup'),
			flashErrDiv = $('.sign-up-error-div'),
			flashErrSpan = $('.sign-up-error-span'),
			flashMsgSessionDiv = $('#div-session-write'),
			sessionWriteScript = $('.session-write-script').html(),
			homeUrl = $('.home-url').html();
			$("#patient").hide();
			$("#doctor").hide();
			$("#doc-link").click(function(){
				$("#patient").hide();
				$("#doctor").show();
			});
			$("#pat-link").click(function(){
				$("#patient").show();
				$("#doctor").hide();
			});

		/*if ($('.doctor-patient-select option:selected')) {
			var $this = $('.doctor-patient-select option:selected');
			doctorOnlyFields.toggle($this.val() == 1);
			patientOnlyFields.toggle($this.val() == 0);
		}*/

		/*doctorPatientSelect.on('change', function() {
			var $this = $(this);
			doctorOnlyFields.toggle($this.val() == 1);
			patientOnlyFields.toggle($this.val() == 0);
		});*/

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
				console.log(response);
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
					 	}
					}).done(function (response) {
						console.log(response);
					});

					$('.tg-user-modal').hide();
					window.location = homeUrl;
					
				} else {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html('An error occured. Please try again');
				}

			});
		});

	});
</script>