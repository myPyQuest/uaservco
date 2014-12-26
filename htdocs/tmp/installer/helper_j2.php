<?php
/**
 * Helper File
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
	$tmp = dirname(str_replace('\\', '/', $config->get('tmp_path').'/x'));

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
	if (JFolder::exists($folder.'/j2')) {
		if (!copy_from_folder($folder.'/j2', 1)) {
			return 0;
		}
	}
	if (JFolder::exists($folder.'/j2_optional')) {
		if (!copy_from_folder($folder.'/j2_optional', 0)) {
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
			if (!(strpos($file, '.menu.ini') === false)) {
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

function installExtension($alias, $name, $type = 'component', $extra = array(), $reinstall = 0)
{
	$app = JFactory::getApplication();

	// Create database object
	$db = JFactory::getDBO();

	$installed = 0;

	$where = array();
	$where[] = '`type` = '.$db->quote($type);

	$element = $alias;
	$folder = '';
	switch ($type) {
		case 'component':
			$element = 'com_'.$element;
			break;
		case 'plugin':
			$folder = isset($extra['folder']) ? $extra['folder'] : 'system';
			unset($extra['folder']);
			$where[] = '`folder` = '.$db->quote($folder);
			break;
		case 'module':
			$element = 'mod_'.$element;

			if ($reinstall) {
				$query = 'DELETE FROM `#__modules`'
					.' WHERE `module` = '.$db->quote($element);
				$db->setQuery($query);
				$db->query();
				$installed = 0;
			} else {
				$query = 'SELECT `id` FROM `#__modules`'
					.' WHERE `module` = '.$db->quote($element)
					.' LIMIT 1';
				$db->setQuery($query);
				$installed = (int) $db->loadResult();
			}
			break;
	}
	$where[] = '`element` = '.$db->quote($element);

	if ($reinstall) {
		$query = 'DELETE FROM `#__extensions`'
			.' WHERE '.implode(' AND ', $where);
		$db->setQuery($query);
		$db->query();
		$installed = 0;
	} else {
		$query = 'SELECT `extension_id` FROM `#__extensions`'
			.' WHERE '.implode(' AND ', $where)
			.' LIMIT 1';
		$db->setQuery($query);
		$installed = (int) $db->loadResult();
	}

	/* >>> FREE >>> */
	/*if ($installed) {
		$version = getXMLVersion('', $alias, $type, $folder);
		if (!(strpos($version, 'PRO') === false)) {
			$app->enqueueMessage(JText::_('NNI_ERROR_PRO_TO_FREE'), 'error');
			return -1;
		} else if (strpos($version, 'FREE') === false) {
			$app->enqueueMessage(JText::_('NNI_ERROR_BEFORE_SWITCH'), 'error');
			return -1;
		}
	}
	/* <<< FREE <<< */

	if (function_exists('beforeInstall')) {
		beforeInstall($db);
	}

	$id = $installed;

	if (!$installed) {
		if ($type == 'module') {
			$element = 'mod_'.$element;

			if (!$installed) {
				$query = 'ALTER TABLE `#__modules`'
					.' AUTO_INCREMENT = 1';
				$db->setQuery($query);
				$db->query();

				$row = JTable::getInstance('module');
				$row->title = $name;
				$row->module = $element;
				$row->ordering = $row->getNextOrder("position='left'");
				$row->position = 'left';
				$row->showtitle = 1;
				$row->language = '*';
				foreach ($extra as $key => $val) {
					if (property_exists($row, $key)) {
						$row->$key = $val;
					}
				}

				if (!$row->store()) {
					$app->enqueueMessage($row->getError(), 'error');
					return 0;
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
		}

		$query = 'ALTER TABLE `#__extensions`'
			.' AUTO_INCREMENT = 1';
		$db->setQuery($query);
		$db->query();

		$row = JTable::getInstance('extension');
		$row->name = strtoupper($alias);
		$row->element = $alias;
		$row->type = $type;
		$row->enabled = 1;
		$row->client_id = 0;
		$row->access = 1;
		switch ($type) {
			case 'component':
				$row->name = strtoupper('com_'.$row->name);
				$row->element = 'com_'.$row->element;
				$row->access = 0;
				$row->client_id = 1;
				break;
			case 'plugin':
				$row->name = strtoupper('plg_'.$folder.'_'.$row->name);
				$row->folder = $folder;
				break;
			case 'module':
				$row->name = strtoupper('mod_'.$row->name);
				$row->element = 'mod_'.$row->element;
				break;
		}
		foreach ($extra as $key => $val) {
			if (property_exists($row, $key)) {
				$row->$key = $val;
			}
		}

		if (!$row->store()) {
			$app->enqueueMessage($row->getError(), 'error');
			return 0;
		}
		$id = (int) $row->extension_id;
	}

	if (!$id) {
		return 0;
	}

	$query = 'UPDATE `#__extensions`'
		.' SET `manifest_cache` = \'\''
		.' WHERE `extension_id` = '.(int) $id;
	;
	$db->setQuery($query);
	$db->query();

	if (in_array($alias, array('nnframework', 'nonumberelements'))) {
		$installer = JInstaller::getInstance();
		$installer->refreshManifestCache((int) $id);
	}

	if ($type == 'component') {
		$query = 'DELETE FROM `#__menu`'
			.' WHERE `link` = '.$db->quote('index.php?option=com_'.$alias);
		$db->setQuery($query);
		$db->query();

		$file = dirname(dirname(__FILE__)).'/extensions/j2/administrator/components/com_'.$alias.'/'.$alias.'.xml';
		$xml = JFactory::getXML($file);

		if (isset($xml->administration) && isset($xml->administration->menu)) {
			$menuElement = $xml->administration->menu;

			if ($menuElement) {
				$data = array();
				$data['menutype'] = 'menu';
				$data['client_id'] = 1;
				$data['title'] = (string) $menuElement;
				$data['alias'] = $alias;
				$data['link'] = 'index.php?option='.'com_'.$alias;
				$data['type'] = 'component';
				$data['published'] = 1;
				$data['parent_id'] = 1;
				$data['component_id'] = $id;
				$attribs = $menuElement->attributes();
				$data['img'] = ((string) $attribs->img) ? (string) $attribs->img : 'class:component';
				$data['home'] = 0;
				$data['language'] = '*';
				$table = JTable::getInstance('menu');

				if (!$table->setLocation(1, 'last-child') || !$table->bind($data) || !$table->check() || !$table->store()) {
					$app->enqueueMessage($table->getError(), 'error');
					return 0;
				}
			}
		}
	}

	if (function_exists('afterInstall')) {
		afterInstall($db);
	}

	$cookieName = JUtility::getHash('version_'.$alias.'_version');
	setcookie($cookieName, '', 0);

	return array((($installed) ? 2 : 1), $id);
}

function installFramework($comp_folder)
{
	$framework_folder = $comp_folder.'/framework/framework';
	$xml_name = 'plugins/system/nnframework/nnframework.xml';
	$xml_file = $framework_folder.'/j2/'.$xml_name;
	if (!JFile::exists($xml_file)) {
		return;
	}

	$do_install = 1;
	$app = JFactory::getApplication();

	$new_version = getXMLVersion($xml_file);
	if ($new_version) {
		$do_install = 1;
		$current_version = getXMLVersion('', 'nnframework', 'plugin');
		if ($current_version) {
			$do_install = version_compare($current_version, $new_version, '<=') ? 1 : 0;
		}
	}

	$success = 1;
	if ($do_install) {
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

function uninstallInstaller($alias = 'nonumber-installer-uninstallme')
{
	$app = JFactory::getApplication();
	// Create database object
	$db = JFactory::getDBO();

	JFolder::delete(JPATH_SITE.'/components/com_'.$alias);
	JFolder::delete(JPATH_ADMINISTRATOR.'/components/com_'.$alias);

	$query = 'DELETE FROM `#__menu`'
		.' WHERE `title` = '.$db->quote('com_nonumber-installer-uninstallme');
	;
	$db->setQuery($query);
	$db->query();

	// Delete language files
	$lang_folder = JPATH_ADMINISTRATOR.'/language';
	$languages = JFolder::folders($lang_folder);
	foreach ($languages as $lang) {
		$file = $lang_folder.'/'.$lang.'/'.$lang.'.com_'.$alias.'.ini';
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