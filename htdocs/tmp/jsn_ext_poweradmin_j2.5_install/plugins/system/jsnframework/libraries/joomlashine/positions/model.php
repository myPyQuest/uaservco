<?php
/**
 * @version     $Id: model.php 15896 2012-09-07 09:42:55Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Update
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Import Joomla libraries
jimport('joomla.application.component.model');

/**
 * Model class of JSN Positions library.
 *
 * 
 *
 * @package  JSN_Framework
 * @since    1.0.3
 */
class JSNPositionsModel extends JModel
{
	/** private variable **/
	private $_template = '';
	
	/** private variable **/
	private $_document  = '';
	
	public function __construct()
	{				
		$this->_template = JSNTemplateHelper::getInstance();
		$this->_document = JFactory::getDocument();
	}
	/**
	 * Return global JSNTemplate object
	 *
	 */
	public static function _getInstance()
	{
		static $instances;
	
		if (!isset($instances)) {
			$instances = array();
		}
	
		if (empty($instances['JSNPositionsModel'])) {
			$instance	= new JSNPositionsModel();
			$instances['JSNPositionsModel'] = $instance;
		}
	
		return $instances['JSNPositionsModel'];
	}
	/**
	 *
	 * Change format of HTML when render modules using base in joomla
	 *
	 * @return: Set data for joomla document
	 */
	public function renderModules()
	{
		$renderer	= $this->_document->loadRenderer('module');
		$positions  = $this->_template->getTemplatePositions();

		if ($positions != null){
			/** if template using joomla modules load **/
			foreach( $positions as $position )
			{
				if ( $this->_document->countModules( $position->name ) ){
					$buffer  = JSNHtmlHelper::openTag('div', array('class'=>"jsn-element-container_inner"));
					$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"jsn-position", 'id'=>$position->name.'-jsnposition'));
					foreach (JModuleHelper::getModules($position->name) as $mod) {
						$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"poweradmin-module-item", 'id'=>$mod->id.'-jsnposition-published', 'title'=>$mod->title, 'showtitle'=>$mod->showtitle))
						             .JSNHtmlHelper::openTag('div', array('id'=>$mod->id.'-content', 'class'=>'jsnpw-module-content'))
						                .$renderer->render($mod, $position->params)
						             .JSNHtmlHelper::closeTag('div')
						           .JSNHtmlHelper::closeTag('div');
					}
					$buffer .= JSNHtmlHelper::closeTag('div');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$this->_document->setBuffer( $buffer, 'modules', "$position->name");
				}
			}
		}else{
			/** if template not set load positions in index.php file **/
			$positions = $this->_template->loadXMLPositions();
			foreach( $positions as $position )
			{
				if ( $this->_document->countModules( $position->name ) ){
					$buffer  = JSNHtmlHelper::openTag('div', array('class'=>"jsn-element-container_inner"));
					$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"jsn-position", 'id'=>$position->name.'-jsnposition'));
					foreach (JModuleHelper::getModules($position) as $mod) {
						$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"poweradmin-module-item", 'id'=>$mod->id.'-jsnposition-published', 'title'=>$mod->title, 'showtitle'=>$mod->showtitle))
						              .JSNHtmlHelper::openTag('div', array('id'=>"moduleid-'.$mod->id.'-content"))
						                 .$renderer->render($mod, $position->params)
						              .JSNHtmlHelper::closeTag('div')
						           .JSNHtmlHelper::closeTag('div');
					}
					$buffer .= JSNHtmlHelper::closeTag('div');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$this->_document->setBuffer( $buffer, 'modules', "$position->name");
				}
			}
		}
	}
	
	/**
	 *
	 * Only render positions and set data to joomla document
	 *
	 * @return: Set data
	 */
	public function renderEmptyModule()
	{
		$positions  = $this->_template->getTemplatePositions();

		if ($positions != null){
			/** if template using joomla modules load **/
			foreach( $positions as $position )
			{
				if ( $this->_document->countModules( $position->name ) ){
					$buffer  = JSNHtmlHelper::openTag('div', array('class'=>"jsn-element-container_inner"));
					$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"jsn-position", 'id'=>$position->name.'-jsnposition'));
					$buffer .= JSNHtmlHelper::openTag('p').$position->name.JSNHtmlHelper::closeTag('p');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$this->_document->setBuffer( $buffer, 'modules', "$position->name");
				}
			}
		}else{
			/** if template not set load positions in index.php file **/
			$positions = $this->_template->loadXMLPositions();
			foreach( $positions as $position )
				{
				if ( $this->_document->countModules( $position->name ) ){
					$buffer  = JSNHtmlHelper::openTag('div', array('class'=>"jsn-element-container_inner"));
					$buffer .= JSNHtmlHelper::openTag('div', array('class'=>"jsn-position", 'id'=>$position->name.'-jsnposition'));
					$buffer .= JSNHtmlHelper::openTag('p').$position->name.JSNHtmlHelper::closeTag('p');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$buffer .= JSNHtmlHelper::closeTag('div');
					$this->_document->setBuffer( $buffer, 'modules', "$position->name");
				}
			}
		}
	}	
	
	/**
	 *
	 * Change format of HTML when dispatch using base in joomla
	 *
	 * @return: Set data buffer for joomla document
	 */
	public function renderComponent()
	{
		$app	   = JFactory::getApplication();
		$itemid    = JRequest::getVar('itemid', '');
		$menu	   = $app->getMenu();

		if ($itemid){
			$menuItem = $menu->getItem($itemid);
		}else{
			$menuItem = $menu->getActive();
		}
		$uri       = new JURI(JURI::current());
		$config = JFactory::getConfig();
		if ($config->get('sef') == 1){
			$route     = new JRouterSite(array('mode'=>1));
			$params    = $route->parse($uri);
			if (empty($params['id']) && !empty($menuItem->id)){
				$uri->parse($menuItem->link);
				$params = $route->parse($uri);
			}
		}else{
			parse_str($uri->getQuery(), $params);
			if (empty($params['id']) && !empty($menuItem->id)){
				$uri->parse($menuItem->link);
				parse_str($uri->getQuery(), $params);
			}
		}

		if (!empty($params['option'])){
			$key = array_search($params['option'], array('', 'com_content', 'com_categories', 'com_banner', 'com_weblinks', 'com_contact', 'com_newsfeeds', 'com_search', 'com_redirect'));
			if ($key){
				if (!empty($params['id'])){
					if ($params['view'] == 'category'){
						$editLink = 'option=com_categories&task=category.edit&id='.$params['id'].'&extension='.$params['option'].'&tmpl=component';
						$task = 'category.apply';
					}else{
					    switch($key)
					    {
					    	case 1: //com_content
				    			$editLink = 'option=com_content&task=article.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'article.apply';
					    		break;
					    	case 2: //com_categories
					    		$editLink = 'option=com_categories&task=category.edit&id='.$params['id'].'&tmpl=component';
					    		$task = 'category.apply';
					    		break;
					    	case 3:
					    		if($params['view'] == 'client'){
					    			$editLink = 'option=com_banners&task=client.edit&id='.$params['id'].'&tmpl=component';
					    			$task = 'client.apply';
					    		}else{
					    			$editLink = 'option=com_banners&task=banner.edit&id='.$params['id'].'&tmpl=component';
					    			$task = 'bannber.apply';
					    		}
					    		break;
					    	case 4:
				    			$editLink = 'option=com_weblinks&task=weblink.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'weblink.apply';
					    		break;
					    	case 5:
				    			$editLink = 'option=com_contact&task=contact.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'contact.apply';
					    		break;
					    	case 6:
				    			$editLink = 'option=com_newsfeeds&task=newsfeed.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'newsfeed.apply';
					    		break;
					    	case 7:
				    			$editLink = 'option=com_search&task=search.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'search.apply';
					    		break;
					    	case 8:
				    			$editLink = 'option=com_redirect&task=link.edit&id='.$params['id'].'&tmpl=component';
				    			$task = 'link.apply';
					    		break;
					    }
					}
				}else{
					$editLink = 'option=com_menus&task=item.edit&id='.@$menuItem->id.'&tmpl=component';
					$task = 'item.save';
				}
			}else{
				//in feature
				$editLink = '';
				$task = '';
			}
		}else{
			$editLink = '';
			$task = '';
		}

		$component = $this->_document->getBuffer( 'component' );
		$component_buffer =  JSNHtmlHelper::openTag('div',  array('class'=>"jsn-component-container", 'id'=>"jsnrender-component"))
							.JSNHtmlHelper::openTag('div',  array('class'=>"jsn-show-component-container"))
							.JSNHtmlHelper::openTag('div',  array('class'=>"jsn-show-component"))
							.JSNHtmlHelper::openTag('span', array('id'=>"tableshow", 'itemid'=>@$menuItem->id, 'editlink'=>base64_encode($editLink), 'title'=>$this->_document->getTitle(), 'task'=>$task)).JSNHtmlHelper::closeTag('span')
							.JSNHtmlHelper::closeTag('div')
							.JSNHtmlHelper::closeTag('div')
							.$component
							.JSNHtmlHelper::closeTag('div');
		$this->_document->setBuffer($component_buffer, 'component');
	}
	
	/**
	 *
	 * Only render empty component
	 */
	public function renderEmptyComponent()
	{
		$component = $this->_document->getBuffer( 'component' );
		$component_buffer =  JSNHtmlHelper::openTag('div',  array('class'=>"jsn-component-container", 'id'=>"jsnrender-component"))
							.JSNHtmlHelper::openTag('p').$this->_document->getTitle().JSNHtmlHelper::closeTag('p')
							.JSNHtmlHelper::closeTag('div');
		$this->_document->setBuffer($component_buffer, 'component');
	}
}