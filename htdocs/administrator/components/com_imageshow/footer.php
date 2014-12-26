<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: footer.php 14335 2012-07-24 09:28:47Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $mainframe, $option, $componentVersion;
$document = JFactory::getDocument();
$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js?v='.$componentVersion);
$objJSNUtils  		= JSNISFactory::getObj('classes.jsn_is_utils');
$shortEdition 		= $objJSNUtils->getEdition();
$componentInfo 		= $objJSNUtils->getComponentInfo();
$componentData 		= json_decode($componentInfo->manifest_cache);
$componentVersion 	= trim($componentData->version);
$hash 				= JApplication::getHash(@$_SERVER['HTTP_USER_AGENT']);
$cookieName			= 'jsn-is-check-version-status-'.$hash;
if(!isset($_COOKIE[$cookieName]) && !$_COOKIE[$cookieName])
{
	$document->addScriptDeclaration("
		window.addEvent('domready', function(){
			var check = false;
			var actionVersionUrl = 'index.php';
			var resultVersionMsg = new Element('span');
			var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
				if(jsonObj.connection) {
					if(jsonObj.update)
					{
						$('jsn-global-check-version-result').style.display = 'inline';

						resultVersionMsg.set('class','jsn-outdated-version');
						resultVersionMsg.set('html','".JText::sprintf('FOOTER_SEE_UPDATE_INSTRUCTIONS', @$return, array('jsSafe'=>true, 'interpretBackSlashes'=>true, 'script'=>false))."');
					}
				}
				resultVersionMsg.inject($('jsn-global-check-version-result'));
			}}).get({'option': 'com_imageshow',
						'controller': 'ajax',
						'task': 'checkUpdateAllElements'
					});
		});
	");
	@setcookie($cookieName, true, time() + 60*60*24, '/' );
}
?>
<div id="jsn-footer" class="jsn-page-footer jsn-bootstrap">
	<ul class="jsn-footer-menu">
		<li class="first">
			<a target="_blank" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip">
				<?php echo JText::_('FOOTER_DOCUMENTATION');?>
			</a>
		</li>
		<li>
			<a target="_blank" href="http://www.joomlashine.com/contact-us/get-support.html">
				<?php echo JText::_('FOOTER_SUPPORT')?>
			</a>
		</li>
		<li>
			<a target="_blank" href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html">
				<?php echo JText::_('FOOTER_VOTE_ON_JED')?>
			</a>
		</li>
		<li class="jsn-iconbar">
			<strong><?php echo JText::_('FOOTER_FOLLOW_US'); ?></strong>
			<a href="http://www.facebook.com/joomlashine" target="_blank" class="jsn-icon16 icon-social icon-facebook" title="<?php echo htmlspecialchars(JText::_('FOOTER_FIND_US_ON_FACEBOOK')); ?>"></a><a href="http://twitter.com/joomlashine" target="_blank" class="jsn-icon16 icon-social icon-twitter" title="<?php echo htmlspecialchars(JText::_('FOOTER_FOLLOW_US_ON_TWITTER')); ?>"></a>
		</li>
	</ul>
	<ul class="jsn-footer-menu">
		<li class="first">
			<?php
			echo '<a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow.html" target="_blank">JSN '. @$componentData->name . ' ' .strtoupper($shortEdition). ' v'.@$componentData->version.'</a> ';
			echo JText::_('FOOTER_BY') . ' <a target="_blank" href="'.((stripos(@$componentData->authorUrl, 'http') !== false) ? @$componentData->authorUrl : 'http://'.@$componentData->authorUrl).'">' . @$componentData->author. '</a>';
			?>
			<?php if($shortEdition == 'free' || $shortEdition == 'pro standard'): ?>
				<a class="label label-important jsn-link-upgrade" href="index.php?option=com_imageshow&controller=upgrader"><?php echo ($shortEdition == 'free')?JText::_('FOOTER_UPGRADE_TO_PRO'):JText::_('FOOTER_UPGRADE_TO_PRO_UNLIMITED');?></a>
			<?php endif;?>
		</li>
		<li style="display: none;" id="jsn-global-check-version-result"></li>
	</ul>
</div>