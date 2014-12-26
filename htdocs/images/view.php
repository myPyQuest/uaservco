<?php 
include('config.php'); 
// Выводим HTML-заголовки:
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
echo '<title>Дозволи на перевезення наднормативних та небезпечних вантажів</title>';
echo '</head>';
echo '<body>';
echo '
<h1>
	<p align="center">
		Дозволи на перевезення наднормативних та небезпечних вантажів
	</p>
</h1>';
echo '
<p align="center">
	ДОВІДКИ ЗА ТЕЛЕФОНОМ 044 524 94 87, 044 524 59 23
</p>';
//search by COL8
echo 	'<form name="f1" method="post" action="search.php">
			<p>
				<span>Пошук за номером: </span>
				<input size="10" type="text" name="textfield">
			</p>
		</form>';
echo "<table border=1 width=\"100%\" bordercolor='#757feb'>"; 
echo "<tr>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>№ п/п</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>№ дозволу ДДАІ</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Перевізник</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Склад автопоїзду</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Державні номери автопоїзду</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Параметри автопоїзду</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Маршрут</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Термін дії дозволу</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Формула розрахунку</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Орієнтовна сума в EUR</b></font></td>"; 
echo "<td align='center' bgcolor='#74a4d2'><font color='#ffffff' face='Arial'><b>Примітки</b></font></td>"; 
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
//echo "<td><a href=edit.php?COL1={$row["COL1"]}>Edit</a></td><td><a href=delete.php?COL1={$row["COL1"]}>Delete</a></td> ";
} 
echo "</table>"; 
//echo "<a href=new.php>New Row</a>"; 
?>