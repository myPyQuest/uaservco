<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: jsnrenders.php 16092 2012-09-17 07:21:15Z hiepnv $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
JSNFactory::localimport('helpers.html.layouts.jsnlayouthelper');
/**
 * @package		Joomla.Administrator
 * @subpackage	com_poweradmin
 * @since		1.7
 */
abstract class JSNRenderHelper
{	
	/**
	 * 
	 * Get view information
	 * 
	 * @param Array $vars
	 */
	public function getInfoView( $vars )
	{
		$componentName = $vars['option'];
		$view = $vars['view'];
	
		// load language
		$lang = JFactory::getLanguage();
		$lang->load($componentName.'.sys', JPATH_ADMINISTRATOR, null, false, false)
		||	$lang->load($componentName.'.sys', JPATH_ADMINISTRATOR.'/components/'.$componentName, null, false, false)
		||	$lang->load($componentName.'.sys', JPATH_ADMINISTRATOR, $lang->getDefault(), false, false)
		||	$lang->load($componentName.'.sys', JPATH_ADMINISTRATOR.'/components/'.$componentName, $lang->getDefault(), false, false);

		$value = Array();
		$value['layout'] = '';
		$value['extension'] = '';
		
		if ( !empty($componentName) ) {
			$value['extension']	= JText::_( $componentName,true );
			if (isset($vars['view'])) {
				// Attempt to load the view xml file.
				$file = JPATH_SITE.'/components/'.$componentName.'/views/'.$vars['view'].'/metadata.xml';
				if (JFile::exists($file) && $xml = simplexml_load_file($file)) {
					// Look for the first view node off of the root node.
					if ($view = $xml->xpath('view[1]')) {
						if (!empty($view[0]['title'])) {
							$vars['layout'] = isset($vars['layout']) ? $vars['layout'] : 'default';

							// Attempt to load the layout xml file.
							// If Alternative Menu Item, get template folder for layout file
							if (strpos($vars['layout'], ':') > 0)
							{
								// Use template folder for layout file
								$temp = explode(':', $vars['layout']);
								$file = JPATH_SITE.'/templates/'.$temp[0].'/html/'.$componentName.'/'.$vars['view'].'/'.$temp[1].'.xml';
								// Load template language file
								$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE, null, false, false)
								||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE.'/templates/'.$temp[0], null, false, false)
								||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE, $lang->getDefault(), false, false)
								||	$lang->load('tpl_'.$temp[0].'.sys', JPATH_SITE.'/templates/'.$temp[0], $lang->getDefault(), false, false);
							}
							else
							{
								// Get XML file from component folder for standard layouts
								$file = JPATH_SITE.'/components/'.$componentName.'/views/'.$vars['view'].'/tmpl/'.$vars['layout'].'.xml';
							}
							if (JFile::exists($file) && $xml = simplexml_load_file($file)) {
								// Look for the first view node off of the root node.
								if ($layout = $xml->xpath('layout[1]')) {
									if (!empty($layout[0]['title'])) {
										$value['layout'] = JText::_(trim((string) $layout[0]['title']),true);
									}
								}
							}
						}
					}
					unset($xml);
				}
				else {
					// Special case for absent views
					$value['layout'] = JText::_($componentName.'_'.$vars['view'].'_VIEW_DEFAULT_TITLE',true);
				}
			}
		}
		
		return $value;
	}
	/**
	 * 
	 * Dispatch component
	 *  
	 * @param Array $params
	 */
	static public function dispatch( $params = Array() )
	{
		$currentOption = JString::strtolower( $params['option'] );
		$currentView   = JString::strtolower( $params['view'] );
		$layout        = @JString::strtolower( $params['layout'] );
		$modelName     = JString::trim( $currentView );
		$modelName[0]  = JString::strtoupper( $params['view'][0] );		
			
		//Load front-end global language
		JFactory::getLanguage()->load("",  JPATH_ROOT);
		//Load front com_content language
		JFactory::getLanguage()->load($currentOption,  JPATH_ROOT);
		
		$modelSuffix = explode('_', $currentOption);
		$modelSuffix = JString::trim($modelSuffix[1]);
		
		JSNFactory::localimport('models.'.$currentOption.'.'.$currentView, 0);
		$modelSuffix[0] = JString::strtoupper($modelSuffix[0]);
		$modelSuffix .= 'Model'.$modelName;
		
		$PoweradminExtensionModel = 'Poweradmin'.$modelSuffix;
		if ( class_exists( $PoweradminExtensionModel ) ){
			$model = new $PoweradminExtensionModel();
			$data  = $model->getData( $params );
		}
		
		/**
		 * Render HTML of current view
		 */
		ob_start();
		
		if ( !empty($layout) ){
			$layoutName = $layout;
		}else{
			$layoutName = 'default';
		}
		
		//Set current option to global
		JRequest::setVar('jsnCurrentOption', $currentOption);
		//Set current layout to global
		JRequest::setVar('jsnCurrentLayout', $layout);
		JRequest::setVar('jsnCurrentView', $currentView);
		JRequest::setVar('jsnCurrentItemid', $params['Itemid']);
		
		$layoutPath = JSN_PATH_RENDER_COMPONENT_LAYOUT . DS . $currentOption . DS . $currentView . DS . $layoutName . '.php';
		
		if ( file_exists( $layoutPath ) ){
			include( $layoutPath );
		}else{
			$info = JSNRenderHelper::getInfoView( $params );
			$message = JText::sprintf('JSN_RAWMODE_MESSAGE_NOT_SUPPORTED_YET', '"'.JString::strtoupper($info['layout']).'"', '"'.JString::strtoupper($info['extension']).'"');			
			echo JSNHtmlHelper::openTag('div', array('id'=>'show-message-not-supported-yet', 'class' => 'show-message-not-supported-yet'))
					.$message
			     .JSNHtmlHelper::closeTag('div');
		}
		
		$contents = ob_get_contents();
		
		ob_end_clean();
		
		return $contents;
	}
}
