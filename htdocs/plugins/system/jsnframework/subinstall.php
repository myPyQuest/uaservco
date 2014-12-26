<?php
/**
 * @version    $Id: subinstall.php 16049 2012-09-14 08:40:39Z hiepnv $
 * @package    JSN_Framework
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Subinstall script for finalizing JSN Framework installation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class PlgSystemJSNFrameworkInstallerScript
{	
	/**
	 * Implement preflight hook.
	 *
	 * This step will be verify permission for install/update process.
	 *
	 * @param   string  $mode    Install or update?
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  boolean
	 */
	public function preflight($mode, $parent)
	{
		$app				= JFactory::getApplication();
		
		// Check current Joomla! version
		// only allow install if version >= 2.5
		$jversion = new JVersion();
		$_currentVersion = $jversion->getShortVersion();
		if (version_compare($_currentVersion, '2.5', '<')) {
			$app->enqueueMessage('Plugin is not compatible with current Joomla! version, installation fail.', 'error');
			return false;
		}
	}
	
	/**
	 * Enable JSN Framework system plugin.
	 *
	 * @param   string  $route  Route type: install, update or uninstall.
	 * @param   object  $_this  The installer object.
	 *
	 * @return  boolean
	 */
	public function postflight($route, $_this)
	{
		// Get a database connector object
		$db = JFactory::getDbo();

		// Enable plugin by default
		$query = $db->getQuery(true);

		$query->update('#__extensions');
		$query->set(array('enabled = 1', 'protected = 1', 'ordering = -9999'));
		$query->where("type = 'plugin'", 'AND');
		$query->where("element = 'jsnframework'", 'AND');
		$query->where("folder = 'system'");

		$db->setQuery($query);
		try
		{
			$db->query();
		}
		catch (Exception $e)
		{
			throw $e;
		}
	}
}
