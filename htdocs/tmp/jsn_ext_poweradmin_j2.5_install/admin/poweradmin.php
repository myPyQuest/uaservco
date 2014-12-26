<?php
/**
 * @version     $Id: poweradmin.php 16024 2012-09-13 11:55:37Z hiepnv $
 * @package     JSNPoweradmin
 * @subpackage  item
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check
if ( ! JFactory::getUser()->authorise('core.manage', JRequest::getCmd('option', 'com_poweradmin')))
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
// Check if JSN Framework installed & enabled.
$jsnframework = JPluginHelper::getPlugin('system','jsnframework');
if(!$jsnframework){
    return JError::raiseWarning(404, JText::_('JSN_POWERADMIN_FRAMEWORK_NOT_INSTALLED'));
}

// Import joomla controller library
jimport('joomla.application.component.controller');
// Require helper file
JLoader::register('PowerAdminHelper', JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'poweradmin.php');

// Get default language
$lang = JFactory::getLanguage();
$code = $lang->getDefault();

// Install default language for backend if necessary
if( ! JSNUtilsLanguage::installed($code) AND JSNUtilsLanguage::installable($code))
{
	// Install default language
	JSNUtilsLanguage::install(array($code));

	// Load default language
	$lang->load(JRequest::getCmd('option', 'com_poweradmin'), JPATH_BASE, $code, true);
}

// Get the appropriate controller
$controller = JController::getInstance('Poweradmin');
$controller = new $controller;

// Perform the request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();
