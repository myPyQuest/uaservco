<?php
/**
 * @version		$Id: edit.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_content
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');

// Create shortcut to parameters.
$params = $this->state->get('params');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'article.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			<?php echo $this->form->getField('articletext')->save(); ?>
			Joomla.submitform(task);
		} else {
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}
</script>

<div class="com-content <?php echo $this->pageclass_sfx; ?>">
	<div class="article-submission">
		<?php if ($params->get('show_page_heading', 1)) : ?>
		<h2 class="componentheading"> <?php echo $this->escape($params->get('page_heading')); ?> </h2>
		<?php endif; ?>
		<form action="<?php echo JRoute::_('index.php?option=com_content&a_id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
			<fieldset>
				<legend><?php echo JText::_('JEDITOR'); ?></legend>
				<div class="clearafter">
					<div style="float: left;">
						<div class="formelm"><span class="field-title"><?php echo $this->form->getLabel('title'); ?></span><?php echo $this->form->getInput('title'); ?> </div>
						<?php if (is_null($this->item->id)):?>
						<div class="formelm"><span class="field-title"><?php echo $this->form->getLabel('alias'); ?></span><?php echo $this->form->getInput('alias'); ?> </div>
						<?php endif; ?>
					</div>
					<div style="float: right;">
						<button type="button" onclick="Joomla.submitbutton('article.save')"> <?php echo JText::_('JSAVE') ?> </button>
						<button type="button" onclick="Joomla.submitbutton('article.cancel')"> <?php echo JText::_('JCANCEL') ?> </button>
					</div>
				</div>
				<?php echo $this->form->getInput('articletext'); ?>
			</fieldset>
			<fieldset>
				<legend><?php echo JText::_('COM_CONTENT_PUBLISHING'); ?></legend>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('catid'); ?></span> <?php echo $this->form->getInput('catid'); ?> </div>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('created_by_alias'); ?></span> <?php echo $this->form->getInput('created_by_alias'); ?> </div>
				<?php if ($this->item->params->get('access-change')): ?>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('state'); ?></span> <?php echo $this->form->getInput('state'); ?> </div>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('featured'); ?></span> <?php echo $this->form->getInput('featured'); ?> </div>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('publish_up'); ?></span> <?php echo $this->form->getInput('publish_up'); ?> </div>
				<div class="formelm"> <span class="field-title"><?php echo $this->form->getLabel('publish_down'); ?></span> <?php echo $this->form->getInput('publish_down'); ?> </div>
				<?php endif; ?>
				<div class="formelm"><span class="field-title"><?php echo $this->form->getLabel('access'); ?></span><?php echo $this->form->getInput('access'); ?> </div>
				<?php if (is_null($this->item->id)):?>
				<div class="form-note">
					<p><?php echo JText::_('COM_CONTENT_ORDERING'); ?></p>
				</div>
				<?php endif; ?>
			</fieldset>
			<fieldset>
				<legend><?php echo JText::_('JFIELD_LANGUAGE_LABEL'); ?></legend>
				<div class="formelm-area"> <span class="field-title"><?php echo $this->form->getLabel('language'); ?></span> <?php echo $this->form->getInput('language'); ?> </div>
			</fieldset>
			<fieldset>
				<legend><?php echo JText::_('COM_CONTENT_METADATA'); ?></legend>
				<div class="formelm-area"> <span class="field-title"><?php echo $this->form->getLabel('metadesc'); ?></span> <?php echo $this->form->getInput('metadesc'); ?> </div>
				<div class="formelm-area"> <span class="field-title"><?php echo $this->form->getLabel('metakey'); ?></span> <?php echo $this->form->getInput('metakey'); ?> </div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
				<?php echo JHTML::_( 'form.token' ); ?>
			</fieldset>
		</form>
	</div>
</div>