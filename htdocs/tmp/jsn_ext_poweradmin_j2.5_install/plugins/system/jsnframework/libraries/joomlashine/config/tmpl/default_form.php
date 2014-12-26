<?php
/**
 * @version     $Id: default_form.php 15298 2012-08-21 04:05:13Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Load tooltips
JHtml::_('behavior.mootools');

foreach ($fieldsets AS $fieldset)
{
?>
	<fieldset>
<?php
	if ($fieldset->label)
	{
?>
		<legend><?php echo JText::_($fieldset->label); ?></legend>
<?php
	}

	foreach ($fieldset->form->getFieldset() AS $field)
	{
		if ($field->label)
		{
?>
		<div id="<?php echo str_replace('_', '-', $field->id); ?>-field" class="control-group">
<?php
		echo $field->label;
?>
			<div class="controls"><?php echo $field->input; ?></div>
		</div>
<?php
		}
		else
		{
?>
		<div id="<?php echo str_replace('_', '-', $field->id); ?>-field">
<?php
		echo $field->input;
?>
		</div>
<?php
		}
	}

	if (isset($fieldset->form->actions))
	{
?>
		<div class="form-actions">
			<div class="jsn-bootstrap"></div>
<?php
		foreach ($fieldset->form->actions AS $action)
		{
?>
			<button class="btn btn-primary" value="<?php echo $action->task; ?>"<?php echo ($action->track ? ' track-change="yes"' : '') . ($action->ajax ? ' ajax-request="yes"' : ''); ?>><?php echo JText::_($action->label); ?></button>
<?php
		}
?>
		</div>
<?php
	}
?>
	</fieldset>
<?php
}
