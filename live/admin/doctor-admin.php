<!-- <link rel="stylesheet" type="text/css" href="/live/admin/estyle.css" media="screen" /> -->
<?php
//--- Disable errors
ini_set("display_errors", "off");

//--- ENVENT Source Code (ESC)
//--- First Created on Wednesday, May 14 2003 by John Ginsberg
//--- For ENSIGHT

//--- Module Name: cache-admin.php
//--- Contains: None

//--- Modified by:
//--- Modified by:

//--- Description:f
//--- Administration for Ensight's content caching system

//--- Permissions:
//--- ALLOW_ALL

//----------------------------------------------------------------

if (!defined ("DB_INCLUDED")) 			{ include_once ("../modules/DB.php"); }
if (!defined ("CONNECT_INCLUDED")) 		{ include_once ("../modules/connect.php"); }
if (!defined ("UTILS_INCLUDED")) 		{ include_once ("../modules/utils.php"); }
if (!defined ("DB_FUNCS_INCLUDED")) 	{ include_once ("../modules/db-functions.php"); }
if (!defined ("CATALOG_INCLUDED")) 		{ include_once ("../modules/catalog.php"); }
if (!defined ("DATE_INCLUDED")) 		{ include_once ("../modules/date.php"); }
if (!defined ("PROFILE_INCLUDED")) 		{ include_once ("../modules/profile.php"); }
if (!defined ("SESSION_INCLUDED")) 		{ include_once ("../modules/session.php"); }
if (!defined ("GLOBALS_INCLUDED")) 		{ include_once ("../modules/globals.php"); }
//include_once ("../custom_modules/common.php");

global $Session_ID, $Profile_ID;

define ("ITEMS_PER_PAGE", 10);
define ("HighlightColor", "#CCFFCC");

//--- Get the user's session ID
if (($Session_ID) && (!$Profile_ID)) {
	$Profile_ID = LocateSession ($Session_ID);
}


if (!$Profile_ID) {
	Redirect ("index.php?Dest_URL=main.html?Message=".urlencode ("You have been logged out. Please login again to continue using this system"));
}


function debug($data){
  echo "<pre>";
  print_r($data);
  echo "</pre>";
}


function CorrectStart ($Count) {
//--- Checks to see if the current page is not past the last page in the result set

	global $Start;

	if (($Count) && (RetrieveCatalogCurrentPage ($Start, ITEMS_PER_PAGE) > RetrieveCatalogPageCount ($Count, ITEMS_PER_PAGE))) {
		$Start = (RetrieveCatalogPageCount ($Count, ITEMS_PER_PAGE) - 1) * ITEMS_PER_PAGE;
	}

}
 
function RetrieveDrList ($SortBy, $OrderBy, $Start, $items_per_page) {

	$SQL = "SELECT * FROM tUsers WHERE doctor=1";
	$SQL .=" ORDER BY $SortBy $OrderBy ".LimitQueryDB ($Start, $items_per_page);
  echo $SQL;
	$Query = QueryDB($SQL);

/*  SELECT * FROM tUsers WHERE doctor=1 ORDER BY first_name last_name -99 LIMIT 0, 10
*/
	return $Query;

}

function RetrieveDrCount () {
  
	$SQL = "SELECT * FROM tUsers WHERE doctor=1";
	$Query = QueryDB($SQL);
	$DrListCount = CountRowsDB ($Query);
	return $DrListCount;

}
//--- Set header custom definitions
  $SubTitle = "Cache Administration";
  $HasFocus = "";
  $HeadScript = "";
  $NavBar = array ("Doctors", true);

  //--- Include display functions
  include_once (ADMIN_FILES."/display.php");
  include_once (ADMIN_FILES."/header.php");


  //--- Start layout
  PrintComments ("<p align=\"justify\"><big>Doctors Manager</big><br />Use this page to maintain Doctors information</p>", "", DO_BREAK, NO_BREAK);

  //--- Set defaults
  if (!$Start) {
  	$Start = 0;
  }

  if (!$SortBy) {
  	$SortBy = "first_name, last_name"; 
    $OrderBy = 'ASC';
  }

  $DrList = RetrieveDrList ($SortBy, $OrderBy, $Start, ITEMS_PER_PAGE);
  $DrListCount = RetrieveDrCount ();

   echo $DrListCount;

   while ($Doctors = ReadFromDB ($DrList)) {

   debug($Doctors);

  }

  ?>
  <script language="javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

<?
  //--- Include display functions
include_once (ADMIN_FILES."/footer.php");
?>
