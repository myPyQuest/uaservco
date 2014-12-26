<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 14439 2012-07-27 03:32:43Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JToolBarHelper::title(JText::_('JSN_IMAGESHOW').': '.JText::_('UPDATER_UPDATER'));
$this->objJSNUtil->callJSNButtonMenu();
$step = JRequest::getCmd('step', '1');
?>
<div id="jsn-imageshow-update" class="jsn-page-update">
	<div class="jsn-page-content jsn-rounded-large jsn-box-shadow-large jsn-bootstrap">
		<span id="jsn-updater-cancel"><a id="jsn-updater-link-cancel" class="jsn-link-action" href="index.php?option=com_imageshow"><?php echo JText::_('UPDATER_BUTTON_CANCEL'); ?></a></span>
		<h1><?php echo JText::sprintf('UPDATER_UPDATE_HEADING', strtoupper($this->edition)); ?></h1>
		<?php
			switch ($step)
			{
				case '2':
					echo $this->loadTemplate('step2');
					break;
				case '3':
					echo $this->loadTemplate('step3');
					break;
				case '1':
				default:
					echo $this->loadTemplate('step1');
					break;
			}
		?>
	</div>
</div>

