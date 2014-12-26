<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: defines.php 16037 2012-09-14 05:08:42Z hiepnv $
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

//Define constants for about & update page
define('JSN_POWERADMIN_EDITION', '');
define('JSN_POWERADMIN_DOC_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-docs.zip');
define('JSN_POWERADMIN_INFO_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-poweradmin.html');
define('JSN_POWERADMIN_UPDATE_LINK', 'index.php?option=com_poweradmin&amp;view=update');
define('JSN_POWERADMIN_REVIEW_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-poweradmin-on-jed.html');


define('JSN_POWERADMIN_DEFINED', true);
define('JSN_POWERADMIN_PATH', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_poweradmin');
define('JSN_POWERADMIN_STYLE_URI', JURI::root(true) . '/administrator/components/com_poweradmin/assets/css/');
define('JSN_POWERADMIN_SITE_PATH', JPATH_ROOT . DS . 'components' . DS . 'com_poweradmin');
define('JSN_POWERADMIN_STYLE_URI_SITE', JURI::root().'components/com_poweradmin/assets/css/');
define('JSN_POWERADMIN_LIB_JSNJS_URI_SITE', 		JURI::root().'components/com_poweradmin/assets/javascripts/joomlashine/');
define('JSN_POWERADMIN_LIB_PATH', JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_poweradmin' . DS . 'libraries' . DS . 'jJoomlashine');
define('JSN_POWERADMIN_LIB_JSNJS_URI', JURI::root() . 'administrator/components/com_poweradmin/assets/js/joomlashine/');
define('JSN_POWERADMIN_LIB_JS_URI', JURI::root() . 'administrator/components/com_poweradmin/assets/js/');
define('JSN_POWERADMIN_IMAGES_URI', JURI::root() . 'administrator/components/com_poweradmin/assets/images/');
define('JSN_POWERADMIN_PLUGIN_ADMINBAR_JS_URI', JURI::root() . 'plugins/system/jsnpoweradmin/assets/js/');
//define('JSN_POWERADMIN_PLUGIN_CLASSES', JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'jsnpoweradmin' . DS . 'jsnpoweradmin');
define('JSN_POWERADMIN_TEMPLATE_PATH', JPATH_ROOT . DS . 'templates');
define('JSN_PATH_RENDER_COMPONENT_LAYOUT', JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_poweradmin' . DS . 'helpers' . DS . 'html' . DS . 'layouts' . DS);

define('JSN_POWERADMIN_IDENTIFY_NAME', 'ext_poweradmin');


define('JSN_FRAMEWORK_ASSETS', JURI::root(true) . '/plugins/system/jsnframework/assets');




//LIST LANGUAGES SUPPORT
global $languages;
$languages[] = array ('code'  => 'en-GB', 'title' => 'English');
$languages[] = array ('code'  => 'de-DE', 'title' => 'German');
$languages[] = array ('code'  => 'es-ES', 'title' => 'Spanish');
$languages[] = array ('code'  => 'fr-FR', 'title' => 'French');
$languages[] = array ('code'  => 'it-IT', 'title' => 'Italian');
$languages[] = array ('code'  => 'ja-JP', 'title' => 'Japanese');
$languages[] = array ('code'  => 'nl-NL', 'title' => 'Dutch');
$languages[] = array ('code'  => 'pl-PL', 'title' => 'Polish');
$languages[] = array ('code'  => 'pt-BR', 'title' => 'Portuguese (Brazil)');
$languages[] = array ('code'  => 'pt-PT', 'title' => 'Portuguese (Portugal)');
$languages[] = array ('code'  => 'ru-RU', 'title' => 'Russian');

define('JSN_POWERADMIN_LIST_LANGUAGE_SUPPORTED', json_encode($languages));


