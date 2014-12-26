<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: config.php 13483 2012-06-23 08:42:33Z binhpt $
-------------------------------------------------------------------------*/
 // no direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.controllerform');

class PoweradminControllerConfig extends JController
{
	public function apply ()
	{
		$user	= JFactory::getUser();
		if ($user->get('id')) {
			$data = JRequest::getVar('params', array(), 'post', 'array');
			$conf = JFactory::getConfig();
			
			if (isset($data['admin_session_timeout_warning']) && 
			   ($data['admin_session_timeout_warning'] <= 0 || $data['admin_session_timeout_warning'] > $conf->get('lifetime', 15))) {
				$data['admin_session_timeout_warning'] = 1;
			}
			
			if (!isset($data['admin_session_timeout_warning_disabled'])) {
				$data['admin_session_timeout_warning_disabled'] = false;
			}
			
			if (!isset($data['admin_session_timer_infinite'])) {
				$data['admin_session_timer_infinite'] = false;
			}
			
			setcookie('jsn-adminbar-disable-warning', $data['admin_session_timeout_warning_disabled']);
			setcookie('jsn-adminbar-timeout-warning', $data['admin_session_timeout_warning']);
			setcookie('jsn-adminbar-session-infinite', $data['admin_session_timer_infinite']);

			if (empty($data['logo_file'])) {
				$data['logo_file'] = 'N/A';
			}

			if (empty($data['logo_link'])) {
				$data['logo_link'] = 'N/A';
			}
			
			$model = $this->getModel('config');
			$model->save($data);
			
			// clear system cache in backend
			$options = array(
				'defaultgroup'	=> '',
				'storage' 		=> $conf->get('cache_handler', ''),
				'caching'		=> true,
				'cachebase'		=> JPATH_ADMINISTRATOR . '/cache'
			);

			jimport('joomla.cache.cache');
			$cache = JCache::getInstance('', $options);
			$cache->clean('_system');
			
			JFactory::getApplication()->enqueueMessage('PowerAdmin configuration has been saved');
			$this->setRedirect('index.php?option=com_poweradmin&view=config');
		}
	}
	
	public function languages ()
	{	
		
		require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'language.php';
		
		$adminSupportedLanguages = JSNLanguageHelper::getSupportedLanguage('administrator');
		$adminSourcePath = JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/admin';
		$adminPath = JPATH_ADMINISTRATOR.DS.'language';		
		$adminLanguages = JRequest::getVar('languages_administrator');
		$adminLanguages[] = JComponentHelper::getParams('com_languages')->get('administrator', 'en-GB');
		
		$siteSupportedLanguages = JSNLanguageHelper::getSupportedLanguage('');
		$siteLanguages = JRequest::getVar('languages_site');
		$siteLanguages[] = JComponentHelper::getParams('com_languages')->get('site', 'en-GB');
		
		$siteSourcePath = JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/site';
		$sitePath = JPATH_SITE.DS.'language';
			
		// Update languages file for administrator section
		foreach ($adminSupportedLanguages as $lang) {			
			if (in_array($lang, $adminLanguages)) {				
				$files = glob($adminSourcePath.DS."{$lang}.*.ini");
				foreach ($files as $file) {
					copy($file, $adminPath.DS.$lang.DS.basename($file));
				}
			}
		}
		
		// Update languages file for site section
		foreach ($siteSupportedLanguages as $lang) {
			if (in_array($lang, $siteLanguages)) {
				$files = glob($siteSourcePath.DS."{$lang}.*.ini");
				foreach ($files as $file) {
					copy($file, $sitePath.DS.$lang.DS.basename($file));
				}
			}
		}
		
		JFactory::getApplication()->enqueueMessage('PowerAdmin configuration has been saved');
		$this->setRedirect('index.php?option=com_poweradmin&view=config&page=languages');
	}

	public function permissions ()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$data	= JRequest::getVar('jform', array(), 'post', 'array');
		$rules = array();
		foreach ($data['rules'] as $name => $_rules) {
			$rules[$name] = array();
			foreach ($_rules as $role => $value) {
				if ($value == '') {
					continue;
				}

				$rules[$name][$role] = $value;
			}
		}

		$rules = new JAccessRules($rules);
		$asset = JTable::getInstance('asset');
		if (!$asset->loadByName("com_poweradmin"))
		{
			$root = JTable::getInstance('asset');
			$root->loadByName('root.1');
			$asset->name = "com_poweradmin";
			$asset->title = "com_poweradmin";
			$asset->setLocation($root->id, 'last-child');
		}

		$asset->rules = (string) $rules;

		if (!$asset->check() || !$asset->store())
		{
			$this->setError($asset->getError());
			return false;
		}

		$this->setRedirect('index.php?option=com_poweradmin&view=config&page=permissions', 'Item successfully saved.');
	}
}




