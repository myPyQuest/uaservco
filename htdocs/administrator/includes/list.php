<?php 
include('config.php'); 
echo "<table border=1 width=\"100%\" >"; 
echo "<tr>"; 
echo "<td align='center' bgcolor='silver'><b>№ п/п</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>№ дозволу ДДАІ</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Перевізник</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Склад автопоїзду</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Державні номери автопоїзду</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Параметри автопоїзду</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Маршрут</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Термін дії дозволу</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Формула розрахунку</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Орієнтовна сума в EUR</b></td>"; 
echo "<td align='center' bgcolor='silver'><b>Примітки</b></td>"; 
echo "</tr>"; 
$result = mysql_query("SELECT * FROM `table3`") or trigger_error(mysql_error()); 
while($row = mysql_fetch_array($result)){ 
foreach($row AS $key => $value) { $row[$key] = stripslashes($value); } 
echo "<tr>";  
echo "<td align='center'>" . nl2br( $row['COL1']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL8']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL3']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL4']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL5']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL6']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL7']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL9']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL10']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL11']) . "</td>";  
echo "<td align='center'>" . nl2br( $row['COL12']) . "</td>";  
echo "<td><a href=edit.php?COL1={$row["COL1"]}>Edit</a></td><td><a href=delete.php?COL1={$row["COL1"]}>Delete</a></td> ";
} 
echo "</table>"; 
echo "<a href=new.php>New Row</a>"; 
?>