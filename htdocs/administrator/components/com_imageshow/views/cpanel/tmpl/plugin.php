<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: plugin.php 14027 2012-07-14 11:25:49Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$showlistID = JRequest::getInt('showlist_id');
$showcaseID = JRequest::getInt('showcase_id');
$pluginInfo = $this->pluginContentInfo;
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 	= $objJSNUtils->overrideURL();
$url 		= $baseURL.'components/com_imageshow/assets/swf';
?>
<script type="text/javascript">
	var clipboard = null;

	window.addEvent('domready', function()
	{
		ZeroClipboard.moviePath = "<?php echo $url; ?>/ZeroClipboard.swf";
		clipboard 		= new ZeroClipboard.Client();
		syntaxPlugin 	= $('syntax-plugin');

		clipboard.addEventListener('complete', function (client, text)
		{
			if (syntaxPlugin.value != '')
			{
				var checkIcon = $$('.jsn-clipboard-checkicon')[0];
				checkIcon.addClass('jsn-clipboard-coppied');
				(function() { checkIcon.removeClass('jsn-clipboard-coppied');  } ).delay(3000);
			}
		});

		clipboard.glue('jsn-clipboard-button', 'jsn-clipboard-container');
		clipboard.setText(syntaxPlugin.value);

		syntaxPlugin.addEvent('change', function(){
			clipboard.setText(syntaxPlugin.value);
		});
	});
</script>
<div class="jsn-plugin-details">
<div class="jsn-bootstrap">
	<div class="form-search">
		<?php
		echo JText::_('CPANEL_PLEASE_INSERT_FOLLOWING_TEXT_TO_YOUR_ARTICLE_AT_THE_POSITION_WHERE_YOU_WANT_TO_SHOW_GALLERY');
		?>
		<div id="jsn-clipboard">
			<span class="jsn-clipboard-input">
				<input type="text" id="syntax-plugin" class="input-xlarge" name="plugin" value="{imageshow sl=<?php echo $showlistID; ?> sc=<?php echo $showcaseID; ?> /}" />
				<span class="jsn-clipboard-checkicon icon-ok"></span>
			</span>
			<span id="jsn-clipboard-container">
				<button id="jsn-clipboard-button" class="btn"><?php echo JText::_('CPANEL_COPY_TO_CLIPBOARD')?></button>
			</span>
		</div>
	</div>
	<?php
	echo JText::_('CPANEL_MORE_DETAILS_ABOUT_PLUGIN_SYNTAX');
	?>
</div>
</div>