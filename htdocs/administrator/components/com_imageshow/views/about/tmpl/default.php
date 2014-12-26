<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 14545 2012-07-28 10:33:24Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.modal','a.jsn-modal');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'ABOUT_ABOUT' ), 'about' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$edition 	= @$this->infoXmlDetail['edition'];
$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
$explodeEdition =  explode(' ', $edition);
echo $objJSNMsg->displayMessage('ABOUT');
?>
<div id="jsn-imageshow-about" class="jsn-page-about">
	<div class="jsn-bootstrap">
		<div class="jsn-product-about">
			<div class="jsn-product-intro">
				<div class="jsn-product-thumbnail">
					<?php echo JHTML::_('image.administrator', 'components/com_imageshow/assets/images/product-thumbnail.png',''); ?>
				</div>
				<div class="jsn-product-details">
					<h2 class="jsn-section-header">
						<?php echo JText::_('JSN') .' '. @$this->componentData->name.'&nbsp;'.strtoupper($this->edition); ?>
					</h2>
					<?php if (strtolower($this->edition) != 'pro unlimited') { ?>
					<p class="alert alert-info">
						<span class="label label-important">PRO</span>&nbsp;
					<?php
						if (strtolower($this->edition) == 'pro standard') {
							echo JText::_('ABOUT_UPGRADE_TO_UNLIMITED');
						}
						elseif (strtolower($this->edition) == 'free') {
							echo JText::_('ABOUT_UPGRADE_TO_PRO');
						}
					?>
						<a href="index.php?option=com_imageshow&controller=upgrader" class="jsn-link-action"><?php echo JText::_('READ_MORE'); ?></a>
					</p>
					<?php } ?>
					<dl>
						<dt><?php echo JText::_('ABOUT_VERSION'); ?>:</dt>
						<dd>
							<strong class="jsn-current-version"><?php echo @$this->componentData->version; ?></strong>&nbsp;-&nbsp;
							<span id="jsn-check-version-result"></span>
						</dd>
						<dt><?php echo JText::_('ABOUT_AUTHOR'); ?>:</dt>
						<dd>
							<a href="http://<?php echo @$this->componentData->authorUrl; ?>"><?php echo @$this->componentData->author; ?></a>
						</dd>
						<dt><?php echo JText::_('ABOUT_COPYRIGHT'); ?>:</dt>
						<dd><?php echo @$this->componentData->copyright; ?></dd>
					</dl>
				</div>
				<div class="clearbreak"></div>
			</div>
			<div class="jsn-product-cta jsn-bgpattern pattern-sidebar">
				<div class="pull-left">
					<ul class="jsn-list-horizontal">
						<li>
							<a href="http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html" target="_blank" class="btn">
								<i class="icon-comment"></i>&nbsp;<?php echo JText::_('ABOUT_VOTE_FOR_THIS_PRODUCT_ON_JED'); ?>
							</a>
						</li>
						<li><a class="btn jsn-is-view-modal" href="http://www.joomlashine.com/free-joomla-templates-promo.html" rel='{"size": {"x": 640, "y": 575}}'><i class="icon-briefcase"></i>&nbsp;<?php echo JText::_('ABOUT_SEE_OTHER_PRODUCTS');?></a></li>
					</ul>
				</div>
				<div class="pull-right">
					<ul class="jsn-list-horizontal">
						<li>
							<a class="jsn-icon24 icon-social icon-facebook" href="http://www.facebook.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('ABOUT_CONNECT_WITH_US_ON_FACEBOOK')); ?>" target="_blank"></a>
						</li>
						<li>
							<a class="jsn-icon24 icon-social icon-twitter" href="http://www.twitter.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('ABOUT_FOLLOW_US_ON_TWITTER')); ?>" target="_blank">
							</a>
						</li>
						<li>
							<a class="jsn-icon24 icon-social icon-youtube" href="http://www.youtube.com/joomlashine" title="<?php echo htmlspecialchars(JText::_('ABOUT_WATCH_US_ON_YOUTUBE')); ?>" target="_blank">
							</a>
						</li>
					</ul>
				</div>
				<div class="clearbreak"></div>
			</div>
		</div>
		<div class="jsn-product-support">
			<div>
				<h3 class="jsn-section-header"><?php echo JText::_('ABOUT_HELP_AND_SUPPORT') ?></h3>
				<p><?php echo JText::_('ABOUT_NEED_HELP') ?></p>
				<ul>
					<li><?php echo JText::sprintf('ABOUT_READ_DOCUMENTATION', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-docs.zip') ?></li>
					<li><?php echo JText::sprintf('ABOUT_ASK_SUPPORT', 'http://www.joomlashine.com/forum.html') ?></li>
					<li><?php echo JText::sprintf('ABOUT_SUBMIT_TICKET', 'http://www.joomlashine.com/dedicated-support.html') ?></li>
				</ul>
				<P><?php echo JText::_('ABOUT_ONLY_FOR_PRO_UNLIMITED') ?></P>
			</div>
			<div>
				<h3 class="jsn-section-header"><?php echo JText::_('ABOUT_FEEDBACK') ?></h3>
				<p><?php echo JText::_('ABOUT_WE_HEAR_YOU') ?></p>
				<ul>
					<li><?php echo JText::sprintf('ABOUT_SUBMIT_BUG', 'http://www.joomlashine.com/contact-us/product-feedback.html') ?></li>
					<li><?php echo JText::sprintf('ABOUT_GIVE_TESTIMONIAL', 'http://www.joomlashine.com/contact-us/product-feedback.html') ?></li>
					<li><?php echo JText::sprintf('ABOUT_REVIEW_PRODUCT_ON_JED', 'http://www.joomlashine.com/joomla-extensions/jsn-imageshow-on-jed.html') ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
