<?php

/*error_reporting(E_ALL);
ini_set('display_errors', 1)*/;

global $Session_ID;
global $Section;
global $BaseCategory;
global $UserStatus;
global $Filename, $UserStatus;

/*echo "<pre>";print_r($_SERVER);echo "</pre>";*/
$BaseCategory = 6;
$Categories = RetrieveCatalogCategories ($BaseCategory, None, STATUS_VISIBLE, ORDER_BY_PREDEF, None, None, None);
/*print_r("<pre>");
print_r($folders);*/
?>
<strong class="logo">
	<a href="/?Session_ID=<?=$Session_ID?>"><img src="/live/images/logo.png" alt="image description"></a>
</strong>
<nav id="tg-nav" class="tg-nav">
<div class="navbar-header">
	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#tg-navigation" aria-expanded="false">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
</div>
<div class="collapse navbar-collapse" id="tg-navigation">
	<ul>
	<li><a href="/?Session_ID=<?=$Session_ID?>">Home</a></li>
	<?php
	$listpracticeUrl = ThisURL . '/doctor/list-your-practice.html';
	while ($Category = ReadFromDB ($Categories)) {
		echo '<li><a href="'. RetrieveCatalogContentURL (CATALOG_CATEGORY, CPE, $Category['Category_ID'],$Category['CategoryDescription_STRING'], DEVICE_PC).'&Session_ID='.$Session_ID.'">'.($Category['TeaserTitle_STRING'] ? stripslashes ($Category['TeaserTitle_STRING']) : stripslashes ($Category['CategoryDescription_STRING'])).'</a></li>';
	}
	?>
		<li><?php echo ($UserStatus == PROFILE_REGISTERED ? '<a href="/live/logout.php?s='.$Session_ID.'&next=/about-us/&prev='.ThisURL.$_SERVER['REQUEST_URI'].'"> Logout</a>' : '<a href="#" data-toggle="modal" data-target=".tg-user-modal">Sign In/Register</a>') ?></li>
		<li><a href="<?=$listpracticeUrl?>" class="tg-btn">List your practise</a></li>
			
	</ul>
</div>
</nav>