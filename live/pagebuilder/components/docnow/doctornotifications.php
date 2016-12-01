<?php

include_once 'custom_modules/common.php';
include_once 'flash_message.php';

global $Profile_ID;
global $Session_ID;

$notifications = loadDoctorNotifications($Profile_ID);

?>
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
	<h3>Doctor Notifications</h3>
	<hr/>
	<div class="container">
		<?php if (!empty($notifications)) : ?>
			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
				<?php foreach ($notifications as $key => $notification) :?>
					<?php 
						$expanded = $key == 0 ? 'true' : 'false';
						$collapse = $key == 0 ? 'in' : '';
					?>
					<div class="panel panel-default">
					  <div class="panel-heading" role="tab" id="heading<?=$key?>">
					    <h4 class="panel-title">
					      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key?>" aria-expanded="<?=$expanded?>" aria-controls="collapse<?=$key?>">
					        <?=$notification['patientName']?> <?=$notification['created']?>
					      </a>
					    </h4>
					  </div>
					  <div id="collapse<?=$key?>" class="panel-collapse collapse <?=$collapse?>" role="tabpanel" aria-labelledby="heading<?=$key?>">
					    <div class="panel-body">
					      <?=$notification['message']?>
					    </div>
					  </div>
					</div>
				<?php endforeach; ?>
			</div>
	<?php else: ?>
			No notifications found.
	<?php endif;?>
	</div>
</div>
