<?php

include_once 'custom_modules/common.php';
include_once 'modules/globals.php';

global $Profile_ID, $Session_ID;

$profileDetails = getProflieRegDetails($Profile_ID);
$UserPreferences = getUserPreferences($Profile_ID);
$username = RetrieveProfileDetails ($Profile_ID);

$specialities = getSpecialities();
$languages = getLanguages();

$languagesArray = explode(",", $profileDetails['language']);
// debug($languagesArray);
?>
<link href="/live/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
<link rel="stylesheet" href="/live/css/bootstrap-select.css">
<script src="/live/js/bootstrap-select.js"></script>
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

		Dropzone.options.uploadWidget = {
		  paramName: 'file',
		  maxFilesize: 1, // MB
		  maxFiles: 1,
		  dictDefaultMessage: 'Drag the front and back of you medical cards here to upload, or click to select one',
		  headers: {
		    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
		  },
		  acceptedFiles: 'image/*',
		  init: function() {
		  	var thisDropzone = this;          	
            var mockFile = { name: 'existing file' };
             
            thisDropzone.options.addedfile.call(thisDropzone, mockFile);

            thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '<?=IMAGE_URL.$profileDetails['profilepic']?>'), 

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
		    formData.append('Profile_ID', '<?php echo $Profile_ID;?>');
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
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth">
		<div class="row">
			<div class="col-md-3">
				<a href="/doctors/dashboard/?Session_ID=<?=$Session_ID?>" class="tg-btn" style="width: 100%;">Dashboard</a>
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
<div  class="col-sm-12">
	<h3>Doctor Profile Setting</h3>
	<hr/>
	<div class="col-md-4"> <!-- required for floating -->
	  <!-- Nav tabs -->
	  <ul class="nav nav-tabs tabs-left sideways">
		<li class="active"><a href="#Profile-v" data-toggle="tab">Profile Image</a></li>
		<li><a href="#Basic-Info-v" data-toggle="tab">Basic Information</a></li>
		<li><a href="#Notifications-v" data-toggle="tab">Notifications Settings</a></li>
		<li><a href="#Password-v" data-toggle="tab">Passwords</a></li>
	  </ul>
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

		<div class="tab-pane" id="Basic-Info-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span"></span>
			</div>
			<div id="profiledatadiv">
				<form action="/live/pagebuilder/components/docnow/savepatientdata.php" name="profiledata" id="profiledata" required >
					<div class="form-group">
						<label for="first_name">Name:</label>
						<input type="text" class="form-control" name="first_name" id="first_name" value="<?=$profileDetails['first_name']?>" required>
					</div>
					<div class="form-group">
						<label for="email">Surname:</label>
						<input type="text" class="form-control" name="last_name" id="last_name" value="<?=$profileDetails['last_name']?>" required >
					</div>
					<div class="form-group">

						<label for="email">Address:</label>
						<input type="text" alt="Start address" name="address" id="address" placeholder="Address" autocomplete="on" required  value="<?=$profileDetails['address']?>" />
						<input type="hidden" id="lat" name="lat" value="<?=$profileDetails['lat']?>" />
						<input type="hidden" id="lng" name="lng" value="<?=$profileDetails['lng']?>" /> 
					</div>
					<!--<div class="form-group">
							<label for="email">Street Address:</label>
							<input type="text" class="form-control" id="address_1" name="address_1" value="<?=$profileDetails['address_1']?>" >
						</div>
						<div class="form-group">
							<label for="email">Street Address Line 2:</label>
							<input type="text" class="form-control" id="address_2" name="address_2" value="<?=$profileDetails['address_2']?>">
						</div>
						<div class="form-group">
							<label for="email">City:</label>
							<input type="text" class="form-control" id="city" name="city" value="<?=$profileDetails['city']?>">
						</div>
						<div class="form-group">
							<label for="email">Province/State:</label>
							<input type="text" class="form-control" id="province" name="province" value="<?=$profileDetails['province']?>" >
						</div>
						<div class="form-group">
							<label for="email">Postal/Zip Code:</label>
							<input type="text" class="form-control" id="postal_code" name="postal_code" value="<?=$profileDetails['postal_code']?>">
						</div>-->
						<div class="form-group">
							<label for="email">Country:</label>
							<select class="selectpicker" id="country_id" name="country_id">
								
								<option value="">--Select--</option>
								<? foreach ($GLOBALS['countries'] as $key => $value) :?>

									<option value="<?=$key?>" <?=($key == DefaultRegion ? 'selected="selected"' : '') ?>><?=ucwords(strtolower($value))?></option>
								<? endforeach ?>
							</select>
						</div>
						<div class="form-group">
							<label for="email">Email address:</label>
							<input type="email" class="form-control" id="email" name="email" value="<?=$username ['Login_STRING']?>" required>
						</div>
						<div class="form-group">
							<label for="cell_phone">Cellphone:</label>
							<input type="text" class="form-control" id="cell_phone" name="cell_phone" value="<?=$profileDetails['cell_phone']?>" required>
						</div>
						<div class="form-group">
							<label for="work_number">Work:</label>
							<input type="text" class="form-control" name="work_number" id="work_number" value="<?=$profileDetails['work_number']?>">
						</div>
					<div class="form-group">
						<label for="pwd">Prefered Number:</label>
						<select name="prefered_number" id="prefered_number" class="selectpicker" >
							<option value="cellphone">Cellphone</option>
							<option value="work">Work</option>
							<option value="home">Home</option>
						</select>
					</div>
					
					<div class="form-group">
		                <label for="birth_date">Date Of Birth:</label>
		                <div class="input-group date form_date col-md-5" data-date="" data-date-format="dd MM yyyy" data-link-field="birth_date" data-link-format="yyyy-mm-dd">
		                    <input class="form-control" size="16" type="text" value="<?php echo ($profileDetails['birth_date'] ? date('d F Y', strtotime($profileDetails['birth_date'])) : ''); ?>" readonly>
		                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
		               		<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
		                </div>
						<input type="hidden" id="birth_date" name="birth_date" value="<?=$profileDetails['birth_date']?>" />
			        </div>
					
					<div class="form-group">
						<label for="gender">Gender:</label>
						<select id="gender" name="gender" class="selectpicker">
							<option value="male" <?=($profileDetails['gender']== 'male' ? 'selected="selected"' : '')?>>Male</option>
							<option value="female" <?=($profileDetails['gender']== 'female' ? 'selected="selected"' : '')?>>Female</option>
						</select>
					</div>
					

					<div class="form-group">
						<label for="comment">Specialties:</label>
				
						<span class="">
							<select id="specialty" name="specialty" class="selectpicker">
								<option value="">Specialty</option>
								<?php foreach($specialities as $key => $value ):?>
									<option value="<?=$key?>" <?=($key == $profileDetails['speciality_id'] ? 'selected="selected"' : '')?>><?=$value?></option>
								<?php endforeach?>
							</select>
						</span>
					</div>
					<div class="form-group">
						<label for="comment">Laungage:</label>
				
						<span class="">
							<select class="selectpicker" name="language[]" id="language" multiple>
							<!-- <option>Language</option> -->
							<?php foreach($languages as $key => $value ):?>
								<option value="<?=$value?>" <?=(in_array($value, $languagesArray) ? 'selected="selected"' : '')?>><?=$value?></option>
							<?php endforeach?>
						</select>
						</span>
					</div>
					<div class="form-group">
						<label for="comment">Education:</label>
						<textarea class="form-control" rows="5" id="comment"></textarea>
					</div>
				  <input type="hidden" name="Profile_ID" value="<?php echo $Profile_ID; ?>">
				  <input type="hidden" name="whichform" value="profiledata">
				  <button type="submit" class="btn btn-success">Submit</button>
				</form>
			</div>
		</div>
		<div class="tab-pane" id="Notifications-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden" id="preferences-error">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span" id="preferences-error-span"></span>
			</div>
			<form  action="/live/pagebuilder/components/docnow/savepatientdata.php" name="preferencesform" id="preferencesform">
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
		<div class="tab-pane" id="Password-v">
			<div id='div-session-write'> </div>
			<div class="alert alert-danger sign-up-error-div hidden" id="password-error">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<span class="sign-up-error-span" id="password-error-span"></span>
			</div>
			<form action="/live/pagebuilder/components/docnow/savepatientdata.php" name="passwordform" id="passwordform">
				<div class="form-group">
					<label for="pwd">Old Password:</label>
					<input type="password" class="form-control" id="pwdOld" name="pwdOld">
					
				</div>
				<div class="form-group">
					<label for="pwd">New Password:</label>
					<input type="password" class="form-control" id="pwd1" name="pwd1">
					
				</div>
				<div class="form-group">
					<label for="pwd">Verify New Password:</label>
					<input type="password" class="form-control" id="pwd2" name="pwd2">
					
				</div>
				<input type="hidden" name="Profile_ID" value="<?php echo $Profile_ID; ?>">
				<input type="hidden" name="whichform" value="password">
				<button type="submit" class="btn btn-success" id="savepassword">Submit</button>
			</form>
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