<?php
/**
 * @version     $Id: controller.php 15293 2012-08-21 02:45:24Z hiepnv $
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
jimport('joomla.application.component.controller');

/**
 * Controller class of JSN Config library.
 *
 * To implement <b>JSNConfigController</b> class, create a controller file
 * in <b>administrator/components/com_YourComponentName/controllers</b> folder
 * then put following code into that file:
 *
 * <code>class YourComponentPrefixControllerConfig extends JSNConfigController
 * {
 * }</code>
 *
 * The <b>JSNConfigController</b> class pre-defines <b>save</b> method for
 * validating then saving configuration data. So, you <b>DO NOT NEED</b> to
 * re-define that method in your controller class.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNConfigController extends JSNBaseController
{
	/**
	 * Validate then save configuration data.
	 *
	 * @return  void
	 */
	function save()
	{
		// Get input object
		$input = JFactory::getApplication()->input;

		// Validate request
		$this->initializeRequest($input);

		// Initialize variables
		$this->model = $this->getModel($input->getCmd('controller') ? $input->getCmd('controller') : $input->getCmd('view'));
		$config      = $this->model->getForm();
		$data        = $input->getVar('jsnconfig', array(), 'post', 'array');

		// Attempt to save the configuration
		$return = true;

		try
		{
			$this->model->save($config, $data);
		}
		catch (Exception $e)
		{
			$return = $e;
		}

		// Complete request
		$this->finalizeRequest($return, $input);
	}
}
