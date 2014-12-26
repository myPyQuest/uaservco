<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_theme_config.php 14211 2012-07-20 07:50:15Z haonv $
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
<div id="jsn-theme-details" class="jsn-bootstrap">
	<form action="index.php?option=com_imageshow&controller=maintenance&type=themeparameters&theme_name=themeclassic&tmpl=component" method="POST" name="adminForm" id="frm_theme_param">
		<div id="jsn-showcase-theme-params">
			<?php echo $this->loadTemplate('theme_parameters'); ?>
		</div>
	</form>
</div>