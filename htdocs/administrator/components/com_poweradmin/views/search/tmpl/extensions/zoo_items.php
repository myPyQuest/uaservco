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
$offset = PoweradminModelZooItemSearch::getTimeOffset();
$db = JFactory::getDBO();
$nullDate = $db->getNullDate();

?>
<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th class="title" width="50%">
					<?php echo JHTML::_('grid.sort', 'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Type', 'a.type', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Published', 'a.state', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JText::_('Frontpage'); ?>
				</th>
				<th>
					<?php echo JText::_('Searchable'); ?>
				</th>
				<th>
					<?php echo JText::_('Comments'); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Access', 'a.access', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Author', 'a.created_by', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Date', 'a.created', @$this->lists['order_Dir'], @$this->lists['order']); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'Hit', 'a.hits', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th>
					<?php echo JHTML::_('grid.sort', 'ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>			
				
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="15">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php 
			for ($i=0; $i < count($this->items); $i++){
			$row = $this->items[$i];			 
			$now = JFactory::getDate()->toUnix();
			
			$publish_up   = JFactory::getDate($row->publish_up);
			$publish_down = JFactory::getDate($row->publish_down);			
			$publish_up->setOffset($offset);
			$publish_down->setOffset($offset);

			$row->published = false;

			if ($now <= $publish_up->toUnix() && $row->state == 1) {
				$row->published = true;
			} else if (($now <= $publish_down->toUnix() || $row->publish_down == $nullDate) && $row->state == 1 ) {
				$row->published = true;
			} else if ($now > $publish_down->toUnix() && $row->state == 1) {
				$row->published = false;
			} else if ($row->state == 0) {
				$row->published = false;
			}			
		
			if ($row->searchable == 0) {
				$searchable = 'unpublish';
				$search_alt = JText::_('None searchable');
			} elseif ($row->searchable == 1) {
				$searchable = 'publish';
				$search_alt = JText::_('Searchable');
			}
			
			if ($row->frontpage) {
				$frontpage = 'publish';
				$frontpage_alt = JText::_('JYES');
			} else {
				$frontpage = 'unpublish';
				$frontpage_alt = JText::_('JNO');
			}
						
			$params = json_decode($row->params);
			$comments_enabled = (int) $params->{'config.enable_comments'};
			
			if ($comments_enabled) {
				$comments = 'publish';
				$comments_alt = JText::_('JENABLEd');
			} else {
				$comments = 'unpublish';
				$comments_alt = JText::_('JDISABLEd');
			}
			
			// author
			$author = $row->created_by_alias;			
			if (!$author) {
				$user = JFactory::getUser($row->created_by);
				if (isset($user)) {
					$author = $user->name;			
				} else {
					$author = JText::_('Guest');
				}
			}
			?>
			<tr class="row<?php echo ($i%2); ?>">
				<td>
					<a href="<?php echo JRoute::_('index.php?option=com_zoo&controller=item&task=edit&changeapp=' . $row->application_id . '&cid='.$row->id); ?>"><?php echo $row->name; ?></a>
					<p class="smallsub">
						<?php echo JText::sprintf('JGLOBAL_LIST_ALIAS', $this->escape($row->alias));?>
					</p>
				</td>
				<td class="center">
					<?php echo $row->type;?>
				</td>
				<td class="center">
					<?php echo strip_tags(JHTML::_('grid.published', $row, $key ),'<img>')?>
				</td>
				<td class="center">
					<?php echo '<a class="jgrid"><span class="state ' . $frontpage . '" title="'.$frontpage_alt.'"></span></a>'?>
				</td>
				<td class="center">
					<?php echo '<a class="jgrid"><span class="state ' . $searchable . '" title="'.$search_alt.'"></span></a>'?>
				</td>
				<td class="center">
					<?php echo '<a class="jgrid"><span class="state ' . $comments . '" title="'.$comments_alt.'"></span></a>'?>
				</td>
				<td class="center">
					<?php echo $row->groupname;?>
				</td>
				<td class="center">
					<?php echo $author;?>
				</td>
				<td class="center">
					<?php echo JFactory::getDate($row->created, $offset)?>
				</td>
				<td class="center">
					<?php echo $row->hits?>
				</td>
				<td class="center">
					<?php echo $row->id?>
				</td>
				
			</tr>
			<?php }; ?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
