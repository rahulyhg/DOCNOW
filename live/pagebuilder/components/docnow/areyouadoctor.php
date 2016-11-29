<?php

include_once ("modules/fusion.php");

$_Item_ID = 17;
$Language = DefaultLanguage;
$Pages = RetrieveContentPages ($_Item_ID, None, $Language, None, 1, STATUS_PUBLISHED, None, None, None, None);
$Page1 = ReadFromDB ($Pages);  

echo ProcessFusion (stripslashes ($Page1['Full_BLOB']), array ("Item_ID" => $_Item_ID, "Page" => 1, "Origin" => "Item ".$_Item_ID, "Reference_ID" => $Reference_ID, "IncludePath" => $IncludePath, "ContentType" => "text/html"), $Profile_ID, (defined ("DefaultFusionProcessor") ? DefaultFusionProcessor : None));
?>