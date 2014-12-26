<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: languages.php 13922 2012-07-12 04:23:17Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<div id="jsn-languages-configuration" class="jsn-bootstrap">
	<h2 class="jsn-section-header">
		<?php echo JText::_('JSN_POWERADMIN_CONFIG_LANGUAGES'); ?>
	</h2>
	<form action="" method="post" name="adminForm">
		<p><?php echo JText::_('JSN_POWERADMIN_SELECT_LANGUAGE_TO_INSTALL')?></p>
		<?php foreach ($this->languages as $lang): ?>
		<div class="jsn-language-item <?php echo $lang->code; ?>">
			<span class="jsn-icon24 icon-flag <?php echo strtolower($lang->code); ?>"></span>
			<label class="checkbox">
				<?php $disabled = (!$this->canInstallLanguage($lang->code, 'administrator') || $this->isInstalledLanguage($lang->code, 'administrator') || !$this->isJoomlaSupport($lang->code, 'administrator')) ? 'disabled' : '' ?>
				<?php $checked  = ($this->isInstalledLanguage($lang->code, 'administrator')) ?>
				
				<input type="checkbox" <?php echo $disabled ?> value="<?php echo $lang->code?>" name="languages_administrator[]" <?php echo $checked ? 'checked' : '' ?> />
				<span><?php echo $lang->code ?> - <?php echo $lang->title ?> (Administrator)</span>
				
				<?php if ($this->isInstalledLanguage($lang->code, 'administrator')): ?>
					<span class="jsn-lang-installed"> - <?php echo JText::_('JSN_POWERADMIN_LANGUAGE_INSTALLED'); ?></span>
				<?php endif ?>
			</label>
			<label class="checkbox">
				<?php $disabled = (!$this->canInstallLanguage($lang->code, 'site') || $this->isInstalledLanguage($lang->code, 'site') || !$this->isJoomlaSupport($lang->code, 'site')) ? 'disabled' : '' ?>
				<?php $checked  = ($this->isInstalledLanguage($lang->code, 'site')) ?>
			
				<input type="checkbox" <?php echo $disabled ?> value="<?php echo $lang->code?>" name="languages_site[]" <?php echo $checked ? 'checked' : '' ?> />
				<span><?php echo $lang->code ?> - <?php echo $lang->title ?> (Site)</span>
				
				<?php if ($this->isInstalledLanguage($lang->code, 'site')): ?>
					<span class="jsn-lang-installed"> - <?php echo JText::_('JSN_POWERADMIN_LANGUAGE_INSTALLED'); ?></span>
				<?php endif ?>
			</label>
		</div>
		<?php endforeach ?>
		<div class="clearbreak"></div>
		
		<div class="form-actions">
			<button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('config.languages')">
				<span class="ui-button-text"><?php echo JText::_('JTOOLBAR_APPLY');?></span>
			</button>
			<input type="hidden" name="option" value="com_poweradmin" />
			<input type="hidden" name="page" value="languages" />
			<input type="hidden" name="task" value="" />
			<?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>