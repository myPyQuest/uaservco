<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: global.php 13883 2012-07-10 11:11:41Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
$item = $this->item;
?>
<div id="jsn-global-configuration">
	<h2 class="jsn-section-header">
		<?php echo JText::_('JSN_POWERADMIN_CONFIG_GLOBAL_PARAMETERS'); ?>
	</h2>
	<form action="" method="post" name="adminForm" class="form-horizontal">
		<div id="jsn_tabs">
			<ul>
				<li><a href="#jsn_config_adminbar"><?php echo JText::_('JSN_POWERADMIN_CONFIG_ADMINBAR'); ?></a></li>
				<li><a href="#jsn_config_sitemanager"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SITEMANAGER'); ?></a></li>			
			</ul>
			<div id="jsn_config_adminbar">
				<?php echo $this->loadTemplate('global.general')?>
				<?php echo $this->loadTemplate('global.menu')?>
				<?php echo $this->loadTemplate('global.user')?>
				<?php echo $this->loadTemplate('global.history')?>
			</div>			
			<div id="jsn_config_sitemanager">
				<?php echo $this->loadTemplate('global.sitemanager')?>
			</div>
		</div>
		<div class="form-actions">          
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('config.apply')">
				<?php echo JText::_('JTOOLBAR_APPLY');?>
			</button>
			<input type="hidden" name="option" value="com_poweradmin" />
			<input type="hidden" name="task" value="" />
		</div>
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>