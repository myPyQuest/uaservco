<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id$
-------------------------------------------------------------------------*/
defined('_JEXEC') or die('Restricted access');
?>
<div id="jsn-permission-configuration" class="jsn-bootstrap">
	<h2 class="jsn-section-header">
		<?php echo JText::_('JSN_POWERADMIN_CONFIG_PERMISSION'); ?>
	</h2>
	<form action="<?php JRoute::_('index.php?option=com_poweradmin&task=config.permissions'); ?>" method="post" name="adminForm">
		<div id="permissions">
			<?php echo $this->form->getInput('rules'); ?>
		</div><!-- /permissions -->

		<div class="form-actions">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('config.permissions')">
				<span class="ui-button-text">Save</span>
			</button>
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>