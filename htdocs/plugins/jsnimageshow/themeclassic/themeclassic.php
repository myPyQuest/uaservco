<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow - Theme Classic
 * @version $Id: themeclassic.php 11943 2012-03-28 10:37:49Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.plugin.plugin' );
include_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_imageshow'.DS.'classes'.DS.'jsn_is_factory.php');
class plgJSNImageshowThemeClassic extends JPlugin
{
	var $_showcaseThemeName = 'themeclassic';
	var $_showcaseThemeType = 'jsnimageshow';
	var $_pathAssets 		= 'plugins/jsnimageshow/themeclassic/assets/';
	var $_tableName			= 'theme_classic';

	function onLoadJSNShowcaseTheme($name, $themeID = 0)
	{
		if($name != $this->_showcaseThemeName){
			return false;
		}

		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);

		ob_start();

		JHTML::stylesheet('style.css', $this->_pathAssets.'css/');
		JHTML::script('jsn_is_classictheme.js', $this->_pathAssets.'js/');
		JHTML::script('jsn_is_accordions.js', $this->_pathAssets.'js/');
		JHTML::script('swfobject.js', $this->_pathAssets.'js/');

		include(dirname(__FILE__).DS.'helper'.DS.'helper.php');
		include(dirname(__FILE__).DS.'views'.DS.'default.php');

		return ob_get_clean();
	}

	function loadMedia()
	{
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		$basePath 			= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 		= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null, $basePath);
		$objThemeMedia->setMediaBasePath();

		JHTML::script('jsn_is_imagemanager.js', $this->_pathAssets.'js/');
		JHTML::stylesheet('system.css', 'templates/system/css/');

		$this->session 		= JFactory::getSession();
		$this->stateFolder	= $objThemeMedia->getStateFolder();
		$this->folderList 	= $objThemeMedia->getFolderList();

		include(dirname(__FILE__).DS.'views'.DS.'media'.DS.'default.php');
	}

	function loadMediaImagesList()
	{
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		$basePath 			= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 		= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null, $basePath);
		$objThemeMedia->setMediaBasePath();

		$document = JFactory::getDocument();
		$document->addScriptDeclaration("var JSNISImageManager = window.parent.JSNISImageManager;");

		$this->session 		= JFactory::getSession();
		$this->folderList 	= $objThemeMedia->getFolderList();
		$this->images 		= $objThemeMedia->getImages();
		$this->folders 		= $objThemeMedia->getFolders();
		$this->baseURL 		= $objThemeMedia->comMediaBaseURL;
		$this->stateFolder	= $objThemeMedia->getStateFolder();

		include(dirname(__FILE__).DS.'views'.DS.'mediaimages'.DS.'default.php');
	}

	function onUpload()
	{
		$basePath 		= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeMedia 	= JSNISFactory::getObj('classes.jsn_is_thememedia', null ,null,  $basePath);
		$objThemeMedia->setMediaBasePath();

		$objThemeMedia->upload();
	}

	function onExtensionBeforeUninstall($eid)
	{
		$query 	= 'DROP TABLE IF EXISTS `#__imageshow_theme_classic`';
		$db 	= JFactory::getDbo();
		$db->setQuery($query);
		$db->query();
	}

	function getLanguageJSNPlugin()
	{
		$language = array();
		$language['admin']['files'] = array('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName.'.ini');
		$language['admin']['path'] 	= array(dirname(__FILE__).DS.'languages');

		return $language;
	}

	function onDisplayJSNShowcaseTheme($args)
	{
		if (!isset($args->theme_name) || $args->theme_name != $this->_showcaseThemeName) {
			return false;
		}
		
		JHTML::stylesheet('style.css', $this->_pathAssets.'css/');
		JPlugin::loadLanguage('plg_'.$this->_showcaseThemeType.'_'.$this->_showcaseThemeName);
		$basePath 			= JPATH_PLUGINS.DS.$this->_showcaseThemeType.DS.$this->_showcaseThemeName;
		$objThemeDisplay 	= JSNISFactory::getObj('classes.jsn_is_themedisplay', null ,null, $basePath);
		$result				= $objThemeDisplay->display($args);
		return $result;
	}

	function listThemeclassicTable()
	{
		$tables = array('#__imageshow_theme_classic', '#__imageshow_theme_classic_parameters');
		return $tables;
	}
}