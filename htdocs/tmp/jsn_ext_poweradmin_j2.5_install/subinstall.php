<?php
/**
 * @version    $Id: subinstall.php 15060 2012-08-14 04:56:05Z thailv $
 * @package    JSNPoweradmin
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$dirInstallTmp	   = dirname(__FILE__) . DS . 'admin' . DS . 'jsninstaller.php';
$dirInstallComponent = dirname(__FILE__) . DS . 'jsninstaller.php';
if (is_file($dirInstallTmp))
{
	include_once $dirInstallTmp;
}
elseif (is_file($dirInstallComponent))
{
	include_once $dirInstallComponent;
}

/**
 * Class for finalizing JSN Poweradmin installation.
 *
 * @package  JSNSample
 * @since    1.0.0
 */
class Com_PoweradminInstallerScript extends JSNInstallerScript
{
	
}