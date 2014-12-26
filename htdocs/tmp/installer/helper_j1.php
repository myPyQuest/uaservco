<?php
/**
 * Helper File (for Joomla! 1.5)
 *
 * @package			NoNumber!-installer
 * @version			12.3.1
 *
 * @author			Peter van Westen <peter@nonumber.nl>
 * @link			http://www.nonumber.nl
 * @copyright		Copyright © 2011 NoNumber! All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Cleanup install files/folders
 */
function cleanupInstall()
{
	$installer = JInstaller::getInstance();
	$source = str_replace('\\', '/', $installer->getPath('source'));
	$config = JFactory::getConfig();
	$tmp = dirname(str_replace('\\', '/', $config->getValue('config.tmp_path').'/x'));

	if (strpos($source, $tmp) === false || $source == $tmp) {
		return;
	}

	$package_folder = dirname($source);
	if ($package_folder == $tmp) {
		$package_folder = $source;
	}

	$package_file = '';
	switch (JRequest::getString('installtype')) {
		case 'url':
			$package_file = JRequest::getString('install_url');
			$package_file = str_replace(dirname($package_file), '', $package_file);
			break;
		case 'upload':
		default:
			if (isset($_FILES) && isset($_FILES['install_package']) && isset($_FILES['install_package']['name'])) {
				$package_file = $_FILES['install_package']['name'];
			}
			break;
	}
	if (!$package_file && $package_folder != $source) {
		$package_file = str_replace($package_folder.'/', '', $source).'.zip';
	}

	$package_file = $tmp.'/'.$package_file;

	JInstallerHelper::cleanupInstall($package_file, $package_folder);
}

/**
 * Copies all files from install folder
 */
function installFiles($folder)
{
	if (JFolder::exists($folder.'/all')) {
		if (!copy_from_folder($folder.'/all', 1)) {
			return 0;
		}
	}
	if (JFolder::exists($folder.'/j1')) {
		if (!copy_from_folder($folder.'/j1', 1)) {
			return 0;
		}
	}
	if (JFolder::exists($folder.'/j1_optional')) {
		if (!copy_from_folder($folder.'/j1_optional', 0)) {
			return 0;
		}
	}
	if (JFolder::exists($folder.'/language')) {
		installLanguages($folder.'/language');
	}
	return 1;
}

/**
 * Copies language files to the specified path
 */
function installLanguagesByPath($folder, $path, $force = 1, $all = 1, $break = 1)
{
	if ($all) {
		$languages = JFolder::folders($path);
	} else {
		$lang = JFactory::getLanguage();
		$languages = array($lang->getTag());
	}
	$languages[] = 'en-GB'; // force to include the English files
	$languages = array_unique($languages);

	if (JFolder::exists($path.'/en-GB')) {
		folder_create($path.'/en-GB');
	}

	foreach ($languages as $lang) {
		if (!JFolder::exists($folder.'/'.$lang)) {
			continue;
		}
		$files = JFolder::files($folder.'/'.$lang);
		foreach ($files as $file) {
			$src = $folder.'/'.$lang.'/'.$file;
			$dest = $path.'/'.$lang.'/'.$file;
			if (!(strpos($file, '.sys.ini') === false)) {
				if (JFile::exists($dest)) {
					JFile::delete($dest);
				}
				continue;
			}
			if ($force || JFile::exists($src)) {
				if (!JFile::copy($src, $dest) && $break) {
					return 0;
				}
			}
		}
	}
	return 1;
}

function installExtension($name, $title, $type = 'component', $extra = array(), $reinstall = 0)
{
	$app = JFactory::getApplication();

	// Create database object
	$db = JFactory::getDBO();

	$installed = 0;

	if (function_exists('beforeInstall_j1')) {
		beforeInstall_j1($db);
	} else if (function_exists('beforeInstall')) {
		beforeInstall($db);
	}

	switch ($type) {
		case 'component':
			if ($reinstall) {
				$query = 'DELETE FROM `#__components`'
					.' WHERE `option` = '.$db->quote('com_'.$name);
				$db->setQuery($query);
				$db->query();
				$installed = 0;
			} else {
				$query = 'SELECT `id` FROM `#__components`'
					.' WHERE `option` = '.$db->quote('com_'.$name)
					.' LIMIT 1';
				$db->setQuery($query);
				$installed = (int) $db->loadResult();
			}

			if (!$installed) {
				$query = 'ALTER TABLE `#__components`'
					.' AUTO_INCREMENT = 1';
				$db->setQuery($query);
				$db->query();

				$row = JTable::getInstance('component');
				$row->name = $title;
				$row->admin_menu_alt = $title;
				$row->option = 'com_'.$name;
				$row->link = 'option=com_'.$name;
				$row->admin_menu_link = 'option=com_'.$name;
				foreach ($extra as $key => $val) {
					$row->$key = $val;
				}

				if (!$row->store()) {
					$app->enqueueMessage($row->getError(), 'error');
					return;
				}
			}

			break;

		case 'plugin':
			// Clean up possible garbage first
			$query = 'DELETE FROM `#__plugins`'
				.' WHERE `element` = '.$db->quote($name)
				.' AND `folder` = \'\'';
			$db->setQuery($query);
			$db->query();

			$folder = $extra['folder'];

			if ($reinstall) {
				$query = 'DELETE FROM `#__plugins`'
					.' WHERE `element` = '.$db->quote($name)
					.' AND `folder` = '.$db->quote($folder);
				$db->setQuery($query);
				$db->query();
				$installed = 0;
			} else {
				$query = 'SELECT `id` FROM `#__plugins`'
					.' WHERE `element` = '.$db->quote($name)
					.' AND `folder` = '.$db->quote($folder)
					.' LIMIT 1';
				$db->setQuery($query);
				$installed = (int) $db->loadResult();
			}

			if (!$installed) {
				$query = 'ALTER TABLE `#__plugins`'
					.' AUTO_INCREMENT = 1';
				$db->setQuery($query);
				$db->query();

				$row = JTable::getInstance('plugin');
				$row->name = $title;
				$row->element = $name;
				$row->published = 1;
				foreach ($extra as $key => $val) {
					$row->$key = $val;
				}

				if (!$row->store()) {
					$app->enqueueMessage($row->getError(), 'error');
					return;
				}
			}

			break;

		case 'module':
			if ($reinstall) {
				$query = 'DELETE FROM `#__modules`'
					.' WHERE `module` = '.$db->quote('mod_'.$name);
				$db->setQuery($query);
				$db->query();
				$installed = 0;
			} else {
				$query = 'SELECT `id` FROM `#__modules`'
					.' WHERE `module` = '.$db->quote('mod_'.$name)
					.' LIMIT 1';
				$db->setQuery($query);
				$installed = (int) $db->loadResult();
			}

			if (!$installed) {
				$query = 'ALTER TABLE `#__modules`'
					.' AUTO_INCREMENT = 1';
				$db->setQuery($query);
				$db->query();

				$row = JTable::getInstance('module');
				$row->title = $title;
				$row->module = 'mod_'.$name;
				$row->ordering = $row->getNextOrder("position='left'");
				$row->position = 'left';
				$row->showtitle = 1;
				foreach ($extra as $key => $val) {
					$row->$key = $val;
				}

				if (!$row->store()) {
					$app->enqueueMessage($row->getError(), 'error');
					return;
				}

				// Clean up possible garbage first
				$query = 'DELETE FROM `#__modules_menu` WHERE `moduleid` = '.( int ) $row->id;
				$db->setQuery($query);
				$db->query();

				// Time to create a menu entry for the module
				$query = 'INSERT INTO `#__modules_menu` VALUES ( '.( int ) $row->id.', 0 )';
				$db->setQuery($query);
				$db->query();
			}

			break;
	}

	if (function_exists('afterInstall_j1')) {
		afterInstall_j1($db);
	} else if (function_exists('afterInstall')) {
		afterInstall($db);
	}

	$cookieName = JUtility::getHash('version_'.$name.'_version');
	setcookie($cookieName, '', 0);

	return ($installed) ? 2 : 1;
}

function installFramework($comp_folder)
{
	$framework_folder = $comp_folder.'/framework/framework';
	$xml_name = 'plugins/system/nnframework.xml';
	$xml_file = $framework_folder.'/j1/'.$xml_name;
	if (!JFile::exists($xml_file)) {
		return;
	}
	$xml_new = JApplicationHelper::parseXMLInstallFile($xml_file);

	$do_install = 1;
	if ($xml_new && isset($xml_new['version'])) {
		$do_install = 1;
		$xml_file = JPATH_SITE.'/'.$xml_name;
		if (JFile::exists($xml_file)) {
			$xml_current = JApplicationHelper::parseXMLInstallFile($xml_file);
			$installed = ($xml_current && isset($xml_current['version']));
			if ($installed) {
				$current_version = $xml_current['version'];
				$new_version = $xml_new['version'];
				$do_install = version_compare($current_version, $new_version, '<=') ? 1 : 0;
			}
		}
	}

	$success = 1;
	if ($do_install) {
		$app = JFactory::getApplication();
		if (!installFiles($framework_folder)) {
			$app->enqueueMessage('Could not install the NoNumber Framework extension', 'error');
			$app->enqueueMessage('Could not copy all files', 'error');
			$success = 0;
		}
		if ($success) {
			$elements_folder = $comp_folder.'/framework/elements';
			if (JFolder::exists(JPATH_SITE.'/plugins/system/nonumberelements') && JFolder::exists($elements_folder)) {
				uninstallLanguages('nonumberelements');
				if (installFiles($elements_folder)) {
					installExtension('nonumberelements', 'System - NoNumber! Elements', 'plugin', array('folder'=> 'system'), 1);
				}
			}
		}
	}

	if ($success) {
		installExtension('nnframework', 'System - NoNumber! Framework', 'plugin', array('folder'=> 'system'), 1);
	}
}

function uninstallInstaller($name = 'nonumber-installer-uninstallme')
{
	$app = JFactory::getApplication();
	// Create database object
	$db = JFactory::getDBO();

	$query = 'SELECT `id` FROM `#__components`'
		.' WHERE `option` = '.$db->quote('com_'.$name)
		.' AND `parent` = 0'
		.' LIMIT 1';
	$db->setQuery($query);
	$id = (int) $db->loadResult();
	if ($id > 1) {
		$installer = JInstaller::getInstance();
		$installer->uninstall('component', $id);
	}
	$query = 'ALTER TABLE `#__components`'
		.' AUTO_INCREMENT = 1';
	$db->setQuery($query);
	$db->query();

	// Delete language files
	$lang_folder = JPATH_ADMINISTRATOR.'/language';
	$languages = JFolder::folders($lang_folder);
	foreach ($languages as $lang) {
		$file = $lang_folder.'/'.$lang.'/'.$lang.'.com_'.$name.'.ini';
		if (JFile::exists($file)) {
			JFile::delete($file);
		}
	}

	// Delete old language files
	$files = JFolder::files(JPATH_SITE.'/language', 'com_nonumber-installer-uninstallme.ini');
	foreach ($files as $file) {
		JFile::delete(JPATH_SITE.'/language/'.$file);
	}

	// Redirect with message
	$app->redirect('index.php?option=com_installer');
}