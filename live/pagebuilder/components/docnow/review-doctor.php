<?php 

/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/
include_once 'custom_modules/common.php';

//debug($_GET);

$profileDetails = getProflieRegDetails($_GET['d']);
$appointment = getAppointmentById($_GET['a']);

$reviews = getReviews($_GET['a']);

?>
<script type="text/javascript">
	$(function() {
		'use strict';
		var 
			reviewform = $('#reviewform'),
			flashErrDiv1 = $('.error-div'),
			flashErrSpan1 = $('.error-span');

			reviewform.on('submit',function (e) {
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
					//console.log(response);
					var responseObj = $.parseJSON(response);
					if (responseObj.error) {
						flashErrDiv1.removeClass('hidden');
						flashErrSpan1.html(responseObj.message);
					} else {
						
						$('#review_div').html('');
						flashErrDiv1.removeClass('hidden');
						flashErrSpan1.html(responseObj.message);
					}

				});
			});
		});
</script>
<div class="col-md-8">
	  <!-- Tab panes -->
	<div class="tab-content">
		<h4>Reviewing the appointment you had with Dr.  <?php echo $profileDetails['first_name']." ".$profileDetails['last_name'];?> on <?=date('d F Y @ H:i:s', strtotime($appointment['start_date']))?></h4>
		<div id='div-session-write'> </div>
		<div class="alert alert-danger error-div hidden">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<span class="error-span"></span>
		</div>
		<div id="review_div">											
			<form action="/live/pagebuilder/components/docnow/savereview.php" name="reviewform" id="reviewform">
				<div class="form-group">
					<label for="review-points">Rating:</label>
					<select name="star" id="star" required>
						<option value="">--Select--</option>
						<option value="1" <?=($reviews['star'] == 1 ? 'selected="selected"' : '')?> >1 Star</option>
						<option value="2" <?=($reviews['star'] == 2 ? 'selected="selected"' : '')?> >2 Stars</option>
						<option value="3" <?=($reviews['star'] == 3 ? 'selected="selected"' : '')?> >3 Stars</option>
						<option value="4" <?=($reviews['star'] == 4 ? 'selected="selected"' : '')?> >4 Stars</option>
						<option value="5" <?=($reviews['star'] == 5 ? 'selected="selected"' : '')?> >5 Stars</option>
					</select>
				</div>
				<div class="form-group">
					<label for="email">Comment:</label>
					<textarea name="comment" id="comment" class="form-control" rows="5" required><?=$reviews['comment'] ?></textarea>
				</div>
			  <input type="hidden" name="doctorprofile_id" value="<?php echo $_GET['d'];?>">
			  <input type="hidden" name="appointment_id" value="<?php echo $_GET['a']; ?>">
			  <button type="submit" class="btn btn-success" id="savereview" >Submit</button>
			</form>
		</div>
	</div>
</div>