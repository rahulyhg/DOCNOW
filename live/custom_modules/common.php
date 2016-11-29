<?
include_once 'modules/DB.php';
include_once 'modules/connect.php';
include_once 'modules/profile.php';
include_once 'modules/MIME.php';
include_once 'modules/catalog.php';
include_once 'modules/session.php';


function getProflieRegDetails($profile_id) {
	
	$SQL = "SELECT * FROM tUsers WHERE profile_id={$profile_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function debug($data){
	echo "<pre>";
	print_r($data);
	echo "</pre>";
}

function redirectToPage($url, $sessionMessage, $sessionMessageClass) {
	session_start();
	$_SESSION['sessionMessage'] = $sessionMessage;
	$_SESSION['sessionMessageClass'] = $sessionMessageClass;
	header('Location: ' . $url); 
		exit;
}

function getProfileEmergencyContact($Profile_ID){

	$SQL = "SELECT * FROM tUserEmergencyDetails WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getUserEmployer($Profile_ID){

	$SQL = "SELECT * FROM tUserEmployer WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}


function getUserPreferences($Profile_ID){

	$SQL = "SELECT * FROM tUserPreferences WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}


function getMedicalAidCards($Profile_ID){

	$SQL = "SELECT * FROM tPatientMedicalAidCards WHERE profile_id={$Profile_ID}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getSpecialities(){

	$SQL = "SELECT * FROM tSpecialty ORDER BY display_order";
	$Query = QueryDB($SQL);
	while($Result = ReadFromDB($Query)){
		$specialities [$Result['id']] = $Result['specialty_name'];

	}

	return $specialities;
}

function getLanguages(){

	$SQL = "SELECT * FROM tLanguage ORDER BY id";
	$Query = QueryDB($SQL);
	while($Result = ReadFromDB($Query)){
		$language [$Result['id']] = $Result['language'];

	}

	return $language;
}

function getSpecialityName($speciality_id){

	$SQL = "SELECT specialty_name FROM tSpecialty WHERE id={$speciality_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);
	return $Result['specialty_name'];
}

function activeUserProfile($profileId) {
	$sql = 'Update Profiles set Status_NUM = "-1" WHERE Profile_ID = "' . $profileId . '"';
    UpdateDB($sql);
}

function loginUser($data, $url) {
	$registrationURL = ThisURL . ROOT_URL . "/LSM.php";
    $data['APIKey'] = 'f0e8212b6bda3ced017c4659bd6ea90b';
    $data['Format'] = 'json';
    $responseJSON = httpPost($registrationURL, $data);
    $resp = json_decode($responseJSON);

    session_start();
	$_SESSION['sessionMessage'] = 'You have been logged in successfully';
	$_SESSION['sessionMessageClass'] = 'alert-success';

    if($resp->Error_NUM == '0'){
    	header('Location: ' . $url.'&Session_ID='.$resp->Session_ID); 
		exit;
    }
}

function httpPost($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($curl);
    curl_close($curl);
    if($response === false) {
	    return 'Curl error: ' . curl_error($curl);
	} 
    curl_close($curl);
    return $response;
}

function saveUserProfilePic($profileId, $directory) {
	$sql = 'Update tUsers set profilepic = "' . $directory . '" WHERE Profile_ID = "' . $profileId . '"';
    UpdateDB($sql);
}

function getUpcomingAppointments($profileId) {
	$SQL = 'SELECT tAppointments.first_name, tAppointments.last_name, tAppointments.title, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '"
		AND tAppointments.start_date > Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$upcomentAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$upcomentAppointments[] = $Result;
	}

	return $upcomentAppointments;	
}

function getDoctorAppointments($profileId) {
	$SQL = 'SELECT tUsers.first_name, tUsers.last_name, tAppointments.title, tAppointments.start_date, tAppointments.confirmed,tAppointments.end_date, tAppointments.id FROM tAppointments LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '"';

	$Query = QueryDB($SQL);
	$appointments = array();
	while ($Result = ReadFromDB($Query)){
		$appointments[] = $Result;
	}

	return $appointments;	
}

function getPaymentMethods() {
 	$SQL = 'SELECT * FROM tPayment_Methods';
	$Query = QueryDB($SQL);
	$paymentMethods = array();
	while ($Result = ReadFromDB($Query)){
		$paymentMethods[] = $Result;
	}

	return $paymentMethods;	
}

function getPaymentMethodById($id) {
 	$SQL = 'SELECT * FROM tPayment_Methods WHERE id = "' . $id .  '"';
	$Query = QueryDB($SQL);
	$paymentMethods = array();
	return ReadFromDB($Query);
}

function saveBooking ($data) {
	$startDate = date("Y-m-d H:i:s", strtotime($data['start_date']));
	$endDate = date("Y-m-d H:i:s", strtotime($data['end_date']));
	$patientProfileId = !empty($data['patient_profile_id']) ? $data['patient_profile_id'] : 0;

	$sql = 'INSERT INTO tAppointments (title, start_date, end_date, patient_profile_id, doctor_profile_id, date_created, payment_method, first_name, last_name, email, cell_phone) VALUES ("' . addslashes($data['title']) . '", "' . $startDate . '", "' . $endDate . '", "' . addslashes($patientProfileId) . '", "' . addslashes($data['doctor_profile_id']) . '", "' . addslashes(date("Y-m-d H:i:s")) . '", "' . addslashes($data['payment_method']) . '", "' . addslashes($data['first_name']) . '", "' . addslashes($data['last_name']) . '", "' . addslashes($data['email']) . '", "' . addslashes($data['cell_phone']) . '");';
	return InsertDB($sql, 'id');
}


function sendDoctorBookingEmail($data) {
    $mailId = 3;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $fullname = $data['first_name'] . ' ' . $data['last_name'];
    $patientEmail = $data['email'];
    $patientCellphone = $data['cell_phone'];
    $bookingDate = date('d-F-Y H:i', strtotime($data['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($data['end_date']));

    $doctorDetails = getProflieRegDetails($data['doctor_profile_id']);
    $fullDoctorName = $doctorDetails['first_name'] . ' ' . $doctorDetails['last_name'];
    $emailTo = $doctorDetails['email'];

    $htmlMessage = str_replace("**doctorName**", $fullDoctorName, $htmlMessage);
    $htmlMessage = str_replace("**patientName**", $fullname, $htmlMessage);
    $htmlMessage = str_replace("**BookingDate**", $bookingDate, $htmlMessage);
    $htmlMessage = str_replace("**patientEmail**", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("**patientCellphone**", $patientCellphone, $htmlMessage);

    SendMultipartMIMEMail ($emailTo, $emailFrom, $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function RetrieveMessage ($Mail_ID) {
    $SQL = "SELECT * FROM Mails WHERE Mail_ID = '$Mail_ID'";
    $Query = QueryDB($SQL);
    return ReadFromDB($Query);
}

function getSimilarDoctors($specialyId, $thisDoctor) {
	$SQL = "SELECT * FROM tUsers WHERE speciality_id={$specialyId} AND profile_id <> {$thisDoctor} ORDER BY RAND() limit 5";
	$Query = QueryDB($SQL);
	$similarDoctors = array();
	while ($Result = ReadFromDB($Query)){
		$similarDoctors[] = $Result;
	}

	return $similarDoctors;
}

function getPatientUpcomingAppointments($profileId) {
	$SQL = 'SELECT tUsers.profile_id, tUsers.last_name, tUsers.first_name,tUsers.profilepic, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
		WHERE tAppointments.patient_profile_id = "' . $profileId . '"
		AND tAppointments.start_date >= Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$upcomentAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$upcomentAppointments[] = $Result;
	}
	return $upcomentAppointments;	
}

function getFeaturedDoctors($specialyId) {
	$SQL = "SELECT * FROM tUsers WHERE speciality_id={$specialyId} ORDER BY RAND() limit 5";
	$Query = QueryDB($SQL);
	$featuredDoctors = array();
	while ($Result = ReadFromDB($Query)){
		$featuredDoctors[] = $Result;
	}

	return $featuredDoctors;
}

function getPatientPastAppointments($profileId) {
	$SQL = 'SELECT tUsers.profile_id, tUsers.last_name, tUsers.first_name,tUsers.profilepic, tAppointments.start_date, tAppointments.end_date, tAppointments.id FROM tAppointments
		LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
		WHERE tAppointments.patient_profile_id = "' . $profileId . '"
		AND tAppointments.start_date < Now() 
		ORDER BY tAppointments.start_date LIMIT 4';

	$Query = QueryDB($SQL);
	$pastAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$pastAppointments[] = $Result;
	}
	return $pastAppointments;	
}

function getDoctorPastAppointments($profileId) {
	$SQL = 'SELECT tAppointments.review_appointment_sent, tAppointments.first_name, tAppointments.last_name, tAppointments.title, tAppointments.start_date, tAppointments.end_date, tAppointments.id AS `appointment_id`
		FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $profileId . '"
		AND tAppointments.end_date < Now() 
		ORDER BY tAppointments.end_date LIMIT 4';

	$Query = QueryDB($SQL);
	$pastAppointments = array();
	while ($Result = ReadFromDB($Query)){
		$pastAppointments[] = $Result;
	}
	return $pastAppointments;	
}

function confirmAppointment($data) {
	$confirmation = $data['confirmation'] == 'reschedule' ? false : true;
	$resheduleStartDate = $data['reschedule_start_date'];
	$resheduleSEndDate = $data['reschedule_end_date'];
	$appointmentId = $data['appointment_id'];

	if (!$confirmation){
		$sql = 'Update tAppointments set reschedule = 1, confirmed = 1, start_date = "' . $resheduleStartDate . '", end_date = "' . $resheduleSEndDate . '" WHERE id = "' . $appointmentId . '"';
	} else {
		$sql = 'Update tAppointments set confirmed = 1, reschedule = 0 WHERE id = "' . $appointmentId . '"';
	}
	
    UpdateDB($sql);
    $appointmentDetails = getPatientAppointmentById($appointmentId);
    sendPatienBookingConfirmation($appointmentDetails);
}

function getAppointmentById($appointment_id){
	$SQL = "SELECT start_date, end_date, tAppointments.id, first_name, last_name,email, cell_phone,	tPayment_Methods.name as payment_method
			FROM tAppointments
			LEFT JOIN tPayment_Methods ON tPayment_Methods.id = tAppointments.payment_method
			 WHERE tAppointments.id={$appointment_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getReviews($appointment_id){

	$SQL = "SELECT * FROM tDocReview WHERE appointment_id={$appointment_id}";
	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return $Result;
}

function getReviewsStarAverage($profile_id){

  $SQL = "SELECT tDR.star FROM tDocReview tDR LEFT JOIN tAppointments tA ON tDR.appointment_id = tA.id WHERE tA.doctor_profile_id={$profile_id}";
  $Query = QueryDB($SQL);
  $RecordCount = CountRowsDB ($Query);

  while ($Result = ReadFromDB($Query)){

    $reviews += $Result['star'];
  }

  return $reviews/$RecordCount;
}

function sendPatienBookingConfirmation($data) {
    $mailId = 4;
    $welcomeMessageDetails = RetrieveMessage($mailId);
    $nameFrom = $welcomeMessageDetails['From_STRING'];
    $emailFrom = $welcomeMessageDetails['FromEmail_STRING'];
    $ccTo = $welcomeMessageDetails['CCTo_STRING'];
    $replyTo = $welcomeMessageDetails['ReplyTo_STRING'];
    $subject = stripslashes($welcomeMessageDetails['MailSubject_STRING']);
    $textMessage = stripslashes($welcomeMessageDetails['MailText_STRING']);
    $htmlMessage = stripslashes($welcomeMessageDetails['MailHTML_STRING']);
    $priority = $welcomeMessageDetails['Priority_NUM'];
    $patientName = $data['patientName'];
    $patientEmail = $data['email'];
    $patientCellphone = $data['cell_phone'];
    $appointmentTime = date('d-F-Y H:i', strtotime($data['start_date'])) . ' ' . date('d-F-Y H:i', strtotime($data['end_date']));

    $doctorName = $data['doctorName'];
    $paymentMethod = $data['paymentMethod'];
    $doctorSpeciality = $data['doctorSpeciality'];
    $doctorAddress = $data['doctorAddress'];

    $htmlMessage = str_replace("*||paymentMethod||*", $paymentMethod, $htmlMessage);
    $htmlMessage = str_replace("*||doctorName||*", $doctorName, $htmlMessage);
    $htmlMessage = str_replace("*||patientName||*", $patientName, $htmlMessage);
    $htmlMessage = str_replace("*||appointmentTime||*", $appointmentTime, $htmlMessage);
    $htmlMessage = str_replace("*||patientEmail||*", $patientEmail, $htmlMessage);
    $htmlMessage = str_replace("*||patientCell||*", $patientCellphone, $htmlMessage);
    $htmlMessage = str_replace("*||doctorAddress||*", $doctorAddress, $htmlMessage);
    $htmlMessage = str_replace("*||doctorSpeciality||*", $doctorSpeciality, $htmlMessage);

    SendMultipartMIMEMail ($patientEmail, $emailFrom, $ccTo, $replyTo, $subject, $textMessage, $htmlMessage, $priority);

}

function getPatientAppointmentById($appointmentId) {
	$SQL = "SELECT tUsers.address, 
					CONCAT(tUsers.first_name, \"' \"', tUsers.last_name) as doctorName, 
					CONCAT(tAppointments.first_name, \"' \"', tAppointments.last_name) as patientName, 
					tAppointments.start_date, 
					tAppointments.end_date, 
					tPayment_Methods.name as paymentMethod, 
					tAppointments.cell_phone, 
					tAppointments.email
			FROM tAppointments
			LEFT JOIN tUsers on tUsers.profile_id = tAppointments.doctor_profile_id
			LEFT JOIN tPayment_Methods ON tPayment_Methods.id = tAppointments.payment_method
			WHERE tAppointments.id = {$appointmentId}";

	$Query = QueryDB($SQL);
	return ReadFromDB($Query);	
}

/*function httpPost($url, $data) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    if($response === false) {
	    return 'Curl error: ' . curl_error($curl);
	} 
    curl_close($curl);
    return $response;
}*/

function getDoctorAppointmentsdates($profileId, $start_date, $end_date) {
    $SQL = "SELECT tAppointments.start_date FROM tAppointments 
        LEFT JOIN tUsers ON tUsers.profile_id = tAppointments.patient_profile_id
        WHERE tAppointments.doctor_profile_id = '" . $profileId . "'
        AND tAppointments.start_date BETWEEN '".$start_date." 00:00:00' AND '".$end_date." 23:59:59'";

    $Query = QueryDB($SQL);
    $appointments = array();
    while ($Result = ReadFromDB($Query)){
        $appointments[] = $Result['start_date'];
    }

    return $appointments;   
}

function daysBetween($start, $end){
   $dates = array();
   while($start <= $end){
       array_push(
           $dates,
           date(
            'Y-m-d',
            $start
           )
       );
       $start += 86400;
   }
   return $dates;
}

function createGoogleProfile($data) {
	$sqlProfile = 'SELECT * FROM Profiles WHERE Login_STRING = "' . $data['email'] . '"';

	$QueryProfile = QueryDB($sqlProfile);
	$readProfile = ReadFromDB($QueryProfile);

	if (!empty($readProfile)) {
		$profileDetails = getProflieRegDetails($readProfile['Profile_ID']);
		$isDoctor = false;
		if ($profileDetails['doctor']) {
			$isDoctor = true;
		}
		$url = $isDoctor ? '/doctors/settings.html': '/patients/settings.html';
		$loggedIn = LoginProfile($readProfile['Profile_ID']);
		$sessionId = CreateUniqueSession($readProfile['Profile_ID']);
		return array('Error_NUM' => 0, 'Error_Msg' => 'Loggin successfully.', 'session_id' => $sessionId, 'url' => $url);
	}

	if (empty($readProfile)) {
		$sqlProfile = 'INSERT INTO Profiles (Login_STRING, KeepAlive_NUM, FirstVisit_DATE, Status_NUM) VALUES ("' . $data['email']. '", "1", "' . date('Y-m-d H:i:s') . '", "-1");';
		$queryProfileId = InsertDB($sqlProfile, 'Profile_ID');
		if (!empty($queryProfileId)) {
		 	$sqlUsers = 'INSERT INTO tUsers (profile_id, first_name, last_name, email) VALUES ("' . $queryProfileId. '", "' . $data['first_name'] . '", "' . $data['last_name'] . '", "' . $data['email'] . '");';
		 	$queryUsersId = InsertDB($sqlUsers, 'id');
		 	if (empty($queryUsersId)) {
		 		return array('Error_NUM' => 1, 'Error_Msg' => 'Unable to create user at this time. Please try again');
		 	} else {
				$loggedIn = LoginProfile($queryProfileId);
				$sessionId = CreateUniqueSession($queryProfileId);
				return array('Error_NUM' => 0, 'Error_Msg' => 'User logged in successfully', 'session_id' => $sessionId, 'url' => '/patients/settings.html');
			}

		 } {
		 	return array('Error_NUM' => 1, 'Error_Msg' => 'Cannot sign you in at this time. Please try again later.');
		 }
	}
}

function checkActiveAppointment($doctorProfileId, $patientProfileId) {
	$SQL = 'SELECT * FROM tAppointments
		WHERE tAppointments.doctor_profile_id = "' . $doctorProfileId . '"
		AND tAppointments.patient_profile_id = "' . $patientProfileId . '"
		AND tAppointments.start_date <= Now() 
		AND tAppointments.end_date >= Now() LIMIT 1';

	$Query = QueryDB($SQL);
	$Result = ReadFromDB($Query);

	return !empty($Result);
}
?>