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

/**
 * Model supports Zoo extension item searching
 * 
 * 
 * @author JoomlaShine
 * @since 1.2.0
 *
 */
class PoweradminModelZooItemSearch extends JModel
{
	
	/**
	 * Method to get total of found record
	 * 
	 * @return number
	 */
	public function getTotal()
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
	 * Method to get Items
	 * 
	 * @return ObjectList
	 */
	public function getItems()
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
	public function getPagination()
	{
		jimport('joomla.html.pagination');
		
		$app = JFactory::getApplication();
				
		return new JPagination ($this->getTotal(), $this->getStart(), $this->getState('list.limit') );
	}
	
	/**
	 * Method to analyze and return sql query
	 * 
	 * @return String
	 */
	private function getQuery()
	{
		$app = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_zoo');
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');		
				
		$filter_order = $app->getUserStateFromRequest('com_poweramdin.search.zoo.filter_order', 'filter_order', 'a.id', 'cmd');
		$filter_order_Dir = $app->getUserStateFromRequest('com_poweramdin.search.zoo.filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');		
		
		$search = $app->getUserStateFromRequest($option.$view.'.keyword', 'keyword', '', 'string');
		$search = JString::strtolower($search);		
		
		$language = $app->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');

		$query[] = 'SELECT';
		
		$query[] = 'a.*,g.title AS groupname, EXISTS (SELECT true FROM #__zoo_category_item WHERE item_id = a.id AND category_id = 0) as frontpage';
		
		$query[] = 'FROM #__zoo_item AS a';
		$query[] = 'LEFT JOIN #__usergroups AS g ON g.id = a.access';
		$query[] = "WHERE LOWER(a.name) LIKE '%{$search}%'";
		
		$filter_order = $filter_order ? $filter_order : 'a.name';
		$query[] = " ORDER BY {$filter_order} {$filter_order_Dir} ";		
		
		return implode(" ", $query);	
		
	}	
	
	/**
	 * Method to get current time offset
	 * 
	 * @return String
	 */
	public function getTimeOffset()
	{
		$config =& JFactory::getConfig();
      	$offset = $config->getValue('config.offset');
      	return $offset;
	}
	
	/**
	 * Method to proceed page start
	 * 
	 * @return Int
	 */
	public function getStart()
	{			
		$app = JFactory::getApplication();
		
		$start = $app->getUserStateFromRequest('com_poweradmin.search.zoo.list.start', 'limitstart', 0, 'int');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();
		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}
	
		return $start;
	}
}