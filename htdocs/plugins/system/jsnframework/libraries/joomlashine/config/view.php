<?php
/**
 * @version     $Id: view.php 16007 2012-09-13 03:31:01Z hiepnv $
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
jimport('joomla.application.component.view');

// Import JSN Config Helper class
jsnimport('joomlashine.config.helper');

/**
 * View class of JSN Config library.
 *
 * To implement <b>JSNConfigView</b> class, create a view file in
 * <b>administrator/components/com_YourComponentName/views</b> folder
 * then put following code into that file:
 *
 * <code>class YourComponentPrefixViewConfig extends JSNConfigView
 * {
 * }</code>
 *
 * Finally, put the method call below into the <b>tmpl/default.php</b> template
 * file of that view to display configuration page:
 *
 * <code>JSNConfigHelper::render($this->config);</code>
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNConfigView extends JView
{
	/**
	 * Display method.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @return	void
	 */
	public function display($tpl = null)
	{
		// Get config declaration
		$configDeclaration = $this->get('Form');

		// Pass data to view
		$this->assignRef('config', $configDeclaration);

		JSNHtmlAsset::loadScript('jsn/core',
			array('lang' => JSNUtilsLanguage::getTranslated(array('JSN_EXTFW_GENERAL_LOADING', 'JSN_EXTFW_GENERAL_CLOSE')))
		);

		JSNHtmlAsset::loadScript('jsn/config',
			array('language' => array('JSN_EXTFW_GENERAL_CLOSE' => JText::_('JSN_EXTFW_GENERAL_CLOSE')))
		);
	
		// Display the template
		parent::display($tpl);
	}
}
