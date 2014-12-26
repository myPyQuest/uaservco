<?php 
include('config.php'); 
if (isset($_POST['submitted'])) { 
foreach($_POST AS $key => $value) { $_POST[$key] = mysql_real_escape_string($value); } 
$sql = "INSERT INTO `table3` ( `COL1` ,  `COL3` ,  `COL4` ,  `COL5` ,  `COL6` ,  `COL7` ,  `COL8` ,  `COL9` ,  `COL10` ,  `COL11` ,  `COL12`  ) VALUES(  '{$_POST['COL1']}' ,  '{$_POST['COL3']}' ,  '{$_POST['COL4']}' ,  '{$_POST['COL5']}' ,  '{$_POST['COL6']}' ,  '{$_POST['COL7']}' ,  '{$_POST['COL8']}' ,  '{$_POST['COL9']}' ,  '{$_POST['COL10']}' ,  '{$_POST['COL11']}' ,  '{$_POST['COL12']}'  ) "; 
mysql_query($sql) or die(mysql_error()); 
echo "Added row.<br />"; 
echo "<a href='list.php'>Back To Listing</a>"; 
} 
?>

<form action='' method='POST'> 
<p><b>COL1:</b><br /><input type='text' name='COL1'/> 
<p><b>COL3:</b><br /><input type='text' name='COL3'/> 
<p><b>COL4:</b><br /><input type='text' name='COL4'/> 
<p><b>COL5:</b><br /><input type='text' name='COL5'/> 
<p><b>COL6:</b><br /><input type='text' name='COL6'/> 
<p><b>COL7:</b><br /><input type='text' name='COL7'/> 
<p><b>COL8:</b><br /><input type='text' name='COL8'/> 
<p><b>COL9:</b><br /><input type='text' name='COL9'/> 
<p><b>COL10:</b><br /><input type='text' name='COL10'/> 
<p><b>COL11:</b><br /><input type='text' name='COL11'/> 
<p><b>COL12:</b><br /><input type='text' name='COL12'/> 
<p><input type='submit' value='Add Row' /><input type='hidden' value='1' name='submitted' /> 
</form> 
