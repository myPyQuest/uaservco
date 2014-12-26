<?php
/**
 * @version     $Id: define.php 16631 2012-10-03 07:58:29Z thailv $
 * @package     JSN_Framework
 * @subpackage  Html
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Define necessary constants
define('JSN_EXT_VERSION_CHECK_URL',		'http://www.joomlashine.com/versioning/product_version.php?category=cat_extension');
define('JSN_EXT_DOWNLOAD_UPDATE_URL',	'http://www.joomlashine.com/index.php?option=com_lightcart&controller=remoteconnectauthentication&task=authenticate&tmpl=component&upgrade=yes');

define('JSN_PATH_FRAMEWORK',	dirname(__FILE__));
define('JSN_PATH_LIBRARIES',	JSN_PATH_FRAMEWORK . DS . 'libraries');
define('JSN_URL_ASSETS',		JURI::root(true) . '/plugins/system/jsnframework/assets');

//Third-party templates.
define('JSN_TEMPLATE_CLASSES_OVERWRITE',	JSN_PATH_FRAMEWORK . DS . 'libraries' . DS . 'template' . DS . 'overwrites' . DS);

// Define necessary variables.
$baseUrl	= JURI::base(true);
$rootUrl	= JURI::root(true);
$component	= preg_replace('/^com_/i', '', JRequest::getCmd('option'));

// Add base path that point to folder contains javascript files of the framework
JSNHtmlAsset::addScriptPath('jsn', 'joomlashine/js');

// Prepare config for current component
JSNHtmlAsset::prepare(JRequest::getCmd('option'), true);

// Predefine script libraries
JSNHtmlAsset::addScriptLibrary('bootstrap', 			'3rd-party/bootstrap/js/bootstrap.min', 				array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.ui', 			'3rd-party/jquery-ui/js/jquery-ui-1.8.16.custom.min', 	array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.cookie', 		'3rd-party/jquery.cookie/jquery.cookie', 				array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.hotkeys', 		'3rd-party/jquery.hotkeys/jquery.hotkeys', 				array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.jstorage', 		'3rd-party/jquery.jstorage/jquery.jstorage', 			array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.jstree', 		'3rd-party/jquery.jstree/jquery.jstree', 				array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.layout', 		'3rd-party/jquery.layout/js/jquery.layout-latest', 		array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.tinyscrollbar', 	'3rd-party/jquery.tinyscrollbar/jquery.tinyscrollbar', 	array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.topzindex', 		'3rd-party/jquery.topzindex/jquery.topzindex', 			array('jquery'));
JSNHtmlAsset::addScriptLibrary('jquery.contextMenu', 	'3rd-party/jquery.contextMenu/jquery.contextMenu', 		array('jquery', 'jquery.ui'));
JSNHtmlAsset::addScriptLibrary('jquery.daterangepicker', '3rd-party/jquery.daterangepicker/js/daterangepicker.jQuery.compressed', array('jquery', 'jquery.ui'));

//Define not supported template providers.
global $notSupportedTemplateAuthors;
$notSupportedTemplateAuthors[] 	= 'joomlart';
$notSupportedTemplateAuthors[] 	= 'yootheme';
$notSupportedTemplateAuthors[] 	= 'joomlaxtc';
$notSupportedTemplateAuthors[]		= 'joomagic';