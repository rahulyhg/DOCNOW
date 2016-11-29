<?php


?>

<script type="text/javascript">
	
	$(document).ready(function(){

		$.getJSON("http://ip-api.com/json/<?=$_SERVER['REMOTE_ADDR']?>", function(data) {
        	//console.log(data)
        	$('#lat1').val(data.lat);
        	$('#lng1').val(data.lon);
        });

		$('.specialty').click(function () {
	     	var value = $(this).attr("id");
	     	
         	$('#speciality1').val(value);
         	$('#specialty-form').submit();
        	
    	});

	});

</script>


<!--************************************ Specialties *************************************--> 

<form action="/search/&Session_ID=<?=$Session_ID?>" method="post" id="specialty-form">
	
	<input type="hidden" name="speciality" value="" id="speciality1">
	<input type="hidden" name="lat" id="lat1" value="">
	<input type="hidden" name="lng" id="lng1" value="">
</form>

<section class="tg-main-section tg-haslayout" style="background-color: #062e4c;">
	<div class="container">
	<div class="col-sm-10 col-sm-offset-1 col-xs-12">
		<div class="tg-theme-heading">
			<h2>Specialities</h2>
			<span class="tg-roundbox"></span>                                
			<div class="tg-description">
				<p>"Every mountain top is within reach if you just keep climbing." <i>Richard James Molloy</i></p>
			</div>
		</div>
	</div>
	<style> th:hover { background-color: #f58320; color: #fff; padding: 10px; } </style>
	<div class="row">
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">GP</th>
					</tr >
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;">
						<a href="javascript:" id="1" class="specialty"> <img src="live/images/gp.png"></a>                                       
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">GYNAECOLOGIST</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;"><a href="javascript:" class="specialty" id="4"><img src="/live/images/gynaelocologist.png"></a> </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">PHYSIOTHERAPY</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;"><a href="javascript:" id="7" class="specialty"> <img src="/live/images/physiotherapy.png"></a>                                        </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">DENTIST</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;"> <a href="javascript:" id="2" class="specialty"> <img src="/live/images/dentistry.png"></a>                                        </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">OPTOMETRY</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;"> <a href="javascript:" id="8" class="specialty"> <img src="/live/images/optometry.png"></a>                                        </td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="col-md-2">
			<table style="border: 1px solid #062e4c;padding-left: 0px;">
				<thead>
					<tr>
						<th style="border: 1px solid #062e4c;padding-left: 0px; color: #fff;">OTHER</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="border: 1px solid #062e4c;padding-left: 0px;"><a href="javascript:" id="10" class="specialty"> <img src="/live/images/cardiology.png"></a>                                        </td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</section>

