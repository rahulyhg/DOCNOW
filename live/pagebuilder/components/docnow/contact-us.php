<?php

global $Profile_ID, $Reference_ID, $Session_ID; 

$_Item_ID = 32;
?>


<script>
	$(function() {
		'use strict';
		var 
			contactusForm = $('#contactus-form'),
			flashErrDiv = $('.sign-up-error-div'),
			flashErrSpan = $('.sign-up-error-span');

		contactusForm.on('submit',function (e) {
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
				} else if(responseObj.Session_ID) {
					
					$('#data').html('Thank you for filling in our contact form. We will be in touch as soon as possible')
				} else {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html('An error occured. Please try again');
				}

			});
		});

	});
</script>

<div id="data" >

	<div class="tg-heading-border tg-small">
		<h2>Chat with us</h2>
	</div>
	<div id='div-session-write'> </div>
	<div class="alert alert-danger sign-up-error-div hidden">
		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<span class="sign-up-error-span"></span>
	</div>
	<form class="form-refinesearch tg-haslayout" action="/live/FPM.php" method="post" id="contactus-form"> 
		<fieldset> 
			<div class="row"> 
				<div class="col-sm-6"> 
					<div class="form-group"> 
						<!-- <input class="form-control" placeholder="Name" type="text">  -->
						<? echo str_replace('type="text"', 'type="text" placeholder="Name" required', InsertFieldAttribute (RetrieveFormProfileElementByName ("Name", "", $_Item_ID, $Profile_ID, $Reference_ID),ATTRIBUTE_CLASS,"form-control")); ?>
					</div> 
				</div> 
				<div class="col-sm-6"> 
					<div class="form-group"> 
						<!-- <input class="form-control" placeholder="Email" type="email">  -->

						<? echo str_replace('type="email"', 'type="email" placeholder="Email" required', InsertFieldAttribute (RetrieveFormProfileElementByName ("Email", "", $_Item_ID, $Profile_ID, $Reference_ID),ATTRIBUTE_CLASS,"form-control")); ?>
					</div>
				</div> 
				<div class="col-sm-6"> 
					<div class="form-group"> 
						<!-- <input class="form-control" placeholder="Phone" type="text"> --> 
						<? echo str_replace('type="tel"', 'type="tel" placeholder="Phone"', InsertFieldAttribute (RetrieveFormProfileElementByName ("Phone", "", $_Item_ID, $Profile_ID, $Reference_ID),ATTRIBUTE_CLASS,"form-control")); ?>
					</div> 
				</div> 
				<div class="col-sm-6"> 
					<div class="form-group"> 
						<span class="select"> 
							<!-- <select class="group"> 
								<option>Specialty</option> 
							</select>  -->
							<? echo InsertFieldAttribute (RetrieveFormProfileElementByName ("Specialty", "", $_Item_ID, $Profile_ID, $Reference_ID),ATTRIBUTE_CLASS,"form-control"); ?>
						</span> 
					</div> 
				</div> 
				<div class="col-sm-12"> 
					<div class="form-group"> 
						<!-- <textarea class="form-control" placeholder="Message"></textarea>  -->
						<? echo str_replace('id="message"', 'id="message" placeholder="Message" required', InsertFieldAttribute (RetrieveFormProfileElementByName ("Message", "", $_Item_ID, $Profile_ID, $Reference_ID),ATTRIBUTE_CLASS,"form-control")); ?>
					</div> 
				</div> 
				<div class="col-sm-6"> 
					<?php

						PrintHiddenField ("s", $Session_ID);
						PrintHiddenField ("i", $_Item_ID);
						PrintHiddenField ("r", $Reference_ID);
						PrintHiddenField ("format", "json");
					?>
					<button type="submit" class="tg-btn">submit</button> 
				</div>
			</div> 
		</fieldset> 
	</form>
</div>