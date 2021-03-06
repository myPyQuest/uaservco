<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 15318 2012-08-21 08:44:11Z hiepnv $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewAssignpages extends JView
{

	public function display($tpl = null)
	{
		$JSNMedia = JSNFactory::getMedia();
		$JSNMedia->addStyleSheet(JSN_POWERADMIN_STYLE_URI. 'styles.css');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.cook.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.tinyscrollbar.js');
		$JSNMedia->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/jquery.hotkeys/jquery.hotkeys.js');
		$JSNMedia->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/jquery.jstree/jquery.jstree.js');
		$JSNMedia->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/jquery.jstorage/jquery.jstorage.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery.topzindex.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.submenu.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.mousecheck.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.functions.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.assignpages.js');
		$JSNMedia->addScriptDeclaration("var baseUrl = '".JURI::root()."';" );

		//require classes
		JSNFactory::localimport('libraries.joomlashine.modules');
		JSNFactory::localimport('libraries.joomlashine.page.assignpages');
		$viewHelper = JSNAssignpages::getInstance();

		$menuTypes = $viewHelper->menuTypeDropDownList();
        $this->assign('menutypes', $menuTypes);

        $app = JFactory::getApplication();
        $moduleid = $app->getUserState('com_poweradmin.assignpages.custompage.moduleid', JRequest::getVar('moduleid', array(), 'get', 'array'));

        if (count($moduleid) == 1 ){
        	$menuitems  = $viewHelper->renderMenu($moduleid[0]);
        	$assignType = JSNModules::checkAssign($moduleid[0]);
        }else{
        	$menuitems  = $viewHelper->renderMenu(0);
        	$assignType = 3;
        }
        $this->assign('menuitems', $menuitems);
        $this->assign('assignType', $assignType);
		return parent::display();
	}
}