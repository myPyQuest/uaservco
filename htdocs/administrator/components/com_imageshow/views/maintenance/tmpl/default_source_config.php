<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_source_config.php 14194 2012-07-20 02:36:54Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
?>
<script>
	window.addEvent('domready', function()
	{
		JSNISImageShow.profileShowHintText();
	});
</script>
<div class="jsn-bootstrap">
	<form action="index.php?option=com_imageshow&controller=maintenance&type=profileparameters&tmpl=component" method="POST" name="adminForm" id="frm_profile_param">
		<?php echo $this->loadTemplate('profile_parameters'); ?>
	</form>
</div>