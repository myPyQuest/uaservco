<?php
/**
 * @version     $Id: helper.php 16039 2012-09-14 05:12:12Z hiepnv $
 * @package     JSN_Framework
 * @subpackage  Upgrade
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Helper class for JSN Upgrade implementation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUpgradeHelper
{
	/**
	 * Render the product upgrade page.
	 *
	 * @param   object  $info          JSON decoded extension's manifest cache.
	 * @param   string  $introForFree  Some introduction about the benefit of upgrading free product to pro standard edition.
	 * @param   string  $introForPro   Some introduction about the benefit of upgrading pro standard product to pro unlimited edition.
	 *
	 * @return  void
	 */
	public static function render($info, $introForFree = '', $introForPro = '', $redirAfterFinish = '')
	{
		require dirname(__FILE__) . DS . 'tmpl' . DS . 'default.php';
	}
}
