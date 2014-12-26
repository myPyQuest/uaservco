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

require_once JPATH_ADMINISTRATOR.DS.'components/com_k2/models/items.php';

class PoweradminModelK2ItemSearch extends K2ModelItems
{
	public function getItems () {
		JRequest::setVar('search', $this->getState('filter.search'));
		
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');

		$dbo = JFactory::getDBO();
		$dbo->setQuery($this->getQuery(), $limitstart, $limit);
		
		return $dbo->loadObjectList();
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
		$limitstart = $app->getUserStateFromRequest ('com_poweramdin.k2.items.limitstart', 'limitstart', 0, 'int');
		
		return new JPagination ($this->getTotal(), $limitstart, $limit );
	}
	
	private function getQuery () {
		$mainframe = JFactory::getApplication();
		$params = JComponentHelper::getParams('com_k2');
		$option = JRequest::getCmd('option');
		$view = JRequest::getCmd('view');
		$db = JFactory::getDBO();
		$limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
		$limitstart = $mainframe->getUserStateFromRequest($option.$view.'.limitstart', 'limitstart', 0, 'int');
		$filter_order = $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'i.id', 'cmd');
		$filter_order_Dir = $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');
		$filter_trash = $mainframe->getUserStateFromRequest($option.$view.'filter_trash', 'filter_trash', 0, 'int');
		$filter_featured = $mainframe->getUserStateFromRequest($option.$view.'filter_featured', 'filter_featured', -1, 'int');
		$filter_category = $mainframe->getUserStateFromRequest($option.$view.'filter_category', 'filter_category', 0, 'int');
		$filter_author = $mainframe->getUserStateFromRequest($option.$view.'filter_author', 'filter_author', 0, 'int');
		$filter_state = $mainframe->getUserStateFromRequest($option.$view.'filter_state', 'filter_state', -1, 'int');
		$search = $mainframe->getUserStateFromRequest($option.$view.'search', 'search', '', 'string');
		$search = JString::strtolower($search);
		$tag = $mainframe->getUserStateFromRequest($option.$view.'tag', 'tag', 0, 'int');
		$language = $mainframe->getUserStateFromRequest($option.$view.'language', 'language', '', 'string');

		$query = "SELECT i.*, g.name AS groupname, c.name AS category, v.name AS author, w.name as moderator, u.name AS editor FROM #__k2_items as i";

		$query .= " LEFT JOIN #__k2_categories AS c ON c.id = i.catid"." LEFT JOIN #__groups AS g ON g.id = i.access"." LEFT JOIN #__users AS u ON u.id = i.checked_out"." LEFT JOIN #__users AS v ON v.id = i.created_by"." LEFT JOIN #__users AS w ON w.id = i.modified_by";

		if($params->get('showTagFilter') && $tag){
			$query .= " LEFT JOIN #__k2_tags_xref AS tags_xref ON tags_xref.itemID = i.id";
		}

		$query .= " WHERE (i.trash={$filter_trash}";
		if ($this->getState('filter.published') == 'all') {
			$query.= " OR i.trash IN (0, 1)";
		}
		$query.= ')';

		if ($search) {

			$search = JString::str_ireplace('*', '', $search);
			$words = explode(' ', $search);
			for($i=0; $i<count($words); $i++){
				$words[$i]= '+'.$words[$i];
				$words[$i].= '*';
			}
			$search = implode(' ', $words);
			$search = $db->Quote($db->getEscaped($search, true), false);

			if($params->get('adminSearch')=='full')
			$query .= " AND MATCH(i.title, i.introtext, i.`fulltext`, i.extra_fields_search, i.image_caption,i.image_credits,i.video_caption,i.video_credits,i.metadesc,i.metakey)";
			else
			$query .= " AND MATCH( i.title )";

			$query.= " AGAINST ({$search} IN BOOLEAN MODE)";
		}

		if ($filter_state > - 1) {
			$query .= " AND i.published={$filter_state}";
		}

		if ($filter_featured > - 1) {
			$query .= " AND i.featured={$filter_featured}";
		}

		if ($filter_category > 0) {
			if ($params->get('showChildCatItems')) {
				require_once (JPATH_SITE.DS.'components'.DS.'com_k2'.DS.'models'.DS.'itemlist.php');
				$categories = K2ModelItemlist::getCategoryTree($filter_category);
				$sql = @implode(',', $categories);
				$query .= " AND i.catid IN ({$sql})";
			} else {
				$query .= " AND i.catid={$filter_category}";
			}
		}

		if ($filter_author > 0) {
			$query .= " AND i.created_by={$filter_author}";
		}

		if($params->get('showTagFilter') && $tag){
			$query .= " AND tags_xref.tagID = {$tag}";
		}
		
		if ($language) {
			$query .= " AND i.language = ".$db->Quote($language);
		}

		if ($filter_order == 'i.ordering') {
			$query .= " ORDER BY i.catid, i.ordering {$filter_order_Dir}";
		} else {
			$query .= " ORDER BY {$filter_order} {$filter_order_Dir} ";
		}

		if(K2_JVERSION=='16'){
			$query = JString::str_ireplace('#__groups', '#__viewlevels', $query);
			$query = JString::str_ireplace('g.name', 'g.title', $query);
		}
		
		return $query;
	}
}
