<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: menuitem.php 12645 2012-05-14 07:45:58Z binhpt $
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * @package		Joomla.Site
 * @subpackage	com_poweradmin
 */
class PoweradminControllerMenuitem extends JControllerForm
{
	/**
	 * 
	 * Delete menu
	 */
	public function deleteMenu()
	{
		$menuid = (int)JRequest::getVar('menuid');
		$res    =  $this->getModel('menuitem')->deleteMenu($menuid);
		$msg    = ($res)?$menuid.'||success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Rebuild menu 
	 */
	public function rebuild()
	{
		$mid = JRequest::getVar('mid', '');
		$res   =  $this->getModel('menuitem')->rebuild($mid);
		$msg   = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Get menus and items
	 */
	public function getMenus()
	{
		//load libraries for the system rener menu
		JSNFactory::localimport('libraries.joomlashine.menu.menuitems');
		$jsnmenuitems = JSNMenuitems::getInstance();
		echo $jsnmenuitems->render();
		jexit();
	}
	
	/**
	 * 
	 * Get menu
	 * 
	 * @return: menu
	 */
	public function getMenu()
	{
		$mid = trim(JRequest::getVar('mid', ''));
		//load libraries for the system rener modules mene
		JSNFactory::localimport('libraries.joomlashine.menu.menuitems');
		$menutype = $this->getModel('menuitem')->getMenuType($mid);
		if ($menutype){
			$menutitle = $this->getModel('menuitem')->getMenuTitle($mid);
			$jsnmenuitems = JSNMenuitems::getInstance();
			echo $jsnmenuitems->renderMenu( $mid, $menutype, $menutitle );
		}else{
			echo 'error';
		}
		jexit();
	}
	
	/**
	 * Render menu
	 */
	public function getMenuType()
	{
		$mid = trim(JRequest::getVar('mid', ''));
		//load libraries for the system rener modules mene
		JSNFactory::localimport('libraries.joomlashine.menu.menuitems');
		$menutype = $this->getModel('menuitem')->getMenuType($mid);
		if ($menutype){
			$jsnmenuitems = JSNMenuitems::getInstance();
			$menutitle    = $this->getModel('menuitem')->getMenuTitle($mid);
			echo $jsnmenuitems->renderMenuItem( $mid, $menutype, $menutitle );
		}else{
			echo 'error';
		}
		jexit();
	}
	
	/**
	 * 
	 * Publish/Unpublish menu item
	 */
	public function menuitempublishing()
	{
		$itemid  = (int)JRequest::getVar('itemid', 0);
		$publish = trim(JRequest::getVar('publish', 'Unpublish'));
		$publish = ($publish == 'Publish')?1:0;
		$res     =  $this->getModel('menuitem')->publishing($itemid, $publish);
		$msg     = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Check in menu item
	 */
	public function checkinmenuitem()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$res    =  $this->getModel('menuitem')->checkin( $itemid );
		$msg    = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Delete menu item
	 */
	public function deletemenuitem()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$res    =  $this->getModel('menuitem')->delete($itemid);
		$msg    = ($res)?'success':'error';
		jexit($msg);
	}
	
   /**
	 * 
	 * Trash menu item
	 */
	public function trashmenuitem()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$res    =  $this->getModel('menuitem')->trash($itemid);
		$msg    = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Set default menu item
	 */
	public function setdefault()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$res    =  $this->getModel('menuitem')->setHome($itemid);
		$msg    = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * 
	 * Rebuild menu item
	 */
	public function rebuilditem()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$res    = $this->getModel('menuitem')->rebuilditem($itemid);
		$msg    = ($res)?'success':'error';
		jexit($msg);
	}
	
	/**
	 * Move menu item
	 */
	public function moveItem()
	{
		$itemid = (int)JRequest::getVar('itemid', 0);
		$menu = JTable::getInstance('menu');
		$menu->load($itemid);
		$menutype = $menu->menutype;
		$parentid = (int)JRequest::getVar('parentid', 1);
		$orders   = JRequest::getVar('orders', array(), '', 'array');
		$res = $this->getModel('menuitem')->moveItem($itemid, $parentid, $orders);
		
		//rebuild menu
		if ($res){
			$menuid = $this->getModel('menuitem')->getMenuId($menutype);
			$this->getModel('menuitem')->rebuild($menuid);
		}

		//print message
		$msg = ($res)?$menutype.'|success':'error';
		jexit($msg);
	}
}
