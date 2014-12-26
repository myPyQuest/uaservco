<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.raw.php 13785 2012-07-05 03:50:44Z hiepnv $
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class PoweradminViewPositionlisting extends JView
{
	public function display($tpl = null)
	{		
		global $templateAuthor, $notSupportedTemplateAuthors;			
		$bypassNotif	= JRequest::getVar('bypassNotif', '');
		$app = JFactory::getApplication();
		
		if(!JSNFactory::_cURLCheckFunctions()){
			$msg 	=	 JText::_('JSN_POWERADMIN_ERROR_CURL_NOT_ENABLED');			
			$app->redirect('index.php?option=com_poweradmin&view=error&tmpl=component', $msg, 'error');
		}
		
		if(!in_array($templateAuthor,$notSupportedTemplateAuthors) || $bypassNotif){
			JSNFactory::localimport('libraries.joomlashine.mode.render');
			$JSNMedia = JSNFactory::getMedia();
			JRequest::setVar('layout', 'visualmode');	
			
			$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.filter.visualmode.js');
			
			$customscripts = "
				var changeposition;
				(function($){
					$(document).ready(function(){
						$('.jsn-poweradmin-position').each(function(){
							$(this)[0].oncontextmenu = function() {
								return false;
							}
						}).mousedown(function(e){
	                        if (e.which == 3){
	                        	if ( !$(this).hasClass('active-position') ){
	                        		$(this).addClass('active-position');
	                        	}else{
	                        		$(this).removeClass('active-position');
	                        	}
	                        }
						});
						changeposition = new $.visualmodeFilter({});
					});
				 })(JoomlaShine.jQuery);";
			
			$JSNMedia->addScriptDeclaration( $customscripts );
			$this->assign('JSNMedia', $JSNMedia);
			
			$jsnpwrender = JSNRender::getInstance();
			$jsnpwrender->renderPage( JURI::root().'index.php?poweradmin=1&vsm_changeposition=1&tp=1', 'Viewpositions' );
			$this->assign('jsnpwrender', $jsnpwrender);
			parent::display();
		}else{			
			$msg 	= JText::_('JSN_POWERADMIN_ERROR_TEMPLATE_NOT_SUPPORTED');			
			$app->redirect('index.php?option=com_poweradmin&view=error&tmpl=component',$msg);			
		}	
		
	}
}
?>