<?php 
include('config.php'); 
if (isset($_GET['COL1']) ) { 
$COL1 = (int) $_GET['COL1']; 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "UPDATE `table3` SET  `COL1` =  '{$_POST['COL1']}' ,  `COL3` =  '{$_POST['COL3']}' ,  `COL4` =  '{$_POST['COL4']}' ,  `COL5` =  '{$_POST['COL5']}' ,  `COL6` =  '{$_POST['COL6']}' ,  `COL7` =  '{$_POST['COL7']}' ,  `COL8` =  '{$_POST['COL8']}' ,  `COL9` =  '{$_POST['COL9']}' ,  `COL10` =  '{$_POST['COL10']}' ,  `COL11` =  '{$_POST['COL11']}' ,  `COL12` =  '{$_POST['COL12']}'   WHERE `COL1` = '$COL1' "; 
mysql_query($sql) or die(mysql_error()); 
echo (mysql_affected_rows()) ? "Edited row.<br />" : "Nothing changed. <br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
$row = mysql_fetch_array ( mysql_query("SELECT * FROM `table3` WHERE `COL1` = '$COL1' ")); 
?>

<form action='' method='POST'> 
<p><b>COL1:</b><br /><input type='text' name='COL1' value='<?= stripslashes($row['COL1']) ?>' /> 
<p><b>COL3:</b><br /><input type='text' name='COL3' value='<?= stripslashes($row['COL3']) ?>' /> 
<p><b>COL4:</b><br /><input type='text' name='COL4' value='<?= stripslashes($row['COL4']) ?>' /> 
<p><b>COL5:</b><br /><input type='text' name='COL5' value='<?= stripslashes($row['COL5']) ?>' /> 
<p><b>COL6:</b><br /><input type='text' name='COL6' value='<?= stripslashes($row['COL6']) ?>' /> 
<p><b>COL7:</b><br /><input type='text' name='COL7' value='<?= stripslashes($row['COL7']) ?>' /> 
<p><b>COL8:</b><br /><input type='text' name='COL8' value='<?= stripslashes($row['COL8']) ?>' /> 
<p><b>COL9:</b><br /><input type='text' name='COL9' value='<?= stripslashes($row['COL9']) ?>' /> 
<p><b>COL10:</b><br /><input type='text' name='COL10' value='<?= stripslashes($row['COL10']) ?>' /> 
<p><b>COL11:</b><br /><input type='text' name='COL11' value='<?= stripslashes($row['COL11']) ?>' /> 
<p><b>COL12:</b><br /><input type='text' name='COL12' value='<?= stripslashes($row['COL12']) ?>' /> 
<p><input type='submit' value='Edit Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
<?php } ?> 
