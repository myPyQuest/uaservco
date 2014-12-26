<?php
/**
 * @version     $Id: permissions.php 14883 2012-08-09 07:50:32Z cuongnm $
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

// Import Joomla rules form field renderer
require_once JPATH_ROOT . DS . 'libraries' . DS . 'joomla' . DS . 'form' . DS . 'fields' . DS . 'rules.php';

/**
 * Create permissions form.
 *
 * Below is a sample field declaration for generating permission manager form:
 *
 * <code>&lt;field
 *     name="permissions" type="permissions" class="inputbox" filter="rules" validate="rules"
 *     component="com_sample" section="component"
 * /&gt;</code>
 * 
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JFormFieldPermissions extends JFormFieldRules
{
	/**
	 * The form field type.
	 *
	 * @var	string
	 */
	public $type = 'Permissions';

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
	 * Get the field input markup for Access Control Lists.
	 *
	 * Optionally can be associated with a specific component and section.
	 *
	 * @return  string
	 */
	protected function getInput()
	{
		// Get rules markup
		$html[] = parent::getInput();

		// Embed Javascript if necessary
		$input = JFactory::getApplication()->input;
		if ($input->getCmd('tmpl') == 'component' AND $input->getInt('ajax') == 1)
		{
			// Get component we are setting permissions for
			$component = $this->element['component'] ? (string) $this->element['component'] : '';

			// Generate Javascript code
			$html[] = '
<script type="text/javascript">
	(function() {
		new Fx.Accordion(
			$$("div#permissions-sliders.pane-sliders .panel h3.pane-toggler"),
			$$("div#permissions-sliders.pane-sliders .panel div.pane-slider"),
			{
				onActive: function(toggler, i) {
					toggler.addClass("pane-toggler-down")
					toggler.removeClass("pane-toggler");
					i.addClass("pane-down");
					i.removeClass("pane-hide");
					Cookie.write("jpanesliders_permissions-sliders' . $component . '", $$("div#permissions-sliders.pane-sliders .panel h3").indexOf(toggler));
				},
				onBackground: function(toggler, i) {
					toggler.addClass("pane-toggler");
					toggler.removeClass("pane-toggler-down");
					i.addClass("pane-hide");
					i.removeClass("pane-down");
				},
				duration: 300,
				display: ' . $input->getInt('jpanesliders_permissions-sliders' . $component, 0, 'cookie') . ',
				show: ' . $input->getInt('jpanesliders_permissions-sliders' . $component, 0, 'cookie') . ',
				alwaysHide: true,
				opacity: false
			}
		);
	})();
</script>
';
		}

		return implode($html);
	}
}
