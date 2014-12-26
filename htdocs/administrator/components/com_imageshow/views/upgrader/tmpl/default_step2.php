<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step2.php 14450 2012-07-27 05:02:11Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$session 		= JFactory::getSession();
$identifier		= md5('jsn_upgrader_jsn_imageshow');
$sessionValue   = $session->get($identifier, array(), 'jsnimageshowsession');
$objJSNUtil			= JSNISFactory::getObj('classes.jsn_is_utils');
$writable			= array();
$writable 			= array_merge($writable, $objJSNUtil->checkFolderUnwritableOnUpdateAndUpgrade('core'));
$writable 			= array_unique($writable);
?>
<?php if(!count($writable)) {?>
<script type="text/javascript">
	var upgrader = new JSNISUpgrader({});
</script>
<form method="POST" action="index.php?option=com_imageshow&controller=upgrader&step=<?php echo (count($sessionValue) && $sessionValue['success'] && count($sessionValue['editions']) > 1)?'3':'2';?>" id="frm-login" name="frm_login" class="form-horizontal" autocomplete="off">
	<h2><?php echo JText::_('UPGRADER_HEADING_STEP1'); ?></h2>
	<p><?php echo JText::_('UPGRADER_LOGIN_MES'); ?></p>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
				<label class="inline" for="username"><?php echo JText::_('UPGRADER_USERNAME'); ?></label>
				<input name="customer_username" id="username" <?php echo (@$sessionValue['customer_username'] !='')?'readonly="readonly" class="input-xlarge jsn-readonly"':'class="input-xlarge"'?> value="<?php echo @$sessionValue['customer_username']; ?>" type="text" onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="upgrader.setNextButtonState(this.form, this.form.next_step_button);" />
			</div>
		</div>
		<div class="span6">
			<div class="control-group">
				<label class="inline" for="password"><?php echo JText::_('UPGRADER_PASSWORD'); ?></label>
				<input name="customer_password" id="password" <?php echo (@$sessionValue['customer_password'] !='')?'readonly="readonly" class="input-xlarge jsn-readonly"':'class="input-xlarge"'?> value="<?php echo @$sessionValue['customer_password']; ?>" type="password"  onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="upgrader.setNextButtonState(this.form, this.form.next_step_button);" />
			</div>
		</div>
    </div>
	<?php
		if (count($sessionValue) && @$sessionValue['success'] && count(@$sessionValue['editions']) > 1)
		{
	?>
	<hr />
	<p><?php echo JText::_('UPGRADER_MULTIPLE_SELECT_MES'); ?></p>
	<div>
		<label class="inline" for="jsn-upgrade-edition-select"><?php echo JText::_('UPGRADER_UPGRADE_TO'); ?></label>
		<select class="input-xlarge" name="jsn_upgrade_edition" id="jsn-upgrade-edition-select" onchange="upgrader.setNextButtonState(this.form, this.form.next_step_button_new);">
			<option value=""><?php echo JText::_('UPGRADER_DEFAULT_SELECT_OPTION'); ?></option>
			<?php
				$editions = $sessionValue['editions'];
				$counte   = count($editions);
				foreach ($editions as $value)
				{
				?>
					<option value="<?php echo strtolower($value)?>"><?php echo $value; ?></option>
				<?php
				}
			?>
		</select>
	</div>
	<?php } else { ?>
		<input type="hidden" name="task" value="authenticate" />
	<?php }?>
	<hr />
	<div class="form-actions">
		<button class="btn btn-primary disabled" id="jsn-upgrader-btn-next" disabled="disabled" onclick="this.disabled=true; this.addClass('disabled'); $('jsn-upgrader-cancel').setStyle('display', 'none'); document.frm_login.submit();" name="<?php echo (count($sessionValue) && $sessionValue['success'] && count($sessionValue['editions']) > 1)?'next_step_button_new':'next_step_button';?>"><?php echo JText::_('UPGRADER_NEXT_BUTTON'); ?></button>
	</div>
	<input type="hidden" name="identify_name" value="<?php echo $this->core->identify_name;?>" />
	<input type="hidden" name="based_identified_name" value="<?php echo $this->core->based_identified_name;?>" />
	<input type="hidden" name="edition" value="<?php echo $this->core->edition;?>" />
	<input type="hidden" name="language" value="<?php echo $this->core->language;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php $session->set($identifier, array(), 'jsnimageshowsession'); ?>
<?php } else { ?>
	<p><strong><?php echo JText::_('UPGRADER_THE_FOLLOWING_FOLDERS_MUST_WRITABLE_PERMISSION'); ?>:</strong></p>
	<?php
		echo '<ul>';
		foreach ($writable as $item)
		{
			echo '<li>'.$item.'</li>';
		}
		echo '</ul>';
	?>
	<p><strong><?php echo JText::_('UPGRADER_PLEASE_SET_WRIABLE_PERMISSION'); ?></strong></p>
	<div class="form-actions">
		<a class="btn btn-primary" href="javascript: void(0);" onclick="window.location.reload(true);"><?php echo JText::_('UPGRADER_TRY_AGAIN'); ?></a>
	</div>
<?php } ?>