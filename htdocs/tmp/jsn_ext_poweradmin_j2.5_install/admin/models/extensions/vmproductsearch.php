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

$vm_config = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php';
$vm_model  = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'vmmodel.php';
$vm_product = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'models' . DS . 'product.php';
if(file_exists($vm_config) &&  file_exists($vm_model) && file_exists($vm_product)){
	require_once $vm_config;
	require_once $vm_model;
	require_once $vm_product;
}else{
	JError::raiseError(500, 'Site-Search does not support current version of Vituemart <a href="index.php">Return to homepage</a>.');	
}
 

/**
 * Model supports VirtueMart extension products searching
 *
 *
 * @author JoomlaShine
 * @since 1.2.0
 *
 */
class PoweradminModelVmProductSearch extends JModel
{
	var $_id 			= 0;
	var $_data			= null;
	var $_query 		= null;
	
	var $_total			= null;
	var $_pagination 	= 0;
	var $_limit			= 0;
	var $_limitStart	= 0;
	var $_maintable 	= '#__virtuemart_products';	// something like #__virtuemart_calcs
	var $_maintablename = '';
	var $_idName		= '';
	var $_cidName		= 'cid';
	var $_togglesName	= null;
	var $_selectedOrderingDir = 'DESC';
	private $_withCount = true;
	var $_noLimit = false;
	var $items;
	/**
	 * Method to get total of found record
	 *
	 * @return number
	 */
	public function getTotal()
	{
		$model = VmModel::getModel('product');
		$this->items = $model->getProductListing(false,false,false,false,true);
 		return count($this->items);
		
	}
		

	/**
	 * Method to get Items
	 *
	 * @return ObjectList
	 */
	public function getItems()
	{				
		$model = VmModel::getModel('product');
		$this->items = $model->getProductListing(false,false,false,false,true);
		return $this->items;
	}
	
	/**
	 * Method to get pagination
	 *
	 * @return Object
	 */
	public function getPagination()
	{
		$model = VmModel::getModel('product');
		$pagination = $model->getPagination();		
		return $pagination;
	}
		

	/**
	 * Method to render link to parent product
	 *
	 * @return String
	 */
	function displayLinkToParent($product_parent_id) {
		$db = JFactory::getDBO();
		$db->setQuery(' SELECT * FROM `#__virtuemart_products_'.VMLANG.'` as l JOIN `#__virtuemart_products` using (`virtuemart_product_id`) WHERE `virtuemart_product_id` = '.$product_parent_id);
		if ($parent = $db->loadObject()){
			$result = JText::sprintf('COM_VIRTUEMART_LIST_CHILDREN_FROM_PARENT', $parent->product_name);
			echo JHTML::_('link', JRoute::_('index.php?view=product&product_parent_id='.$product_parent_id.'&option=com_virtuemart'), $parent->product_name, array('title' => $result));
		}
	}

	

}
