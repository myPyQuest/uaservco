<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: package.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');

class JSNDownloadPackage
{
	var $_tmpPackageName 	= '';
	var $_downloadURL		= '';
	var $_tmpFolder			= '';

	function _getFilenameFromURL($url)
	{
		if (is_string($url))
		{
			$parts = explode('/', $url);
			return $parts[count($parts) - 1];
		}
		return false;
	}

	function _cURLCheckFunctions()
	{
		if (!function_exists("curl_init") &&
				!function_exists("curl_setopt") &&
				!function_exists("curl_exec") &&
				!function_exists("curl_close")) {
			return false;
		};
		
		return true;
	}

	function _fOPENCheck()
	{
		return (boolean) ini_get('allow_url_fopen');
	}

	function readFileZipToBinaryData($file)
	{
		if (!JFile::exists($file)) return false;
		
		$file = @fopen($file, 'r');
		$contents = '';
		
		while (!feof($file))
		{
			$contents .= fread($file, 8192);
		
			if ($contents === false) {
				return false;
			}
		}
		
		fclose($file);
		return $contents;
	}
}