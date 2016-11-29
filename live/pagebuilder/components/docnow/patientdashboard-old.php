<?
include_once 'custom_modules/common.php';

global $Profile_ID;

$profileDetails = getProflieRegDetails($Profile_ID);


?>
<link href='/live/css/fullcalendar.css' rel='stylesheet' />
<link href='/live/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/live/js/moment.min.js'></script>
<script src='/live/js/fullcalendar.min.js'></script>
<script>

	$(document).ready(function() {
		
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			defaultDate: '<? echo date('Y-m-d');?>',
			defaultView: 'agendaWeek',
			navLinks: true, // can click day/week names to navigate views
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				var title = prompt('Event Title:');
				var eventData;
				if (title) {
					eventData = {
						title: title,
						start: start,
						end: end
					};
					$('#calendar').fullCalendar('renderEvent', eventData, true); // stick? = true
				}
				$('#calendar').fullCalendar('unselect');
			},
			editable: true,
			eventLimit: true, // allow "more" link when too many events
			events: [
				{
					title: 'All Day Event',
					start: '2016-10-01'
				},
				{
					title: 'Long Event',
					start: '2016-10-07',
					end: '2016-10-10'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2016-10-09T16:00:00'
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: '2016-10-16T16:00:00'
				},
				{
					title: 'Conference',
					start: '2016-10-11',
					end: '2016-10-13'
				},
				{
					title: 'Meeting',
					start: '2016-10-12T10:30:00',
					end: '2016-10-12T12:30:00'
				},
				{
					title: 'Lunch',
					start: '2016-10-18T12:00:00'
				},
				{
					title: 'Meeting',
					start: '2016-10-18T14:30:00'
				},
				{
					title: 'Happy Hour',
					start: '2016-10-18T17:30:00'
				},
				{
					title: 'Dinner',
					start: '2016-10-18T20:00:00'
				},
				{
					title: 'Birthday Party',
					start: '2016-10-13T07:00:00'
				},
				{
					title: 'Click for Google',
					url: 'http://google.com/',
					start: '2016-10-28'
				}
			]
		});
		
	});

</script>
<style>

	#calendar {
		max-width: 900px;
		margin: 0 auto;
	}

</style>
<div class="col-lg-9 col-md-9 col-sm-8 col-xs-12 pull-right">
	<div class="tg-dashboard tg-haslayout">
		<div class="tg-docprofilechart tg-haslayout">
			<div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 tg-findheatlhwidth">
				<div class="row">
					<div class="tg-docinfo tg-haslayout">
						<div class="tg-box">
							<div class="tg-heading-border tg-small">
								<h3>welcome, <?php echo $profileDetails['first_name']." ".$profileDetails['last_name'];?></h3>
							</div>
							<div class="tg-description">
								<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmodporia incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quisti nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consuatag.</p>
							</div>
							<div class="tg-bottominfo tg-haslayout">
								
								<div class="tg-regardsright">
									<button type="button" class="btn btn-success">Find A Doctor</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 tg-expectwidth">
			<div class="row">
				<div class="tg-support">
					<div class="tg-heading-border tg-small">
						<h3><a href="#">get support</a></h3>
					</div>
					<ul class="tg-doccontactinfo">
						<li>
							<i class="fa fa-map-marker"></i>
							<address>37 Homestead Road, Rivonia, Sandton
</address>
						</li>
						<li>
							<i class="fa fa-phone"></i>
							<span>+27 11 787 7666</span>
						</li>
						<li>
							<i class="fa fa-envelope-o"></i>
							<a href="#">info@docnow.com</a>
						</li>
						<li>
							<i class="fa fa-envelope-o"></i>
							<a href="#">suggestions@docnow.com</a>
						</li>
						<li>
							<i class="fa fa-fax"></i>
							<span>+27 11 787 7666</span>
						</li>
					</ul>
				</div>
			</div>
		</div>
		</div>
		<div class="tg-graph tg-haslayout">
			<div class="tg-profilehits">
				<div class="tg-heading-border tg-small">
					<h3>Calander</h3>
				</div>
				<!-- <img src="/live/images/Calander.JPG" /> -->
				<div id="calendar"></div>
			</div>
		</div>
		<div class="tg-docrank tg-haslayout">
			<div class="col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<div class="row">
					<div class="tg-heading-border tg-small">
						<h3>Overall Rank</h3>
					</div>
					<div class="tg-ratingbox">
						<div class="tg-stars">
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star-o"></i>
						</div>
						<strong>very good</strong>
					</div>
					<div class="tg-description">
						<p>Consectetur adipisicing elit seidodia tempor incididunt ut labore etolore magna aliqua. Ut enim ad minimaia quis nostrud exercitation. Aullamco at laboris nisi ut aliquip exea. </p>
					</div>
					<a href="#">Read More</a>
				</div>
			</div>
			<div class="col-lg-8 col-md-6 col-sm-12 col-xs-12">
				<div class="tg-costumerreview">
					<div class="tg-heading-border tg-small">
						<h3>Favorite Doctors</h3>
					</div>
					<div id="tg-reviewscrol" class="tg-reviewscrol">
						<ul class="tg-reviews">
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/dashboard/img-02.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Sean Doe</a></h3>
											</div>
											
										</div>
										<div class="tg-description">
											<p>Consectetur adipisicing elit sed do eiusmod temididunt ut labore et dolore magna aliqua.</p>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/dashboard/img-02.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Sean Doe</a></h3>
											</div>
											
										</div>
										<div class="tg-description">
											<p>Consectetur adipisicing elit sed do eiusmod temididunt ut labore et dolore magna aliqua.</p>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/dashboard/img-02.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Sean Doe</a></h3>
											</div>
											
										</div>
										<div class="tg-description">
											<p>Consectetur adipisicing elit sed do eiusmod temididunt ut labore et dolore magna aliqua.</p>
										</div>
									</div>
								</div>
							</li>
							<li>
								<div class="tg-review">
									<figure class="tg-reviwer-img">
										<a href="#"><img alt="image description" src="/live/images/dashboard/img-02.jpg"></a>
									</figure>
									<div class="tg-reviewcontet">
										<div class="comment-head">
											<div class="pull-left">
												<h3><a href="#">Sean Doe</a></h3>
											</div>
											
										</div>
										<div class="tg-description">
											<p>Consectetur adipisicing elit sed do eiusmod temididunt ut labore et dolore magna aliqua.</p>
										</div>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-lg-3 col-md-3 col-sm-4 col-xs-12">
	<aside id="tg-sidebar">
		<div class="tg-widget tg-widget-doctor">
			<figure class="tg-docprofile-img">
				<figcaption>
					<h4><?php echo $profileDetails['first_name']." ".$profileDetails['last_name'];?></h4>
					<span>Premium Account User</span>
				</figcaption>
				<span class="tg-featuredicon"><em class="fa fa-bolt"></em></span>
				<a href="#"><img alt="image description" src="/images/<?php echo $profileDetails['profilepic'];?>"></a>
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