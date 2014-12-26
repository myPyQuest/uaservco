<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: view.html.php 14475 2012-07-27 09:40:40Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.view');

class PoweradminViewConfig extends JView
{
	protected $item;
	protected $form;
	protected $state;
	
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		
		$page = JRequest::getVar('page', 'global');
		if (!in_array($page, array('global', 'languages', 'permissions'))) {
			return false;
		}

		$JSNMedia    = JSNFactory::getMedia();
		$JSNMedia->addScript(JSN_POWERADMIN_LIB_JS_URI. 'jquery-bootstrap/bootstrap-tabs.js');
		
		switch ($page) {
			case 'languages':
				$this->languages =  json_decode(JSN_POWERADMIN_LIST_LANGUAGE_SUPPORTED);
			break;

			case 'permissions':
				$this->form = $this->get('PermissionForm');
				$customScript = '
					(function ($) {
						$("#params_tab").tabs();	
					})(JoomlaShine.jQuery);
				';
				$JSNMedia->addScriptDeclaration( $customScript );
			break;
			
			default:
				$customScript = "
					var baseUrl       = '".JURI::root()."';		
					var pop ='';
					
					(function ($){						
						$(document).ready(function  (){							
							$( '#jsn_tabs' ).tabs();
						    
						    $('#logo-select').click(function (){
								pop = $.JSNUIWindow
								(
									baseUrl + 'administrator/index.php?option=com_media&view=images&tmpl=component&asset=com_poweradmin&author=&fieldid=params_logo_file',
									{
										modal  : true,
										width  : 800,
										height : 550,
										title : '".JText::_('JSN_POWERADMIN_CONFIG_SELECT_LOGO_FILE')."',
										buttons: {
											'Close': function(){							
												$(this).dialog('close');
											}
										}
									}
								);	
							});
						});
						
					})(JoomlaShine.jQuery);
					
					function jInsertFieldValue(value, id) {
						var old_id = document.id(id).value;
						if (old_id != id) {
							var elem = document.id(id);
							elem.value = value;
							elem.fireEvent('change');
						}
						pop.close();
					}					
				";
				$JSNMedia->addScriptDeclaration( $customScript );
				$this->state	= $this->get('State');
				$this->item		= new JRegistry();
				$this->form		= $this->get('Form');

				$this->item->loadArray($this->get('Item'));
				
				$coverages = $this->form->getValue('search_coverage', 'params');
				if (empty($coverages)) {
					$this->form->setValue('search_coverage', 'params', PowerAdminHelper::getSearchCoverages());
				}
			break;
		}
		
		$this->page = $page;
		
		$this->_addAssets($page);
		$this->_addToolbar($page);
		
		parent::display($tpl);
	}
	protected function isJoomlaSupport ($locale, $area)
	{
		$path = ($area == 'site') ? JPATH_SITE : JPATH_ADMINISTRATOR;
		$path.= '/language/' . $locale;
		return is_dir($path);
	}
	
	protected function isDefaultLanguage ($locale, $area)
	{
		$params		 = JComponentHelper::getParams('com_languages');
		$langDefault = $params->get($area, 'en-GB');
		
		return $locale == $langDefault;
	}
	
	protected function canInstallLanguage ($locale, $section)
	{
		if($section == 'site'){
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_poweradmin/languages/site/'.$locale.'.com_poweradmin.ini';
			$langPath   = JPATH_SITE . '/language/'.$locale;
		}else{
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_poweradmin/languages/admin/'.$locale.'.com_poweradmin.ini';
			$langPath   = JPATH_ADMINISTRATOR . '/language/'.$locale;
		}		
		
		return is_dir($langPath) && is_writable($langPath) && is_file($sourcePath);
	}
	
	protected function isInstalledLanguage ($locale, $section)
	{
		$langPath = ($section == 'site') ? JPATH_SITE . '/language/'.$locale : JPATH_ADMINISTRATOR . '/language/'.$locale;
		
		if (!is_dir($langPath)) {
			return false;
		}
		
		$langFiles = glob("{$langPath}/{$locale}.com_poweradmin.*");
		return count($langFiles) > 0;
	}
	
	/**
	 * Register page title and toolbar buttons for configuration page
	 * @return void
	 */
	private function _addToolbar ($page) {
		JToolBarHelper::title(JText::_('JSN_POWERADMIN_CONFIGURATION_TITLE'), 'poweradmin-config');
	}
	
	private function _addAssets () {
	
		$JSNMedia = JSNFactory::getMedia();
		$JSNMedia->addStyleSheet(JSN_POWERADMIN_STYLE_URI."styles.css");
		$JSNMedia->addScript('components/com_poweradmin/assets/js/joomlashine/config.js');
	}
}