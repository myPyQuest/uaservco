<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: k2_categories.php 13902 2012-07-11 10:34:39Z thangbh $
-------------------------------------------------------------------------*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$this->lists['order_Dir']	= $this->escape($this->state->get('list.direction'));
$this->lists['order']		= $this->escape($this->state->get('list.ordering'));
$this->filter_trash = false;
$this->filter_featured = false;
$this->ordering = ($this->lists['order'] == 'a.lft');
$this->dateFormat = (K2_JVERSION == '16') ? JText::_('K2_J16_DATE_FORMAT',true) : JText::_('K2_DATE_FORMAT',true);
$this->nullDate = JFactory::getDBO()->getNullDate();
?>

<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2_TITLE', 'c.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JText::_('K2_PARAMETER_INHERITANCE',true); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2_ASSOCIATED_EXTRA_FIELD_GROUPS', 'extra_fields_group', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2_ACCESS_LEVEL', 'c.access', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2_PUBLISHED', 'c.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JText::_('K2_IMAGE',true); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'K2_ID', 'c.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($this->items as $key => $row) :	?>
			<tr class="row<?php echo ($key%2); ?>">
				<td>
					<?php if ($this->filter_trash): ?>
					<?php if ($row->trash): ?>
					<strong><?php echo $row->treename; ?></strong>
					<?php else: ?>
					<?php echo $row->treename; ?>
					<?php endif; ?>
					<?php else: ?>
					<a href="<?php echo JRoute::_('index.php?option=com_k2&view=category&cid='.$row->id); ?>"><?php echo $row->treename; ?>
					</a>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $row->inheritFrom; ?>
				</td>
				<td class="center">
					<?php echo $row->extra_fields_group; ?>
				</td>
				<td class="center">
					<?php echo ($this->filter_trash || K2_JVERSION=='16')?strip_tags(JHTML::_('grid.access', $row, $key )):JHTML::_('grid.access', $row, $key ); ?>
				</td>
				<td class="center">
					<?php echo ($this->filter_trash)?strip_tags(JHTML::_('grid.published', $row, $key ),'<img>'):JHTML::_('grid.published', $row, $key ); ?>
				</td>
				<td class="center">
					<?php if($row->image): ?>
					<a href="<?php echo JURI::root().'media/k2/categories/'.$row->image; ?>" class="modal">
						<img src="templates/<?php echo $this->template; ?>/images/menu/icon-16-media.png" alt="<?php echo JText::_('K2_PREVIEW_IMAGE',true); ?>" />
					</a>
					<?php endif; ?>
				</td>
				<td class="center">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
