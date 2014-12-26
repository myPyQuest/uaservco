<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_step1.php 14450 2012-07-27 05:02:11Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$objJSNUtil			= JSNISFactory::getObj('classes.jsn_is_utils');
$writable			= array();
$needUpdateTheme  	= false;
$needUpdateSource 	= false;
$needUpdateCore 	= false;
?>
<p>
<?php echo JText::_('UPDATER_BASIC_INFO'); ?>
</p>
<div class="alert alert-info">
	<p><span class="label label-info"><?php echo JText::_('IMPORTANT_INFO'); ?></span></p>
	<?php echo JText::_('UPDATER_IMPORTANT_INFO'); ?>
</div>
<?php
$elementID 		= JRequest::getInt('element_id');
$type 			= JRequest::getVar('type');
$objVersion		= new JVersion();
$authentication =  false;
$return	= JRequest::getVar('return', '', 'get');
if ($this->imageshowCore->commercial && $this->imageshowCore->needUpdate ) {
	$authentication = true;
}

$dataUpdate = array();
$core 		= array();
$themes 	= array();
$sources 	= array();
$html = '';
foreach ($this->themes as $key => $theme)
{
	if ($theme->needUpdate)
	{
		$needUpdateTheme = true;
		if ($theme->authentication) $authentication = true;
		$themeInfoUpdate 					= new stdClass();
		$themeInfoUpdate->elementID 		= 'jsn-update-element-theme-'.$key;
		$themeInfoUpdate->identify_name 	= $theme->identified_name;
		$themeInfoUpdate->edition 			= '';
		$themeInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$themes[] 							= $themeInfoUpdate;
		$html .= '<li id="jsn-update-element-theme-'.$key.'">'.JText::_('UPDATER_THEME').' "'. $theme->name .'" '.JText::sprintf('UPDATER_FROM_VERSION_TO_VERSION', $theme->oldVersion, $theme->newVersion).'</li>';
	}
}

foreach ($this->sources as $key => $source)
{
	if ($source->needUpdate)
	{
		$needUpdateSource = true;
		if ($source->authentication) $authentication = true;
		$sourceInfoUpdate 					= new stdClass();
		$sourceInfoUpdate->elementID 		= 'jsn-update-element-source-'.$key;
		$sourceInfoUpdate->identify_name 	= $source->identified_name;
		$sourceInfoUpdate->edition 			= '';
		$sourceInfoUpdate->joomla_version 	= $objVersion->RELEASE;
		$sources[] 							= $sourceInfoUpdate;
		$html .= '<li id="jsn-update-element-source-'.$key.'">'.JText::_('UPDATER_SOURCE').' "'. $source->name.'" '.JText::sprintf('UPDATER_FROM_VERSION_TO_VERSION', $source->oldVersion, $source->newVersion).'</li>';
	}
}
?>
<?php if (count($sources) || count($themes) || $this->imageshowCore->needUpdate){ ?>
	<?php
		if ($this->imageshowCore->needUpdate)
		{
			$writable = array_merge($writable, $objJSNUtil->checkFolderUnwritableOnUpdateAndUpgrade('core'));
		}
		if ($needUpdateTheme)
		{
			$writable = array_merge($writable, $objJSNUtil->checkFolderUnwritableOnUpdateAndUpgrade('theme'));
		}
		if ($needUpdateSource)
		{
			$writable = array_merge($writable, $objJSNUtil->checkFolderUnwritableOnUpdateAndUpgrade('source'));
		}
		$writable = array_unique($writable);
	?>
	<?php if (!count($writable)) {?>
	<p><?php echo JText::_('UPDATER_UPDATE_DESCRIPTION'); ?></p>
	<ul>
		<?php if ($this->imageshowCore->needUpdate): ?>
			<?php
				$needUpdateCore					= true;
				$coreInfoUpdate 				= new stdClass();
				$coreInfoUpdate->elementID 		= 'jsn-update-element-core';
				$coreInfoUpdate->identify_name 	= $this->imageshowCore->id;
				$coreInfoUpdate->edition 		= $this->imageshowCore->edition;
				$coreInfoUpdate->joomla_version = $objVersion->RELEASE;
				$core[] 						= $coreInfoUpdate;
			?>
			<li id="jsn-update-element-core"><?php echo JText::_('UPDATER_JSN_IMAGESHOW_CORE');?> <?php echo JText::sprintf('UPDATER_FROM_VERSION_TO_VERSION', $this->imageshowCore->version, $this->imageshowCore->newVersion); ?></li>
		<?php endif;?>
		<?php
			echo $html;
		?>
	</ul>
	<form method="POST" action="index.php?option=com_imageshow&controller=updater&step=<?php echo ($authentication)?'2':'3';?>&return=<?php echo $return;?>" id="frm_updateinfo" name="frm_updateinfo" class="upgrader-from" autocomplete="off">
		<div class="form-actions">
			<p>
				<a class="btn btn-primary" href="javascript: void(0);" onclick="document.frm_updateinfo.submit();" id="jsn-proceed-button">
					<?php echo JText::_('UPDATER_BUTTON_UPDATE'); ?>
				</a>
			</p>
		</div>
		<input type="hidden" name="task" value="update_proceeded" />
	</form>
	<?php } else { ?>
	<p><strong><?php echo JText::_('UPDATER_THE_FOLLOWING_FOLDERS_MUST_WRITABLE_PERMISSION'); ?>:</strong></p>
	<?php
		echo '<ul>';
		foreach ($writable as $item)
		{
			echo '<li>'.$item.'</li>';
		}
		echo '</ul>';
	?>
	<p><strong><?php echo JText::_('UPDATER_PLEASE_SET_WRIABLE_PERMISSION'); ?></strong></p>
	<div class="form-actions">
		<a class="btn btn-primary" href="javascript: void(0);" onclick="window.location.reload(true);"><?php echo JText::_('UPDATER_TRY_AGAIN'); ?></a>
	</div>
	<?php } ?>
<?php } else if (!count($sources) && !count($themes) && !$this->imageshowCore->needUpdate && $this->canAutoUpdate) { ?>
	<p><strong><?php echo JText::_('UPDATER_UPDATE_NO_UPDATE_FOUND'); ?></strong></p>
<?php } else {?>
	<p><?php echo JText::_('UPDATER_UPDATE_FAILED_TO_CONTACT_TO_VERSIONING_SERVER'); ?></p>
<?php }?>
