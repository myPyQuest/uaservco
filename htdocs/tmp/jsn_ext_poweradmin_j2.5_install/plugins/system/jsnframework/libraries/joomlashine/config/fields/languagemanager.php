<?php
/**
 * @version     $Id: languagemanager.php 16456 2012-09-26 09:18:47Z hiepnv $
 * @package     JSN_Framework
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla library
jimport('joomla.filesystem.folder');

/**
 * Create language manager form.
 *
 * Below is a sample field declaration for generating language manager form:
 *
 * <code>&lt;field name="languagemanager" type="languagemanager" /&gt;</code>
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JFormFieldLanguageManager extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var	string
	 */
	protected $type = 'LanguageManager';

	/**
	 * Always return null to disable label markup generation.
	 *
	 * @return  string
	 */
	protected function getLabel()
	{
		return '';
	}

	/**
	 * Get the language manager markup.
	 *
	 * @return  string
	 */
	protected function getInput()
	{
		// Preset output
		$html[] = '
<p class="item-title">' . JText::_('JSN_EXTFW_LANGUAGE_SELECT') . '</p>';

		foreach ($this->getOptions() AS $lang)
		{
			// Initialize variable
			$langText = JText::_('JSN_EXTFW_LANGUAGE_' . strtoupper(str_replace('-', '', $lang)));

			// Build input attributes
			$aChecked	= JSNUtilsLanguage::installed($lang) ? ' checked="checked"' : '';
			$aStatus	= ! empty($aChecked) ? "\n\t\t" : '';
			$aDisabled	= ( ! JSNUtilsLanguage::installable($lang) OR JSNUtilsLanguage::installed($lang) OR ! JSNUtilsLanguage::supported($lang))
						? ' disabled="disabled"' : '';

			$sChecked	= JSNUtilsLanguage::installed($lang, true) ? ' checked="checked"' : '';
			$sStatus	= ! empty($sChecked) ? "\n\t\t" : '';
			$sDisabled	= ( ! JSNUtilsLanguage::installable($lang, true) OR JSNUtilsLanguage::installed($lang, true) OR ! JSNUtilsLanguage::supported($lang, true))
						? ' disabled="disabled"' : '';

			// Generate markup for language manager
			$html[] = '
<div class="jsn-language-item ' . $lang . '">
	<span class="jsn-icon24 icon-flag ' . strtolower($lang) . '"></span>
	<label class="checkbox">
		<input type="checkbox" name="languagemanager[a][]" value="' . $lang . '"' . $aDisabled . $aChecked . ' />
		<span>' . $lang . ' - ' . $langText . ' (' . JText::_('JADMINISTRATOR') . ')</span>' . $aStatus . '
	</label>
	<label class="checkbox">
		<input type="checkbox" name="languagemanager[s][]" value="' . $lang . '"' . $sDisabled . $sChecked . ' />
		<span>' . $lang . ' - ' . $langText . ' (' . JText::_('JSITE') . ')</span>' . $sStatus . '
	</label>
</div>';
		}

		$html[] = '
<input type="hidden" name="' . $this->name . '" value="JSN_CONFIG_SKIP_SAVING" />
<div class="clearbreak"></div>
';

		return implode($html);
	}

	/**
	 * Get the field options for supported language list.
	 *
	 * @return  array
	 */
	protected function getOptions()
	{
		// Looking for language packages
		$admin	= JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR . DS . 'language' . DS . 'admin');
		$site	= JFolder::folders(JPATH_COMPONENT_ADMINISTRATOR . DS . 'language' . DS . 'site');

		if ($admin AND $site)
		{
			$options = array_merge($admin, $site);
		}
		elseif ($admin OR $site)
		{
			$options = $admin ? $admin : $site;
		}

		return isset($options) ? array_unique($options) : array();
	}
}
