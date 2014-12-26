<?php
/**
 * @version     $Id: jsnselectbox.php 15481 2012-08-24 10:41:19Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
defined('JPATH_BASE') or die;

/**
 * Supports an HTML select list of form
 * 
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JFormFieldJSNSelectbox extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'JSNSelectBox';

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
		if (empty($this->element['label']))
		{
			return;
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
	 * Get the select box field input markup.
	 *
	 * @return  string
	 */
	protected function getInput()
	{
		// Get radio button options
		$options = $this->getOptions();
		$class   = empty($this->element['class']) ? "inputbox jsn-select-value" : $this->element['class'];
		$html	= JHTML::_('select.genericList', $options, $this->name, 'class="' . $class . '"', 'value', 'text', $this->value);
		return $html;
	}

	/**
	 * Get the field options for select box.
	 *
	 * @return  array
	 */
	protected function getOptions()
	{
		// Preset options array
		$options = array ();

		foreach ($this->element->children() as $option)
		{
			// Only add <option /> elements
			if ($option->getName() != 'option')
			{
				continue;
			}
			// Create a new option object based on the <option /> element
			$tmp = JHtml::_(
					'select.option', (string) $option['value'], JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text', ((string) $option['disabled'] == 'true')
			);

			// Set option attributes
			$tmp->class = (string) $option['class'];
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the options array
			$options[] = $tmp;
		}
		reset($options);
		return $options;
	}
}
