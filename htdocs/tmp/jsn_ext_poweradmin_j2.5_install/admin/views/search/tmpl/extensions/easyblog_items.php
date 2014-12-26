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
				<th width="50%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'Title', 'a.title', $order_Dir, $order); ?></th>
				<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_CONTRIBUTED_IN' ); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_FEATURED' ); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_PUBLISHED' ); ?></th>
				<th width="1%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_FRONTPAGE' ); ?></th>						
				<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_CATEGORY' ); ?></th>
				<th width="10%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_BLOGS_AUTHOR' ); ?></th>
				<th width="10%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_DATE', 'a.created', $order_Dir, $order ); ?></th>
				<th width="3%" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_BLOGS_HITS', 'a.hits', $order_Dir, $order ); ?></th>
				<th width="5%" nowrap="nowrap"><?php echo JText::_( 'COM_EASYBLOG_LANGUAGE' );?></th>
				<th width="20" nowrap="nowrap"><?php echo JHTML::_('grid.sort', 'COM_EASYBLOG_ID', 'a.id', $order_Dir, $order ); ?></th>				
					
				
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
		<?php for ($i = 0; $i < count($this->items); $i++ ){
			$row = $this->items[$i];
			$user		= JFactory::getUser( $row->created_by );			
			$editLink	= JRoute::_('index.php?option=com_easyblog&c=blogs&task=edit&blogid='.$row->id);
			$published 	= JHTML::_('grid.published', $row, $i );
			$date		= JFactory::getDate($row->created, $offset);
			
			$extGroupName = '';
			if( !empty($row->external_group_id) )
			{
				$blog_contribute_source = EasyBlogHelper::getHelper( 'Groups' )->getGroupSourceType();
				$extGroupName			= EasyBlogHelper::getHelper( 'Groups' )->getGroupContribution( $row->id, $blog_contribute_source, 'name' );
				$extGroupName           = $extGroupName . ' (' . ucfirst($blog_contribute_source) . ')';
			}
			
			$contributionDisplay    = '';
			if( $row->issitewide )
			{
				$contributionDisplay    = JText::_('COM_EASYBLOG_BLOGS_WIDE');
			}
			else
			{
				$contributionDisplay    = ( !empty( $extGroupName ) ) ? $extGroupName : $row->teamname;
			}
			
			
		?>
			<tr class="row<?php echo ($i%2); ?>">
				<td>
					<a href="<?php echo $editLink; ?>"><?php echo $row->title; ?></a>					
				</td>
				<td><?php echo $contributionDisplay;  ?></td>
				<td align="center">
					<a class="jgrid"><span class="state <?php echo  EasyBlogHelper::isFeatured( 'post' , $row->id ) ? 'publish' : 'unpublish' ?> " ></span></a>								
				</td>
				<td >
				    <?php if(!$row->published == 2 && !$row->published == 3 && !$row->published == POST_ID_TRASHED) : ?>				   
					<a class="jgrid"><span class="state unpublish " ></span></a>
					<?php else:?>
					<a class="jgrid"><span class="state publish " ></span></a>
					<?php endif; ?>
				</td>
				<td>
				<?php echo JHTML::_( 'grid.boolean' , $i , $row->frontpage , '' , '' ); ?>
				</td>
				<td align="center">					
					<?php echo getCategoryName( $row->category_id);?>					
				</td>
				<td>
				<?php echo $user->name; ?>
				</td>	
				<td>
				<?php echo $date?>
				</td>	
				<td>
				<?php echo $row->hits?>
				</td>
				<td align="center">
				<?php if ($row->language=='*' || empty( $row->language) ){ ?>
					<?php echo JText::alt('JALL', 'language'); ?>
				<?php } else { ?>
					<?php echo $this->escape( $this->getLanguageTitle( $row->language) ); ?>
				<?php } ?>
				</td>		
				<td><?php echo $row->id?></td>
			</tr>
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