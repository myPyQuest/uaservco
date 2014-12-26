<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: menuitem.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.modellist');
/**
 *
 * @package		Joomla.Admin
 * @subpackage	com_poweradmin
 * @since		1.6
 */
class PoweradminModelMenuitem extends JModelList
{
	/**
	 * 
	 * Delete an menu module 
	 * @param interger $modid
	 */
	public function deleteMenu( $mid )
	{
		$db	= JFactory::getDbo();
		$query = $db->getQuery(true);
		
		//get menu type
		$query->select("menutype");
		$query->from("#__menu_types");
		$query->where("id=".$db->quote($mid));
		$db->setQuery($query);
		$menutype = $db->loadResult();
		
		//delete all items
		$query->clear();
		$query->delete();
		$query->from("#__menu");
		$query->where("menutype = ".$db->quote($menutype));
		$db->setQuery($query);
		$db->query();
		
		//delete menu type
		$query->clear();
		$query->delete();
		$query->from("#__menu_types");
		$query->where("id=".$db->quote($mid));
		$db->setQuery($query);
		return (bool) $db->query();
	}
	/**
	 * 
	 * Get title of menu_type
	 * @param Number $mid
	 */
	public function getMenuTitle($mid){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select("title");
		$query->from("#__menu_types");
		$query->where("id=".$db->quote($mid));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Get menu type
	 * @param Number $mid
	 */
	public function getMenuType($mid){
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select("menutype");
		$query->from("#__menu_types");
		$query->where("id=".$db->quote($mid));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * 
	 * Get menu type
	 * @param String $menutype
	 */
	public function getMenuId($menutype)
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query->select("id");
		$query->from("#__menu_types");
		$query->where("id=".$db->quote($menutype));
		$db->setQuery($query);
		return $db->loadResult();
	}
	
	/**
	 * Method rebuild the entire nested set tree.
	 *
	 * @return	boolean	False on failure or error, true otherwise.
	 * @since	1.6
	 */
	public function rebuild($mid)
	{
		// Initialiase variables.
		$db = $this->getDbo();		
		$menu = JTable::getInstance('menu');

		if (!$menu->rebuild()) {
			$this->setError($menu->getError());
			return false;
		}
		
		$menutype = $this->getMenuType($mid);
		
		// Convert the parameters not in JSON format.
		$db->setQuery(
			'SELECT id, params' .
			' FROM #__menu' .
			' WHERE params NOT LIKE '.$db->quote('{%') .
			'  AND params <> '.$db->quote('') .
		    '  AND menutype = '.$db->quote($menutype)
		);

		$items = $db->loadObjectList();
		if ($error = $db->getErrorMsg()) {
			$this->setError($error);
			return false;
		}

		foreach ($items as &$item)
		{
			$registry = new JRegistry;
			$registry->loadJSON($item->params);
			$params = (string)$registry;

			$db->setQuery(
				'UPDATE #__menu' .
				' SET params = '.$db->quote($params).
				' WHERE id = '.(int) $item->id
			);
			if (!$db->query()) {
				$this->setError($error);
				return false;
			}

			unset($registry);
		}

		// Clean the cache
		$this->cleanCache();
				
		return true;
	}
	
	/**
	 * 
	 * Rebuild itemid
	 * @param int $itemid
	 */
	public function rebuilditem($itemid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$menu = JTable::getInstance('menu');
		if (!$menu->rebuild()) {
			$this->setError($menu->getError());
			return false;
		}
		
		$query->select("params");
		$query->from("#__menu");
		$query->where("id=".$db->quote($itemid));
		$db->setQuery($query);
		$params = $db->loadResult();
		$registry = new JRegistry;
		$registry->loadJSON($params);
		$params = (string)$registry;
		$query->clear();
		$query->update("#__menu");
		$query->set("params = ".$db->quote($params));
		$query->where("id=".$db->quote($itemid));
		$db->setQuery($query);
		unset($registry);
		$this->cleanCache();
		return (bool) $db->query();
	}
	
	/**
	 * 
	 * Publishing menu item
	 * @param interger $id
	 * @param string $publish
	 */
	public function publishing($id, $publish)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update("#__menu");
		$query->set("published = ".$db->quote($publish));
		$query->where("id=".$db->quote($id));
		$db->setQuery($query);
		return (bool) $db->query();
	}
	
	/**
	 * 
	 * Check-in menu items
	 * @param interger $ids
	 */
	public function checkin( $itemid )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update('#__menu');
		$query->set('checked_out = 0, checked_out_time = '.$this->_db->quote($this->_db->getNullDate()));
		$query->where('id = '.$db->quote($itemid));
		$db->setQuery($query);
		return $db->query();
	}
	
	/**
	 * 
	 * Trash menu item
	 * @param int $itemid
	 */
	public function trash($itemid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update("#__menu");
		$query->set("published = -2");
		$query->where("id=".$db->quote($itemid)." AND home = 0");
		$db->setQuery($query);
		return (bool) $db->query();
	}
	
	/**
	 * 
	 * Delete menu item
	 * @param interger $itemid
	 */
	public function delete($itemid)
	{
		$menu = JTable::getInstance('menu');		
		return $menu->delete($itemid);
	}
	
	/**
	 * 
	 * set menu default for joomla
	 * @param int $itemid
	 */
	public function setHome($itemid)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('count(id)');
		$query->from('#__menu');
		$query->where('published = 1 AND id = '.$db->quote($itemid));
		$db->setQuery($query);
		if ((int) $db->loadResult() >= 1){
			$query->clear();
			$query->update("#__menu");
			$query->set("home = 0");
			$db->setQuery($query);
			if ($db->query()){
				$query->clear();
				$query->update("#__menu");
				$query->set("home=1");
				$query->where("id=".$db->quote($itemid));
				$db->setQuery($query);
				return (bool) $db->query();
			}
		}
		return false;
	}
	
	/**
	 * Move item to new position
	 * 
	 * @param: int $itemid 
	 * @param: int $parentid
	 * @param: int $ordering
	 * 
	 * @return: Save to database new position for menu item
	 */
	public function moveItem($itemid, $parentid, $orders)
	{		
		$db = JFactory::getDBO();
		
		// save your drag & drop
		$query = $db->getQuery(true);
		$query->update("#__menu");
		$query->set("parent_id = ".$db->Quote( $parentid ));
		$query->where("id = ".$db->Quote( $itemid ));
		$db->setQuery( $query );
		if (!$db->query()){
			JText::printf('JSN_POWERADMIN_DATABASE_ERROR', $db->getErrorMsg());
			return false;
		}
		

		//save your sort on position
		foreach($orders as $position => $itemid){
			//save ordering
			$this->saveOrder($itemid, $position);
		}		
		
		return true;
	}
	
	/**
	 * Get ordering
	 * 
	 * @param: int $itemid
	 * @param: int $parentid
	 * @param: int $ordering
	 * 
	 * @return: Get order position in the database for a menu item
	 */
	protected function getOrdering($itemid, $parentid, $ordering)
	{
		$menu = JTable::getInstance('menu');
		$menu->load($itemid);
		$menutype = $menu->menutype;
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id, ordering');
		$query->from("#__menu");
		$query->where("menutype = ".$db->quote($menutype)." AND parent_id = ".$db->quote($parentid)." AND published <> -2 AND id <> ".$db->quote($itemid));
		$query->order("ordering");
		$db->setQuery($query);
		$orders = $db->loadObjectList();
		if (count($orders) > 0){
			$_iorder = 1;
			foreach($orders as $order){
				if ($_iorder == $ordering){
					$_iorder++;
				}
				$this->saveOrder($order->id, $_iorder);
				$_iorder++;
			}
		}		
	}
	
	/**
	 * Save order for menu item
	 * 
	 * @param: int $itemid
	 * @param: int $ordering
	 * 
	 * @return: Save the database
	 */
	protected function saveOrder($itemid, $ordering)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->update("#__menu");
		$query->set("ordering = ".$db->quote($ordering));
		$query->where("id=".$db->quote($itemid));
		$db->setQuery($query);
		$db->query();
	}
	/**
	 * 
	 * Get all menu items article layout
	 * 
	 * @return: Array
	 */
	public function getAllItems( $querys )
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select("id");
		$query->from("#__menu");
		$query->where("link LIKE '%option=com_content%'");
		foreach($querys as $key => $value){
			if (!empty($value)){
			 	$query->where("link LIKE '%".$key."=".$value."%'");
			}
		}
		$db->setQuery((string) $query);
		$rows = $db->loadObjectList();
		return $rows;
	}
}