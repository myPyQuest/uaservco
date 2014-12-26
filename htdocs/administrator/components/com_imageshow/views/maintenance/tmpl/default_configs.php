<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_configs.php 14353 2012-07-25 02:45:24Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>
<script>
(function($){
	$(document).ready(function(){
		$( "#jsn_tabs" ).tabs();
	})
})(jQuery);
</script>
<div id="jsn-configuration">
	<h2 class="jsn-section-header">
		<?php echo JText::_('MAINTENANCE_GLOBAL_PARAMETERS'); ?>
	</h2>
	<form action="index.php?option=com_imageshow&controller=maintenance&type=msgs" method="POST" name="adminForm" id="frm_param" class="form-horizontal">
		<div id="jsn_tabs">
			<ul>
				<li><a href="#jsn_config_general"><?php echo JText::_('GENERAL'); ?></a></li>
			</ul>
			<div id="jsn_config_general">
				<div class="control-group">
					<label class="control-label hasTip" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_SHOW_QUICK_ICONS'));?>::<?php echo htmlspecialchars(JText::_('MAINTENANCE_SHOW_QUICK_ICONS_DES')); ?>"><?php echo JText::_('MAINTENANCE_SHOW_QUICK_ICONS');?></label>
					<div class="controls">
						<?php echo $this->lists['showQuickIcons']; ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label hasTip" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_ENABLE_UPDATE_CHECKING'));?>::<?php echo htmlspecialchars(JText::_('MAINTENANCE_ENABLE_UPDATE_CHECKING_DES')); ?>"><?php echo JText::_('MAINTENANCE_ENABLE_UPDATE_CHECKING');?></label>
					<div class="controls">
						<?php echo $this->lists['enableUpdateChecking']; ?>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label hasTip" title="<?php echo htmlspecialchars(JText::_('MAINTENANCE_NUMBER_OF_IMAGES_ON_LOADING'));?>::<?php echo htmlspecialchars(JText::_('MAINTENANCE_NUMBER_OF_IMAGES_ON_LOADING_DES')); ?>"><?php echo JText::_('MAINTENANCE_NUMBER_OF_IMAGES_ON_LOADING');?></label>
					<div class="controls">
						<input type="text" value="<?php echo (is_null(@$this->parameters->number_of_images_on_loading) || @$this->parameters->number_of_images_on_loading =='')?'30':$this->parameters->number_of_images_on_loading;?>" id="number_of_images_on_loading" name="number_of_images_on_loading" class="jsn-master input-mini">
					</div>
				</div>
				<input type="hidden" name="option" value="com_imageshow" />
				<input type="hidden" name="controller" value="maintenance" />
				<input type="hidden" name="task" value="saveparam" id="task" />
				<?php echo JHTML::_('form.token'); ?>
			</div>
		</div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
		</div>
	</form>
</div>