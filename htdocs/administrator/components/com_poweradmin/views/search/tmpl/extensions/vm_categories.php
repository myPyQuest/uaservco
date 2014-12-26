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
$mainframe			= JFactory::getApplication();
$option = JRequest::getCmd('option');
$view = JRequest::getCmd('view');

$order		= $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'category_name', 'cmd');
$order_Dir	= $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');

$model = VmModel::getModel('category');

?>
<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>				
			<th align="left" width="50%">
				<?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_CATEGORY_NAME' ) , 'category_name', $order_Dir, $order ); ?>				
			</th>
			<th align="left" width="30%"> 
				<?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_DESCRIPTION' ) , 'category_description', $order_Dir, $order ); ?>				
			</th>
			<th align="left" width="11%">
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_S'); ?>
			</th>			
			<th align="center" width="5%">
				<?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PUBLISHED' ) , 'c.published', $order_Dir, $order ); ?>				
			</th>
			<th width="2%">
				<?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_ID' ) , 'virtuemart_category_id', $order_Dir, $order ); ?>	
			</th>						
			
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
			for ($i = 0; $i < count($this->items); $i++)
			{
				$cat = $this->items[$i];
				
				$published = JHTML::_('grid.published', $cat, $i);
				$editlink = JRoute::_('index.php?option=com_virtuemart&view=category&task=edit&cid=' . $cat->virtuemart_category_id);
				// 			$statelink	= JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $cat->virtuemart_category_id);
				$showProductsLink = JRoute::_('index.php?option=com_virtuemart&view=product&virtuemart_category_id=' . $cat->virtuemart_category_id);
			?>
				<tr>
					<td align="left">						
						<a href="<?php echo $editlink;?>"><?php echo $this->escape($cat->category_name);?></a>
					</td>
					<td align="left">
						<?php echo $cat->category_description; ?>
					</td>
					<td>
						<?php echo  $model->countProducts($cat->virtuemart_category_id);//ShopFunctions::countProductsByCategory($row->virtuemart_category_id);?>
						&nbsp;<a href="<?php echo $showProductsLink; ?>">[ <?php echo JText::_('COM_VIRTUEMART_SHOW');?> ]</a>
					</td>
					<td align="center">
						<?php echo strip_tags($published,"<img>");?>
					</td>
					<td><?php echo $cat->virtuemart_category_id;?></td>
				</tr>	
			<?php }?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>