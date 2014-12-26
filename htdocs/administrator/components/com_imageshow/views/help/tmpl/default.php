<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 14027 2012-07-14 11:25:49Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'HELP_HELP_AND_SUPPORT' ), 'help' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('HELP_AND_SUPPORT');
$shortEdition = $objJSNUtils->getShortEdition();
$objJSNXML = JSNISFactory::getObj('classes.jsn_is_readxmldetails');
$xmlDetails = $objJSNXML->parserXMLDetails();
?>

<div id="jsn-imageshow-help" class="jsn-page-help">
	<div class="jsn-bootstrap">
		<div class="row-fluid">
			<div class="span4">
				<h2 class="jsn-section-header"><?php echo JText::_('HELP_DOCUMENTATION'); ?></h2>
				<div class="jsn-section-content">
					<?php echo JText::_('HELP_DES_DOCUMENTATION'); ?>
					<ul>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip" target="_blank"><?php echo JText::_('HELP_DOWNLOAD_PDF_DOCUMENTATION'); ?>
						</a></li>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-videos.html" target="_blank"><?php echo JText::_('HELP_WATCH_QUICK_START_VIDEO'); ?>
						</a></li>
					</ul>
				</div>
			</div>
			<div class="span4">
				<h2 class="jsn-section-header"><?php echo JText::_('HELP_SUPPORT_FORUM'); ?></h2>
				<div class="jsn-section-content">
					<?php echo JText::_('HELP_DES_CHECK_SUPPORT_FORUM'); ?>
					<ul>
						<?php if (strtolower($xmlDetails['edition']) == 'pro standard' || strtolower($xmlDetails['edition']) == 'pro unlimited') :?>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/forum/" target="_blank"><?php echo JText::_('HELP_CHECK_SUPPORT_FORUM'); ?>
						</a></li>
						<?php endif;?>
						<?php if ($shortEdition != 'pro'):?>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html" target="_blank"><?php echo JText::_('HELP_BUY_PRO_STANDARD_EDITION'); ?>
						</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<div class="span4">
				<h2 class="jsn-section-header"><?php echo JText::_('HELP_HELPDESK_SYSTEM'); ?></h2>
				<div class="jsn-section-content">
					<?php echo JText::_('HELP_DES_HELPDESK_SYSTEM'); ?>
					<ul>
						<?php if (strtolower($xmlDetails['edition']) == 'pro unlimited') :?>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/dedicated-support.html" target="_blank"><?php echo JText::_('HELP_SUBMIT_TICKET_IN_HELPDESK_SYSTEM'); ?>
						</a></li>
						<?php endif;?>
						<?php if ($shortEdition != 'pro'):?>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/joomla-extensions/buy-jsn-imageshow.html" target="_blank"><?php echo JText::_('HELP_BUY_PRO_UNLIMITED_EDITION'); ?>
						</a></li>
						<?php endif; ?>
						<?php if (strtolower($xmlDetails['edition']) == 'pro standard') :?>
						<li><a class="jsn-link-action" href="http://www.joomlashine.com/docs/general/how-to-upgrade-to-pro-unlimited-edition.html" target="_blank"><?php echo JText::_('HELP_UPGRADE_TO_PRO_UNLIMITED_EDITION'); ?>
						</a></li>
						<?php endif;?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
