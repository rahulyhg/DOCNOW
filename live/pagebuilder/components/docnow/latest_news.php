<?php
include_once ("modules/catalog.php");


function date_compare($a, $b){
    $t1 = strtotime($a['orderDate']);
    $t2 = strtotime($b['orderDate']);
    return $t2 - $t1;
}    
$Category_ID = 23;	//Web/Content/blog
//$Category_ID = 7;		//Web/Content/News

$CatalogItems = RetrieveCatalogItems ($Category_ID, CATALOG_CONTENT, STATUS_VISIBLE, None, None, ORDER_BY_DATE, ORDER_ASC, None, None);

while ($CatalogItem = ReadFromDB ($CatalogItems)) {
    $all_rows[] = $CatalogItem;
}
$all_rows = array_reverse($all_rows); 
?>
<ul>
    <?php foreach ($all_rows as $key => $CatalogItem): 

    if($key < 2){
    list ($Language, $Version) = explode ('/', LocateBestContentLanguage (GetVar ("HTTP_ACCEPT_LANGUAGE"),$CatalogItem['Item_ID'], STATUS_PUBLISHED)."/".None);
    $Pages = RetrieveContentPages ($CatalogItem['Item_ID'], None, DefaultLanguage, $Version, 1, STATUS_PUBLISHED, None, None, None, None);
    $Page1 = ReadFromDB ($Pages); 
    $Item_ID = $Page1['Item_ID']; 
    $ItemDetails = RetrieveCatalogItemByItemID ($Item_ID);
    ?>
    <li>
        <figure class="tg-imgdoc">
            <img src="/content/<?php echo $Page1['Teaser_PIC'];?>" alt="<?php echo $ItemDetails['ItemCode_STRING'];?>" height="70px" width="70px">
            <div class="tg-img-hover">
                <a href="<? echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>"><i class="icon-zoom"></i></a>
            </div>
        </figure>
        <div class="tg-docinfo">
            <span class="tg-docname"><a href="<? echo RetrieveCatalogContentURL (CATALOG_ITEM, CPE, $Page1['Item_ID'], $ItemDetails['ItemCode_STRING']);?>"><b><?php echo stripslashes ($ItemDetails['ItemCode_STRING']); ?></b></a></span>
           
            <div class="tg-designation">
                <p><?php echo mb_strimwidth(stripslashes($Page1['Teaser_BLOB']), 0, 100, "...");?></p>
            </div>
        </div>
    </li>
<?php }
endforeach ?>
</ul>