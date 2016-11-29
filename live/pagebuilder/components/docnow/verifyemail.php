<?php

require_once ('modules/profile.php');
require_once ('custom_modules/common.php');
require_once ('modules/catalog.php');

global $Session_ID;

if (isset($_GET['Profile_ID']) && $_GET['Profile_ID'] > '') {
	$profileId = $_GET['Profile_ID'];
	$profileDetails = RetrieveProfileDetails ($profileId);
	
	if (empty($profileDetails)) {
		redirectToPage(ThisURL, 'Unable to retrieve your details' , 'alert-error');
	}

	if ($profileDetails['Status_NUM'] == '-2') {
		activeUserProfile($profileId);
		$userDetails = getProflieRegDetails($profileId);
		$itemId = $userDetails['doctor'] == '1' ? 37 : 35;
		$data['Pwd'] = $profileDetails['Password_STRING'];
		$data['Eml'] = $profileDetails['Email_STRING'];
		$url = RetrieveCatalogContentURL(CATALOG_ITEM, CPE, $itemId, RetrieveCatalogItemCode($itemId), DEVICE_PC);
		//$data['Dest_URL'] = $url;
		loginUser($data, $url);
	} else{
		redirectToPage(ThisURL, 'The email address has already been verified. Please try signing in directly.' , 'alert-error');
	}

} else {
	if (empty($profileDetails)) {
		redirectToPage(ThisURL, 'Unable to retrieve your details' , 'alert-error');
	}
}