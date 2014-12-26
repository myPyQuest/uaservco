<?php
/**
 * @version     $Id: language.php 15106 2012-08-15 08:22:05Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Utils
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for working with language.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUtilsLanguage
{
	/**
	 * Check if a language is installable.
	 *
	 * @param   string   $code      Language code.
	 * @param   boolean  $frontend  TRUE for frontend, FALSE for backend.
	 *
	 * @return  boolean
	 */
	public static function installable($code, $frontend = false)
	{
		// Initialize variables
		$sourcePath = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . JFactory::getApplication()->input->getCmd('option') . DS . 'language' . DS . ($frontend ? 'site' : 'admin') . DS . $code . DS . "{$code}." . JFactory::getApplication()->input->getCmd('option') . '.ini';
		$langPath   = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . DS . 'language' . DS . $code;

		// Check if language is installable
		$installable = is_dir($langPath) && is_writable($langPath) && is_file($sourcePath);

		return $installable;
	}

	/**
	 * Check if a language is already installed.
	 *
	 * @param   string   $code      Language code.
	 * @param   boolean  $frontend  TRUE for frontend, FALSE for backend.
	 *
	 * @return  boolean
	 */
	public static function installed($code, $frontend = false)
	{
		// Initialize variable
		$langPath = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . DS . 'language' . DS . $code;

		if ( ! is_dir($langPath))
		{
			// Language folder does not exists
			return false;
		}

		// Check if language is already installed
		$installed = count(glob($langPath . DS . "{$code}." . JFactory::getApplication()->input->getCmd('option') . '.*'));

		return $installed;
	}

	/**
	 * Check if a language is supported by Joomla!.
	 *
	 * @param   string   $code      Language code.
	 * @param   boolean  $frontend  TRUE for frontend, FALSE for backend.
	 *
	 * @return  boolean
	 */
	public static function supported($code, $frontend = false)
	{
		// Get language folder
		$langPath = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . DS . 'language' . DS . $code;

		// Check if language is supported by Joomla!
		$supported = is_dir($langPath);

		return $supported;
	}

	/**
	 * Install languages to Joomla's language folder.
	 *
	 * @param   array    $codes      Array of language code need to be installed
	 * @param   boolean  $frontend   TRUE for frontend, FALSE for backend.
	 * @param   boolean  $overwrite  Set to TRUE to force language file installation event if already installed.
	 *
	 * @return  void
	 */
	public static function install($codes, $frontend = false, $overwrite = false)
	{
		// Initialize variables
		$sourcePath = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . JFactory::getApplication()->input->getCmd('option') . DS . 'language' . DS . ($frontend ? 'site' : 'admin');
		$langPath   = ($frontend ? JPATH_SITE : JPATH_ADMINISTRATOR) . DS . 'language';

		foreach ($codes AS $code)
		{
			// Check if language should be installed
			if (self::supported($code, $frontend) AND ($overwrite OR ! self::installed($code, $frontend)) AND self::installable($code, $frontend))
			{
				// Get language files need to be installed
				$files = glob($sourcePath . DS . $code . DS . "{$code}.*");

				// Copy language files to the appropriate language folder
				foreach ($files AS $file)
				{
					JFile::copy($file, $langPath . DS . $code . DS . basename($file));
				}
			}
		}
	}

	/**
	 * Create Javascript language object.
	 *
	 * This method create Javascript object containing raw text as key and its
	 * meaning, in active language, as value. For example, the following method
	 * call:
	 *
	 * <code>JSNUtilsLanguage::toJavascript(
	 *     'JSN.lang',
	 *     array(
	 *         'JSN_EXTFW_LANGUAGE_ENGB',
	 *         'JSN_EXTFW_LANGUAGE_DEDE',
	 *         'JSN_EXTFW_LANGUAGE_FRFR'
	 *     )
	 * );</code>
	 *
	 * Will generate and return the Javascript code below (assuming active
	 * language in Joomla is English):
	 *
	 * <code>JSN.lang = {
	 *     'JSN_EXTFW_LANGUAGE_ENGB': 'English',
	 *     'JSN_EXTFW_LANGUAGE_DEDE': 'German',
	 *     'JSN_EXTFW_LANGUAGE_FRFR': 'French'
	 * };</code>
	 *
	 * @param   string  $name   Javascript variable to hold text translation.
	 * @param   array   $texts  Array of raw text.
	 *
	 * @return  string
	 */
	public static function toJavascript($name, $texts)
	{
		// Preset variable
		$js = array();

		// Generate text translation
		foreach ($texts AS $text)
		{
			$js[] = "'{$text}': '" . str_replace("'", "\\'", JText::_($text)) . "'";
		}

		// Finalize Javascript code
		$js = "{$name} = {" . implode(', ', $js) . '};';

		return $js;
	}

	/**
	 * Method to get text translation.
	 *
	 * @param   array  $strings  String to translate.
	 *
	 * @return  array
	 */
	public static function getTranslated ($strings)
	{
		$translated = array();

		foreach ($strings AS $string)
		{
			$translated[strtoupper($string)] = JText::_($string);
		}

		return $translated;
	}
}
