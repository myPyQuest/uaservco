<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step3.php 14464 2012-07-27 08:07:06Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
global $mainframe;
$session 					= JFactory::getSession();
$identifierIsCustomer		= md5('jsn_upgrader_jsn_imageshow_is_customer');
$identifier					= md5('jsn_upgrader_jsn_imageshow');
$isCustomer					= $session->get($identifierIsCustomer, false, 'jsnimageshowsession');
$sessionValue   			= $session->get($identifier, array(), 'jsnimageshowsession');
if (count($sessionValue))
{
	$JSNEdition = (string) $sessionValue['editions'][0];
	$customerName = (string) $sessionValue['customer_username'];
	$customerPass = (string) $sessionValue['customer_password'];
}
else
{
	$JSNEdition 	= (string) JRequest::getString('jsn_upgrade_edition');
	$customerName 	= (string) JRequest::getVar('customer_username');
	$customerPass 	= JRequest::getVar('customer_password', '', 'post', 'string', JREQUEST_ALLOWRAW);
}

if (!$isCustomer || $JSNEdition == '' || $customerName == '' || $customerPass == '')
{
	$mainframe->redirect('index.php?option=com_imageshow&controller=upgrader');
}
?>
<script type="text/javascript">
	var upgraderData = JSON.decode('<?php echo json_encode($this->core); ?>');
	var upgrader = new JSNISUpgrader(upgraderData);
	window.addEvent('domready', function() {
		upgrader.upgrade('<?php echo @$customerName; ?>', '<?php echo @$customerPass; ?>', '<?php echo $JSNEdition; ?>');
	});
</script>
<h2><?php echo JText::sprintf('UPGRADER_HEADING_STEP2', strtoupper($JSNEdition)); ?></h2>
<div id="jsn-upgrader-upgrading">
	<p><?php echo JText::_('UPGRADER_UPGRADE_UPGRADING'); ?></p>
	<ul>
		<li id="jsn-upgrader-data-package-title">
			<?php echo JText::_('UPGRADER_UPGRADE_DOWNLOAD_PACKAGE'); ?>.&nbsp;
			<span class="jsn-icon16 icon-loading" id="jsn-upgrader-downloading"></span>
			<span id="jsn-upgrader-downloading-text" class="jsn-upgrader-process-message"><span></span></span>
			<span class="jsn-icon16 icon-ok" id="jsn-upgrader-successful-downloading"></span>
			<span class="jsn-icon16 icon-remove" id="jsn-upgrader-unsuccessful-downloading">&nbsp;</span>
			<br /><span id="jsn-upgrader-unsuccessful-downloading-message"></span>
		</li>
		<li id="jsn-upgrader-data-package-installing-title">
			<?php echo JText::_('UPGRADER_UPGRADE_INSTALLING_DOWNLOAD_PACKAGE'); ?>.&nbsp;
			<span class="jsn-icon16 icon-loading" id="jsn-upgrader-installing"></span>
			<span id="jsn-upgrader-installing-text" class="jsn-upgrader-process-message"><span></span></span>
		</li>
		<li id="jsn-upgrader-upgraded">
			<?php echo JText::_('UPGRADER_UPGRADED'); ?>.&nbsp;
			<span class="jsn-icon16 icon-ok"></span>
		</li>
		<li id="jsn-upgrader-failure">
			<?php echo JText::_('UPGRADER_INSTALL_PACKAGE'); ?>.&nbsp;
			<span class="jsn-icon16 icon-remove"></span>
		</li>
	</ul>
</div>
<div id="jsn-installation-successfull-message" class="jsn-installation-successfull-message">
	<?php echo JText::_('UPGRADER_INSTALLATION_CONGRATULATION'); ?>
</div>
<div class="form-actions">
	<button id="jsn-installation-button-finish" class="btn btn-primary" onclick="window.location.href='index.php?option=com_imageshow';">
	<?php echo  JText::_('INSTALLER_FINISH');?>
	</button>
	<button id="jsn-installation-button-close" class="btn btn-primary" onclick="window.location.href='index.php?option=com_imageshow';">
	<?php echo  JText::_('INSTALLER_CLOSE');?>
	</button>
</div>
<div id="jsn-upgrader-manual-upgrading">
</div>
<?php $session->set($identifierIsCustomer, false, 'jsnimageshowsession'); ?>
<?php $session->set($identifier, array(), 'jsnimageshowsession'); ?>