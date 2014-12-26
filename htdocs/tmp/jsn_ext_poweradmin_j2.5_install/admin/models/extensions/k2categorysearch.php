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

require_once JPATH_ADMINISTRATOR.DS.'components/com_k2/models/categories.php';

class PoweradminModelK2CategorySearch extends K2ModelCategories
{
	// public function getItems () {
		// JRequest::setVar('search', $this->getState('filter.search'));
		// JRequest::setVar('filter_order', $this->getState('list.ordering', 'c.ordering'));
// 		
		// return parent::getData();
	// }
// 	
	// public function getTotal () {
		// JRequest::setVar('search', $this->getState('filter.search'));
		// return parent::getTotal();
	// }
// 	
	// public function getPagination () {
		// jimport('joomla.html.pagination');
// 		
		// $app = JFactory::getApplication();
// 		
		// $limit = $app->getUserStateFromRequest ('global.list.limit', 'limit', $app->getCfg( 'list_limit' ), 'int');
		// $limitstart = $app->getUserStateFromRequest ('com_poweramdin.k2.categories.limitstart', 'limitstart', 0, 'int');
// 		
		// return new JPagination ($this->getTotal(), $limitstart, $limit );
	// }
// 	
	public function getItems () {
		JRequest::setVar('search', $this->getState('filter.search'));
		
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);

		$dbo = JFactory::getDBO();
		$dbo->setQuery($this->getQuery(), $limitstart, $limit);
		$rows = $dbo->loadObjectList();
		
		if(K2_JVERSION=='16'){
			foreach($rows as $row){
				$row->parent_id = $row->parent;
				$row->title = $row->name;
			}
		}
		$categories = array();

		if ($search) {
			foreach ($rows as $row) {
				$row->treename = $row->name;
				$categories[]=$row;
			}

		}
		else {
			$categories = $this->indentRows($rows);
		}
		if (isset($categories)){
			$total = count($categories);
		}
		else {
			$total = 0;
		}
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		$categories = @array_slice($categories, $pageNav->limitstart, $pageNav->limit);
		foreach($categories as $category) {
			$category->parameters = new JParameter($category->params);
			if($category->parameters->get('inheritFrom')) {
				$db->setQuery("SELECT name FROM #__k2_categories WHERE id = ".(int)$category->parameters->get('inheritFrom'));
				$category->inheritFrom = $db->loadResult();
			}
			else {
				$category->inheritFrom = '';
			}
		}
		return $categories;
	}
	
	public function getTotal () {
		JRequest::setVar('search', $this->getState('filter.search'));
		
		$query = $this->getQuery();
		$query = preg_replace('/^SELECT(.*?)FROM/i', 'SELECT COUNT(*) FROM', $query);
		$dbo = JFactory::getDBO();
		$dbo->setQuery($query);
		
		return $dbo->loadResult();
	}
	
	public function getPagination () {
		jimport('joomla.html.pagination');
		
		$app = JFactory::getApplication();
		
		$limit = $app->getUserStateFromRequest ('global.list.limit', 'limit', $app->getCfg( 'list_limit' ), 'int');
		$limitstart = $app->getUserStateFromRequest ('com_poweramdin.k2.categories.limitstart', 'limitstart', 0, 'int');
		
		return new JPagination ($this->getTotal(), $limitstart, $limit );
	}
	
	private function getQuery () {
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'c.ordering', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');

		$query = "SELECT c.*, g.name AS groupname, exfg.name as extra_fields_group FROM #__k2_categories as c LEFT JOIN #__groups AS g ON g.id = c.access LEFT JOIN #__k2_extra_fields_groups AS exfg ON exfg.id = c.extraFieldsGroup WHERE c.id>0";

		if ($this->getState('filter.published') != 'all'){
			$query .= " AND c.trash=0";
		}

		if ($search) {
			$query .= " AND LOWER( c.name ) LIKE ".$db->Quote('%'.$db->getEscaped($search, true).'%', false);
		}

		if ($filter_state > -1) {
			$query .= " AND c.published={$filter_state}";
		}
		if ($language) {
			$query .= " AND c.language = ".$db->Quote($language);
		}

		$query .= " ORDER BY {$filter_order} {$filter_order_Dir}";

		if(K2_JVERSION=='16'){
			$query = JString::str_ireplace('#__groups', '#__viewlevels', $query);
			$query = JString::str_ireplace('g.name AS groupname', 'g.title AS groupname', $query);
		}
		
		return $query;
	}
}
