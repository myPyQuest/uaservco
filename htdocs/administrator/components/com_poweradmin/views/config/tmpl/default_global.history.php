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

$searchCoverages = PowerAdminHelper::getSearchCoverages();
$searchCoveragesOrder = $this->item->get('search_coverage_order', $searchCoverages);
if (!is_array($searchCoveragesOrder)) {
	$searchCoveragesOrder = explode(',', $searchCoveragesOrder);
	$searchCoveragesOrder = array_map('trim', $searchCoveragesOrder);
}

$notOrderedCoverages = array_diff($searchCoverages, $searchCoveragesOrder);
if (!empty($notOrderedCoverages) && is_array($notOrderedCoverages)) {
	$searchCoveragesOrder = array_merge($searchCoveragesOrder, $notOrderedCoverages);
}

$selectedCoverages = $this->item->get('search_coverage', $searchCoverages);
if (!is_array($selectedCoverages)) {
	$selectedCoverages = array();
}
?>
<fieldset>
	<legend><?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_HISTORY')?></legend>	
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_HISTORY_COUNT_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_HISTORY_COUNT'); ?></label>
		<div class="controls">
			<input type="text" class="span1" value="<?php echo $this->item->get('history_count', 30) ?>" id="params_history_count" name="params[history_count]">	
			<span class="help-inline"><?php echo JText::_('JSN_POWERADMIN_CONFIG_ITEM_TEXT')?></span>	
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_RESULTS_COUNT_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_RESULTS_COUNT'); ?></label>
		<div class="controls">
			<input type="text" class="span1" value="<?php echo $this->item->get('search_result_num', 10) ?>" id="params_search_result_num" name="params[search_result_num]">
			<span class="help-inline"><?php echo JText::_('JSN_POWERADMIN_CONFIG_ITEM_TEXT')?></span>			
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_TRASHED_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_TRASHED'); ?></label>
		<div class="controls">
			<label class="radio inline">						
				<input id="params_search_trashed0" <?php echo $this->item->get('search_trashed', 1) == 0 ? "checked" : " "; ?> type="radio" name="params[search_trashed]" value="0">
				<?php echo JText::_('JNo')?>
			</label>
			<label class="radio inline">
				<input id="params_search_trashed1" <?php echo $this->item->get('search_trashed', 1) == 1 ? "checked" : " "; ?> type="radio" name="params[search_trashed]" value="1">
				<?php echo JText::_('JYes')?>
			</label>	
		</div>
	</div>
	<div class="control-group">
		<label class="control-label hasTip" title="<?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_COVERAGES_DESC')?>"><?php echo JText::_('JSN_POWERADMIN_CONFIG_SEARCH_COVERAGES'); ?></label>
		<div class="controls" id="params_search_coverage">
			<ul class="sortable">
				<?php foreach ($searchCoveragesOrder as $coverage): ?>
				<li class="item" id="<?php echo $coverage ?>">
					<label class="checkbox">
						<?php $checked = in_array($coverage, $selectedCoverages) ? 'checked' : '' ?>
						<input type="checkbox" name="params[search_coverage][]" value="<?php echo $coverage ?>" <?php echo $checked ?> />
						<?php echo JText::_('JSN_POWERADMIN_COVERAGE_'.strtoupper($coverage)) ?>
					</label>
				</li>
				<?php endforeach ?>
			</ul>
			<input type="hidden" value="<?php echo implode(',', $searchCoveragesOrder) ?>" id="params_search_coverage_order" name="params[search_coverage_order]" />
		</div>
	</div>
</fieldset>