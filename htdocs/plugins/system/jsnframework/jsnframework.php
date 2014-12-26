<?php
/**
 * @version    $Id: jsnframework.php 16701 2012-10-04 10:01:18Z hiepnv $
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * System plugin for initializing JSN Framework.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class PlgSystemJSNFramework extends JPlugin
{
	/**
	 * @var JApplication
	 */
	private $_app = null;

	/**
	 * @var JLanguage
	 */
	private $_language = null;

	/**
	 * Register JSN Framework initialization.
	 *
	 * @return  void
	 */
	public function onAfterInitialise()
	{
		// Initialize JSN Framework
		require_once dirname(__FILE__) . DS . 'libraries' . DS . 'loader.php';
		require_once dirname(__FILE__) . DS . 'define.php';

		// Get application object
		$this->_app = JFactory::getApplication();

		// Get active language
		$this->_language = JFactory::getLanguage();

		// Check if language file exists for active language
		if ( ! file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'language' . DS . $this->_language->getDefault() . DS . $this->_language->getDefault() . '.plg_system_jsnframework.ini'))
		{
			// If requested component has the language file, install then load it
			if (file_exists(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . $this->_app->input->getCmd('option') . DS . 'language' . DS . 'admin' . DS . $this->_language->getDefault() . DS . $this->_language->getDefault() . '.plg_system_jsnframework.ini'))
			{
				JSNLanguageHelper::install((array) $this->_language->getDefault(), false, true);
				$this->_language->load('plg_system_jsnframework', JPATH_BASE, null, true);
			}
			// Otherwise, try to load language file from plugin directory
			else
			{
				$this->_language->load('plg_system_jsnframework', JSN_PATH_FRAMEWORK, null, true);
			}
		}
		else
		{
			$this->_language->load('plg_system_jsnframework', JPATH_BASE, null, true);
		}
	}

	
	/**
	 * Before render needs using this function to make format of HTML of modules
	 *
	 * @return: Changed HTML format
	 */
	public function onBeforeRender()
	{		
		if ($this->_app->isAdmin()) return;
	
		$poweradmin         = JRequest::getCmd('poweradmin', 0);
		$vsm_changeposition = JRequest::getCmd('vsm_changeposition', 0);
		$jsnHelper 			= JSNPositionsModel::_getInstance();
		
		if ( $poweradmin == 1 ){
			if( $vsm_changeposition == 0 ){
				$jsnHelper->renderModules();
			}else if( $vsm_changeposition == 1 ){
				$jsnHelper->renderEmptyModule();
			}
		}
	}
	
	/**
	 * Do some output manipulation.
	 *
	 * Auto-inject <b>jsn-master tmpl-nameOfDefaultTemplate</b> into the class
	 * attribute of <b>&lt;body&gt;</b> tag if not already exists. This
	 * automation only affects backend page.
	 *
	 * @return  void
	 */
	public function onAfterRender()
	{
		// Get the rendered HTML code
		$html = JResponse::getBody();

		// Continue only if this is admin page
		if ($this->_app->isAdmin())
		{
			// Get body tag from responce
			if (preg_match('/<body[^>]*>/i', $html, $match) AND strpos($match[0], 'jsn-master tmpl-' . $this->_app->getTemplate()) === false)
			{
				if (strpos($match[0], 'class=') === false)
				{
					$match[1] = substr($match[0], 0, -1) . ' class=" jsn-master tmpl-' . $this->_app->getTemplate() . ' ">';
				}
				else
				{
					$match[1] = str_replace('class="', 'class=" jsn-master tmpl-' . $this->_app->getTemplate() . ' ', $match[0]);
				}

				$html = str_replace($match[0], $match[1], $html);

				// Set manipulated HTML code
				JResponse::setBody($html);
			}
		}

		// Attach JS declaration
		$html = preg_replace('/<\/head>/i', JSNHtmlAsset::buildHeader() . '</head>', $html);
		JResponse::setBody($html);
	}
	
	/**
	 * Proceed positions rendering
	 * 
	 * Remove default tp=1 layout, replace by jsn style to 
	 * show page positions
	 * 
	 * @return void
	 */
	public function onAfterDispatch()
	{
		$poweradmin         = JRequest::getCmd('poweradmin', 0);
		$vsm_changeposition = JRequest::getCmd('vsm_changeposition', 0);
	
		$jsnHelper 			= JSNPositionsModel::_getInstance();

		if ($this->_app->isAdmin() &&
				JRequest::getVar('format', '') != 'raw' &&
				JRequest::getVar('option', '') == 'com_poweradmin'  &&
				JRequest::getVar('view') != 'update') {
			$JSNMedia = JSNFactory::getMedia();
			$JSNMedia->addMedia();
			return;
		}
		
		if ($poweradmin == 1) {
			if( $vsm_changeposition == 0 ) {
				$jsnHelper->renderComponent();
			}
			else if($vsm_changeposition == 1) {
				$jsnHelper->renderEmptyComponent();
			}
		}
	}
}
