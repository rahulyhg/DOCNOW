<?php
include_once 'custom_modules/common.php';

$specialities = getSpecialities();

global $Session_ID;

/*debug($specialities);
*/
?>
<!-- <link rel="stylesheet" href="/live/css/bootstrap-select.css">
<script src="/live/js/bootstrap-select.js"></script> -->

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
    $("#search_banner").click(function(e) {

    	if($('#speciality').val() == ''){

    		alert('Please select the specialty you want to search for');
    		return false;
    	}

    	/*if($('#location').val() == ''){

    		alert('Please type in your location');
    		return false;
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

		     	/*console.log(status)
		     	console.log(results)*/
		     	   if (status === 'OK') {
				      if (results[1]) {
				   
				       $('#location').val(results[1].formatted_address);
				        
				      } else {
				        //window.alert('No results found');
				      }
				    } 
		     });
		    //$('#location').val(position.coords.name);
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
      	//$("#searchform").submit();
       
        e.preventDefault();
    });
});
</script>

<form class="tg-searchform" id="searchform" action="/search/?Session_ID=<?=$Session_ID?>" method="post">
	<div class="col-md-9">
		<!--Upper Row-->
		<div class="col-md-6">
			<div class="form-group">
				<span class="select">
					<select id="speciality" name="speciality"  required>
						<option value="">Speciality</option>
						<?php foreach($specialities as $key => $value ):?>
							<option value="<?=$key?>"><?=$value?></option>
						<?php endforeach?>
					</select>
				</span>
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
			
				<input type="text" alt="Start address" name="location" id="location" placeholder="Enter a location" autocomplete="on" required />
				<input type="hidden" id="lat" name="lat" value="" />
				<input type="hidden" id="lng" name="lng" value="" />  
			
			</div>
		</div>
		
	</div>
	<div class="col-md-3">
		<div class="col-md-12">
			<div class="form-group">
				<a id="search_banner" class="tg-btn tg-btn-lg" href="">Search</a>
			</div>
		</div>
	</div>
	
</form>
