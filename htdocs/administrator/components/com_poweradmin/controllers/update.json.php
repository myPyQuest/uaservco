<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: update.json.php 12764 2012-05-17 04:37:38Z binhpt $
-------------------------------------------------------------------------*/

defined('_JEXEC') or die( 'Restricted access' );
require_once JPATH_COMPONENT_ADMINISTRATOR.DS.'libraries/joomlashine/download/package.auth.php';

class PowerAdminControllerUpdate extends JController
{
	/**
	 * Download latest installation package
	 * @return void
	 */
	public function download ()
	{
		$folderPath 	= JPATH_ROOT.DS.'tmp';
		$output 		= array('status' => 'failed');
		
		if (is_dir($folderPath) && is_writable($folderPath))
		{
			$versionInfo	= new JVersion();
			$username		= JRequest::getString('username', null);
			$password		= JRequest::getString('password', null);
			
			$options 				= new stdClass();
			$options->identifyName 	= JSN_POWERADMIN_IDENTIFY_NAME;
			$options->edition 		= JSN_POWERADMIN_EDITION;
			$options->joomlaVersion = $versionInfo->RELEASE;
			$options->upgrade		= 'yes';
			
			if ($username != null && $password != null) {
				$options->username = $username;
				$options->password = $password;
			}
			
			$downloader = new JSNDownloadPackageAuth(JSN_POWERADMIN_AUTOUPDATE_URL);
			
			if ($result = $downloader->download($options)) {
				JFactory::getSession()->set('poweradmin.updatePackage', $result);
				$output['status'] = 'done';
			}
			else {
				$output['message'] = $downloader->_msgError;
			}
		}
		
		echo json_encode($output);
	}
	
	/**
	 * Do install downloaded package for update
	 * @return void
	 */
	public function install ()
	{
		$output = array();
		$session = JFactory::getSession();
		
		if (!$session->has('poweradmin.updatePackage')) {
			return $this->_sendMessage('failed', JText::_('JSN_POWERADMIN_UPDATE_INVALID_PACKAGE'));
		}
		
		$installer   = JInstaller::getInstance();
		$packageFile = $session->get('poweradmin.updatePackage');
		$extractedPackage = JInstallerHelper::unpack(JPATH_ROOT.DS.'tmp'.DS.$packageFile);
		
		if (!isset($extractedPackage['dir'])) {
			return $this->_sendMessage('failed', JText::_('JSN_POWERADMIN_FAILED_EXTRACT_PACKAGE'));
		}
		
		if (!$installer->install($extractedPackage['dir'])) {
			return $this->_sendMessage('failed', $installer->getError());
		}
		
		// Delete temporary files and folders
		JInstallerHelper::cleanUpInstall($extractedPackage['packagefile'], $extractedPackage['dir']);
		$this->_sendMessage('done');
	}
	
	/**
	 * Send a message to client as JSON object
	 * @param string $status
	 * @param string $message
	 */
	private function _sendMessage ($status, $message = '')
	{
		$output = array();
		$output['status'] = $status;
		
		if (!empty($message)) {
			$output['message'] = $message;
		}
		
		echo json_encode($output);
		return true;
	}
}