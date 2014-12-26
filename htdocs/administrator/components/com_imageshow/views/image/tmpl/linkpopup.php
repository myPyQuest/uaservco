<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: linkpopup.php 13756 2012-07-04 03:12:38Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$tab = JRequest::getVar('tab');
?>
<script type="text/javascript">
(function($){
	$(document).ready(function () {
		$("#tabs-link").tabs({
			cache: false,
			ajaxOptions: {
				error: function( xhr, status, index, anchor ) {
					$(anchor.hash).html();
				},
				beforeSend: function (e, ui){
					$('#loading-wrapper', window.parent.document).show();
					$('.ui-tabs-panel').html('');
				},
				success: function (){
					$('#loading-wrapper', window.parent.document).hide();
				}
			}
		});
		<?php if ($tab == 'menu') { ?>
	    $('#tabs-link').tabs('select', 1);
	    <?php } else {?>
	    $('#tabs-link').tabs('select', 0);
	    <?php } ?>
	})
})(jQuery);
</script>
<div id="tabs-link" class="jsn-tabs">
	<ul>
		<li><a href="index.php?option=com_imageshow&controller=articles&tmpl=component&function=jsnGetArticle"><?php echo JText::_('SHOWLIST_POPUP_IMAGE_ARTICLE');?></a></li>
		<li><a href="index.php?option=com_imageshow&controller=menus&tmpl=component&function=jsnGetMenuItems"><?php echo JText::_('SHOWLIST_POPUP_IMAGE_MENU');?></a></li>
	</ul>
</div>