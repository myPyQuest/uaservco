<?php
// connect to db
$link = mysql_connect('localhost', 'uaservco', 'X6Yx9pvr43a7');
if (!$link) {
    die('Not connected : ' . mysql_error());
}

if (! mysql_select_db('Uaservco') ) {
    die ('Can\'t use test : ' . mysql_error());
}
mysql_query("SET NAMES 'utf8'");
?>