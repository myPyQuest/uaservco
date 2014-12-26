<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: helper.php 8411 2011-09-22 04:45:10Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
abstract class modImageShowQuickIconHelper
{
	protected static $buttons 		  = array();
	protected static $_updateText	  = '';

	public static function button($button)
	{
		ob_start();
		require JModuleHelper::getLayoutPath('mod_imageshow_quickicon', 'default_button');
		$html = ob_get_clean();
		return $html;
	}

	public static function &getButtons()
	{
		if (empty(self::$buttons))
		{
			self::$buttons = array(
				array(
					'link' => JRoute::_('index.php?option=com_imageshow'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-jcontrolpanel.png',
					'text' => JText::_('LAUNCH_PAD'),
					'extra_text' => ''
				),
				array(
					'link' => JRoute::_('index.php?option=com_imageshow&controller=showlist'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-showlist.png',
					'text' => JText::_('SHOWLISTS_MANAGER'),
					'extra_text' => ''
				),
				array(
					'link' => JRoute::_('index.php?option=com_imageshow&controller=showlist&task=add'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-newshowlist.png',
					'text' => JText::_('ADD_NEW_SHOWLISTS'),
					'extra_text' => ''
				),
				array(
					'link' => JRoute::_('index.php?option=com_imageshow&controller=showcase'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-showcase.png',
					'text' => JText::_('SHOWCASES_MANAGER'),
					'extra_text' => ''
				),
				array(
					'link' => JRoute::_('index.php?option=com_imageshow&controller=showcase&task=add'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-newshowcase.png',
					'text' => JText::_('ADD_NEW_SHOWCASES'),
					'extra_text' => ''
				),
				array(
					'link' => JRoute::_('index.php?option=com_imageshow&controller=about'),
					'image' => 'modules/mod_imageshow_quickicon/assets/images/icons/icon-about.png',
					'text' => JText::_('ABOUT'),
					'extra_text' => ''
				)
			);
		}
		return self::$buttons;
	}

	public static function approveModule($moduleName, $publish = 1)
	{
		$db 	=& JFactory::getDBO();
		$query 	= 'UPDATE #__modules SET published ='.$publish.' WHERE module = '.$db->Quote($moduleName, false);
		$db->setQuery($query);
		if (!$db->query())
		{
			return false;
		}
		return true;
	}
}