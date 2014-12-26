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
?>
<fieldset>
	<legend><?php echo JText::_('JSN_POWERADMIN_CONFIG_GENERAL')?></legend>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_ENABLE_ADMINBAR_DESC') ?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_ENABLE_ADMINBAR'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_enable_adminbar0" <?php echo $this->item->get('enable_adminbar', 1) == 0 ? "checked" : ""; ?> type="radio" name="params[enable_adminbar]" value="0">
				<?php echo JText::_('JNo'); ?>
			</label>
			<label class="radio inline">
				<input id="params_enable_adminbar1" <?php echo $this->item->get('enable_adminbar', 1) == 1 ? "checked" : ""; ?> type="radio" name="params[enable_adminbar]" value="1">
				<?php echo JText::_('JYes'); ?>
			</label>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SET_PINNED_BAR_DESC') ?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SET_PINNED_BAR'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_pinned_bar0" <?php echo $this->item->get('pinned_bar', 1) == 0 ? "checked" : ""; ?>  type="radio" name="params[pinned_bar]" value="0">
				<?php echo JText::_('JNo'); ?>
			</label>
			<label class="radio inline">
				<input id="params_pinned_bar1" <?php echo $this->item->get('pinned_bar', 1) == 1 ? "checked" : " "; ?> type="radio" name="params[pinned_bar]" value="1">
				<?php echo JText::_('JYes'); ?>
			</label>	
		</div>
	</div>
</fieldset>