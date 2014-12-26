<?php
/**
 * @version    $Id$
 * @package    JSN_Poweradmin
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.modeladmin');
$vm_config = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php';
$vm_model  = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'vmmodel.php';
$vm_product = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' . DS . 'category.php';
if(file_exists($vm_config) &&  file_exists($vm_model) && file_exists($vm_product)){
	require_once $vm_config;
	require_once $vm_model;
	require_once $vm_product;
}else{
	JError::raiseError(500, 'Site-Search does not support current version of Vituemart <a href="index.php">Return to homepage</a>.');	
}
 

/**
 * Model supports Zoo extension Category searching
 *
 *
 * @author JoomlaShine
 * @since 1.2.0
 *
 */
class PoweradminModelVmCategorySearch extends JModel
{
	
	
	/**
	 * Method to get total of found record
	 *
	 * @return number
	 */
	public function getTotal()
	{
		$app = JFactory::getApplication();
		$keyword = $app->getUserStateFromRequest('search.keyword', 'keyword', '');		
		
		$model = VmModel::getModel('category');		
		return count($model->getCategories(false, false, false, $keyword));		
	}
		

	/**
	 * Method to get Items
	 *
	 * @return ObjectList
	 */
	public function getItems()
	{			
		$app = JFactory::getApplication();			
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$keyword = $app->getUserStateFromRequest('search.keyword', 'keyword', '');
		
		$order		= $app->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'category_name', 'cmd');
		$order_Dir	= $app->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		
		$model = VmModel::getModel('category');
		$model->checkFilterOrder($order);
		$model->checkFilterDir($order_Dir);
		return $model->getCategoryTree(0,0, false, $keyword);
	}
	
	/**
	 * Method to get pagination
	 *
	 * @return Object
	 */
	public function getPagination()
	{
		$model = VmModel::getModel('category');
		$pagination = $model->getPagination();
		return $pagination;
	}
	
	
}
