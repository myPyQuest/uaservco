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
	<legend><?php echo JText::_('JSN_POWERADMIN_CONFIG_USER')?></legend>	
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SESSION_INFINITE_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SESSION_INFINITE'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_admin_session_timer_infinite0" <?php echo $this->item->get('admin_session_timer_infinite', 0) == 0 ? "checked" : " "; ?>
					type="radio" name="params[admin_session_timer_infinite]" value="0">
				<?php echo JText::_('JNo')?>
			</label>
			<label class="radio inline">
				<input id="params_admin_session_timer_infinite1" <?php echo $this->item->get('admin_session_timer_infinite', 0) == 1 ? "checked" : " "; ?> type="radio" name="params[admin_session_timer_infinite]" value="1">
				<?php echo JText::_('JYes')?>
			</label>	
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SESSION_TIMEOUT_WARNING_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SESSION_TIMEOUT_WARNING'); ?></label>
		<div class="controls">
			<input type="text" class="span1" value="<?php echo $this->item->get('admin_session_timeout_warning', 1) ?>" id="params_admin_session_timeout_warning" name="params[admin_session_timeout_warning]">	
			<span class="help-inline"><?php echo JText::_('JSN_POWERADMIN_CONFIG_MINUTE_OR')?></span>
			<label class="help-inline">
				<input type="checkbox" value="1" <?php echo $this->item->get('admin_session_timeout_warning_disabled', 0) == 1 ? "checked" : " "; ?> id="params_admin_session_timeout_warning_disabled" name="params[admin_session_timeout_warning_disabled]">
			<?php echo JText::_('JSN_POWERADMIN_CONFIG_DISABLE_TIMEOUT_WARNING')?>
			</label>
		</div>
	</div>
</fieldset>