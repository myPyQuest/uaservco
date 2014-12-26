<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_profile.php 14151 2012-07-18 04:56:05Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe;
$showlistID 	= JRequest::getInt('showlist_id', 0);
$sourceIdentify = JRequest::getVar('source_identify', '');
$sourceType		= JRequest::getVar('image_source_type', '');
$return			= JRequest::getVar('return', '', 'get');
if (base64_decode($return) != @$_SERVER['HTTP_REFERER'])
{
	$mainframe->redirect(base64_decode($return));
	return;
}
$availableProfile = array();

if ($sourceIdentify != '') {
	$imageSource = JSNISFactory::getSource($sourceIdentify, $sourceType, $showlistID);
	$availableProfile = $imageSource->getAvaiableProfiles();
}
$availableProfile = array_reverse($availableProfile);
$exsitedAvailableProfile = count($availableProfile);
$availableProfile[] = array('value' => 0,
				'text' => ' - '.JText::_('SHOWLIST_PROFILE_SELECT_PROFILE').' - ');
$availableProfile = array_reverse($availableProfile);
?>
<script type="text/javascript">
	function onSubmit()
	{
		if ($('profile_type_new') != null && $('profile_type_new').checked)
		{
			$('task').value = 'createprofile';
			if ($('external_source_id')){
				$('external_source_id').value = 0;
			}
			JSNISImageShow.submitFormProfile();
		}
		if ($('profile_type_available') != null && $('profile_type_available').checked)
		{
			$('task').value = 'changeprofile';
			var form = document.adminForm;
			if (form.external_source_id.selectedIndex == 0)
			{
				alert( "<?php echo JText::_('SHOWLIST_PROFILE_SELECT_AVAILABLE_PROFILE', true); ?>");
				return;
			}

			JSNISImageShow.submitForm();
		}
	}

	JSNISImageShow.submitForm = function()
	{
		if ($('submit-available-profile-form') != null)
		{
			$('submit-available-profile-form').disabled = true;
			$('submit-available-profile-form').addClass('disabled');
		}

		JSNISImageShow.submitProfile('adminForm');
	}

	window.addEvent('domready', function()
	{

	});
	parent.gIframeFunc = onSubmit;
</script>
<script type="text/javascript">
(function($){
   $(document).ready(function () {
	    $("#accordion").accordion({
	        header: "h3",
	        autoHeight: false,
	        changestart: function(event, ui)
	        {
	        	$('.jsn-accordion-radio',ui.oldHeader).removeAttr('checked');
				$('.jsn-accordion-radio',ui.newHeader).attr('checked','checked');
	        },
	        create: function(event, ui) {
				$('.ui-state-active', this).removeClass('ui-state-default');
		    }
	    });
	});
})(jQuery);
</script>

<div id="jsn-showlist-install-sources-verify" class="ui-dialog-contentpane">
<div class="jsn-bootstrap">
	<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
	<div id="accordion">
	<?php if ($exsitedAvailableProfile) { ?>
		<h3><a href="#"><input id="profile_type_available" class="jsn-accordion-radio" type="radio" value="available" checked="checked" name="profile_type">
		<?php echo JText::_('SHOWLIST_PROFILE_SELECT_AVAILABLE_PROFILE')?></a></h3>
		<div id="jsn-showlist-available-profile">
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('SHOWLIST_PROFILE_SELECT_AVAILABLE_PROFILE')?></label>
				<div class="controls">
					<?php echo JHTML::_('select.genericList', $availableProfile, 'external_source_id', 'class="jsn-master jsn-input-xxlarge-fluid"', 'value', 'text');?>
				</div>
			</div>
		</div>
	<?php } ?>
		<h3><a href="#"><input id="profile_type_new" class="jsn-accordion-radio" type="radio" value="available" name="profile_type" <?php echo (!$exsitedAvailableProfile)?' style="display: none;" checked="checked"':'';?>>
		<?php echo JText::_('SHOWLIST_PROFILE_CREATE_NEW_PROFILE')?></a></h3>
		<div id="jsn-showlist-profile-params">
			<?php
				$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.'source'.$sourceIdentify.DS.'views'.DS.'showlist'.DS.'tmpl');
				echo $this->loadTemplate($sourceIdentify);
			?>
			<div class="content-center">
				<span class="jsn-source-icon-loading" id="jsn-create-source"></span>
			</div>
		</div>
	</div>
	<input type="hidden" id="task" name="task" value="" />
	<input type="hidden" name="source_identify" value="<?php echo $sourceIdentify; ?>" />
	<input type="hidden" name="image_source_type" value="external" />
	<input type="hidden" name="showlist_id" value="<?php echo $showlistID; ?>"/>
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showlist" />
	<?php echo JHTML::_('form.token'); ?>
	</form>
</div>
</div>