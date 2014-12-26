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
 * Model supports Zoo extension Category searching
 *
 *
 * @author JoomlaShine
 * @since 1.2.0
 *
 */
class PoweradminModelEasyblogItemSearch extends JModel
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
	 * Method to proceed sql query
	 *
	 * @return String
	 */
	function getQuery()
	{
		// Get the WHERE and ORDER BY clauses for the query
		$where		= $this->_buildQueryWhere();
		$orderby	= $this->_buildQueryOrderBy();
		$db			= $this->getDBO();

		$query	= 'SELECT DISTINCT a.*, tp.`team_id`, t.`title` as `teamname`, g.`group_id` as `external_group_id`';
		$query	.= ' FROM ' . $db->nameQuote( '#__easyblog_post' ) . ' AS a ';


		$query	.= ' LEFT JOIN #__easyblog_team_post AS tp ';
		$query	.= ' ON a.`id`=tp.`post_id`';

		$query	.= ' LEFT JOIN #__easyblog_team AS t ';
		$query	.= ' ON tp.`team_id`=t.`id`';

		$query	.= ' LEFT JOIN #__easyblog_external_groups AS g ';
		$query	.= ' ON a.`id` = g.`post_id`';

		$query	.= $where . ' ' . $orderby;

		return $query;
	}

	/**
	 * Method to build WHERE statement of sql query
	 * 
	 * @return String
	 */
	function _buildQueryWhere()
	{
		$mainframe			= JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		
		$db					= $this->getDBO();
		
		$filter_language 	= $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');
		$search 			= $mainframe->getUserStateFromRequest( $option.$view.'search', 'keyword', '', 'string');
		$search 			= $db->getEscaped( trim(JString::strtolower( $search ) ) );

		$source 			= JRequest::getVar( 'filter_source' , '-1' );

		$where = array();
		

		if( $filter_language && $filter_language != '*')
		{
			$where[]	= ' a.`language`= ' . $db->Quote( $filter_language );	
		}

		if ($search)
		{
			$where[] = ' LOWER( a.title ) LIKE \'%' . $search . '%\' ';
		}

		$where[] 	= ' `ispending` = ' . $db->Quote('0');

		$where 		= count( $where ) ? ' WHERE ' . implode( ' AND ', $where ) : '' ;

		return $where;
	}

	/**
	 * Method to build ODER BY statement of sql query
	 *
	 * @return String
	 */
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
