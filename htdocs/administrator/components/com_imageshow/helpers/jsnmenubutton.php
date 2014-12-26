<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: jsnmenubutton.php 14176 2012-07-19 08:58:46Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.utilities.utility' );
class JButtonJSNMenuButton extends JButton
{
	protected $_name = 'JSNMenuButton';

	public function fetchButton($type = 'JSNMenuButton')
	{
		$edit 		= JRequest::getVar('edit');
		$text		= JText::_('JSN_MENU_BUTTON');
		$document 	= JFactory::getDocument();
		$strAlert = '';

		if (!is_null($edit))
		{
			$strAlert = 'jsnMenu.getElements(\'a\').each(function(el)
					{
						el.addEvent(\'click\', function(event)
						{
							event = new Event(event);
							event.stop();

							JSNISImageShow.menuLinkRedirect = el.href;

							var result = confirm("'.JText::_('JSN_MENU_CONFIRM_BOX_ALERT', true).'");

							if (result == true)
							{
							  	JSNISImageShow.jsnMenuSaveToLeave(\'save\', JSNISImageShow.menuLinkRedirect);
							}
							else
							{
							  	JSNISImageShow.jsnMenuSaveToLeave(\'notsave\', JSNISImageShow.menuLinkRedirect);
							}
						});
					});';
		}

		$document->addScriptDeclaration("
			window.addEvent('domready', function()
			{
				JSNISImageShow.jsnMenuEffect();

				var jsnMenu = $('jsn-menu');

				JSNISImageShow.menuLinkRedirect = null;

				".$strAlert."
			})
		");

		$objJSNShowlist = JSNISFactory::getObj('classes.jsn_is_showlist');
		$objJSNShowcase = JSNISFactory::getObj('classes.jsn_is_showcase');
		$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
		$listShowlist 	= $objJSNShowlist->getLastestShowlist(5);
		$listShowcase 	= $objJSNShowcase->getLastestShowcase(5);

		$html  = '<ul id="jsn-menu" class="clearafter">';
		$html .= '	<li class="jsn-menu-trigger"><a><span class="jsn-icon32 icon-menu"></span>'.$text.'</a>';
		$html .= '		<ul class="jsn-mainmenu jsn-box-shadow-mini jsn-rounded-mini">';
		$html .= '			<li class="first parent primary"><a href="index.php?option=com_imageshow"><span class="jsn-icon24 icon-off"></span>'.JText::_('JSN_MENU_LAUNCH_PAD').'</a></li>';
		$html .= '			<li class="parent primary"><a href="index.php?option=com_imageshow&controller=showlist"><span class="jsn-icon24 icon-file"></span>'.JText::_('JSN_MENU_SHOWLISTS').'</a>';
		$html .= $objJSNUtils->renderListItems($listShowlist);
		$html .= '			</li>';
		$html .= '			<li class="parent primary"><a href="index.php?option=com_imageshow&controller=showcase"><span class="jsn-icon24 icon-monitor"></span>'.JText::_('JSN_MENU_SHOWCASES').'</a>';
		$html .= $objJSNUtils->renderListItems($listShowcase, 'showcase');
		$html .= '			</li>';
		$html .= '			<li class="separator"></li>';
		$html .= '			<li><a href="index.php?option=com_imageshow&controller=maintenance&type=configs" class="clearafter">'.JText::_('JSN_MENU_CONFIGURATION_AND_MAINTENANCE').'</a></li>';
		$html .= '			<li><a href="index.php?option=com_imageshow&controller=help" class="clearafter">'.JText::_('JSN_MENU_HELP_AND_SUPPORT').'</a></li>';
		$html .= '			<li class="last"><a href="index.php?option=com_imageshow&controller=about" class="clearafter">'.JText::_('JSN_MENU_ABOUT').'</a></li>';
		$html .= '		</ul>';
		$html .= '	</li>';
		$html .= '</ul>';

		return $html;
	}

	public function fetchId() {
		return "jsn-is-menu-button";
	}
}