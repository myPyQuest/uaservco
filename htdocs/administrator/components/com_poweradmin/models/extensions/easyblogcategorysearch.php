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

class PoweradminModelEasyblogCategorySearch extends JModel
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
	 * Method to proceed page start
	 *
	 * @return Int
	 */
	public function getStart()
	{
		$app = JFactory::getApplication();
	
		$start = $app->getUserStateFromRequest('com_poweradmin.search.easyblog.list.start', 'limitstart', 0, 'int');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();
		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}
	
		return $start;
	}

	
	public function getItems()
	{
		@require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_easyblog' . DS . 'models' . DS . 'categories.php';
		$query = $this->getQuery();
		$dbo = JFactory::getDBO();
		$dbo->setQuery($query);
		$items = $dbo->loadObjectList();
		
		$model = new EasyBlogModelCategories();
		
		for( $i = 0 ; $i < count( $items ); $i++ )
		{
			$category				= $items[ $i ];
		
			$category->count		= $model->getUsedCount( $category->id );
			$category->child_count	= $model->getChildCount( $category->id );
		
			$ordering[$category->parent_id][] = $category->id;
			$items[$i]	= $category;
		}
		
		return $items;
	}

	public function getPagination()
	{
		jimport('joomla.html.pagination');
		
		$app = JFactory::getApplication();
				
		return new JPagination ($this->getTotal(), $this->getStart(), $this->getState('list.limit') );
	}

	private function getQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere();
		$orderby	= $this->_buildQueryOrderBy();
		$db			= $this->getDBO();

		$query	= 'SELECT a.*, ';
		$query	.= '( SELECT COUNT(id) FROM ' . $db->nameQuote( '#__easyblog_category' ) . ' ';
		$query	.= 'WHERE lft < a.lft AND rgt > a.rgt AND a.lft != ' . $db->Quote( 0 ) . ' ) AS depth ';
		$query	.= 'FROM ' . $db->nameQuote( '#__easyblog_category' ) . ' AS a ';
		$query	.= $where;
		

		$query	.= $orderby;

		return $query;

	}

	
	function _buildQueryWhere()
	{
		$mainframe			= JFactory::getApplication();		
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db					= $this->getDBO();

		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'keyword', '', 'string');
		$search = JString::strtolower($search);

		$where = array();

		$where[]            = $db->nameQuote( 'lft' ) . '!=' . $db->Quote( 0 );
		
		if ($search)
		{
			$where[] = ' LOWER( title ) LIKE \'%' . $search . '%\' ';
		}

		$where 		= ( count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' );

		return $where;
	}

	function _buildQueryOrderBy()
	{
		$mainframe			= JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		
		$filter_order		= $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'a.title', 'cmd');
		$filter_order_Dir	= $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');

		$orderby 			= ' ORDER BY '.$filter_order.' '.$filter_order_Dir.', ordering';

		return $orderby;
	}

}
