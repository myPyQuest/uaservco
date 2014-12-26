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

// Import Joomla library
jimport('joomla.application.component.view');

/**
 * View class of JSN Positions.
 *
 *
 * @package  JSN_Framework
 * @since    1.0.3
 */
class JSNPositionsView extends JView
{
	/**
	 * Custom sript
	 * @var array
	 */
	private static $customScripts = array();
	
	/**
	 * Display page
	 * 
	 * @see JView::display()
	 */
	public function display($tpl = null)
	{	
		global $notSupportedTemplateAuthors;
		$app = JFactory::getApplication();
		$document = JFactory::getDocument();			
		$template = JSNTemplateHelper::getInstance();
		$onPositionClick = '';		
		$initFilter = '';
		$displayNotice = JRequest::getInt('notice');
		$bypassNotif	= JRequest::getVar('bypassNotif', '');
		
		//Get template author.
		$templateAuthor = $template->getAuthor();		
		
		//Display notice and return if template not supported.
		if(!$displayNotice && in_array($templateAuthor, $notSupportedTemplateAuthors) && !$bypassNotif){
			$msg 	= JText::_('JSN_EXTFW_ERROR_TEMPLATE_NOT_SUPPORTED');
			$app->redirect($_SERVER["REQUEST_URI"].'&notice=1', $msg);			
		}else if($displayNotice){
			return;
		}
		
		$document->addStyleSheet(JSN_URL_ASSETS . '/joomlashine/css/jsn-positions.css');
		$document->addScript(JSN_URL_ASSETS . '/3rd-party/jquery/jquery-1.7.1.min.js');
		
		if(isset($this->filterEnabled) && $this->filterEnabled){
			$document->addScript(JSN_URL_ASSETS . '/joomlashine/js/positions.filter.js');
			$initFilter = 'changeposition = new $.visualmodeFilter({});';
		}
		
		if(isset($this->customScripts)){
			$document->addScriptDeclaration( implode('\n', $this->customScripts) );
		} 
		
		
		$onPositionClick = isset($this->onPositionClickCallBack) ? implode('\n', $this->onPositionClickCallBack) : '';
		
		$_customScript = "
			var changeposition;
			(function($){				
				$(document).ready(function (){
					$('.jsn-position').each(function(){
						$(this)[0].oncontextmenu = function() {
							return false;
						}
					})
					.click(function () {
						" . $onPositionClick . "
					});
				});
				" . $initFilter . "
			})(jQuery);
		";		
		$document->addScriptDeclaration($_customScript);
				
		$jsnrender = JSNPositionsRender::getInstance();
		$jsnrender->renderPage( JURI::root().'index.php?poweradmin=1&vsm_changeposition=1&tp=1', 'changePosition' );
		$this->assignRef('jsnrender', $jsnrender);		
		parent::display($tpl);
	}
	
	/**
	 * Method to add customs javacript into page
	 * 
	 * @param string $customScript
	 */
	
	public function addCustomScripts($customScript = '')
	{
		$this->customScripts[] = $customScript;
		return;
	}
	
	/**
	 * Method to add javascript callback
	 * functions after a position clicked
	 * 
	 * @param string $script
	 */
	public function addPositionClickCallBack($script = '')
	{
		$this->onPositionClickCallBack[] = $script;
		return;
	}
	
	/**
	 * Method to enable/disable position filter
	 * 
	 * @param bool $filterEnabled
	 */
	public function setFilterable($filterEnabled = false)
	{
		$this->filterEnabled = $filterEnabled;
	}	

	
	public function _addAssets()
	{		
	
	}
}