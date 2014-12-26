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
$identifier		= md5('jsn_updater_jsn_imageshow');
$sessionValue   = $session->get($identifier, array(), 'jsnimageshowsession');
$paramsLang 	= JComponentHelper::getParams('com_languages');
$adminLang 		= $paramsLang->get('administrator', 'en-GB');
$return			= JRequest::getVar('return', '', 'get');
?>
<script type="text/javascript">
	var updater = new JSNISUpdater({});
</script>
<form method="POST" action="index.php?option=com_imageshow&controller=updater&step=<?php echo (count($sessionValue) && $sessionValue['success'])?'3':'2';?>&return=<?php echo $return;?>" id="frm-login" name="frm_login" class="upgrader-from form-horizontal" autocomplete="off">
	<h2><?php echo JText::_('UPDATER_HEADING_STEP1'); ?></h2>
	<p><?php echo JText::_('UPGRADER_LOGIN_MES'); ?></p>
	<div class="row-fluid">
		<div class="span6">
			<div class="control-group">
				<label class="inline" for="username"><?php echo JText::_('UPGRADER_USERNAME'); ?></label>
				<input name="customer_username" id="username" class="input-xlarge"  type="text" onchange="updater.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="updater.setNextButtonState(this.form, this.form.next_step_button);" />
			</div>
		</div>
		<div class="span6">
			<div class="control-group">
				<label class="inline" for="password"><?php echo JText::_('UPGRADER_PASSWORD'); ?></label>
				<input name="customer_password" class="input-xlarge" id="password" type="password"  onchange="updater.setNextButtonState(this.form, this.form.next_step_button);" onkeyup="updater.setNextButtonState(this.form, this.form.next_step_button);" />
			</div>
		</div>
	</div>
	<input type="hidden" name="task" value="authenticate" />
	<hr />
	<div class="form-actions">
		<button class="btn btn-primary disabled" id="jsn-upgrader-btn-next" disabled="disabled" onclick="this.disabled=true; this.addClass('disabled'); $('jsn-updater-link-cancel').setStyle('display', 'none'); document.frm_login.submit();" name="next_step_button"><?php echo JText::_('UPGRADER_NEXT_BUTTON'); ?></button>
	</div>
	<input type="hidden" name="identify_name" value="<?php echo $this->imageshowCore->id;?>" />
	<input type="hidden" name="based_identified_name" value="" />
	<input type="hidden" name="edition" value="<?php echo $this->imageshowCore->edition;?>" />
	<input type="hidden" name="language" value="<?php echo $adminLang;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php $session->set($identifier, array(), 'jsnimageshowsession'); ?>