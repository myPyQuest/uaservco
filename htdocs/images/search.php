<?php
include('config.php'); 
echo "<a href='view.php'>Back To Listing</a>"; 
// Выводим HTML-заголовки:
echo '<html>';
echo '<head>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">';
echo '<title>Пошук</title>';
echo '</head>';
echo '<body>';
echo '<h3>Пошук за номером дозволу</h3>';
if (isset($_POST['radiobutton']));
if (isset($_POST['textfield'])) echo "Пошук за номером = ".$_POST['textfield']."<br>";
//приведем к int
$nom = $_POST['textfield'];
// создать соединение
//mysql_connect($host,$user,$password) OR DIE("Не могу создать соединение "); 
/* выбрать базу данных. Если произойдет ошибка - вывести ее */ 
//mysql_select_db($db) or die(mysql_error()); 
// составить запрос 
$query = "SELECT * FROM `table3` WHERE COL8 = $nom"; 
// определить кодировку вывода запроса
//mysql_query("SET NAMES 'utf8'");
// Выполнить запрос. Если произойдет ошибка - вывести ее.
$q = mysql_query($query) or die(mysql_error());
// Как много нашлось таких столбцов и полей
$rows = mysql_num_rows($q);
if ($rows == 0) echo "Не знайдено відповідностей";
	else echo "Знайдено строк: ".$rows;
echo "<table border=\"1\" width=\"100%\" align=\"center\">";
	for ($c=0; $c<$rows; $c++){
		echo "<tr>";
		$f = mysql_fetch_array($q);
		echo "
		<td align=\"center\">$f[COL1]</td>
		<td align=\"center\">$f[COL8]</td>
		<td align=\"center\">$f[COL3]</td>";
		echo "
		<td align=\"center\">$f[COL4]</td>
		<td align=\"center\">$f[COL5]</td>
		<td align=\"center\">$f[COL6]</td>";
		echo "
		<td align=\"center\">$f[COL7]</td>
		<td align=\"center\">$f[COL9]</td>
		<td align=\"center\">$f[COL10]</td>";
		echo "
		<td align=\"center\">$f[COL11]</td>
		<td align=\"center\">$f[COL12]</td>";
		echo "</tr>";
	}
echo "</table>";
//
//
//
mysql_close();
?>