<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_profile_picasa.php 14154 2012-07-18 04:57:43Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$externalSourceID = JRequest::getInt('external_source_id');
$objJSNPicasaSelect = JSNISFactory::getObj('sourcepicasa.classes.jsn_is_picasaselect', null, null, 'jsnplugin');
?>
<script language="javascript">
	JSNISImageShow.submitForm = function()
	{
		var form = document.adminForm;
		form.submit();
		window.top.setTimeout('window.parent.jQuery.closeAllJSNWindow(); window.top.location.reload(true)', 1000);
	}

	function onSubmit()
	{
		var form 				= document.adminForm;
		var params 				= {};
		params.picasaUserName 	= form.picasa_username.value;
		params.configTitle 		= form.external_source_profile_title.value;

		if(params.configTitle == '' || params.picasaUserName == '')
		{
			alert( "<?php echo JText::_('PICASA_MAINTENANCE_REQUIRED_FIELD_PROFILE_CANNOT_BE_LEFT_BLANK', true); ?>");
			return;
		}
		else
		{
			var url  = 'index.php?option=com_imageshow&controller=maintenance&task=checkEditProfileExist&source=picasa&external_source_profile_title='+params.configTitle+'&external_source_id='+<?php echo $this->sourceInfo->external_source_id; ?>;
			params.validateURL 	= 'index.php?option=com_imageshow&controller=maintenance&task=validateProfile&validate_screen=_maintenance&source=picasa&picasa_username='+ params.picasaUserName;
			JSNISImageShow.checkEditProfile(url, params);
		}
	}
	parent.gIframeFunc = onSubmit;
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
		<input type="text" class="jsn-master jsn-input-xxlarge-fluid" name ="external_source_profile_title" id="external_source_profile_title" value = "<?php echo @$this->sourceInfo->external_source_profile_title;?>"/>
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
		<input type="text" <?php echo ($this->countShowlist) ? 'disabled="disabled" class="jsn-readonly jsn-master jsn-input-xxlarge-fluid"' : 'class="jsn-master jsn-input-xxlarge-fluid"'; ?>value="<?php echo @$this->sourceInfo->picasa_username;?>" name="picasa_username" />
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
		<?php
			$thumbSize = $objJSNPicasaSelect->getThumbnailSizeOptions();
			echo JHTML::_('select.genericList', $thumbSize, 'picasa_thumbnail_size', 'class="jsn-master jsn-input-xxlarge-fluid"', 'value', 'text', $this->sourceInfo->picasa_thumbnail_size);
		?>
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
		<?php
			$imageSize = $objJSNPicasaSelect->getImageSizeOptions();
			echo JHTML::_('select.genericList', $imageSize, 'picasa_image_size', 'class="jsn-master jsn-input-xxlarge-fluid"', 'value', 'text', $this->sourceInfo->picasa_image_size);
		?>
	</div>
</div>
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="controller" value="maintenance" />
<input type="hidden" name="task" value="saveprofile" id="task" />
<input type="hidden" name="source" value="picasa" />
<input type="hidden" name="external_source_id" value="<?php echo $externalSourceID; ?>" id="external_source_id" />
<?php echo JHTML::_( 'form.token' ); ?>