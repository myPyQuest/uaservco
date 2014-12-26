<?php
/**
 * @version     $Id: controller.php 15885 2012-09-07 05:03:22Z cuongnm $
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

// Import Joomla library
jimport('joomla.application.component.controller');

/**
 * Controller class of JSN Upgrade library.
 *
 * To implement <b>JSNUpgradeController</b> class, create a controller file
 * in <b>administrator/components/com_YourComponentName/controllers</b> folder
 * then put following code into that file:
 *
 * <code>class YourComponentPrefixControllerUpgrade extends JSNUpgradeController
 * {
 * }</code>
 *
 * The <b>JSNUpgradeController</b> class pre-defines <b>download</b> and
 * <b>install</b> method to handle product upgrade task. So, you <b>DO NOT
 * NEED</b> to re-define those methods in your controller class.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUpgradeController extends JSNUpdateController
{
}
