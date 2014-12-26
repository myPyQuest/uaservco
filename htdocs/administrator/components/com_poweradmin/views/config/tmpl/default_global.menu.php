<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id$
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$logoFile = $this->item->get('logo_file', 'administrator/components/com_poweradmin/assets/images/logo-jsnpoweradmin.png');
$logoLink = $this->item->get('logo_link', 'index.php?option=com_poweradmin&view=config');
$logoSlogan = $this->item->get('logo_slogan',JText::_('JSN_POWERADMIN_CONFIG_LOGO_SLOGAN_DEFAULT'));

if ($logoFile == 'N/A') {	
	$logoFile = '';
}

if ($logoLink == 'N/A') {	
	$logoLink = '';
}
?>
<fieldset>
	<legend><?php echo JText::_('JSN_POWERADMIN_CONFIG_MENU')?></legend>	
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_FILE_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_FILE'); ?></label>
		<div class="controls">
			<div class="input-append">
				<input type="text" readonly="readonly" value="<?php echo $logoFile ?>" id="params_logo_file" name="params[logo_file]" class="span6"><button type="button" class="btn" id="logo-select">...</button>
			</div>
			<button type="button" class="btn inline" onclick=" document.id('params_logo_file').value=''; document.id('params_logo_file').fireEvent('change'); return false; ">Clear</button>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_LINK_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_LINK'); ?></label>
		<div class="controls">
			<input type="text" value="<?php echo $logoLink ?>" id="params_logo_link" name="params[logo_link]" class="span6">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_SLOGAN_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_LOGO_SLOGAN'); ?></label>
		<div class="controls">
			<input type="text" value="<?php echo $logoSlogan ?>" id="params_logo_slogan" name="params[logo_slogan]" class="span6">
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_ENABLE_UNINSTALL_MENU_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_ENABLE_UNINSTALL_MENU'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_allow_uninstall0" <?php echo $this->item->get('allow_uninstall', 1) == 0 ? "checked" : ""; ?> type="radio" name="params[allow_uninstall]" value="0">
				<?php echo JText::_('JNo')?>
			</label>
			<label class="radio inline">
				<input id="params_allow_uninstall1" <?php echo $this->item->get('allow_uninstall', 1) == 1 ? "checked" : " "; ?> type="radio" name="params[allow_uninstall]" value="1">
				<?php echo JText::_('JYes')?>
			</label>	
		</div>
	</div>
</fieldset>