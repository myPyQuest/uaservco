<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: imageshow.php 14425 2012-07-26 10:52:59Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined( '_JEXEC') or die( 'Restricted access');
global $mainframe;
$option = JRequest::getVar('option');
$task = JRequest::getVar('task');
if ($option != 'image' && $task != 'editimage')
{
	JHTML::_('behavior.mootools');
}
$mainframe  = JFactory::getApplication();
$user 		= JFactory::getUser();
require_once(JPATH_COMPONENT.DS.'controller.php');
require_once(JPATH_COMPONENT.DS.'classes'.DS.'jsn_is_factory.php');
require_once(JPATH_COMPONENT.DS.'defines.imageshow.php');
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'media.php');
JTable::addIncludePath(JPATH_COMPONENT.DS.'tables');
$controllerName = JRequest::getCmd('controller');

global $objectLog,$componentVersion;
$application  			= JFactory::getApplication();
$templateName 			= $application->getTemplate();
$objUtils	  			= JSNISFactory::getObj('classes.jsn_is_utils');
$componentInfo 			= $objUtils->getComponentInfo();
$componentManifest		= json_decode($componentInfo->manifest_cache);
$componentVersion       = $componentManifest->version;
$document    = JFactory::getDocument();
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/bootstrap.min.css?v='.$componentVersion);
$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
if ($templateName == 'aplite') {
	JHTML::stylesheet('jsn_apilefix.css','administrator/components/com_imageshow/assets/css/');
}

JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_LAUNCHPAD'), 'index.php?option=com_imageshow', $controllerName == '');
JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_SHOWLISTS'), 'index.php?option=com_imageshow&controller=showlist', $controllerName == 'showlist');
JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_SHOWCASES'), 'index.php?option=com_imageshow&controller=showcase', $controllerName == 'showcase');
JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_CONFIGURATION'), 'index.php?option=com_imageshow&controller=maintenance', $controllerName == 'maintenance');
JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_HELP'), 'index.php?option=com_imageshow&controller=help', $controllerName == 'help');
JSubMenuHelper::addEntry(JText::_('JSN_IMAGESHOW_MENU_ABOUT'), 'index.php?option=com_imageshow&controller=about', $controllerName == 'about');

$objShowcaseTheme = JSNISFactory::getObj('classes.jsn_is_showcasetheme');
$objectLog 		  = JSNISFactory::getObj('classes.jsn_is_log');
//get component version

$objShowcaseTheme->enableAllTheme();

if ($controller = JRequest::getWord('controller'))
{
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

$classname	= 'ImageShowController'.$controller;
$controller	= new $classname();
$controller->execute( JRequest::getVar( 'task'));
$controller->redirect();