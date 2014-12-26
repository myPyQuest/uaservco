<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.raw.php 12795 2012-05-21 02:35:16Z binhpt $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class PoweradminViewChangeposition extends JView
{
	public function display($tpl = null)
	{
		//load libraries for the system rener
		JSNFactory::localimport('libraries.joomlashine.mode.render');
		JRequest::setVar('layout', 'visualmode');
		$app = JFactory::getApplication();
						
		$JSNMedia = JSNFactory::getMedia();
		$template = JSNFactory::getTemplate();
		
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.functions.js');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.filter.visualmode.js');
		
		$moduleid = $app->getUserState( 'com_poweradmin.changeposition.moduleid' );
		$active_positions = Array();
		$model = $this->getModel('changeposition');
		for( $i = 0; $i < count($moduleid); $i++ ){
			$active_positions[] = "$('#".$model->getModulePosition(  $moduleid[$i] )."-jsnposition').addClass('active-position').attr('title', 'Active position');";
		}
		
		$customscripts = "
		    var baseUrl  = '".JURI::root()."';
			var moduleid = new Array();
			moduleid = [". @implode(",", $moduleid)."];
			var changeposition;
			(function($){
				$(document).ready(function(){
					".implode(PHP_EOL, $active_positions)."
					$('.jsn-poweradmin-position').each(function(){
						$(this)[0].oncontextmenu = function() {
							return false;
						}
					}).click(function(){
						if ( !$(this).hasClass('active-position') ){
							$.setPosition(moduleid, $(this).attr('id').replace('-jsnposition', ''));
						}
					});
					changeposition = new $.visualmodeFilter({});
				});
			 })(JoomlaShine.jQuery);";
		
		$JSNMedia->addScriptDeclaration( $customscripts );		
		$this->assign('JSNMedia', $JSNMedia);		
		$jsnpwrender = JSNRender::getInstance();
		$jsnpwrender->renderPage( JURI::root().'index.php?poweradmin=1&vsm_changeposition=1&tp=1', 'changePosition' );
		$this->assign('jsnpwrender', $jsnpwrender);		
		parent::display();
	}
}
?>