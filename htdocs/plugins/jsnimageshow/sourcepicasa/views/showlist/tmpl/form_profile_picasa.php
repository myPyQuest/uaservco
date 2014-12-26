<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_profile_picasa.php 14154 2012-07-18 04:57:43Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$objJSNPicasaSelect = JSNISFactory::getObj('sourcepicasa.classes.jsn_is_picasaselect', null, null, 'jsnplugin');
?>
<script language="javascript">
	JSNISImageShow.submitFormProfile = function()
	{
		var form				= document.adminForm;
		var params				= {};
		params.configTitle		= form.external_source_profile_title.value;
		params.picasaUserName	= form.picasa_username.value;

		if(params.configTitle == '' || params.picasaUserName == '')
		{
			alert( "<?php echo JText::_('PICASA_MAINTENANCE_REQUIRED_FIELD_PROFILE_CANNOT_BE_LEFT_BLANK', true); ?>");
			return;
		}
		else
		{
			var url				= 'index.php?option=com_imageshow&controller=maintenance&task=checkEditProfileExist&source=picasa&external_source_profile_title='+params.configTitle+'&external_source_id=0&rand='+ Math.random();
			params.validateURL 	= 'index.php?option=com_imageshow&controller=maintenance&task=validateProfile&validate_screen=_maintenance&source=picasa&picasa_username='+ params.picasaUserName+'&rand='+ Math.random();
			JSNISImageShow.checkEditProfile(url, params);
		}
	}
	window.addEvent('domready', function()
	{
		JSNISImageShow.profileShowHintText();
	});
</script>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('PICASA_MAINTENANCE_TITLE_PROFILE_TITLE');?> <a class="hint-icon jsn-link-action" href="javascript:void(0);">(?)</a></label>
	<div class="controls">
		<div class="jsn-preview-hint-text">
			<div class="jsn-preview-hint-text-content clearafter">
				<?php echo JText::_('PICASA_MAINTENANCE_DES_PROFILE_TITLE');?>
				<a href="javascript:void(0);" class="jsn-preview-hint-close jsn-link-action">[x]</a>
			</div>
		</div>
		<input class="jsn-master jsn-input-xxlarge-fluid" type="text" name="external_source_profile_title" id="external_source_profile_title" value= "<?php echo @$this->sourceInfo->external_source_profile_title;?>"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('PICASA_MAINTENANCE_TITLE_PICASA_USER');?> <a class="hint-icon jsn-link-action" href="javascript:void(0);">(?)</a></label>
	<div class="controls">
		<div class="jsn-preview-hint-text">
			<div class="jsn-preview-hint-text-content clearafter">
				<?php echo JText::_('PICASA_MAINTENANCE_DES_PICASA_USER');?>
				<a href="javascript:void(0);" class="jsn-preview-hint-close jsn-link-action">[x]</a>
			</div>
		</div>
		<input class="jsn-master jsn-input-xxlarge-fluid" type="text" value="" name="picasa_username"/>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('PICASA_MAINTENANCE_TITLE_THUMBNAIL_MAX_SIZE');?> <a class="hint-icon jsn-link-action" href="javascript:void(0);">(?)</a></label>
	<div class="controls">
		<div class="jsn-preview-hint-text">
			<div class="jsn-preview-hint-text-content clearafter">
				<?php echo JText::_('PICASA_MAINTENANCE_THUMBNAIL_MAX_SIZE_DESC');?>
				<a href="javascript:void(0);" class="jsn-preview-hint-close jsn-link-action">[x]</a>
			</div>
		</div>
		<?php echo $objJSNPicasaSelect->getSelectBoxThumbnailSize(); ?>
	</div>
</div>
<div class="control-group">
	<label class="control-label"><?php echo JText::_('PICASA_MAINTENANCE_TITLE_IMAGE_MAX_SIZE');?> <a class="hint-icon jsn-link-action" href="javascript:void(0);">(?)</a></label>
	<div class="controls">
		<div class="jsn-preview-hint-text">
			<div class="jsn-preview-hint-text-content clearafter">
				<?php echo JText::_('PICASA_MAINTENANCE_IMAGE_MAX_SIZE_DESC');?>
				<a href="javascript:void(0);" class="jsn-preview-hint-close jsn-link-action">[x]</a>
			</div>
		</div>
		<?php echo $objJSNPicasaSelect->getSelectBoxImageSize(); ?>
	</div>
</div>