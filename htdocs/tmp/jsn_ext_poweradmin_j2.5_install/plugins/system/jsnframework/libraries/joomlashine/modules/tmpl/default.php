<?php
/**
 * @version     $Id$
 * @package     JSN_Mobilize
 * @subpackage  AdminComponent
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */
// No direct access to this file
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_modules' . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
$action  = JRequest::getCmd('jsnaction', '');
$actionForm = isset($_SERVER['QUERY_STRING'])?'index.php?'.$_SERVER['QUERY_STRING']:'';
?>
<div class="jsn-page-list">
	<div class="jsn-bootstrap">
		<form class="form-inline" action="<?php echo JRoute::_($actionForm); ?>" method="post" name="adminForm" id="adminForm">
			<div class="jsn-fieldset-filter">
				<fieldset>
					<div class="pull-left jsn-fieldset-search">
						<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL');?></label>
						<input type="text" class="input-large" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search'));?>" title="<?php echo JText::_('COM_MODULES_MODULES_FILTER_SEARCH_DESC');?>" />
						<button class="btn btn-icon" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT');?>" type="submit"><i class="icon-search"></i></button>
						<button class="btn btn-icon" type="button" onclick="document.id('filter_search').value='';this.form.submit();" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR');?>"><i class="icon-remove"></i></button>
					</div>
					<div class="pull-right jsn-fieldset-select">
						<select name="filter_module" class="inputbox" onchange="this.form.submit()">
							<option value=""><?php echo JText::_('COM_MODULES_OPTION_SELECT_MODULE');?></option>
							<?php echo JHtml::_('select.options', ModulesHelper::getModules($this->state->get('filter.client_id')), 'value', 'text', $this->state->get('filter.module'));?>
						</select>
					</div>
					<div class="clearbreak"></div>
				</fieldset>
			</div>
			<table class="table table-bordered table-striped table-popup">
				<thead>
					<tr>
						<?php
						$classHover = "jsnhover";
						if ($action != "update")
						{
							$classHover = "";
							?>
							<th width="1%">
								<input type="checkbox" name="checkall-toggle" class="checkall" />
							</th>
							<?php
						}
						?>
						<th class="title">
							<?php echo JHtml::_('grid.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder);?>
						</th>
						<th width="15%" class="left">
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_POSITION', 'position', $listDirn, $listOrder);?>
						</th>
						<th width="10%" class="left" >
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_MODULE', 'name', $listDirn, $listOrder);?>
						</th>
						<th width="10%">
							<?php echo JHtml::_('grid.sort', 'COM_MODULES_HEADING_PAGES', 'pages', $listDirn, $listOrder);?>
						</th>
						<th width="1%" class="nowrap">
							<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder);?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="10">
							<?php echo $this->pagination->getListFooter();?>
						</td>
					</tr>
				</tfoot>
				<tbody>
					<?php
					foreach ($this->items as $i => $item) :
						?>
					<tr class="<?php echo $classHover;?>" data-title="<?php echo $this->escape($item->title); ?>" data-id="<?php echo $item->id; ?>">
							<?php
							if ($action != "update")
							{
								?>
								<td class="center checkbox-items">
									<input type="checkbox" value="<?php echo $item->id;?>" name="cid[]" data-title="<?php echo $this->escape($item->title);?>">
								</td>
								<?php
							}
							?>
							<td>
								<?php echo $this->escape($item->title);?>
							</td>

							<td class="left">
								<?php
								if ($item->position)
								{
									echo $item->position;
								}
								else
								{
									echo ':: ' . JText::_('JNONE') . ' ::';
								}
								?>
							</td>
							<td class="left">
								<?php echo $item->name; ?>
							</td>
							<td class="center">
								<?php echo $item->pages; ?>
							</td>
							<td class="center">
								<?php echo (int) $item->id; ?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<div>
				<input type="hidden" name="task" value="" />
				<input type="hidden" name="filter_client_id" value="0" />
				<input type="hidden" name="filter_state" value="1" />
				<input type="hidden" name="boxchecked" value="0" />
				<input type="hidden" name="filter_order" value="<?php echo $listOrder;?>" />
				<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn;?>" />
				<?php echo JHtml::_('form.token');?>
			</div>
		</form>
	</div>
</div>