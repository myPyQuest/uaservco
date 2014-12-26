<?php
/**
 * NoNumber! Framework Helper File: VersionCheck
 *
 * @package			NoNumber! Framework
 * @version			12.3.1
 *
 * @author			Peter van Westen <peter@nonumber.nl>
 * @link			http://www.nonumber.nl
 * @copyright		Copyright © 2011 NoNumber! All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

class NNVersions
{
	public static $instance = null;

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new NoNumberVersions;
		}

		return self::$instance;
	}

	public static function instance()
	{
		// backward compatibility
		return self::getInstance();
	}
}

class NoNumberVersions
{
	var $_version = '12.3.1';

	function getMessageBlock($extension = '', $xml = '', $version = '')
	{
		if (!$extension || (!$xml && !$version)) {
			return '';
		}

		$alias = preg_replace('#[^a-z\-]#', '', strtolower($extension));

		if ($xml) {
			$xml = JApplicationHelper::parseXMLInstallFile(JPATH_SITE.'/'.$xml);
			if ($xml && isset($xml['version'])) {
				$version = $xml['version'];
			}
		}

		if (!$version) {
			return '';
		}

		JHtml::_('behavior.mootools');
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/plugins/system/nnframework/js/script.js?v='.$this->_version);
		$url = 'http://download.nonumber.nl/extensions.php';
		$script = "
			window.addEvent( 'domready', function() {
				nnScripts.loadajax(
					'".$url."',
					'nnScripts.displayVersion( data, \'".$alias."\', \'".$version."\' )',
					'nnScripts.displayVersion( \'\' )'
				);
			});
		";
		$document->addScriptDeclaration($script);

		return $this->createMessage($alias, $version);
	}

	function createMessage($alias, $version)
	{
		$is_pro = !(strpos($version, 'PRO') === false);
		$version = str_replace('PRO', '', $version);

		$has_nnem = 0;
		if (JFile::exists(JPATH_ADMINISTRATOR.'/components/com_'.$alias.'/'.$alias.'.xml')
			|| JFile::exists(JPATH_ADMINISTRATOR.'/components/com_'.$alias.'/com_'.$alias.'.xml')
		) {
			$has_nnem = 1;
		}

		$url = 'http://www.nonumber.nl/'.$alias.'#download';
		if ($has_nnem) {
			$url = 'index.php?component=nonumbermanager';
		}

		$msg = '<strong>'
			.JText::_('NN_NEW_VERSION_AVAILABLE')
			.': <a href="'.$url.'" target="_blank">'
			.JText::sprintf('NN_UPDATE_TO', '<span id="nonumber_newversionnumber_'.$alias.'"></span>')
			.'</a></strong><br /><em style="color:#999999">'
			.JText::sprintf('NN_CURRENT_VERSION', $version)
			.' ('
			.JText::_('NN_ONLY_VISIBLE_TO_ADMIN')
			.')</em>';

		$msg = '<div id="nonumber_version_'.$alias.'" style="display: none;border:3px solid #F0DC7E;background-color:#EFE7B8;color:#CC0000;margin:10px 0;padding: 2px 5px;">'
			.html_entity_decode($msg, ENT_COMPAT, 'UTF-8')
			.'</div>';

		return $msg;
	}

	function getCopyright($extension, $version)
	{
		$html = array();
		$html[] = '<p style="text-align:center;">';
		$html[] = $extension;
		if ($version) {
			if (!(strpos($version, 'PRO') === false)) {
				$version = str_replace('PRO', '', $version);
				$version .= ' <small>[PRO]</small>';
			} else if (!(strpos($version, 'FREE') === false)) {
				$version = str_replace('FREE', '', $version);
				$version .= ' <small>[FREE]</small>';
			}
			$html[] = ' v'.$version;
		}
		$html[] = ' - '.JText::_('COPYRIGHT').' &copy; '.date('Y').' NoNumber! '.JText::_('ALL_RIGHTS_RESERVED');
		$html[] = '</p>';

		return implode('', $html);
	}

	// old
	function getMessage($extension = '', $xml = '', $version = '')
	{
		if (!$extension || (!$xml && !$version)) {
			return '';
		}

		$alias = preg_replace('#[^a-z\-]#', '', str_replace('?', '-', strtolower($extension)));

		if ($xml) {
			$xml = JApplicationHelper::parseXMLInstallFile(JPATH_SITE.'/'.$xml);
			if ($xml && isset($xml['version'])) {
				$version = $xml['version'];
			}
		}

		if (!$version) {
			return '';
		}

		JHtml::_('behavior.mootools');
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/plugins/system/nnframework/js/script.js?v='.$this->_version);
		$url = 'http://www.nonumber.nl/ext/version.php?ext='.$alias.'&version='.$version;
		$script = "
			window.addEvent( 'domready', function() {
				nnScripts.loadajax(
					'".$url."',
					'nnScripts.displayVersionOld( data, \'".$alias."\', \'".$version."\' )',
					'nnScripts.displayVersionOld( \'\' )'
				);
			});
		";
		$document->addScriptDeclaration($script);

		return $this->createMessage($alias, $version);
	}

	function getVersion($extension, $xml)
	{
		if (!$extension || !$xml) {
			return '';
		}

		$version = '';
		if ($xml) {
			$xml = JApplicationHelper::parseXMLInstallFile(JPATH_SITE.'/'.$xml);
			if ($xml && isset($xml['version'])) {
				$version = $xml['version'];
			}
		}
		return $version;
	}

	static function getXMLVersion($extension = 'nnframework', $type = 'system', $admin = 1, $urlformat = 0)
	{
		if (!$extension) {
			$extension = 'nnframework';
		}
		if (!$type) {
			$type = 'system';
		}
		if (!strlen($admin)) {
			$admin = 1;
		}

		switch ($type) {
			case 'component':
			case 'components':
			case 'module':
			case 'modules':
				$type .= in_array($type, array('component', 'module')) ? 's' : '';
				if ($admin) {
					$path = JPATH_ADMINISTRATOR;
				} else {
					$path = JPATH_SITE;
				}
				$path .= '/'.$type.'/'.($type == 'modules' ? 'mod_' : 'com_').$extension.'/'.($type == 'modules' ? 'mod_' : '').$extension.'.xml';
				break;
			default:
				$path = JPATH_SITE.'/plugins/'.$type.'/'.$extension.'/'.$extension.'.xml';
				break;
		}

		$version = '';
		$xml = JApplicationHelper::parseXMLInstallFile($path);
		if ($xml && isset($xml['version'])) {
			$version = trim(strtolower($xml['version']));
			if ($urlformat) {
				$version = '?v='.$version;
			}
		}

		return $version;
	}

	function setMessage($current_version = '0', $version_file = '')
	{
		echo $this->getMessage(str_replace('version_', '', $version_file), '', $current_version);
	}
}