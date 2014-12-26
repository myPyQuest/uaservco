<?php
/**
 * @version     $Id$
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

// Import Joomla view library
jimport('joomla.application.component.view');
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_modules' . DS . 'views' . DS . 'modules' . DS . 'view.html.php';
require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_modules' . DS . 'models' . DS . 'modules.php';
require_once JPATH_ROOT . DS . 'plugins' . DS . 'system' . DS . 'jsnframework' . DS . 'libraries' . DS . 'joomlashine' . DS . 'modules' . DS . 'modules.php';
$lang = JFactory::getLanguage();
$lang->load('com_modules');

/**
 * View class of JSN Positions.
 *
 *
 * @package  JSN_Framework
 * @since    1.0.3
 */
class JSNModulesView extends ModulesViewModules
{

	/**
	 * Constructor
	 *
	 * @param   array  $config  A named configuration array for object construction.<br/>
	 *                          name: the name (optional) of the view (defaults to the view class name suffix).<br/>
	 *                          charset: the character set to use for display<br/>
	 *                          escape: the name (optional) of the function to use for escaping strings<br/>
	 *                          base_path: the parent path (optional) of the views directory (defaults to the component folder)<br/>
	 *                          template_plath: the path (optional) of the layout directory (defaults to base_path + /views/ + view name<br/>
	 *                          helper_path: the path (optional) of the helper files (defaults to base_path + /helpers/)<br/>
	 *                          layout: the layout (optional) to use to display the view<br/>
	 *
	 * @since   11.1
	 */
	public function __construct($config = array ())
	{
		$this->_document = JFactory::getDocument();
		parent::__construct($config);
		$model = JModel::getInstance('Modules', 'ModulesModel');
		$this->setModel($model, true);
	}

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Include the component HTML helpers.
		$this->_path['template'] = array (JPATH_ROOT . DS . 'plugins/system/jsnframework/libraries/joomlashine/modules/tmpl');
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
		$this->_addAssets();
		parent::display($tpl);
	}
	
	/**
	 * Add the libraries css and javascript
	 *
	 *  @return void
	 *
	 * @since	1.6
	 */
	private function _addAssets()
	{
		$this->_document->addStyleSheet(JSN_URL_ASSETS . '/3rd-party/bootstrap/css/bootstrap.min.css');
		$this->_document->addStyleSheet(JSN_URL_ASSETS . '/joomlashine/css/jsn-gui.css');
	}
}