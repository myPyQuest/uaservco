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
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_SHOW_HELP_ON_FIRST_RUN_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_SHOW_HELP_ON_FIRST_RUN'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_show_help_on_first_run0" <?php echo $this->item->get('show_help_on_first_run', 1) == 0 ? "checked" : " "; ?> type="radio" name="params[show_help_on_first_run]" value="0">
				<?php echo JText::_('JNo')?>
			</label>
			<label class="radio inline">
				<input id="params_show_help_on_first_run1" <?php echo $this->item->get('show_help_on_first_run', 1) == 1 ? "checked" : " "; ?> type="radio" name="params[show_help_on_first_run]" value="1">
				<?php echo JText::_('JYes')?>
			</label>
		</div>
	</div>
</fieldset>