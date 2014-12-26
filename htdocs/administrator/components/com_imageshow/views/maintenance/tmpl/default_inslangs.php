<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default_inslangs.php 13879 2012-07-10 10:50:23Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
?>
<div id="jsn-languages-configuration">
	<h2 class="jsn-section-header">
		<?php echo JText::_('MAINTENANCE_LANGUAGES'); ?>
	</h2>
	<form action="index.php?option=com_imageshow&controller=maintenance&type=lang" method="POST" name="adminForm" id="frm_lang" class="form-horizontal">
	<p class="item-title"><?php echo JText::_('MAINTENANCE_LANG_SELECT_LANGUAGE_TO_BE_INSTALLED'); ?></p>
		<?php foreach ($this->languages as $lang): ?>
		<div class="jsn-language-item <?php echo $lang->code; ?>">
			<span class="jsn-icon24 icon-flag <?php echo strtolower($lang->code); ?>"></span>
			<label class="checkbox">
				<?php $disabled = (!$this->canInstallLanguage($lang->code, 'administrator') || $this->isInstalledLanguage($lang->code, 'administrator') || !$this->isJoomlaSupport($lang->code, 'administrator')) ? 'disabled' : '' ?>
				<?php $checked  = ($this->isInstalledLanguage($lang->code, 'administrator')) ?>

				<input type="checkbox" <?php echo $disabled ?> value="<?php echo $lang->code?>" name="languages_administrator[]" <?php echo $checked ? 'checked' : '' ?> />
				<span><?php echo $lang->code ?> - <?php echo $lang->title ?> (Administrator)</span>

				<?php if ($this->isInstalledLanguage($lang->code, 'administrator')): ?>
					<span class="jsn-lang-installed"> - <?php echo JText::_('MAINTENANCE_LANG_INSTALLED'); ?></span>
				<?php endif ?>
			</label>
			<label class="checkbox">
				<?php $disabled = (!$this->canInstallLanguage($lang->code, 'site') || $this->isInstalledLanguage($lang->code, 'site') || !$this->isJoomlaSupport($lang->code, 'site')) ? 'disabled' : '' ?>
				<?php $checked  = ($this->isInstalledLanguage($lang->code, 'site')) ?>

				<input type="checkbox" <?php echo $disabled ?> value="<?php echo $lang->code?>" name="languages_site[]" <?php echo $checked ? 'checked' : '' ?> />
				<span><?php echo $lang->code ?> - <?php echo $lang->title ?> (Site)</span>

				<?php if ($this->isInstalledLanguage($lang->code, 'site')): ?>
					<span class="jsn-lang-installed"> - <?php echo JText::_('MAINTENANCE_LANG_INSTALLED'); ?></span>
				<?php endif ?>
			</label>
		</div>
		<?php endforeach ?>
		<div class="clearbreak"></div>
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" value="<?php echo JText::_('SAVE'); ?>"><?php echo JText::_('SAVE'); ?></button>
		</div>
		<input type="hidden" name="option" value="com_imageshow" />
		<input type="hidden" name="controller" value="maintenance" />
		<input type="hidden" name="task" value="installLang" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>