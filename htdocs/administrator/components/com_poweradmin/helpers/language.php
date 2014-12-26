<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: language.php 13382 2012-06-18 08:35:00Z hiepnv $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

final class JSNLanguageHelper
{
	public static function getSupportedLanguage ($area = 'site')
	{
		$path = (($area == 'site') ? JPATH_COMPONENT_ADMINISTRATOR.DS.'languages/site' : JPATH_COMPONENT_ADMINISTRATOR).DS.'languages/admin';
		$files = glob("{$path}/*.ini");		
		$supportedLanguages = array();
		foreach ($files as $file) {
			$name = basename($file);
			if (preg_match('/^([a-z]+)\-([A-Z]+)\./', $name, $matches)) {
				$code = $matches[1].'-'.$matches[2];
				if (!in_array($code, $supportedLanguages)) {
					$supportedLanguages[] = $code;
				}
			}
		}		
		return $supportedLanguages;
	}
}




