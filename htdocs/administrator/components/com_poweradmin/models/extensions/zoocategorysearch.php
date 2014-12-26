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

/**
 * Model supports Zoo extension Category searching
 *
 *
 * @author JoomlaShine
 * @since 1.2.0
 *
 */
class PoweradminModelZooCategorySearch extends JModel
{
	/**
	 * Method to get total of found record
	 *
	 * @return number
	 */
	public function getTotal () 
	{
		$query[] = "SELECT count(*) FROM(";
		$query[] = $this->getQuery();
		$query[] = ") AS cnt";
		$query = implode(" ", $query);
	
		$dbo = JFactory::getDBO();
		$dbo->setQuery($query);
	
		return $dbo->loadResult();
	}
	
	/**
	 * Method to get found items
	 * 
	 * @return ObjectList
	 */
	public function getItems () 
	{
		$query = $this->getQuery();
		$dbo = JFactory::getDBO();
		$dbo->setQuery($this->getQuery(), $this->getStart(), $this->getState('list.limit'));
		return $dbo->loadObjectList();
	}
	
	/**
	 * Method to get pagination
	 * 
	 * @return Object
	 */	
	public function getPagination () 
	{
		jimport('joomla.html.pagination');
		
		$app = JFactory::getApplication();
				
		return new JPagination ($this->getTotal(), $this->getStart(), $this->getState('list.limit') );
	}
	
	/**
	 * Method to proceed sql query
	 * 
	 * @return String
	 */
	private function getQuery () 
	{
			$app 	= JFactory::getApplication();
			$option = JRequest::getCmd('option');
			$view 	= JRequest::getCmd('view');
			
			$search = $app->getUserStateFromRequest($option.$view.'search', 'keyword', '', 'string');
			$search = JString::strtolower($search);
			
			$query[] = 'SELECT';
			$query[] = 'c.*, GROUP_CONCAT(DISTINCT ci.item_id) as item_ids';
			
			$query[] = 'FROM';
			$query[] = '#__zoo_category as c  USE INDEX (APPLICATIONID_ID_INDEX) LEFT JOIN #__zoo_category_item as ci ON ci.category_id = c.id';
			
			$query[] = 'WHERE';
			$query[] = "c.name LIKE '%{$search}%'";
			
			$query[] = 'GROUP BY';
			$query[] = 'c.id';
		
			return implode(" ", $query);		
	}
	
	/**
	 * Method to proceed page start
	 * 
	 * @return Int
	 */
	public function getStart()
	{
		$app = JFactory::getApplication();
	
		$start = $app->getUserStateFromRequest('com_poweradmin.search.zoocategory.list.start', 'limitstart', 0, 'int');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();
		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}
	
		return $start;
	}
}