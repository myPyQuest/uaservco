<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: default.php 13896 2012-07-11 05:10:27Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('behavior.tooltip');
?>
<div id="jsn-product-configuration" class="jsn-page-configuration">
	<div class="jsn-bootstrap jsn-bgpattern pattern-sidebar">
		<div>
			<div class="jsn-page-nav">
				<ul class="nav nav-list">
					<li class="nav-header"><?php echo JText::_('JSN_POWERADMIN_CONFIG'); ?></li>
					<li<?php echo ($this->page == 'global')?' class="active"':''; ?>><a id="linkconfigs" href="index.php?option=com_poweradmin&view=config"><i class="jsn-icon32 icon-cog"></i><?php echo JText::_('JSN_POWERADMIN_CONFIG_GLOBAL_PARAMETERS'); ?></a></li>
					<li<?php echo ($this->page == 'languages')?' class="active"':''; ?>><a id="linklangs" href="index.php?option=com_poweradmin&view=config&page=languages"><i class="jsn-icon32 icon-globe"></i><?php echo JText::_('JSN_POWERADMIN_CONFIG_LANGUAGES'); ?></a></li>
					<li<?php echo ($this->page == 'permissions')?' class="active"':''; ?>><a id="linkpermissions" href="index.php?option=com_poweradmin&view=config&page=permissions"><i class="jsn-icon32 icon-lock"></i><?php echo JText::_('JSN_POWERADMIN_CONFIG_PERMISSION'); ?></a></li>
				</ul>
			</div>
			<div class="jsn-page-content"><div>
				<?php include dirname(__FILE__).DS."{$this->page}.php" ?>
			</div></div>
			<div class="clearbreak"></div>
		</div>
	</div>
</div>

<?php include(JSN_POWERADMIN_PATH.DS.'footer.php');?>