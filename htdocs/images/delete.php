<?php 
include('config.php'); 
$COL1 = (int) $_GET['COL1']; 
//echo $COL1
mysql_query("DELETE FROM `table3` WHERE `COL1` = '$COL1' ") ; 
echo (mysql_affected_rows()) ? "Row deleted.<br /> " : "Nothing deleted.<br /> "; 
?> 

<a href='list.php'>Back To Listing</a>