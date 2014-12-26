<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: defines.imageshow.php 14514 2012-07-28 07:42:22Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
define('JSN_IMAGESHOW_ADMIN_PATH', JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow');
define('JSN_IS_PATH_JSN_PLUGIN', JPATH_PLUGINS.DS.'jsnimageshow');
define('JSN_IMAGESHOW_AUTOUPDATE_URL', 'http://www.joomlashine.com/index.php?option=com_lightcart&controller=remoterequestauthentication&task=authenticate&tmpl=component');
define('JSN_IS_CUSTOMER_AREA', 'http://www.joomlashine.com/index.php?option=com_lightcart&view=customerarea');
$objJSNProfile 	= JSNISFactory::getObj('classes.jsn_is_profile');
$parameters 	= $objJSNProfile->getParameters();
define('JSN_IMAGESHOW_CATEGORY_EXTENSION', 'cat_extension');
define('JSN_IMAGESHOW_CATEGORY', 'cat_ext_imageshow');
if (is_null(@$parameters->enable_update_checking) || $parameters->enable_update_checking)
{
	define('JSN_IMAGESHOW_INFO_URL', 'http://www.joomlashine.com/versioning/product_version.php?category='.JSN_IMAGESHOW_CATEGORY_EXTENSION);
}
else
{
	define('JSN_IMAGESHOW_INFO_URL', '');
}
// IMAGESHOW GLOBAL PATH
define('JSN_IMAGESHOW_FILE_URL', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-sample-data-j25.zip');
define('JSN_IMAGESHOW_CATEGORY_IMAGESOURCES', 'jsnisimagesources');
define('JSN_IMAGESHOW_CATEGORY_THEMES', 'jsnisthemes');
define('JSN_IMAGESHOW_IDENTIFIED_NAME', 'imageshow');
// LIST PLUGIN INSTALLED
$imageSource = array('picasa');
$theme = array('themeclassic');
$pluginInstalledList = array('imageSource' => $imageSource, 'theme' => $theme);
define('PluginInstalledList', json_encode($pluginInstalledList));

//LIST LANGUAGES SUPPORT
$languages[] = array ('code'  => 'en-GB', 'title' => 'English');
$languages[] = array ('code'  => 'de-DE', 'title' => 'German');
$languages[] = array ('code'  => 'fr-FR', 'title' => 'French');
$languages[] = array ('code'  => 'nl-NL', 'title' => 'Dutch');
$languages[] = array ('code'  => 'pl-PL', 'title' => 'Polish');
$languages[] = array ('code'  => 'pt-PT', 'title' => 'Portuguese (Portugal)');
$languages[] = array ('code'  => 'it-IT', 'title' => 'Italian');

define('JSN_IMAGESHOW_LIST_LANGUAGE_SUPPORTED', json_encode($languages));
define('JSN_IS_BUY_LINK', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-buy-now.html');