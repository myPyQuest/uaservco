<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 15828 2012-09-05 09:12:27Z hiepnv $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewTemplates extends JView
{

	public function display($tpl = null)
	{
		$JSNMedia = JSNFactory::getMedia();
		$JSNMedia->addStyleSheet(JSN_POWERADMIN_STYLE_URI. 'styles.css');
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.mousecheck.js");
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.submenu.js");
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JSNJS_URI."jsn.manage-styles.js");		

		$JSNMedia->addStyleDeclaration("
		.template-item {
			background: url(".JSN_POWERADMIN_IMAGES_URI."loader.gif) no-repeat center center;
		}
		.loading {
			background: url(".JSN_POWERADMIN_IMAGES_URI."indicator.gif) no-repeat center right;
		}
		");

		$JSNMedia->addScriptDeclaration("
            (function ($){
               $(document).ready(function (){
                    $('#client-switch').change(function (e) {
                        var val =$(this).attr('value');
                        if(val == 0){
                            $('.template-list').hide();
                            $('#site').show();
                        }else{
                            $('.template-list').hide();
                            $('#admin').show();
                        }
                    })
	           });
            })(JoomlaShine.jQuery);
        ");

		$model = $this->getModel('templates');
		$rows  = $model->getTemplates();
		$adminRows  = $model->getTemplates(1);

		//assign to view
		$this->assign('templates', $rows);
		$this->assign('adminTemplates', $adminRows);
		return parent::display();
	}

}