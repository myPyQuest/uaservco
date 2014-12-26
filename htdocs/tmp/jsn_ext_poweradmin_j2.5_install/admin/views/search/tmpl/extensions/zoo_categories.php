<?php
/**
 * @version    $Id$
 * @package    JSN_Poweradmin
 * @author     JoomlaShine Team <support@joomlashine.com>
 * @copyright  Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$this->lists['order_Dir']	= $this->escape($this->state->get('list.direction'));
$this->lists['order']		= $this->escape($this->state->get('list.ordering'));

?>
<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>
					<?php echo JHTML::_('grid.sort', JText::_('Name'), 'c.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				
				<th width="100">
					<?php echo JText::_('Items'); ?>
				</th>
				<th width="80">
					<?php echo JText::_('Published'); ?>
				</th>
				<th width="50">
					<?php echo JHTML::_('grid.sort', 'ID', 'c.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="10">
					<?php // echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php 
			for ($i = 0; $i < count($this->items); $i++) 
			{
				$row = $this->items[$i];
				$itemCnt = 0;
				if($row->item_ids){
					$itemCnt = count(explode(",", $row->item_ids));
				}				 	
				
				if ($row->published) {
					$published = 'publish';
					$publish_alt = JText::_('JPUBLISHED');
				} else {
					$published = 'unpublish';
					$publish_alt = JText::_('JUNPUBLISHED');
				}
			?>
			<tr class="row<?php echo ($i%2); ?>">
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_zoo&controller=category&task=edit&changeapp=' . $row->application_id . '&cid='.$row->id); ?>"><?php echo $row->name; ?></a>
					<p class="smallsub">
						<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($row->alias));?>
					</p>
				</td>
				<td>
					<?php echo $itemCnt;?>
				</td>
				<td >
					<?php echo '<a class="jgrid"><span class="state ' . $published . '" title="'.$publish_alt.'"></span></a>'?>
				</td>
				<td >
					<?php echo $row->id;?>
				</td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>