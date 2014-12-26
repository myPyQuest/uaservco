<?php
/**
 * @version    $Id$
 * @package    JSNPoweradmin
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
 * Class for finalizing JSN Poweradmin installation.
 *
 * @package  JSNPoweradmin
 * @since    1.0.0
 */

abstract class JSNInstallerScript
{
	/**
	 * XML manifest.
	 *
	 * @var  SimpleXMLElement
	 */
	private $_manifest;

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
			$app->enqueueMessage('Component is not compatible with current Joomla! version, installation fail.', 'error');
			return false;
		}
		
		// Initialize variables
		
		$installer			= $parent->getParent();
		$this->_manifest	= $installer->getManifest();

		// Get component dependency
		$this->_relatedExtensions = $this->_parseRelatedExtensions($installer);

		// Check environment
		$canInstallExtension		= true;
		$canInstallSiteLanguage		= is_writable(JPATH_SITE . DS . 'language');
		$canInstallAdminLanguage	= is_writable(JPATH_ADMINISTRATOR . DS . 'language');

		if ($canInstallSiteLanguage === false)
		{
			$app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_SITE . DS . 'language'), 'error');
		}
		else
		{
			foreach (glob(JPATH_SITE . DS . 'language/*', GLOB_ONLYDIR) AS $dir)
			{
				if ( ! is_writable($dir))
				{
					$canInstallSiteLanguage = false;
					$app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
				}
			}
		}

		if ($canInstallAdminLanguage === false)
		{
			$app->enqueueMessage(sprintf('Cannot install language file at "%s"', JPATH_ADMINISTRATOR . DS . 'language'), 'error');
		}
		else
		{
			foreach (glob(JPATH_ADMINISTRATOR . DS . 'language/*', GLOB_ONLYDIR) AS $dir)
			{
				if ( ! is_writable($dir))
				{
					$canInstallAdminLanguage = false;
					$app->enqueueMessage(sprintf('Cannot install language file at "%s"', $dir), 'error');
				}
			}
		}

		// Checking directory permissions for dependency installation
		foreach ($this->_relatedExtensions AS $extension)
		{
			// Remove installed dependency
			if ($extension->remove == true)
			{
				$this->_removeExtension($extension);
				continue;
			}

			// Install dependency
			switch ($extension->type)
			{
				case 'plugin':
					$path = JPATH_ROOT . DS . 'plugins' . DS . $extension->folder;
					if ( ! is_dir($path) OR ! is_writable($path))
					{
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $path), 'error');
					}
				break;

				case 'component':
					$sitePath	= JPATH_SITE . DS . 'components';
					$adminPath	= JPATH_ADMINISTRATOR . DS . 'components';

					if ( ! is_dir($sitePath) OR ! is_writable($sitePath))
					{
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $sitePath), 'error');
					}

					if ( ! is_dir($adminPath) OR ! is_writable($adminPath))
					{
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $adminPath), 'error');
					}
				break;

				case 'module':
					$path = ($extension->client == 'site' ? JPATH_SITE : JPATH_ADMINISTRATOR) . DS . 'modules';

					if ( ! is_dir($path) OR ! is_writable($path))
					{
						$canInstallExtension = false;
						$app->enqueueMessage(sprintf('Cannot install %s "%s" because "%s" is readonly', $extension->type, $extension->name, $path), 'error');
					}
				break;
			}
		}

		return $canInstallExtension AND $canInstallSiteLanguage AND $canInstallAdminLanguage;
	}

	/**
	 * Implement postflight hook.
	 *
	 * @param   string  $type    Extension type.
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  void
	 */
	public function postflight($type, $parent)
	{
		// Initialize variables
		$installer			= $parent->getParent();
		$app				= JFactory::getApplication();
		$this->_manifest	= $installer->getManifest();

		// Get component dependency
		$this->_relatedExtensions = $this->_parseRelatedExtensions($installer);

		foreach ($this->_relatedExtensions AS $extension)
		{
			// Continue if dependency is removed
			if ($extension->remove == true)
			{
				continue;
			}

			// Install dependency
			$subInstaller = new JInstaller;

			if ( ! $subInstaller->install($extension->source))
			{
				$app->enqueueMessage(sprintf('Error installing %s "%s"', $extension->type, $extension->name), 'error');
				continue;
			}

			// Update dependency status
			$this->_updateExtensionSettings($extension);
			$app->enqueueMessage(sprintf('Install %s "%s" was successfull', $extension->type, $extension->name));
		}
		
		//run the script if using old version without schemas
		//$this->_runFirstSqlScript($installer);
		
		//load Advancemodules plugin after
		$isAdvmInstalled = count($this->_getExtension('advancedmodules', 'plugin'));
		if($isAdvmInstalled){
			$dbo = JFactory::getDBO();
			$dbo->setQuery("UPDATE #__extensions SET ordering=1 WHERE element='advancedmodules' AND type='plugin'");
			@$dbo->query();
		}
	}

	/**
	 * Implement uninstall hook.
	 *
	 * @param   object  $parent  JInstaller object.
	 *
	 * @return  void
	 */
	public function uninstall($parent)
	{
		// Initialize variables
		$installer			= $parent->getParent();
		$this->_manifest	= $installer->getManifest();
		$this->_uninstall	= true;

		// Get component dependency
		$this->_relatedExtensions = $this->_parseRelatedExtensions($installer);

		// Disable all dependency
		$this->_disableAllRelatedExtensions();

		// Remove all dependency
		foreach ($this->_relatedExtensions AS $extension)
		{
			$this->_removeExtension($extension);
		}
	}

	/**
	 * Retrieve related extensions from manifest file.
	 *
	 * @param   object  $installer  JInstaller object.
	 *
	 * @return  array
	 */
	private function _parseRelatedExtensions($installer)
	{	
		// Declared component dependency.
		static $relatedExtensions;

		// Continue only if component dependency not parsed before
		if ( ! isset($relatedExtensions) OR ! is_array($relatedExtensions))
		{
			// Start parsing component dependency
			$relatedExtensions = array();

			if (isset($this->_manifest->subinstall) AND $this->_manifest->subinstall instanceOf SimpleXMLElement)
			{
				// Loop on each node to retrieve dependency information
				foreach ($this->_manifest->subinstall->children() AS $node)
				{
					// Verify tag name
					if ($node->name() !== 'extension')
					{
						continue;
					}

					// Get dependency information
					$attributes	= $node->attributes();
					$name		= (isset($attributes->name))	? (string) $attributes->name	: '';
					$type		= (isset($attributes->type))	? (string) $attributes->type	: '';
					$folder		= (isset($attributes->folder))	? (string) $attributes->folder	: '';
					$publish	= (isset($attributes->publish)	AND ((string) $attributes->publish == 'true'	OR (string) $attributes->publish == 'yes'));
					$lock		= (isset($attributes->lock)		AND ((string) $attributes->lock == 'true'		OR (string) $attributes->lock == 'yes'));
					$remove		= (isset($attributes->remove)	AND ((string) $attributes->remove == 'true'		OR (string) $attributes->remove == 'yes'));
					$client		= (isset($attributes->client))		? (string) $attributes->client		: 'site';
					$position	= (isset($attributes->position))	? (string) $attributes->position	: '';
					$ordering	= (isset($attributes->ordering))	? (string) $attributes->ordering	: '1';
					$title		= (isset($attributes->title))		? (string) $attributes->title		: $name;

					// Validate dependency
					if (empty($name) OR empty($type) OR ! in_array($type, array('plugin', 'module', 'component')))
					{
						continue;
					}

					if ($type == 'plugin' AND empty($folder))
					{
						continue;
					}

					if ($type == 'plugin' AND $name == 'jsnframework')
					{
						// Call method to safely install/uninstall framework
						(isset($this->_uninstall) AND $this->_uninstall)
							? $this->_uninstallFramework($installer, $attributes)
							: $this->_installFramework($installer, $attributes);
					}
					else
					{
						// Prepare dependency installation
						$extension = new StdClass;
						$extension->type	= $type;
						$extension->name	= $name;
						$extension->folder	= $folder;
						$extension->publish	= $publish;
						$extension->lock	= $lock;
						$extension->remove	= $remove;
						$extension->client	= $client;
						$extension->source	= $installer->getPath('source') . DS . $attributes->dir;

						if ($type == 'module')
						{
							$extension->position = $position;
							$extension->ordering = $ordering;
							$extension->title = $title;
						}

						$relatedExtensions[] = $extension;
					}
				}
			}
		}

		return $relatedExtensions;
	}

	/**
	 * Update dependency status.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	private function _updateExtensionSettings($extension)
	{
		// Update extensions table
		$table = JTable::getInstance('Extension');
		$table->load(array('element' => $extension->name));
		$table->enabled		= ($extension->publish == true)		? 1 : 0;
		$table->protected	= ($extension->lock == true)		? 1 : 0;
		$table->client_id	= ($extension->client == 'site')	? 0 : 1;
		$table->store();

		// Update module instance
		if ($extension->type == 'module')
		{
			$module = JTable::getInstance('module');
			$module->load(array('module' => $extension->name));
			$module->title		= $extension->title;
			$module->ordering	= $extension->ordering;
			$module->published	= ($extension->publish == true) ? 1 : 0;
			$module->position	= $extension->position;
			$module->store();

			if (is_numeric($module->id) AND $module->id > 0)
			{
				$dbo = JFactory::getDBO();
				$dbo->setQuery("INSERT INTO #__modules_menu (moduleid, menuid) VALUES ({$module->id}, 0)");
				$dbo->query();
			}
		}

		return $this;
	}

	/**
	 * Disable all dependency.
	 *
	 * @return  object  Return itself for method chaining.
	 */
	private function _disableAllRelatedExtensions()
	{
		foreach ($this->_relatedExtensions AS $extension)
		{
			$this->_disableExtension($extension);
		}

		return $this;
	}

	/**
	 * Disable a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  void
	 */
	private function _disableExtension($extension)
	{
		// Get database object
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__extensions SET enabled=0 WHERE element='{$extension->name}'");
		$dbo->query();
	}

	/**
	 * Unlock a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  void
	 */
	private function _unlockExtension($extension)
	{
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__extensions SET protected=0 WHERE element='{$extension->name}'");
		$dbo->query();
	}

	/**
	 * Remove a dependency.
	 *
	 * @param   object  $extension  Extension to update.
	 *
	 * @return  void
	 */
	private function _removeExtension($extension)
	{
		// Initialize variables
		$app = JFactory::getApplication();

		$dbo = JFactory::getDBO();
		$dbo->setQuery("SELECT * FROM #__extensions WHERE element='{$extension->name}'");
		$extensions = $dbo->loadObjectList();

		foreach ($extensions as $ext) {
			$installer = new JInstaller();

			$this->_disableExtension($extension);
			$this->_unlockExtension($extension);

			if ($ext->extension_id > 0) {
				if ($installer->uninstall($extension->type, $ext->extension_id)) {
					$app->enqueueMessage(sprintf('%s "%s" has been uninstalled', ucfirst($extension->type), $extension->name));
				}
				else {
					$app->enqueueMessage(sprintf('Cannot uninstall %s "%s"', $extension->type, $extension->name . ' ' . $ext->extension_id));
				}
			}
		}
	}
	
	/**
	 * Get a dependency.
	 *
	 * @param   string  $name  Name of extension.
	 *
	 * @return  object
	 */
	private function _getExtension($name, $type = null)
	{
		$dbo 	= JFactory::getDBO();
		$query 	= "SELECT * FROM #__extensions WHERE element='{$name}'" ;
		if($type) {
			$query .= " AND type='{$type}'";
		}
		$dbo->setQuery($query);
		return $dbo->loadObjectList();
	}

	/**
	 * Method for safely install JSN Framework.
	 *
	 * @param   object  &$installer   JInstaller object to handle installation.
	 * @param   object  &$attributes  Attributes of the framework to be installed.
	 *
	 * @return  void
	 */
	private function _installFramework(&$installer, &$attributes)
	{
		// Get database object
		$dbo 	= JFactory::getDbo();
		$query	= $dbo->getQuery(true);

		// Build query to get framework installation status
		$query = $dbo->getQuery(true);
		$query->select('manifest_cache, params');
		$query->from('#__extensions');
		$query->where("type = 'plugin'", 'AND');
		$query->where("element = 'jsnframework'", 'AND');
		$query->where("folder = 'system'");

		// Set query for execution
		$dbo->setQuery($query);

		// Get information about the framework to be installed
		$dir	= isset($attributes->dir)	? (string) $attributes->dir		: '';
		$name	= isset($attributes->name)	? (string) $attributes->name	: '';
		$dir	= JPATH::clean($installer->getPath('source') . DS . $dir . DS . $name . '.xml');

		if ($fwStatus = $dbo->loadObject() AND isset($fwStatus->params))
		{
			// Initialize variables
			$manifest	= json_decode($fwStatus->manifest_cache);
			$params		= (array) json_decode($fwStatus->params);
			
			if (is_file($dir) AND ($xml = JFactory::getXML($dir)))
			{
				if (version_compare($manifest->version, (string) $xml->version, '<'))
				{
					// Framework to be installed is newer than the existing one, mark for update
					$doInstall = true;
				}
			}
		}
		else
		{
			// Framework to be installed not exist, mark for install
			$doInstall = true;
		}

		if (isset($doInstall) AND $doInstall)
		{
			// Initialize variable to do installation
			$app			= JFactory::getApplication();
			$subInstaller	= new JInstaller;

			if ( ! $subInstaller->install(dirname($dir)))
			{
				$app->enqueueMessage('Error installing JSN Framework system plugin', 'error');
			}
			else
			{
				$app->enqueueMessage('Install JSN Framework system plugin was successfull');
			}
		}

		// Set/update framework dependent list
		isset($params) OR $params = array();

		if ( ! isset($params[($ext = strtolower($this->_manifest->name))]))
		{
			$params[$ext] = 1;

			$query = $dbo->getQuery(true);
			$query->update('#__extensions');
			$query->set("params = '" . json_encode($params) . "'");
			$query->where("type = 'plugin'", 'AND');
			$query->where("element = 'jsnframework'", 'AND');
			$query->where("folder = 'system'");

			$dbo->setQuery($query);
			$dbo->query();
		}
	}

	/**
	 * Method for safely uninstall JSN Framework.
	 *
	 * @param   object  &$installer   JInstaller object to handle installation.
	 * @param   object  &$attributes  Attributes of the framework to be installed.
	 *
	 * @return  void
	 */
	private function _uninstallFramework(&$installer, &$attributes)
	{
		// Initialize variables
		$app	= JFactory::getApplication();
		$dbo	= JFactory::getDBO();
		$query	= $dbo->getQuery(true);

		// Build query to get framework installation status
		$query->select('extension_id, params');
		$query->from('#__extensions');
		$query->where("type = 'plugin'", 'AND');
		$query->where("element = 'jsnframework'", 'AND');
		$query->where("folder = 'system'");

		// Set query for execution
		$dbo->setQuery($query);

		if ($fwStatus = $dbo->loadObject() AND isset($fwStatus->params))
		{
			// Initialize variables
			$id		= $fwStatus->extension_id;
			$params	= (array) json_decode($fwStatus->params);

			if (isset($params[($ext = strtolower($this->_manifest->name))]))
			{
				if (count($params) == 1)
				{
					// Initialize variables for installing framework
					$installer = new JInstaller;
					$framework = new stdClass;
					$framework->name = 'jsnframework';

					// Disable and unlock framework
					$this->_disableExtension($framework);
					$this->_unlockExtension($framework);

					// Uninstall framework
					if ($installer->uninstall('plugin', $id))
					{
						$app->enqueueMessage('JSN Framework system plugin has been uninstalled');
					}
					else
					{
						$app->enqueueMessage('Cannot uninstall JSN Framework');
					}
				}
				else
				{
					// Set/update framework dependent counting
					unset($params[$ext]);

					$query = $dbo->getQuery(true);
					$query->update('#__extensions');
					$query->set("params = '" . json_encode($params) . "'");
					$query->where("type = 'plugin'", 'AND');
					$query->where("element = 'jsnframework'", 'AND');
					$query->where("folder = 'system'");

					$dbo->setQuery($query);
					$dbo->query();
				}
			}
		}
	}
	
}
