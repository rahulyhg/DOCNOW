<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', 1);*/

include_once 'custom_modules/common.php';
include_once 'modules/globals.php';
include_once 'flash_message.php';

global $Profile_ID, $Session_ID;

$profileDetails = getProflieRegDetails($Profile_ID);

$emergencyContact = getProfileEmergencyContact($Profile_ID);
$userEmployer = getUserEmployer($Profile_ID);
$UserPreferences = getUserPreferences($Profile_ID);
$MedicalAidCards = getMedicalAidCards($Profile_ID);
$username = RetrieveProfileDetails ($Profile_ID);

/*echo "<pre>";print_r($UserPreferences);echo "</pre>";
*/
?>

<link href="/live/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<style>
	.tabs-left, .tabs-right {
	  border-bottom: none;
	  padding-top: 2px;
	}
	.tabs-left {
	  border-right: 1px solid #ddd;
	}
	.tabs-right {
	  border-left: 1px solid #ddd;
	}
	.tabs-left>li, .tabs-right>li {
	  float: none;
	  margin-bottom: 2px;
	}
	.tabs-left>li {
	  margin-right: -1px;
	}
	.tabs-right>li {
	  margin-left: -1px;
	}
	.tabs-left>li.active>a,
	.tabs-left>li.active>a:hover,
	.tabs-left>li.active>a:focus {
	  border-bottom-color: #ddd;
	  border-right-color: transparent;
	}

	.tabs-right>li.active>a,
	.tabs-right>li.active>a:hover,
	.tabs-right>li.active>a:focus {
	  border-bottom: 1px solid #ddd;
	  border-left-color: transparent;
	}
	.tabs-left>li>a {
	  border-radius: 4px 0 0 4px;
	  margin-right: 0;
	  display:block;
	}
	.tabs-right>li>a {
	  border-radius: 0 4px 4px 0;
	  margin-right: 0;
	}
</style>

<link rel="stylesheet" href="/live/css/dropzone.css">
<script type="text/javascript" src="/live/js/dropzone.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>

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
	
	$(function() {
		'use strict';
		var 
			profiledataForm = $('#profiledata'),
			flashErrDiv = $('.sign-up-error-div'),
			flashErrSpan = $('.sign-up-error-span');

		profiledataForm.on('submit',function (e) {
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
				if (responseObj.error) {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html(responseObj.message);
				} else {
					
					$('#profiledatadiv').html(responseObj.message)
				}

			});
		});

		var 
			passwordform = $('#passwordform'),
			flashErrDiv1 = $('#password-error'),
			flashErrSpan1 = $('#password-error-span');

		passwordform.on('submit',function (e) {
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
				if (responseObj.error) {
					flashErrDiv1.removeClass('hidden');
					flashErrSpan1.html(responseObj.message);
				} else {
					
					$('#Password-v').html(responseObj.message)
				}

			});
		});

		var 
			preferencesform = $('#preferencesform'),
			flashErrDiv2 = $('#preferences-error'),
			flashErrSpan2 = $('#preferences-error-span');

		preferencesform.on('submit',function (e) {
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
				if (responseObj.error) {
					flashErrDiv2.removeClass('hidden');
					flashErrSpan2.html(responseObj.message);
				} else {
					
					$('#Notifications-v').html(responseObj.message)
				}

			});
		});

		Dropzone.options.medicalAidFront = {
		  paramName: 'medical_aid_front',
		  maxFilesize: 1, // MB
		  maxFiles: 1,
		  dictDefaultMessage: 'Drag the front of your medical cards here to upload, or click to select one',
		  headers: {
		    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
		  },
		  acceptedFiles: 'image/*',
		  init: function() {		  	
		       
	        var thisDropzone = this;          	
            var mockFile = { name: 'existing file' };
             
            thisDropzone.options.addedfile.call(thisDropzone, mockFile);

            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '<?=IMAGE_URL.$MedicalAidCards['medical_aid_front']?>'),                 
		           
		    this.on('success', function( file, resp ){
		      //console.log('File: ' + file );
		      console.log(resp );
		      var responseObj = $.parseJSON(resp);
		      if(responseObj.valid){

		      	$('#profilepic').val(responseObj.file)
		      }


		    });
		    this.on('thumbnail', function(file) {
		    	// console.log(file);
		      if ( file.width <  370 || file.height <  377 ) {
		        file.rejectDimensions();
		      }
		      else {
		        file.acceptDimensions();
		      }
		    });
		  },
		  accept: function(file, done) {
		    file.acceptDimensions = done;
		    file.rejectDimensions = function() {
		      done('The image must be at least  370 x 377')
		    };
		  }, 
		  sending: function(file, xhr, formData){
		    formData.append('Profile_ID', '<?php echo $Profile_ID;?>'),
		    formData.append('Card_face', 'medical_aid_front');
		  },
		  uploadMultiple: false,
          acceptedFiles: '.jpg, .jpeg, .png, .svg'

		};

		Dropzone.options.medicalAidBack = {
		  paramName: 'medical_aid_back',
		  maxFilesize: 1, // MB
		  maxFiles: 1,
		  dictDefaultMessage: 'Drag the back of your medical cards here to upload, or click to select one',
		  headers: {
		    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
		  },
		  acceptedFiles: 'image/*',
		  init: function() {

		  	var thisDropzone = this;          	
            var mockFile = { name: 'existing file' };
             
            thisDropzone.options.addedfile.call(thisDropzone, mockFile);

            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '<?=IMAGE_URL.$MedicalAidCards['medical_aid_back']?>'),

		    this.on('success', function( file, resp ){
		      //console.log('File: ' + file );
		      console.log(resp );
		      var responseObj = $.parseJSON(resp);
		      if(responseObj.valid){

		      	$('#profilepic').val(responseObj.file)
		      }


		    });
		    this.on('thumbnail', function(file) {
		    	// console.log(file);
		      if ( file.width <  370 || file.height <  377 ) {
		        file.rejectDimensions();
		      }
		      else {
		        file.acceptDimensions();
		      }
		    });
		  },
		  accept: function(file, done) {
		    file.acceptDimensions = done;
		    file.rejectDimensions = function() {
		      done('The image must be at least  370 x 377')
		    };
		  }, 
		  sending: function(file, xhr, formData){
		    formData.append('Profile_ID', '<?php echo $Profile_ID;?>'),
		    formData.append('Card_face', 'medical_aid_back');
		  },
		  uploadMultiple: false,
          acceptedFiles: '.jpg, .jpeg, .png, .svg'

		};

	});

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
<style type="text/css">
.dropzone {
    background: white none repeat scroll 0 0;
    border: 2px dashed #0087f7;
    border-radius: 5px;
}
</style> 

<form action="/search/&Session_ID=<?=$Session_ID?>" method="post" id="specialty-form">
	
	<input type="hidden" name="speciality" value="1" id="speciality1">
	<input type="hidden" name="lat" id="lat1" value="">
	<input type="hidden" name="lng" id="lng1" value="">
</form>    
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
<div class="col-sm-12">
	<h3>Patient Profile Setting</h3>

	<hr/>

	<div class="col-md-4"> <!-- required for floating -->
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs tabs-left sideways">
	  	<li class="active"><a href="#Profile-v" data-toggle="tab">Profile Image</a></li>
		<li><a href="#Basic-Info-v" data-toggle="tab">Profile</a></li>
		<li><a href="#Password-v" data-toggle="tab">Password</a></li>
		<li><a href="#Notifications-v" data-toggle="tab">Notifications Settings</a></li>
		<li><a href="#Medical-v" data-toggle="tab">Medical Aid</a></li>
<!-- 		<li><a href="#Aurthorizations-v" data-toggle="tab">Aurthorizations</a></li>
 -->	  </ul>
	</div>

	<div class="col-md-8">
	  <!-- Tab panes -->
	  <div class="tab-content">

	  	<div class="tab-pane active" id="Profile-v">
			<h4>Name:  <?php echo $profileDetails['first_name']." ".$profileDetails['last_name'];?></h4>
			<div class="tg-editprofile tg-haslayout">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth">
					<div class="row">
						<div class="tg-editimg tg-haslayout">
							<div class="tg-heading-border tg-small">
								<h3>upload photo</h3>
							</div>
							<figure class="tg-docimg">
								<!-- <img src="images/dashboard/img-03.jpg" alt="image descriptio">
								<a href="#" class="tg-deleteimg"><i class="fa fa-plus"></i></a>
								<a href="#" class="tg-uploadimg"><i class="fa fa-upload"></i></a> -->
								<form id="upload-widget" method="post" action="/live/pagebuilder/components/docnow/moveuploadtolocation.php" class="dropzone">
									<div class="fallback">
										<input name="file" type="file" value="<?=$profileDetails['profilepic']?>" />
									</div>
								</form>
							</figure>
							<div class="tg-uploadtips">
								<h4>tips for uploading</h4>
								<div class="tg-description">
									<p>Update your Photo manually, If not set then the default gravatar will be set to your profile.</p>
								</div>
								<ul class="tg-instructions">
									<li>Max Upload Size: 1MB</li>
									<li>Dimensions: 370x377</li>
									<li>Extensions: Jpeg, Png</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		<div class="tab-pane active" id="Basic-Info-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span"></span>
			</div>
			<div id="profiledatadiv">											
				<form action="/live/pagebuilder/components/docnow/savepatientdata.php" name="profiledata" id="profiledata">
					<div class="form-group">
						<label for="first_name">Name:</label>
						<input type="text" class="form-control" name="first_name" id="first_name" value="<?=$profileDetails['first_name']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Surname:</label>
						<input type="text" class="form-control" name="last_name" id="last_name" value="<?=$profileDetails['last_name']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Nickname:</label>
						<input type="text" class="form-control" id="nickname" name="nickname" value="<?=$profileDetails['nickname']?>">
					</div>
					<div class="form-group">
						<label for="email">Legal guarduian (If under 18):</label>
						<input type="text" class="form-control" id="guarduian" name="guarduian" value="<?=$profileDetails['guarduian']?>">
					</div>
					<div class="form-group">
						<label for="email">Street Address:</label>
						<input type="text" class="form-control" id="address_1" name="address_1" value="<?=$profileDetails['address_1']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Street Address Line 2:</label>
						<input type="text" class="form-control" id="address_2" name="address_2" value="<?=$profileDetails['address_2']?>" required>
					</div>
					<div class="form-group">
						<label for="email">City:</label>
						<input type="text" class="form-control" id="city" name="city" value="<?=$profileDetails['city']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Province/State:</label>
						<input type="text" class="form-control" id="province" name="province" value="<?=$profileDetails['province']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Postal/Zip Code:</label>
						<input type="text" class="form-control" id="postal_code" name="postal_code" value="<?=$profileDetails['postal_code']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Country:</label>
						<select class="form-control" id="country_id" name="country_id" required>
							
							<option value="">--Select--</option>
							<? foreach ($GLOBALS['countries'] as $key => $value) :?>

								<option value="<?=$key?>" <?=($key == DefaultRegion || $key == $profileDetails['country_id'] ? 'selected="selected"' : '') ?>><?=ucwords(strtolower($value))?></option>
							<? endforeach ?>
						</select>
					</div>
					<div class="form-group">
						<label for="email">Email address:</label>
						<input type="email" class="form-control" id="email" name="email" value="<?=$username ['Login_STRING']?>" required>
					</div>
					<div class="form-group">
						<label for="pwd">Cellphone:</label>
						<input type="text" class="form-control" id="cell_phone" name="cell_phone" value="<?=$profileDetails['cell_phone']?>" required>
					</div>
					<div class="form-group">
						<label for="pwd">Work (Optional):</label>
						<input type="text" class="form-control" name="work_number" id="work_number" value="<?=$profileDetails['work_number']?>">
						
					</div>
					<div class="form-group">
						<label for="pwd">Home (Optional):</label>
						<input type="text" class="form-control" id="home_number" name="home_number" value="<?=$profileDetails['home_number']?>">
					</div>
					<div class="form-group">
						<label for="pwd">Prefered Number:</label>
						<select name="prefered_number" id="prefered_number" required>
							<option value="cellphone">Cellphone</option>
							<option value="work">Work</option>
							<option value="home">Home</option>
						</select>
					</div>
					
					<div class="form-group">
						
						<!-- <input type="date" class="form-control" id="birth_date" name="birth_date" value="<? //$profileDetails['birth_date']?>"> -->

					            <!--  <label for="birth_date" class="col-md-2 control-label">Date Picking</label> -->
		                <label for="birth_date">Date Of Birth:</label>
		                <div class="input-group date form_date col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-field="birth_date" data-link-format="yyyy-mm-dd">
		                    <input class="form-control" size="16" type="text" value="<?php echo ($profileDetails['birth_date'] ? date('d F Y', strtotime($profileDetails['birth_date'])) : ''); ?>" readonly required>
		                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		               		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                </div>
						<input type="hidden" id="birth_date" name="birth_date" value="<?=$profileDetails['birth_date']?>" />
			        </div>
					
					<div class="form-group">
						<label for="pwd">Gender:</label>
						<select id="gender" name="gender" required>
							<option value="male">Male</option>
							<option value="female">Female</option>
						</select>
					</div>
					
					<div class="form-group">
						<label for="pwd">Marital Status:</label> 
						<select id="marital_status" name="marital_status" required>          
							<option value="single">Single</option>
							<option value="married">Married</option>
							<option value="partnered">Partnered</option>
							<option value="separated">Separated</option>
							<option value="divorced">Divorced</option>
							<option value="widowed">Widowed</option>
						</select>
					</div>
					
					<div class="form-group">
						<label for="pwd">Emergency Contact:</label>
						<input type="text" class="form-control" placeholder="Name" name="emergency_contact_name" id="emergency_contact_name" value="<?=$emergencyContact['emergency_contact_name']?>" required>
						<input type="text" class="form-control" placeholder="Surname" id="emergency_contact_surname" name="emergency_contact_surname" value="<?=$emergencyContact['emergency_contact_surname']?>" required>
						<input type="text" class="form-control" placeholder="Relationship" name="emergency_contact_relationship" id="emergency_contact_relationship" value="<?=$emergencyContact['emergency_contact_relationship']?>" required>
						<input type="text" class="form-control" placeholder="Number" name="emergency_contact_number" id="emergency_contact_number" value="<?=$emergencyContact['emergency_contact_number']?>" required>
						
					</div>
					
					<div class="form-group">
						<label for="pwd">Employer:</label>
						<input type="text" class="form-control" placeholder="Name" name="employer_name" id="employer_name" value="<?=$userEmployer['employer_name']?>" required>
						<input type="text" class="form-control" placeholder="Address" name="employer_address" value="<?=$userEmployer['employer_address']?>" required>
						<input type="text" class="form-control" placeholder="Contact Number" name="employer_contact_number" value="<?=$userEmployer['employer_contact_number']?>" required>
						
					</div>
				  <input type="hidden" name="Profile_ID" value="<?php echo $Profile_ID; ?>">
				  <input type="hidden" name="whichform" value="profiledata">
				  <button type="submit" class="btn btn-success" id="savepatientdata" >Submit</button>
				</form>
			</div>
		</div>
		<div class="tab-pane" id="Password-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden" id="password-error">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span" id="password-error-span"></span>
			</div>
			<form action="/live/pagebuilder/components/docnow/savepatientdata.php" name="passwordform" id="passwordform">
				<div class="form-group">
					<label for="pwd">Old Password:</label>
					<input type="password" class="form-control" id="pwdOld" name="pwdOld" required>
					
				</div><div class="form-group">
					<label for="pwd">New Password:</label>
					<input type="password" class="form-control" id="pwd1" name="pwd1" required>
					
				</div><div class="form-group">
					<label for="pwd">Verify New Password:</label>
					<input type="password" class="form-control" id="pwd2" name="pwd2" required>
					
				</div>
				<input type="hidden" name="Profile_ID" value="<?php echo $Profile_ID; ?>">
				<input type="hidden" name="whichform" value="password">
				<button type="submit" class="btn btn-success" id="savepassword">Submit</button>
			</form>
		</div>
		<div class="tab-pane" id="Notifications-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden" id="preferences-error">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span" id="preferences-error-span"></span>
			</div>
			<form action="/live/pagebuilder/components/docnow/savepatientdata.php" name="preferencesform" id="preferencesform">
				<h4>Emails</h4>
				<div class="checkbox">
					<label><input type="checkbox" name="wellness_reminder" id="wellness_reminder" value="1" <?php echo ($UserPreferences['wellness_reminder'] == 1 ? 'checked' : ''); ?> > Wellness reminders</label>
				</div>
				<h4>App Settings</h4>
				<div class="checkbox">
					<label><input type="checkbox" name="push_appointment_reminder" id="push_appointment_reminder" value="1"  <?php echo ($UserPreferences['push_appointment_reminder'] == 1 ? 'checked' : ''); ?>> Push notify appointment reminders</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="push_appointment_reschedule" id="push_appointment_reschedule" value="1"  <?php echo ($UserPreferences['push_appointment_reschedule'] == 1 ? 'checked' : ''); ?> > Push notify if appointment is rescheduled or cancelled</label>
				</div>
				<div class="checkbox">
					<label><input type="checkbox" name="push_wellness_reminder" id="push_wellness_reminder" value="1"  <?php echo ($UserPreferences['push_wellness_reminder'] == 1 ? 'checked' : ''); ?> > Push notify wellness reminders</label>
				</div>
				<input type="hidden" name="Profile_ID" value="<?php echo $Profile_ID; ?>">
				<input type="hidden" name="whichform" value="preferences">
				<button type="submit" class="btn btn-success">Submit</button>
			</form>
		</div>
		<div class="tab-pane" id="Medical-v">
			<div class="form-group">
				<label for="pwd">Medical Aid Front:</label>
				<!-- <input type="file" class="form-control" name="medical_aid_front" id="medical_aid_front"> -->
				<form id="medical-aid-front" method="post" action="/live/pagebuilder/components/docnow/uploadImages.php" class="dropzone">
					<div class="fallback">
						<input name="medical_aid_front" type="file" value="<?=$MedicalAidCards['medical_aid_front']?>"/>
						
					</div>
				</form>
			</div>
			
			<div class="form-group">
				<label for="pwd">Medical Aid Back:</label>
				<!-- <input type="file" class="form-control" name="medical_aid_back" id="medical_aid_back"> -->
				<form id="medical-aid-back" method="post" action="/live/pagebuilder/components/docnow/uploadImages.php" class="dropzone">
					<div class="fallback">
						<input name="medical_aid_back" type="file" value="<?=$MedicalAidCards['medical_aid_back']?>" />
						
					</div>
				</form>
			</div>
		</div>
	  </div>
	</div>

	<div class="clearfix"></div>
</div>
<script type="text/javascript" src="/live/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>

<script type="text/javascript">
	$('.form_date').datetimepicker({
        language:  'eg',
        weekStart: 0,
        todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
    });
</script>