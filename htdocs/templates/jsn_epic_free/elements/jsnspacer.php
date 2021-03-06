<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJSNSpacer extends JFormField
{
	protected $type = 'JSNSpacer';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 * @since   11.1
	 */
	protected function getInput()
	{
		return ' ';
	}

	/**
	 * Method to get the field label markup.
	 *
	 * @return  string  The field label markup.
	 * @since   11.1
	 */
	protected function getLabel()
	{
		$jsnUtils  = JSNUtils::getInstance();
		$result    = $jsnUtils->getTemplateDetails();
		$proExists = $jsnUtils->checkProEditionExist($jsnUtils->getTemplateName(), true);
		
		if (!JSN_CACHESENSITIVE && $this->element['cachesensitive'] == 'yes')
		{
			return implode('', array());
		}

		$html = array();

		$class = $this->element['class'] ? (string) $this->element['class'] : '';
		$html[] = '<span class="spacer">';
		$html[] = '<span class="before"></span>';
		$html[] = '<span class="'.$class.'">';
		if ((string) $this->element['hr'] == 'true') {
			$html[] = '<hr class="'.$class.'" />';
		}
		else {
			$label = '';
			// Get the label text from the XML element, defaulting to the element name.
			$text = $this->element['label'] ? (string) $this->element['label'] : (string) $this->element['name'];
			$text = $this->translateLabel ? JText::_($text) : $text;

			// Build the class for the label.
			$class = !empty($this->description) ? 'hasTip' : '';
			$class = $this->required == true ? $class.' required' : $class;

			// Add the opening label tag and main attributes attributes.
			$label .= '<label id="'.$this->id.'-lbl" class="'.$class.'"';

			// If a description is specified, use it to build a tooltip.
			if (!empty($this->description)) {
				$label .= ' title="'.htmlspecialchars(trim($text, ':').'::' .
							($this->translateDescription ? JText::_($this->description) : $this->description), ENT_COMPAT, 'UTF-8').'"';
			}

			// Add the label text and closing tag.
			if ($proExists === false)
			{
				$upgrade_link = '<strong><a class="link-action jsn-modal" rel="{handler: \'iframe\', size: {x: 750, y: 650}, closable: false}" href="../index.php?template='.strtolower($result->name).'&tmpl=jsn_upgrade&template_style_id='.JRequest::getInt('id').'">Upgrade now</a></strong>';
			}
			else
			{
				$upgrade_link = '';
			}

			$label .= '>'.JText::sprintf($text, $upgrade_link).'</label>';
			$html[] = $label;
		}
		$html[] = '</span>';
		$html[] = '<span class="after"></span>';
		$html[] = '</span>';
		
		return implode('', $html);
	}

	/**
	 * Method to get the field title.
	 *
	 * @return  string  The field title.
	 * @since   11.1
	 */
	protected function getTitle()
	{
		return $this->getLabel();
	}
}
