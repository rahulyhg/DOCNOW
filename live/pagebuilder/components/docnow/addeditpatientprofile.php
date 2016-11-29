<?php

global $Profile_ID, $Session_ID;

session_start();

// echo "<pre>";print_r($_SESSION['proflieRegDetails']);echo "</pre>";


?>
<link rel="stylesheet" href="/live/css/dropzone.css">
<script type="text/javascript" src="/live/js/dropzone.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	// Add a new repeating section
	$('#add_field').click(function(){
	    var lastRepeatingGroup = $('.repeatingSection').last();
	    lastRepeatingGroup.clone().insertAfter(lastRepeatingGroup);
	    return false;
	});

	Dropzone.options.uploadWidget = {
	  paramName: 'file',
	  maxFilesize: 1, // MB
	  maxFiles: 1,
	  dictDefaultMessage: 'Drag an image here to upload, or click to select one',
	  headers: {
	    'x-csrf-token': document.querySelectorAll('meta[name=csrf-token]')[0].getAttributeNode('content').value,
	  },
	  acceptedFiles: 'image/*',
	  init: function() {
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
	  }
	};

});

$(function() {
		'use strict';
		var profileForm = $('#profiledata');
			/*flashErrDiv = $('.sign-in-error-div'),
			flashErrSpan = $('.sign-in-error-span'),
			dashboardUrl = $('.dashboard-url').html();*/

		$('#add').click(function(){

			profileForm.on('submit',function (e) {

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
					window.location.href = '/patients/dashboard/&Session_ID=<?=$Session_ID?>';
					/*var responseObj = $.parseJSON(response);
					console.log(responseObj);
					if (responseObj.Error_NUM && responseObj.Error_NUM > 0) {
						flashErrDiv.removeClass('hidden');
						flashErrSpan.html(responseObj.Error_STRING);
					} else if(responseObj.url != '') {
						window.location.href = responseObj.url;
					} else {
						flashErrDiv.removeClass('hidden');
						flashErrSpan.html('An error occured. Please try again');
					}
					*/
				});
			});
		});
	});
</script>
<style type="text/css">
.dropzone {
    background: white none repeat scroll 0 0;
    border: 2px dashed #0087f7;
    border-radius: 5px;
}
</style>

<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 pull-right">
		<fieldset>
			<div class="tg-editprofile tg-haslayout">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 tg-findheatlhwidth">
					<div class="row">
						<div class="tg-editimg tg-haslayout">
							<div class="tg-heading-border tg-small">
								<h3>upload photo</h3>
							</div>
							<figure class="tg-docimg">
								<!-- <img src="/live/images/dashboard/img-03.jpg" alt="image descriptio">
								<a href="#" class="tg-deleteimg"><i class="fa fa-plus"></i></a>
								<a href="#" class="tg-uploadimg"><i class="fa fa-upload"></i></a> -->
								<form id="upload-widget" method="post" action="/live/pagebuilder/components/docnow/uploadImages.php" class="dropzone">
									<div class="fallback">
										<input name="file" type="file" />
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
		<form class="tg-formeditprofile tg-haslayout" id="profiledata" action="/live/pagebuilder/components/docnow/saveprofiledata.php">
			
			<input type="hidden" name="profilepic" id="profilepic" value="">
			<?=PrintHiddenField ("Profile_ID", $Profile_ID);?>
			<div class="tg-bordertop tg-haslayout">
				<div class="tg-formsection">
					<div class="tg-heading-border tg-small">
						<h3>Basic Information</h3>
					</div>
					<div class="row">
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Name*" name="first_name" value="<?=$_SESSION['proflieRegDetails']['first_name']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Second Name" name="second_name" value="<?=$_SESSION['proflieRegDetails']['']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Last Name" name="last_name" value="<?=$_SESSION['proflieRegDetails']['last_name']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="email" placeholder="Email*" name="email" value="<?=$_SESSION['proflieRegDetails']['email']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Phone*" name="phone" value="<?=$_SESSION['proflieRegDetails']['phone']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Fax*" name="fax" value="<?=$_SESSION['proflieRegDetails']['fax']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Address*" name="address" value="<?=$_SESSION['proflieRegDetails']['address']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-6 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="text" placeholder="Skype" name="skype" value="<?=$_SESSION['proflieRegDetails']['skype']?>">
							</div>
						</div>
						<div class="col-md-4 col-sm-12 col-xs-12">
							<div class="form-group">
								<input class="form-control" type="url" placeholder="URL" name="websiteurl" value="<?=$_SESSION['proflieRegDetails']['websiteurl']?>">
							</div>
						</div>
						<!-- <div class="col-sm-12">
							<div class="tg-addfield">
								<button type="submit">
									<i class="fa fa-plus"></i>
									<span>add field</span>
								</button>
							</div>
						</div> -->
					</div>
				</div>
			</div>
			<div class="tg-bordertop tg-haslayout">
				<div class="tg-formsection">
					<div class="tg-heading-border tg-small">
						<h3>Medical History*</h3>
					</div>
					<div class="row">
						
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="repeatingSection">
								<div class="form-group">
									<textarea class="form-control" rows="5" id="medical_history" name="medical_history[]" placeholder="Medical History"></textarea>
								</div>
							</div>
						</div>
						<div class="col-sm-12">
							<div class="tg-addfield">
								<button type="submit" id="add_field">
									<i class="fa fa-plus"></i>
									<span>add field</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>			
			<button type="submit" class="tg-btn" id="add">add</button>
		</fieldset>
	</form>
</div>
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
	<aside id="tg-sidebar">
		<div class="tg-widget tg-widget-doctor">
			<figure class="tg-docprofile-img">
				<figcaption>
					<h4><?php echo $_SESSION['proflieRegDetails']['first_name'].' '.$_SESSION['proflieRegDetails']['last_name']; ?></h4>
				</figcaption>
				<span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
				<a href="#"><img alt="image description" src="/live/images/dashboard/img-01.jpg"></a>
			</figure>
		</div>
		<div class="tg-widget tg-widget-accordions">
			<h3>Quick Links</h3>
			<ul class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<li class="panel panel-default">
					<a href="reviews.html">Past appointment and review lists</a>
				</li>
				<li class="panel panel-default">
					<a href="#">Notifications - send sms and emails</a>
				</li>
				<li class="panel panel-default">
					<a href="edit-profile-patient.html">Edit Profile</a>
				</li>
			</ul>
		</div>
	</aside>
</div>
