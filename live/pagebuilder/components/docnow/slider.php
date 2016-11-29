<?php
/*error_reporting(E_ALL);
ini_set('display_errors', 1);*/


$Category_ID = 12;

$Query = RetrieveCatalogItems ($Category_ID, array(CATALOG_LINK,CATALOG_CONTENT,CATALOG_SHORTCUT), STATUS_VISIBLE, None, None, ORDER_BY_PREDEF, None, None, None);

?>
<div class="carousel-inner" role="listbox">
 
 <?php
 	$i=0;
  	while($Result = ReadFromDB($Query)){

  		$_Item_ID = $Result['Item_ID'];

  		switch ($Result['ItemType_CHAR']) {

  			case 'C':
  				$Status = STATUS_PUBLISHED;
				list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"), $_Item_ID, STATUS_PUBLISHED)."/".None);
				//--- Get Content
				$Pages = RetrieveContentPages ($_Item_ID, None, $Language, $Version, $_Start + 1, $Status, None, None, None, None);
				$Page1 = ReadFromDB ($Pages);

				
				$image = ThisURL."/content/".($Page1['Teaser_PIC'] ? $Page1['Teaser_PIC'] : $Page1['Full_PIC']);
				$title = ($Page1['TeaserTitle_STRING'] ? stripslashes ($Page1['TeaserTitle_STRING']) : stripslashes ($Page1['FullTitle_STRING']));
				$desciption = ($Page1['Teaser_BLOB'] ? stripslashes ($Page1['Teaser_BLOB']) : '');
  				break;
  			case 'S':
  				include_once 'modules/shortcuts.php';
  				$ShortcutDetail = RetrieveShortcutDetails ($_Item_ID, None);

  				$image = ThisURL."/content/".$ShortcutDetail['Teaser_PIC'];
  				$title = $ShortcutDetail['TeaserTitle_STRING'];
  				$desciption = $ShortcutDetail['Teaser_BLOB'];
  				break;
  			
  			default:

				include_once 'modules/links.php';
  				$LinkDetail = RetrieveLinkDetails ($_Item_ID);

  				$image = ThisURL."/content/".$LinkDetail['Teaser_PIC'];
  				$title = $LinkDetail['TeaserTitle_STRING'];
  				$desciption = $LinkDetail['Teaser_BLOB'];


  				break;
  		}

		
?>
		<div class="item <?php echo ($i==0 ? 'active' : ''); ?>">
			<img src="<?php echo $image; ?>">
			<div class="carousel-caption">
				<h2><?php echo $title; ?></h2>
				<p><?php echo $desciption;?></p>
			</div>
		</div>
<?php
$i++;
	}
?>
  
</div>