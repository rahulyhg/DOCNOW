<?php
include_once("inc/facebook.php"); //include facebook SDK
######### Facebook API Configuration ##########
$appId = '1838595756377173'; //Facebook App ID
$appSecret = 'f93f228790a192094b164df18381d37a'; // Facebook App Secret
//$homeurl = 'http://localhost/facebook_login_with_php/';  //return to home
$homeurl = '/';
$fbPermissions = 'email';  //Required facebook permissions

//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret

));
$fbuser = $facebook->getUser();
?>