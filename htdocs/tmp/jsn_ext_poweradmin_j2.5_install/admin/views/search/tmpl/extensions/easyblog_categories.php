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

$order_Dir	= $this->escape($this->state->get('list.direction'));
$order		= $this->escape($this->state->get('list.ordering'));
$offset 	= PoweradminModelEasyblogItemSearch::getTimeOffset();
$ehelper 	= JPATH_ROOT . DS . 'components' . DS . 'com_easyblog' . DS . 'helpers' . DS . 'helper.php' ;
if(file_exists($ehelper)){
	require_once( $ehelper );
	function getCategoryName( $id )
	{
		$category	= EasyBlogHelper::getTable( 'ECategory' , 'Table');
		$category->load( $id );
		return JText::_( $category->title );
	}
	if(class_exists("EasyBlogHelper")) {

?>
<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>								
				<th class="title" style="text-align: left;"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_CATEGORIES_CATEGORY_TITLE' ) , 'title', $order_Dir, $order ); ?></th>
				<th width="5%"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_DEFAULT' ); ?></th>				
				<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_PRIVACY' ); ?></th>
				<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_PUBLISHED' ); ?></th>								
				<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_ENTRIES' ); ?></th>
				<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_CATEGORIES_CHILD_COUNT' ); ?></th>
				<th class="title" width="15%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_EASYBLOG_CATEGORIES_AUTHOR' ) , 'created_by', $order_Dir, $order ); ?></th>				
				<th width="1%">ID</th>			
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
			for ($i = 0; $i < count($this->items); $i ++)
			{
				$row = $this->items[$i];			
				$link 			= 'index.php?option=com_easyblog&amp;c=category&amp;task=edit&amp;catid='. $row->id;				
				$published 	= JHTML::_('grid.published', $row, $i );
				$user		= JFactory::getUser( $row->created_by );
			?>
			<td>
				<a href="<?php echo $editLink; ?>"><?php echo $row->title; ?></a>	
			</td>
			<td>
				<a class="jgrid"><span class="state <?php echo $row->default ? 'publish' : 'unpublish'?> " ></span></a>
			</td>
			<td align="center">
				<?php echo ( $row->private ) ? JText::_('COM_EASYBLOG_CATEGORIES_PRIVATE') : JText::_('COM_EASYBLOG_CATEGORIES_PUBLIC') ?>
			</td>
			<td align="center">
				<?php echo $published; ?>
			</td>
			<td>
				<a href="<?php echo JRoute::_('index.php?option=com_easyblog&view=blogs&filter_category=' . $row->id);?>"><?php echo $row->count;?></a>
			</td>
			<td align="center">
				<?php echo $row->child_count; ?>
			</td>
			<td>
				<?php echo $user->name; ?>
			</td>
			<td align="center"><?php echo $row->id;?></td>
			<?php }?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>
<?php 
	}else{
		echo '<div id="system-message-container">
				<dl id="system-message">
				<dt class="">Message</dt>
				<dd class="error">
					<ul>
						<li>Sorry. Site search does not support current version of Easy Blog</li>
					</ul>
				</dd>
				</dl>
				</div>';		
	}
}else{
	echo '<div id="system-message-container">
			<dl id="system-message">
				<dt class="">Message</dt>
				<dd class="error">
					<ul>
						<li>Sorry. Site search does not support current version of Easy Blog</li>
					</ul>
				</dd>
			</dl>
			</div>';	
}?>