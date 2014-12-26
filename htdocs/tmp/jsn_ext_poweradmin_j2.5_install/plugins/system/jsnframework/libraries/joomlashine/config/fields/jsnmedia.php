<?php
/**
 * @version     $Id: jsnmedia.php 15511 2012-08-27 03:01:49Z cuongnm $
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

/**
 * Create image selector field.
 *
 * Below is a sample field declaration for generating image selector field:
 *
 * <code>&lt;field
 *     name="site_logo" type="jsnmedia" default=""
 *     label="JSN_SAMPLE_SITE_LOGO_LABEL" description="JSN_SAMPLE_SITE_LOGO_DESC"
 * /&gt;</code>
 *
 * @package  JSN_Framework
 * @since    1.0.0
 *
 */
class JFormFieldJSNMedia extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var  string
	 */
	protected $type = 'JSNMedia';

	/**
	 * Get the field label markup.
	 *
	 * @return  string
	 */
	protected function getLabel()
	{
		// Preset label
		$label = '';

		if ($this->hidden)
		{
			return $label;
		}

		// Get the label text from the XML element, defaulting to the element name
		$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
		$text = $this->translateLabel ? JText::_($text) : $text;

		// Build the class for the label
		$class = array ('control-label');
		$class[] = ! empty($this->description) ? ' hasTip' : '';
		$class[] = $this->required == true ? ' required' : '';
		$class[] = ! empty($this->labelClass) ? ' ' . $this->labelClass : '';
		$class   = implode('', $class);

		// Add the opening label tag and class attribute
		$label .= '<label class="' . $class . '"';

		// If a description is specified, use it to build a tooltip
		if ( ! empty($this->description))
		{
			$label .= ' title="' . htmlspecialchars(trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8') . '"';
		}

		// Add the label text and closing tag
		$label .= '>' . $text . ($this->required ? '<span class="star">&#160;*</span>' : '') . '</label>';

		return $label;
	}

	/**
	 * Method to get the field input markup for a media selector.
	 *
	 * @return  string  The field input markup.
	 */
	protected function getInput()
	{
		// Preset output
		$html = array ();

		// Initialize variables
		$editable		= isset($this->element['editable'])		? (string) $this->element['editable']		: '';
		$clearButton	= isset($this->element['clearButton'])	? (string) $this->element['clearButton']	: '';
		$mediaRoot		= isset($this->element['directory'])	? (string) $this->element['directory']		: '';

		$selectorLink	= JURI::root() . 'plugins/system/jsnframework/libraries/joomlashine/choosers/media.php'
						. '?component=' . JFactory::getApplication()->input->getCmd('option')
						. '&root=' . $mediaRoot . '&current=' . $this->value . '&element=' . $this->id . '&handler=JSNMediaUpdateField';

		// Load script to handle selection update
		$html[] = JSNHtmlAsset::loadScript(
			'jsn/media',
			array(
				'url'		=> $selectorLink,
				'field'		=> "#{$this->id}",
				'language'	=> JSNUtilsLanguage::getTranslated(array('JSN_EXTFW_CONFIG_CLICK_TO_SELECT', 'JSN_EXTFW_GENERAL_CLOSE'))
			),
			true
		);

		// Initialize attributes
		$class		= ' class="' . (isset($this->element['class']) ? (string) $this->element['class'] : 'span11') . '"';
		$disabled	= $editable ? '' : ' disabled="disabled"';

		// Generate clear button
		$clear = '';
		if ($clearButton)
		{
			$clear	= '<button class="btn inline">' . JText::_('JSN_EXTFW_GENERAL_CLEAR') . '</button>';
		}

		// Generate markup
		$html[]	= '<div class="input-append row-fluid">'
				. '<input type="text" id="' . $this->id . '" name="' . $this->name . '" value="' . $this->value . '"' . $class . $disabled . ' />'
				. '<button class="btn">...</button>' . $clear
				. '</div>';

		return implode($html);
	}
}
