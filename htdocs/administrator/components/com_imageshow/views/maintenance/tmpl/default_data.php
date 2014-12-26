<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_data.php 14537 2012-07-28 09:42:53Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$selectedTab 				= JRequest::getInt('tab', 0, 'get');
$myaction	 				= JRequest::getVar('myaction');
$user 		 				= JFactory::getUser();
$session 					= JFactory::getSession();
$restoreResult 				= $session->get('JSNISRestore');
$methodInstallSampleData 	= JRequest::getVar('method_install_sample_data');
$objJSNUtil 				= JSNISFactory::getObj('classes.jsn_is_utils');
$canAutoDownload			= $objJSNUtil->checkEnvironmentDownload();
$db 						= JFactory::getDbo();
?>
<script language="javascript" type="text/javascript">
	var restoreOption = {
		wait_text: '<?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_WAIT_TEXT', true); ?>',
		process_text: '<?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_PROCESS_TEXT', true); ?>',
		textTag: 'span'
	};

	JSNISInstallDefault.options = $merge(JSNISInstallDefault.options, restoreOption);
	JSNISInstallShowcaseThemes.options = $merge(JSNISInstallShowcaseThemes.options, JSNISInstallDefault.options);
	JSNISInstallImageSources.options = $merge(JSNISInstallImageSources.options, JSNISInstallDefault.options);

	function backup()
	{
		document.getElementById('frm_backup').submit();
	}

	function restore()
	{
		if (document.getElementById('file-upload').value == ""){
			alert( "<?php echo JText::_('MAINTENANCE_BACKUP_YOU_MUST_SELECT_A_FILE_BEFORE_IMPORTING', true); ?>" );
			return false;
		}else {
			document.getElementById('frm_restore').submit();
		}
	}

	function clearSessionRestoreResult(){
		document.adminFormRestore.task.value = 'clearSessionRestoreResult';
		document.adminFormRestore.submit();
	}

	function setButtonState(form)
	{
		if(form.agree_install_sample.checked)
		{
			form.button_installation_data.disabled = false;
			$(form.button_installation_data).removeClass('disabled');
		}
		else
		{
			form.button_installation_data.disabled = true;
			$(form.button_installation_data).addClass('disabled');
		}
	}

	function setBackupButtonState(form)
	{
		var showlist = form.showlists.checked;
		var showcase = form.showcases.checked;
		var filename = form.filename.value;
		if ((showlist || showcase) && filename != '')
		{
			form.button_backup_data.disabled = false;
			$(form.button_backup_data).removeClass('disabled');
		}
		else
		{
			form.button_backup_data.disabled = true;
			$(form.button_backup_data).addClass('disabled');
		}
	}

	function setRestoreButtonState(form)
	{
		var filedata = form.filedata.value;
		if (filedata != '')
		{
			form.button_backup_restore.disabled = false;
			$(form.button_backup_restore).removeClass('disabled');
		}
		else
		{
			form.button_backup_restore.disabled = true;
			$(form.button_backup_restore).addClass('disabled');
		}
	}
</script>
<script>
(function($){
	$(document).ready(function () {
		$('#jsn_tabs').tabs();
		<?php if ($selectedTab == '1') { ?>
	    $('#jsn_tabs').tabs('select', 1);
	    <?php } else {?>
	    $('#jsn_tabs').tabs('select', 0);
	    <?php } ?>
	})
})(jQuery);
</script>
<div id="jsn-data">
	<h2 class="jsn-section-header">
		<?php echo JText::_('MAINTENANCE_DATA'); ?>
	</h2>
	<div id="jsn_tabs">
		<ul>
			<?php if (strtolower($db->name) != 'sqlsrv') {?>
			<li><a href="#tab1"><?php echo JText::_('MAINTENANCE_SAMPLE_DATA_INSTALLATION'); ?></a></li>
			<li><a href="#tab2"><?php echo JText::_('MAINTENANCE_DATA_BACKUP_AND_RESTORE'); ?></a></li>
			<?php } ?>
			<li<?php echo (strtolower($db->name) == 'sqlsrv')?' class="active"':''; ?>><a <?php echo (strtolower($db->name) == 'sqlsrv')?' href="#tab1"':' href="#tab3"'; ?> data-toggle="tab"><?php echo JText::_('MAINTENANCE_DATA_MAINTENANCE'); ?></a></li>
		</ul>
		<?php if (strtolower($db->name) != 'sqlsrv') {?>
		<div id="tab1">
			<?php
				if ($methodInstallSampleData != '' && $methodInstallSampleData == 'manually' || !$canAutoDownload)
				{
					echo $this->loadTemplate('manual_sampledata');
				}
				else
				{
					echo $this->loadTemplate('auto_sampledata');
				}
			?>
		</div>
		<div id="tab2">
			<form action="index.php?option=com_imageshow&controller=maintenance" method="POST" name="adminFormBackup" id="frm_backup" onsubmit="return false;">
				<fieldset>
					<div>
						<legend><?php echo JText::_('MAINTENANCE_DATA_BACKUP')?></legend>
					</div>
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_FILENAME'); ?>:</label>
								<div class="controls">
									<input type="text" id="filename" name="filename" onkeyup="return setBackupButtonState(this.form);"/ class="jsn-input-large-fluid">
									<label for="timestamp" class="checkbox">
										<input type="checkbox" name="timestamp" id="timestamp" value="1" />
										<?php echo JText::_('MAINTENANCE_BACKUP_ATTACH_TIMESTAMP_TO_FILENAME'); ?>
									</label>
								</div>
							</div>
						</div>
						<div class="span6">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_OPTIONS'); ?>:</label>
								<div class="controls">
									<label for="showlist" class="checkbox">
										<input type="checkbox" name="showlists" id="showlist" value="1" onclick="return setBackupButtonState(this.form);"/>
										<?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWLISTS'); ?>
									</label>
									<label for="showcases" class="checkbox">
										<input type="checkbox" name="showcases" id="showcases" value="1" onclick="return setBackupButtonState(this.form);"/>
										<?php echo JText::_('MAINTENANCE_BACKUP_BACKUP_SHOWCASES'); ?>
									</label>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<div class="form-actions">
					<button class="btn btn-primary disabled" type="button" value="<?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?>" onclick="backup();" disabled="disabled" name="button_backup_data"><?php echo JText::_('MAINTENANCE_BACKUP_BACKUP');?></button>
				</div>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="backup" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
			<?php
				if ($canAutoDownload) {
					echo $this->loadTemplate('auto_restore');
				} else {
					echo $this->loadTemplate('manual_restore');
				}
			?>
		</div>
		<?php } ?>
		<div class="<?php echo (strtolower($db->name) == 'sqlsrv')?' active':''; ?>" <?php echo (strtolower($db->name) == 'sqlsrv')?' id="tab1"':' id="tab3"'; ?>>
			<form action="index.php?option=com_imageshow&controller=maintenance" name="adminFormDatamaintenance" id="frm_datamaintenance">
				<fieldset>
					<legend>
						<?php echo JText::_("MAINTENANCE_RECREATE_THUMBNAILS");?>&nbsp;
						<span class="jsn-icon16 icon-loading" id="jsn-creating-thumbnail"></span>
						<span class="jsn-icon16 icon-check" id="jsn-creat-thumbnail-successful"></span>
						<span class="jsn-icon16 icon-warning" id="jsn-creat-thumbnail-unsuccessful"></span>
					</legend>
					<div class="control-group">
						<p><?php echo JText::_('MAINTENANCE_THIS_PROCESS_WILL_RECREATE_ALL_THUMBNAILS'); ?></p>
					</div>
					<div class="form-actions">
						<button class="btn btn-primary" id="jsn-button-delete-obsolete-thumnail" type=button value="<?php echo JText::_('MAINTENANCE_START'); ?>" onclick="JSNISImageShow.deleteObsoleteThumbnails('<?php echo JUtility::getToken();?>')"><?php echo JText::_('MAINTENANCE_START'); ?></button>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>