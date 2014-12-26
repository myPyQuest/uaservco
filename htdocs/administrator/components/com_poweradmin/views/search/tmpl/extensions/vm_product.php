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

$order		= $mainframe->getUserStateFromRequest($option.$view.'filter_order', 'filter_order', 'a.title', 'cmd');
$order_Dir	= $mainframe->getUserStateFromRequest($option.$view.'filter_order_Dir', 'filter_order_Dir', '', 'word');

if(file_exists($_currencyfile = JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'currencydisplay.php')){
	require_once $_currencyfile;
}

$productreviews = VmModel::getModel('ratings');

if(class_exists('CurrencyDisplay')){
	$vendor_model = VmModel::getModel('vendor');
	$vendor_model->setId(1);
	$vendor = $vendor_model->getVendor();
	$currencyDisplay = CurrencyDisplay::getInstance($vendor->vendor_currency,$vendor->virtuemart_vendor_id);
}else{
	$currencyDisplay = 'NA';
}

?>
<form action="<?php echo JRoute::_('index.php?option=com_poweradmin&view=search'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="table table-bordered table-striped">
		<thead>
			<tr>								
				<th width="30%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PRODUCT_NAME' ) , 'product_name', $order_Dir, $order ); ?></th>				
                <th width="20%"><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PRODUCT_CHILDREN_OF' ) , 'product_parent_id', $order_Dir, $order ); ?></th>                
                <th><?php echo JText::_('COM_VIRTUEMART_PRODUCT_MEDIA'); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PRODUCT_SKU' ) , 'product_sku', $order_Dir, $order ); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PRODUCT_PRICE_TITLE' ) , 'product_price', $order_Dir, $order ); ?></th>
				<th><?php echo JText::_( 'COM_VIRTUEMART_CATEGORY'); ?></th>
				<th><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_MANUFACTURER_S' ) , 'mf_name', $order_Dir, $order ); ?></th>
				<th><?php echo JText::_('COM_VIRTUEMART_REVIEW_S'); ?></th>
				<th width="40px" ><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_PUBLISHED' ) , 'published', $order_Dir, $order ); ?></th>
                <th><?php echo JHTML::_('grid.sort', JText::_( 'COM_VIRTUEMART_ID' ) , 'p.virtuemart_product_id', $order_Dir, $order ); ?></th>
						
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
			for ($i = 0; $i < count($this->items); $i++)
			{
				$product = $this->items[$i];
				$published = strip_tags(JHTML::_('grid.published', $product, $i ), '<img>' );
				$link = 'index.php?option=com_virtuemart&view=product&task=edit&virtuemart_product_id='.$product->virtuemart_product_id.'&product_parent_id='.$product->product_parent_id;				
			?>
			<tr>
				<td>
					<?php echo JHTML::_('link', JRoute::_($link), $product->product_name, array('title' => JText::_('COM_VIRTUEMART_EDIT').' '.$product->product_name)); ?>
                </td>
                <td>
                	<?php
                         if ($product->product_parent_id  ) {
							PoweradminModelVmProductSearch::displayLinkToParent($product->product_parent_id);
						}
                    ?>
                </td>
	                <?php					
						$link = JRoute::_('index.php?view=media&virtuemart_product_id='.$product->virtuemart_product_id.'&option=com_virtuemart');
						$product->mediaitems = count($product->virtuemart_media_id);
					?>
				</td>
				<td>				
					<?php echo JHTML::_('link', $link, '<span class="icon-nofloat vmicon vmicon-16-media"></span> ('.$product->mediaitems.')', 'title ="'. JText::_('COM_VIRTUEMART_MEDIA_MANAGER').'" ' );?>
				</td>
				<td><?php echo $product->product_sku; ?></td>
				<td>
					<?php 
					if(!empty($product->product_price) && !empty($product->product_currency) ){
						$product->product_price_display = $currencyDisplay->priceDisplay($product->product_price,(int)$product->product_currency,1,true);
					}	
					echo isset($product->product_price_display)? $product->product_price_display:JText::_('COM_VIRTUEMART_NO_PRICE_SET') 
					?>
				</td>
				<td>
					<?php 
						if(class_exists('shopfunctions')){
							$product->categoriesList = shopfunctions::renderGuiList('virtuemart_category_id','#__virtuemart_product_categories','virtuemart_product_id',$product->virtuemart_product_id,'category_name','#__virtuemart_categories','virtuemart_category_id','category');
						}else{
							$product->categoriesList = 'NA';
						}
						
						echo $product->categoriesList;
					?>
				</td>
				<td><?php echo JHTML::_('link', JRoute::_('index.php?view=manufacturer&task=edit&virtuemart_manufacturer_id[]='.$product->virtuemart_manufacturer_id.'&option=com_virtuemart'), $product->mf_name); ?></td>
				
				<?php 
					$link = 'index.php?option=com_virtuemart&view=ratings&task=listreviews&virtuemart_product_id='.$product->virtuemart_product_id; 
					$product->reviews = $productreviews->countReviewsForProduct($product->virtuemart_product_id);
				?>
				<td><?php echo JHTML::_('link', $link, $product->reviews.' ['.JText::_('COM_VIRTUEMART_REVIEW_FORM_LBL').']'); ?></td>

				<!-- published -->
				<td><?php echo $published; ?></td>
				<td><?php echo $product->virtuemart_product_id; ?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHTML::_('form.token'); ?>
</form>