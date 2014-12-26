<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsn_is_language.php 13903 2012-07-11 10:37:31Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
include_once JPATH_ROOT.DS.'administrator'.DS.'components'.DS.'com_imageshow'.DS.'helpers'.DS.'language.php';
class JSNISLanguage
{
	var $_adminSupportedLanguages 	= null;
	var $_siteSupportedLanguages 	= null;
	var $_pluginLanguages 			= null;
	var $_adminSourcePath 			= '';
	var $_siteSourcePath 			= '';
	var $_adminPath 				= '';
	var $_sitePath 					= '';

	public static function getInstance()
	{
		static $instanceLang;

		if ($instancelang == null)
		{
			$instanceLang = new JSNISLanguage();
		}

		return $instanceLang;
	}

	function JSNISLanguage()
	{
		$this->_adminSupportedLanguages 	= JSNISLanguageHelper::getSupportedLanguage('administrator');
		$this->_siteSupportedLanguages 		= JSNISLanguageHelper::getSupportedLanguage('');
		$this->_adminSourcePath 			= JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/admin';
		$this->_siteSourcePath 				= JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/site';
		$this->_adminPath 					= JPATH_ADMINISTRATOR.DS.'language';
		$this->_sitePath 					= JPATH_SITE.DS.'language';
		$this->_pluginLanguages				= $this->getPluginLanguages();
	}

	function install($langs, $area)
	{
		if ($area == 'site')
		{
			foreach ($this->_siteSupportedLanguages as $lang)
			{
				if (in_array($lang, $langs))
				{
					$files = glob($this->_siteSourcePath.DS."{$lang}.*.ini");
					foreach ($files as $file)
					{
						copy($file, $this->_sitePath.DS.$lang.DS.basename($file));
					}
				}
			}

			//Install lang for sources and themes
			if (isset($this->_pluginLanguages['site']))
			{
				foreach ($langs as $lang)
				{
					foreach ($this->_pluginLanguages['site'] as $plugin)
					{
						$files = glob($plugin.DS."{$lang}.*.ini");
						foreach ($files as $file)
						{
							copy($file, $this->_sitePath.DS.$lang.DS.basename($file));
						}
					}
				}
			}
		}
		else
		{
			foreach ($this->_adminSupportedLanguages as $lang)
			{
				if (in_array($lang, $langs))
				{
					$files = glob($this->_adminSourcePath.DS."{$lang}.*.ini");
					foreach ($files as $file)
					{
						copy($file, $this->_adminPath.DS.$lang.DS.basename($file));
					}
				}
			}

			//Install lang for sources and themes
			if (isset($this->_pluginLanguages['admin']))
			{
				foreach ($langs as $lang)
				{
					foreach ($this->_pluginLanguages['admin'] as $plugin)
					{
						$files = glob($plugin.DS."{$lang}.*.ini");
						foreach ($files as $file)
						{
							copy($file, $this->_adminPath.DS.$lang.DS.basename($file));
						}
					}
				}
			}
		}
	}

	function getPluginLanguages()
	{
		JPluginHelper::importPlugin('jsnimageshow');
		$dispatcher 	= JDispatcher::getInstance();
		$plugins 		= $dispatcher->trigger('getLanguageJSNPlugin');
		$languages		= array();
		foreach ($plugins as $plugin)
		{
			foreach ($plugin as $position => $language)
			{
				$languages [$position][$language['files'][0]] = (string) $language['path'][0];
			}
		}
		return $languages;
	}

	function getFilterLangSystem()
	{
		$app 			= JFactory::getApplication();
		$router 		= $app->getRouter();
		$modeSef 		= ($router->getMode() == JROUTER_MODE_SEF) ? true : false;
		$languageFilter = $app->getLanguageFilter();
		$uri 			= JFactory::getURI();
		$langCode		= JLanguageHelper::getLanguages('lang_code');
		$langDefault	= JComponentHelper::getParams('com_languages')->get('site', 'en-GB');

		$realPath = 'index.php?';

		if ($languageFilter)
		{
			if (isset($langCode[$langDefault]))
			{
				if ($modeSef)
				{
					$realPath = '';
					$realPath .= JFactory::getConfig()->get('sef_rewrite') ? '' : 'index.php/';
					$realPath .= $langCode[$langDefault]->sef.'/?';
				}
				else
				{
					$realPath = 'index.php?lang='.$uri->getVar('lang').'%26';
				}
			}
		}

		return $realPath;
	}
}