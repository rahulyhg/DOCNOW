<?php
include_once 'custom_modules/common.php';

global $_PAGE_TITLE;

$specialities = getSpecialities();
$languages = getLanguages();

//debug($_POST);

$_PAGE_TITLE = $specialities[$_POST['speciality']];
?>
<link rel="stylesheet" href="/live/css/bootstrap-select.css">
<script src="/live/js/bootstrap-select.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script>
function initialize() {


	var defaultBounds = new google.maps.LatLngBounds(
		new google.maps.LatLng(40.802089, -124.163751)
		);

	var input = document.getElementById('location');

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


$(document).ready(function() {

	 $("#reset").click(function(e) {

	 	/*alert('test')*/
	 	/*
	 	$('#location').val(' ');
	 	$('#lat').val(' ');
	 	$('#lng').val(' ');
	 	$('#speciality').val(' ');
	 	$('#gender').val(' ');
	 	$('#rate').val(' '); 
	 	$('#language').val(' ');*/
	 	$('#speciality').removeAttr('selected');
	 	$("#searchform").find('input:text, input:password, input:file, select, textarea').val('');
    	$("#searchform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
	 });

    $("#search").click(function(e) {

    	/*if($('#speciality').val() == ''){

    		alert('Please select a speciality')
    		return false
    	}*/

		if (navigator.geolocation) {

	        	navigator.geolocation.getCurrentPosition(showPosition, showError);

		    } else { 
		       alert("Geolocation is not supported by this browser.");
		    }

		    function showPosition(position) {

		    	console.log(position)
		    	 var geocoder = new google.maps.Geocoder;
		    	 var latlng = {lat: position.coords.latitude, lng: position.coords.longitude};
			     geocoder.geocode({'location': latlng}, function(results, status) {

			     	console.log(status)
			     	//console.log(results[1].formatted_address)
			     	var address = '';
		     	   if (status === 'OK') {
				     
				       address = results[1].formatted_address;
				        
				    }
					$('#location').val(address);
			     });
			    // alert($('#location').val())
			    $('#lat').val(position.coords.latitude);
	        	$('#lng').val(position.coords.longitude);
	        	$("#searchform").submit();

			}

			function showError(error) {
				console.log(error.code)
			    switch(error.code) {
			        case error.PERMISSION_DENIED:
			           //alert("User denied the request for Geolocation.");
				           
			           if($('#location').val() == ''){
			           		alert('Please enter  your location')
			           		return false;
			           }else{
			           		$("#searchform").submit();
			           }	
			            break;
			        case error.POSITION_UNAVAILABLE:
			           //alert("Location information is unavailable.");
			           	$("#searchform").submit();
			            break;
			        case error.TIMEOUT:
			            //alert("The request to get user location timed out.");
			            $("#searchform").submit();
			            break;
			        case error.UNKNOWN_ERROR:
			            //alert("An unknown error occurred.");
			            $("#searchform").submit();
			            break;
			    }

			   
			}
			e.preventDefault();
		});
	});

</script>

<div class="tg-refinesearcharea">
	<div class="tg-heading-border tg-small">
		<h2>advanced search</h2>
	</div>
	<form class="form-refinesearch tg-haslayout" method="post" id="searchform" action="<?=$_SERVER['REQUEST_URI']?>">
		<fieldset>
			<div class="row">
				<div class="col-sm-2">
					<div class="form-group">
						<div class="form-group">
							<span class="">
								<select id="speciality" name="speciality" class="selectpicker">
									<option value="">Specialty</option>
									<?php foreach($specialities as $key => $value ):?>
										<option value="<?=$key?>" <?=($key == $_POST['speciality'] ? 'selected="selected"' : '')?>><?=$value?></option>
									<?php endforeach?>
								</select>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<div class="form-group">
							<span class="">
								<!-- <select class="group">
									<option>Location</option>
								</select> -->
								<input type="text" alt="Start address" name="location" id="location" placeholder="Enter a location" value="<?=$_POST['location']?>" />
								<input type="hidden" id="lat" name="lat" value="<?=$_POST['lat']?>" />
								<input type="hidden" id="lng" name="lng" value="<?=$_POST['lng']?>" />  
								<div id="type-selector" class="controls hidden">
						          <input type="radio" name="type" id="changetype-all" checked="checked">
						        </div>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<div class="form-group">
							<span class="">
								<select class="selectpicker" id="gender" name="gender">
									<option value="">Gender</option>
									<option value="male" <?=($_POST['gender'] == 'male'? 'selected="selected"' : '')?> >Male</option>
									<option value="female" <?=($_POST['gender'] == 'female'? 'selected="selected"' : '')?> >Female</option>
								</select>
							</span>
						</div>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select class="selectpicker" name="rate" id="rate">
							<option value="">Rating</option>
							<option value="1" <?=($_POST['rate'] == '1'? 'selected="selected"' : '')?>>1 Star</option>
							<option value="2" <?=($_POST['rate'] == '2'? 'selected="selected"' : '')?>>2 Stars</option>
							<option value="3" <?=($_POST['rate'] == '3'? 'selected="selected"' : '')?>>3 Stars</option>
							<option value="4" <?=($_POST['rate'] == '4'? 'selected="selected"' : '')?>>4 Stars</option>
							<option value="5" <?=($_POST['rate'] == '5'? 'selected="selected"' : '')?>>5 Stars</option>
							<option value="greater than 5" <?=($_POST['rate'] == 'greater than 5'? 'selected="selected"' : '')?>>&gt; 5 Stars</option>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<select class="selectpicker" name="language" id="language" >
							<option value="">Language</option>
							<?php foreach($languages as $key => $value ):?>
								<option value="<?=$value?>" <?=($value == $_POST['language'] ? 'selected="selected"' : '')?>><?=$value?></option>
							<?php endforeach?>
						</select>
					</div>
				</div>
				<div class="col-sm-2">
					<div class="form-group">
						<button type="submit" class="tg-btn" id="search">search</button>
					</div>
				</div>
				
			<!-- 	<div class="col-sm-6">
					<div class="tg-doclisthead">
						<div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<ul class="tg-listing-views pull-left">
									<li><a href="#"><i class="fa fa-th-large"></i></a></li>
									<li class="active"><a href="#"><i class="fa fa-th-list"></i></a></li>
								</ul>
							</div>
						</div>
					</div>
				</div> -->
				<!-- <div class="col-sm-6">
					<button type="reset" class="tg-btn-reset" id="reset">
						<i class="fa fa-rotate-left"></i>
						<span>Reset fillter</span>
					</button>
				</div> -->
			</div>
		</fieldset>
	</form>
</div>