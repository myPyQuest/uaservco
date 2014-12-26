<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: visualmode.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$doc = JFactory::getDocument();
error_reporting(0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $doc->getLanguage(); ?>" lang="<?php echo  $doc->getLanguage(); ?>" dir="<?php echo  $doc->getDirection(); ?>" >
<head>
<?php	
$this->JSNMedia->addMedia();
/** Add Scripts and StyleSheets to header of page **/
$header = array();
$header[] = $this->jsnpwrender->getHeader();
echo implode(PHP_EOL, $header);
?>
<style type="text/css">
	body{
		background:#FFF;
	}
</style>
</head>
<?php
	$body = $this->jsnpwrender->getBody(); 
?>
<body <?php echo $body->attr;?>>
	<div id="jsn-page-container" >
		<?php echo $body->html;?>
	</div>	
</body>
</html>
<?php
