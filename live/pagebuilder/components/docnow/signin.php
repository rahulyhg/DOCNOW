<?php
	include_once ("modules/profile.php");
	// include_once 'custom_modules/common.php';
	include_once("custom_modules/facebook_login/config.php");
	include_once("custom_modules/facebook_login/includes/functions.php");
	global $Session_ID;
?>
<meta name="google-signin-client_id" content="931911201221-n2feu53r6lr2oi3k7jfgi6cismccft82.apps.googleusercontent.com">
<script src="https://apis.google.com/js/platform.js" async defer></script>

<script>
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      /*document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';*/
        console.log('Please log ' +
        'into this app.');
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      /*document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';*/
        console.log('Please log ' + 'into Facebook.');
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1838595756377173',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.8' // use graph api version 2.8
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me',{ locale: 'en_US', fields: 'id, name, email'}, function(response) {
      console.log('Successful login for: ' + response.name);
      /*document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';*/

        var
			socialloginUrl = $('.social-profile-signin-url').html(),
			sessionWriteScript = $('.session-write-script').html(),
			flashErrDiv = $('.sign-in-error-div'),
			flashErrSpan = $('.sign-in-error-span'),

        loginData = {
			id: response.id,
			name: response.name,
			// imageUrl: profile.getImageUrl(),
			email: response.email
		};
		FB.logout();
		$.ajax({
			type: 'post',
			url: socialloginUrl,
			data: loginData
		}).done(function (response) {
			var responseObj = $.parseJSON(response);
			if (responseObj.Error_NUM && responseObj.Error_NUM > 0) {
				flashErrDiv.removeClass('hidden');
				flashErrSpan.html(responseObj.Error_Msg);
			} else if(responseObj.Error_NUM == 0) {
				$.ajax({
					type: 'post',
					url: sessionWriteScript,
					data: {
						sessionMessage: "You have been logged in successfully.",
				 		sessionMessageClass: "alert-success"
				 	}
				}).done(function (response) {
					window.location = responseObj.url + '?Session_ID=' + responseObj.session_id;
				});
			} else {
				flashErrDiv.removeClass('hidden');
				flashErrSpan.html('An error occured. Please try again');
			}
		});
    });
  }
</script>

<!--
  Below we include the Login Button social plugin. This button uses
  the JavaScript SDK to present a graphical Login button that triggers
  the FB.login() function when clicked.
-->


<div class="alert alert-danger sign-in-error-div hidden">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	<span class="sign-in-error-span"></span>
</div>
<span class="session-write-script hidden"><?='/live/session_write.php'?></span>
<span class="social-profile-signin-url hidden"><?='/live/signingoogle.php'?></span>
<span class="dashboard-url hidden"><?=ThisURL . '/patients/settings.html'?></span>
<form id='signin-form' class="tg-form-modal tg-form-signin" action="/live/signin.php">
	<?=PrintHiddenField ("Session_ID", $Session_ID);?>
	<fieldset>
		<div class="form-group">
			<input type="login" class="form-control" placeholder="Email Address" name="Eml">
		</div>
		<div class="form-group">
			<input type="password" class="form-control" placeholder="Password" name="Pwd">
		</div>
		<div class="form-group tg-checkbox">
			<label>
				<input type="checkbox" class="form-control" name="RememberMe">Remember Me	</label>
			<a class="tg-forgot-password" href="#">
				<i>Forgot Password</i>
				<i class="fa fa-question-circle"></i>
			</a>
		</div>
		<button class="tg-btn tg-btn-lg">LOGIN now</button>
	</fieldset>
</form>

<div class="row">
	<div class="col-xs-6">
		<div class="g-signin2" data-onsuccess="onSignIn">
		</div>
	</div>

	<div class="col-xs-6">
		<fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
		</fb:login-button>
	</div>
</div>

<script>
	function onSignIn(googleUser) {
		var
			socialloginUrl = $('.social-profile-signin-url').html(),
			sessionWriteScript = $('.session-write-script').html(),
			flashErrDiv = $('.sign-in-error-div'),
			flashErrSpan = $('.sign-in-error-span'),
			dashboardUrl = $('.dashboard-url').html(),
			profile = googleUser.getBasicProfile(),
			loginData = {
				id: profile.getId(),
				name: profile.getName(),
				imageUrl: profile.getImageUrl(),
				email: profile.getEmail()
			};
			
		  console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
		  console.log('Name: ' + profile.getName());
		  console.log('Image URL: ' + profile.getImageUrl());
		  console.log('Email: ' + profile.getEmail());
		  signOut();

		if (loginData) {
			// console.log(loginData);
			$.ajax({
				type: 'post',
				url: socialloginUrl,
				data: loginData
			}).done(function (response) {
				console.log(response);
				var responseObj = $.parseJSON(response);
				if (responseObj.Error_NUM && responseObj.Error_NUM > 0) {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html(responseObj.Error_Msg);
				} else if(responseObj.Error_NUM == 0) {
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: "You have been logged in successfully.",
					 		sessionMessageClass: "alert-success"
					 	}
					}).done(function (response) {
						window.location = responseObj.url + '?Session_ID=' + responseObj.session_id;
					});
				} else {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html('An error occured. Please try again');
				}
			});
		}
	}

	function signOut() {
		var auth2 = gapi.auth2.getAuthInstance();
		auth2.signOut().then(function () {
		  console.log('User signed out.');
		});
	}
	$(function() {
		'use strict';

		var 
			signinForm = $('#signin-form'),
			flashErrDiv = $('.sign-in-error-div'),
			flashErrSpan = $('.sign-in-error-span'),
			sessionWriteScript = $('.session-write-script').html(),
			dashboardUrl = $('.dashboard-url').html();

		signinForm.on('submit',function (e) {
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
				console.log(responseObj);
				if (responseObj.Error_NUM && responseObj.Error_NUM > 0) {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html(responseObj.Error_STRING);
				} else if(responseObj.url != '') {
					$.ajax({
						type: 'post',
						url: sessionWriteScript,
						data: {
							sessionMessage: "You have been logged in successfully.",
					 		sessionMessageClass: "alert-success"
					 	}
					}).done(function (response) {
						window.location.href = responseObj.url;
					});
					
				} else {
					flashErrDiv.removeClass('hidden');
					flashErrSpan.html('An error occured. Please try again');
				}

			});
		});
	});
</script>