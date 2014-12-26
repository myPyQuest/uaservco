<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: delete_source.php 14287 2012-07-23 11:14:16Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>

<form action="index.php?option=com_imageshow&controller=showlist&task=element&tmpl=component" method="post" name="adminForm" id="adminForm">
<div id="jsn-image-source-profile-details">
	<h3 class="jsn-section-header"><?php echo JText::_('MAINTENANCE_SOURCE_IMAGE_SOURCE_DELETION'); ?></h3>
	<p><?php echo JText::_('MAINTENANCE_SOURCE_ARE_YOU_SURE_YOU_WANT_TO_DELETE_THIS_IMAGE_SOURCE'); ?></p>
	<div class="button">
        	<button type="button" value="<?php echo JText::_('DELETE'); ?>" onclick="JSNISImageShow.deleteSource();" class="link-button"><?php echo JText::_('DELETE'); ?></button>
            <button type="button" value="<?php echo JText::_('CANCEL');?>" onclick="window.top.setTimeout('SqueezeBox.close()', 200);" class="link-button"><?php echo JText::_('CANCEL');?></button>
	</div>
</div>
<input type="hidden" name="plugin_source_id" value="<?php echo JRequest::getInt('plugin_source_id', 0); ?>" />
<input type="hidden" name="option" value="com_imageshow" />
<input type="hidden" name="task" value="uninstallImageSource" />
<input type="hidden" name="controller" value="maintenance" />
<?php echo JHTML::_( 'form.token' ); ?>
</form>