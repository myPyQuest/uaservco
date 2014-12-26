<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step1.php 14495 2012-07-28 03:13:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$objJSNUtil		= JSNISFactory::getObj('classes.jsn_is_utils');
?>
<p>
<?php echo JText::sprintf('UPGRADER_BASIC_INFO', (($this->edition == 'free')?'PRO':'PRO UNLIMITED')); ?>
</p>
<div class="alert alert-info">
	<p><span class="label label-info"><?php echo JText::_('IMPORTANT_INFO'); ?></span></p>
	<?php
		echo JText::_('UPGRADER_FREE_IMPORTANT_INFO');
	?>
</div>
<?php
if ($this->edition == 'free')
{
	echo JText::_('UPGRADER_STANDARD_BENEFITS');
}
echo JText::_('UPGRADER_UNLIMITED_BENEFITS');
?>
<form method="POST" action="index.php?option=com_imageshow&controller=upgrader&step=2" id="frm-upgradeinfo" name="frm_upgradeinfo" autocomplete="off">
	<div class="form-actions">
		<p>
			<a class="btn btn-primary" href="javascript: void(0);" onclick="document.frm_upgradeinfo.submit();" id="jsn-proceed-button">
		<?php
			echo JText::sprintf('UPGRADER_PROCEED_BUTTON', (($this->edition == 'free')?'PRO':'PRO UNLIMITED'));
		?></a>
		</p>
		<p>
			<a class="jsn-link-action" target="_blank" href="<?php echo JSN_IS_BUY_LINK; ?>">
		<?php
			echo JText::sprintf('UPGRADER_BUY_LINK_TEXT', ($this->edition == 'free')?'PRO':'PRO UNLIMITED');
		?></a>
		</p>
	</div>
	<input type="hidden" name="task" value="upgrade_proceeded" />
</form>