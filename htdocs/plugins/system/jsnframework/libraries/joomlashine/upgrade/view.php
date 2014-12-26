<?php
/**
 * @version     $Id: view.php 15971 2012-09-11 10:28:31Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Upgrade
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

// Import JSN Upgrade Helper class
jsnimport('joomlashine.upgrade.helper');

/**
 * View class of JSN Upgrade library.
 *
 * To implement <b>JSNUpgradeView</b> class, create a view file in
 * <b>administrator/components/com_YourComponentName/views</b> folder
 * then put following code into that file:
 *
 * <code>class YourComponentPrefixViewUpgrade extends JSNUpgradeView
 * {
 * }</code>
 *
 * Finally, put the method call below into the <b>tmpl/default.php</b> template
 * file of that view to display product upgrade page:
 *
 * <code>JSNUpgradeHelper::render(
 *     $this->product,
 *     JText::_('JSN_SAMPLE_UPGRADE_BENEFITS_FREE'),
 *     JText::_('JSN_SAMPLE_UPGRADE_BENEFITS_PRO')
 * );</code>
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUpgradeView extends JView
{
	/**
	 * Display method.
	 *
	 * @param   string  $tpl  The name of the template file to parse.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		// Is product upgradable?
		if ( ! ($edition = JSNUtilsText::getConstant('EDITION')) OR strcasecmp($edition, 'pro unlimited') == 0)
		{
			$app = JFactory::getApplication();
			$app->redirect('index.php?option=' . $app->input->getCmd('option') . '&view=update');
		}

		// Get product info
		$info = JSNUtilsXml::loadManifestCache();

		// Pass data to view
		$this->assignRef('product', $info);

		// Add assets
		JSNHtmlAsset::loadScript('jsn/upgrade',
			array(
				'button'	=> 'jsn-proceed-button',
				'language'	=> array('JSN_EXTFW_GENERAL_STILL_WORKING', 'JSN_EXTFW_GENERAL_PLEASE_WAIT')
			)
		);

		// Display the template
		parent::display($tpl);
	}
}
