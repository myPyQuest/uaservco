<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_profiles.php 14191 2012-07-19 12:26:54Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' ); ?>
<script type="text/javascript">
	JSNISImageShow.removeProfile = function($profile)
	{
		var r = confirm('<?php echo JText::_('MAINTENANCE_SOURCE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE_SOURCE_PROFILE', true);?>');

		if (r == true)
		{
			var ajax = new Request({
				url: 'index.php?option=com_imageshow&controller=maintenance&task=removeProfile&rand='+ Math.random(),
				method: 'post',
				data: $profile,
				onComplete: function(response)
				{
					window.top.location.reload(true);
				}
			});
			ajax.send();
		}
	}

	JSNISImageShow.uninstallImageSource = function($source)
	{
		var r = confirm('<?php echo JText::_('MAINTENANCE_SOURCE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE_SOURCE', true);?>');

		if (r == true)
		{
			var ajax = new Request({
				url: 'index.php?option=com_imageshow&controller=maintenance&task=uninstallImageSource&rand='+ Math.random(),
				method: 'post',
				data: $source,
				onComplete: function(response)
				{
					window.top.location.reload(true);
				}
			});
			ajax.send();
		}
	}
</script>
<div id="jsn-image-source-profiles" class="jsn-page-list">
	<h2 class="jsn-section-header">
		<?php echo JText::_('MAINTENANCE_IMAGE_SOURCE_PROFILES'); ?>
	</h2>
	<form action="index.php?option=com_imageshow&controller=maintenance&type=profiles" method="POST" name="adminForm" id="frm_profile" class="form-inline">
		<table class="table table-bordered" border="0">
			<thead>
				<tr>
					<th width="20" class="center">#</th>
					<th class="title" nowrap="nowrap"><?php echo JText::_('TITLE'); ?> </th>
					<th width="80" nowrap="nowrap" class="center"><?php echo JText::_('MAINTENANCE_SOURCE_VERSION'); ?> </th>
					<th width="80" nowrap="nowrap" class="center"><?php echo JText::_('ACTION'); ?> </th>
				</tr>
			</thead>
			<tbody>
			<?php
			$token = JUtility::getToken();
			$db = JFactory::getDBO();

			for ($i=0, $n = count($this->listSources); $i < $n; $i++)
			{
				$row = $this->listSources[$i];
				if ($row->type != 'folder' )
				{
					$manifestPlugin = json_decode($row->pluginInfo->manifest_cache);
			?>
					<tr>
						<td class="center"><?php echo $i + 1; ?></td>
						<td>
							<?php echo $this->escape($row->title); ?>
							<?php if ($row->type == ('external') && count($row->profiles) > 0) { ?>
								<span class="jsn-image-source-seperator">|</span>
								<a href="#" class="jsn-link-action" onclick="JSNISImageShow.toggleListProfile(this, '<?php echo 'jsn-image-source-profile-item-' . $row->identified_name; ?>' ); return false;">
									<span class="jsn-image-source-open-profile"><?php echo JText::_('MAINTENANCE_SOURCE_SEE_PROFILES')?></span>
									<span class="jsn-image-source-close-profile"><?php echo JText::_('MAINTENANCE_SOURCE_CLOSE')?></span>
								</a>
							<?php } ?>
						</td>
						<td class="center"><?php  echo $manifestPlugin->version; ?></td>
						<td class="actionprofile center">
							<?php if(JFile::exists(JPATH_PLUGINS.DS.'jsnimageshow'.DS.$row->pluginInfo->element.DS.'views'.DS.'maintenance'.DS.'tmpl'.DS.'default_profile_parameters.php')) { ?>
							<a rel='{"size": {"x": 400, "y": 500}}' href="index.php?option=com_imageshow&controller=maintenance&type=profileparameters&source_type=<?php echo $row->pluginInfo->element; ?>&tmpl=component" class="jsn-icon16 icon-pencil jsn-is-form-modal" name="<?php echo JText::_('MAINTENANCE_SOURCE_PARAMETER_SETTINGS'); ?>" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_SOURCE_EDIT_SETTINGS'))?>"></a>
							&nbsp;
							<?php } ?>
							<?php
								$sourceDelete = new stdClass();
								$sourceDelete->plugin_source_id = $row->pluginInfo->extension_id;
								$sourceDelete->{$token} = 1;
							?>
							<a onclick='JSNISImageShow.uninstallImageSource(<?php echo json_encode($sourceDelete); ?>); return false;' href="#" class="jsn-icon16 icon-trash" title="<?php echo htmlspecialchars(JText::_('DELETE'))?>"></a>
						</td>
					</tr>
			<?php
					if ($row->profiles)
					{
						for ($i2 = 0, $n2 = count( $row->profiles ); $i2 < $n2; $i2++)
						{
							$profile = $row->profiles[$i2];
							$profile->{$token} = 1;
							?>

							<tr class="jsn-image-source-profile-item-<?php echo $row->identified_name;?> jsn-image-source-profile-close">
								<td></td>
								<td class="jsn-image-source-profile-title">
									<?php echo $this->escape($profile->external_source_profile_title); ?>
									<span class="jsn-image-source-seperator">|</span>
									<a class="jsn-is-view-modal jsn-link-action" rel='{"size": {"x": 500, "y": 300}}' href="index.php?option=com_imageshow&controller=showlist&task=elements&tmpl=component&limit=0&external_source_id=<?php echo $profile->external_source_id; ?>&image_source_name=<?php echo $profile->image_source_name; ?>" name="<?php echo JText::_('SHOWLIST_IMAGE_SOURCE_PROFILE_SHOWLISTS'); ?>">
										<?php echo JText::_('MAINTENANCE_SOURCE_SEE_SHOWLISTS'); ?>
									</a>
								</td>
								<td align="center"></td>
								<td align="center" class="center actionprofile" nowrap="nowrap">
									<a name="<?php echo JText::_('MAINTENANCE_SOURCE_PROFILE_SETTINGS'); ?>" rel='{"size": {"x": 400, "y": 500}}' href="index.php?option=com_imageshow&controller=maintenance&type=editprofile&source_type=<?php echo $profile->image_source_name; ?>&tmpl=component&external_source_id=<?php echo $profile->external_source_id; ?>&count_showlist=<?php echo $profile->totalshowlist; ?>" class="jsn-icon16 icon-pencil jsn-is-form-modal" title="<?php echo htmlspecialchars(JText::_('EDIT'))?>"></a>&nbsp
									<a onclick='JSNISImageShow.removeProfile(<?php echo json_encode($profile);?>);' href="#" class="jsn-icon16 icon-trash" title="<?php echo htmlspecialchars(JText::_('DELETE'));?>"></a>
								</td>
							</tr>
			<?php
						}
					}
				}
			}
			?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4" class="center"><?php echo $this->pagination->getListFooter(); ?></td>
				</tr>
			</tfoot>
		</table>
		<input type="hidden" name="option" value="com_imageshow" />
		<input type="hidden" name="controller" value="maintenance" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="task" value="" id="task" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
