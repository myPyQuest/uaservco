<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 14191 2012-07-19 12:26:54Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.html.pane');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_( 'MAINTENANCE_CONFIGURATION_AND_MAINTENANCE' ), 'maintenance' );
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$type  			= JRequest::getWord('type','backup');
$sourceType  	= JRequest::getString('source_type');
$themeName 		= JRequest::getString('theme_name');
?>
<script language="javascript">
window.addEvent('domready', function(){
	JSNISImageShow.Maintenance();
});
</script>
<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	if(empty($sourceType) && empty($themeName))
	{
		echo $objJSNMsg->displayMessage('CONFIGURATION_AND_MAINTENANCE');
?>
<div id="jsn-imageshow-configuration-maintenance" class="jsn-page-configuration">
	<div class="jsn-bootstrap jsn-bgpattern pattern-sidebar">
		<div>
			<div class="jsn-page-nav">
				<ul class="nav nav-list">
					<li class="nav-header"><?php echo JText::_('MAINTENANCE_CONFIGURATION'); ?></li>
					<li<?php echo ($type=='configs')?' class="active"':''; ?>><a id="linkconfigs" href="#"><i class="jsn-icon32 icon-cog"></i><?php echo JText::_('MAINTENANCE_GLOBAL_PARAMETERS'); ?></a></li>
					<li<?php echo ($type=='msgs')?' class="active"':''; ?>><a id="linkmsgs" href="#"><i class="jsn-icon32 icon-comment"></i><?php echo JText::_('MAINTENANCE_MESSAGES'); ?></a></li>
					<li<?php echo ($type=='inslangs')?' class="active"':''; ?>><a id="linklangs" href="#"><i class="jsn-icon32 icon-globe"></i><?php echo JText::_('MAINTENANCE_LANGUAGES'); ?></a></li>
				</ul>
				<ul class="nav nav-list">
					<li class="nav-header"><span><?php echo JText::_('MAINTENANCE_MAINTENANCE'); ?></span></li>
					<li<?php echo ($type=='data')?' class="active"':''; ?>><a id="linkdata" href="#"><i class="jsn-icon32 icon-database"></i><?php echo JText::_('MAINTENANCE_DATA'); ?></a></li>
					<li<?php echo ($type=='profiles')?' class="active"':''; ?>><a id="linkprofile" href="#"><i class="jsn-icon32 icon-folder"></i><?php echo JText::_('MAINTENANCE_IMAGE_SOURCE_PROFILES'); ?></a></li>
					<li<?php echo ($type=='themes')?' class="active"':''; ?>><a id="linkthemes" href="#"><i class="jsn-icon32 icon-picture"></i><?php echo JText::_('MAINTENANCE_THEMES_MANAGER'); ?></a></li>
				</ul>
			</div>
			<div class="jsn-page-content"><div>
<?php
	}
	switch($type)
	{
		case 'inslangs':
			echo $this->loadTemplate('inslangs');
		break;
		case 'msgs':
			echo $this->loadTemplate('messages');
		break;
		case 'profiles':
			echo $this->loadTemplate('profiles');
		break;
		case 'editprofile':
			$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.'source'.$sourceType.DS.'views'.DS.'maintenance'.DS.'tmpl');
			echo $this->loadTemplate('edit_source_profile');
		break;
		case 'configs':
			echo $this->loadTemplate('configs');
		break;
		case 'sampledata':
			echo $this->loadTemplate('sampledata');
		break;
		case 'themes':
			echo $this->loadTemplate('themes');
		break;
		case 'themeparameters':
			$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.$themeName.DS.'views'.DS.'maintenance'.DS.'tmpl');
			echo $this->loadTemplate('theme_config');
		break;
		case 'profileparameters':
			$this->addTemplatePath(JPATH_PLUGINS.DS.'jsnimageshow'.DS.$sourceType.DS.'views'.DS.'maintenance'.DS.'tmpl');
			echo  $this->loadTemplate('source_config');
		break;
		case 'data':
			echo $this->loadTemplate('data');
		break;
		default:
		break;
	}
	if(empty($sourceType) && empty($themeName))
	{
?>
			</div></div>
			<div class="clearbreak"></div>
		</div>
	</div>
</div>
<?php
	}
?>
