<?php
/* переменные доступа к базе */
$host = "localhost";
$user = "root";
$password = "";
$db = "test";
/* создать соединение */ 
mysql_connect($host,$user,$password) OR DIE("Не могу создать соединение "); 
/* выбрать базу данных. Если произойдет ошибка - вывести ее */ 
mysql_select_db($db) or die(mysql_error()); 
/* составить запрос*/ 
$query = "SELECT * FROM `table3`"; 
/* определить кодировку вывода запроса*/
mysql_query("SET NAMES 'utf8'");
/* Выполнить запрос. Если произойдет ошибка - вывести ее. */ 
$q = mysql_query($query) or die(mysql_error());
/* Как много нашлось таких столбцов и полей*/ 
$rows = mysql_num_rows($q);
$fields = mysql_num_fields($q);
//search by COL1
echo 	"<form name=\"form1\" method=\"post\" action=\"search.php\">
		<p>
		<span>Пошук за номером: </span>
		<input name=\"radiobutton\" type=\"radio\" value=\"=\">=
		<input name=\"radiobutton\" type=\"radio\" value=\">\">>
		<input name=\"radiobutton\" type=\"radio\" value=\"<\"><
		<input size=\"10\" type=\"text\" name=\"textfield\">
		</p>";
/*------------------------------add new fields--------------------------------*/

// Выводим заголовок таблицы:
echo "<table border=\"1\" width=\"100%\" align=\"center\">";
	for ($c=0; $c<$rows; $c++){
		echo "<tr>";
		$f = mysql_fetch_array($q);
		echo "
		<td align=\"center\">$f[COL1]</td>
		<td align=\"center\">$f[COL2]</td>
		<td align=\"center\">$f[COL3]</td>";
		echo "
		<td align=\"center\">$f[COL4]</td>
		<td align=\"center\">$f[COL5]</td>
		<td align=\"center\">$f[COL6]</td>";
		echo "
		<td align=\"center\">$f[COL7]</td>
		<td align=\"center\">$f[COL8]</td>
		<td align=\"center\">$f[COL9]</td>";
		echo "
		<td align=\"center\">$f[COL10]</td>
		<td align=\"center\">$f[COL11]</td>
		<td align=\"center\">$f[COL12]</td>";
		echo "</tr>";
	}
echo "</table>";
/*for ($c=0; $c<$number; $c++)
 {
 $f = mysql_fetch_array($q);
 echo "$f[COL1] $f[COL2] $f[COL3] $f[COL4] <br>";
 }
// выводим на страницу сайта заголовки HTML-таблицы
//echo '</pre>'; 
// выводим в HTML-таблицу все данные клиентов из таблицы MySQL 
while($data = mysql_fetch_array($res)){ echo " } 
echo '	<table border="1" width=100%>
	<tbody>
	<tr>
	<th>Имя</th>
	<th>Телефон</th>
	<th>E-Mail</th>
	<th>Имя</th>
	<th>Телефон</th>
	<th>E-Mail</th>
	<th>Имя</th>
	<th>Телефон</th>
	<th>E-Mail</th>
	<th>Имя</th>
	<th>Телефон</th>
	<th>E-Mail</th>
	</tr>
	<tr>
	<td>" . $data['COL1'] . "</td>
	<td>" . $data['COL2'] . "</td>
	<td>" . $data['COL3'] . "</td>
	<td>" . $data['COL4'] . "</td>
	<td>" . $data['COL5'] . "</td>
	<td>" . $data['COL6'] . "</td>
	<td>" . $data['COL7'] . "</td>
	<td>" . $data['COL8'] . "</td>
	<td>" . $data['COL9'] . "</td>
	<td>" . $data['COL10'] . "</td>
	<td>" . $data['COL11'] . "</td>
	<td>" . $data['COL12'] . "</td>
	</tr>
	</tbody>
	</table>
	<pre>
	';
*/
/* Закрыть соединение */ 
mysql_close();
?>