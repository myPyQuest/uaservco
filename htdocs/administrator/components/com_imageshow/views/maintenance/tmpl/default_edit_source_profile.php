<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_edit_source_profile.php 13794 2012-07-05 08:34:06Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
$externalSourceID = JRequest::getInt('external_source_id');
$sourceType = JRequest::getString('source_type');
?>
<script>
	window.addEvent('domready', function()
	{
		JSNISImageShow.profileShowHintText();
	});
</script>
<div id="jsn-image-source-profile-details">
<div class="jsn-bootstrap">
	<form name='adminForm' id='adminForm' action="index.php" method="post" onsubmit="return false;">
		<?php echo $this->loadTemplate('profile_'.$sourceType);?>
		<div class="content-center">
			<!--<button onclick="return onSubmit();" id="submit-new-profile-form" class="btn"><?php echo JText::_('SAVE'); ?></button>
			--><span class="jsn-source-icon-loading" id="jsn-create-source"></span>
		</div>
	</form>
</div>
</div>
