<?php
/**
 * @version     $Id: view.html.php 16024 2012-09-13 11:55:37Z hiepnv $
 * @package     JSN_Poweradmin
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class PoweradminViewChangeposition extends JSNPositionsView
{
	public function display($tpl = null)
	{
 		$app = JFactory::getApplication();
 		$document = JFactory::getDocument();
 		$moduleid = $app->getUserState( 'com_poweradmin.changeposition.moduleid' );
 		$active_positions = Array();
 		$model = $this->getModel('changeposition');
 		for( $i = 0; $i < count($moduleid); $i++ ){
 			$active_positions[] = "$('#".$model->getModulePosition(  $moduleid[$i] )."-jsnposition').addClass('active-position').attr('title', 'Active position');";
 		}
 		
 		$document->addScript( JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.jquery.noconflict.js');
 		$document->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.functions.js');
 		$document->addScript(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.filter.visualmode.js');
 		
 		
 		//Enable position filter. 		
 		$this->setFilterable(true);
 		
		$customScript = "
			var baseUrl  = '".JURI::root()."';
			var moduleid = new Array();
			moduleid = [". @implode(",", $moduleid)."];			
			(function ($){
				$(document).ready(function (){
					".implode(PHP_EOL, $active_positions)."
				});
			})(JoomlaShine.jQuery);
 		"; 
 								
 		$this->addCustomScripts($customScript);
 		
 		//Callback after position clicked.
 		$onPostionClick = "
 			if ( !$(this).hasClass('active-position') ){
 				$.setPosition(moduleid, $(this).attr('id').replace('-jsnposition', ''));
 			}
 		";
 		$this->addPositionClickCallBack($onPostionClick);

		parent::display($tpl);
	}
}
