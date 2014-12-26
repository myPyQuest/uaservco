<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: default.php 14476 2012-07-27 09:49:49Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWCASE_SHOWCASES_MANAGER'), 'showcase' );
JToolBarHelper::addNew();
JToolBarHelper::editList();
JToolBarHelper::custom( 'copy', 'copy.png', 'copy_f2.png', 'Copy', true );
JToolBarHelper::divider();
JToolBarHelper::publishList();
JToolBarHelper::unpublishList();
JToolBarHelper::divider();
JToolBarHelper::deleteList();
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$ordering	= true;
//echo $ordering;
?>
<?php
	$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
	echo $objJSNMsg->displayMessage('SHOWCASES');
?>
<div id="jsn-showcases" class="jsn-page-list">
	<div class="jsn-bootstrap">
		<form action="index.php?option=com_imageshow&controller=showcase" method="post" name="adminForm" class="form-inline">
		<div class="jsn-fieldset-filter">
			<fieldset>
				<div class="pull-left jsn-fieldset-search">
					<label class="control-label"><?php echo JText::_('FILTER'); ?> :</label>
					<input type="text" name="showcase_title" id="showcase_title" value="<?php echo $this->lists['showcaseTitle'];?>" class="input-xlarge"/>
					<button class="btn" onclick="this.form.submit();"><?php echo JText::_('GO'); ?></button>
					<button class="btn" onclick="document.getElementById('filter_state').value=''; document.getElementById('showcase_title').value=''; this.form.submit();"><?php echo JText::_('RESET'); ?></button>
				</div>
				<div class="pull-right jsn-fieldset-select">
					<?php echo $this->lists['state'];?>
				</div>
			</fieldset>
		</div>
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th width="2%" class="center">#</th>
					<th width="2%" class="center">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
					</th>
					<th nowrap="nowrap" width="70%">
						<?php echo JHTML::_('grid.sort',  JText::_('SHOWCASE_TITLE'), 'showcase_title', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th class="center" nowrap="nowrap" width="5%">
						<?php echo JHTML::_('grid.sort', JText::_('SHOWCASE_PUBLISHED'), 'published', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
					<th width="10%" nowrap="nowrap" class="center">
						<?php echo JHTML::_('grid.sort',   'Order', 'ordering', $this->lists['order_Dir'], $this->lists['order'] ); ?>
						<?php
							if ($ordering) echo JHTML::_('grid.order',  $this->items );
						?>
					</th>
					<th width="10%" nowrap="nowrap" class="center">
						<?php echo JText::_('SHOWCASE_THEME'); ?>
					</th>
					<th width="5%" nowrap="nowrap" class="center">
						<?php echo JHTML::_('grid.sort', JText::_('ID'), 'showcase_id', $this->lists['order_Dir'], $this->lists['order'] ); ?>
					</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="7" align="center">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>
			<tbody>
			<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row 			= &$this->items[$i];
				$objJSNShowcaseTheme = JSNISFactory::getobj('classes.jsn_is_showcasetheme');
				$themeProfile   = $objJSNShowcaseTheme->getThemeProfile($row->showcase_id);
				$link 			= JRoute::_( 'index.php?option=com_imageshow&controller=showcase&task=edit&cid[]='. $row->showcase_id );
				$checked 		= JHTML::_('grid.id', $i, $row->showcase_id );
				$published 		= JHTML::_('grid.published', $row, $i );

				?>
				<tr class="<?php echo "row$k"; ?>">
					<td class="center">
						<?php echo $this->pagination->getRowOffset( $i ); ?>
					</td>
					<td class="center">
						<?php echo $checked; ?>
					</td>
					<td>
						<span class="editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWCASE_EDIT_SHOWCASE'));?>::<?php echo $this->escape($row->showcase_title); ?>">
							<a href="<?php echo $link; ?>">
							<?php echo $this->escape($row->showcase_title); ?>
							</a>
						</span>
					</td>
					<td class="center">
						<?php
							echo $published;
						?>
					</td>
					<td class="jsn-list-order center">
		                <div class="input-prepend">
							<span class="add-on"><?php echo $this->pagination->orderUpIcon( $i, true, 'orderup', 'Move Up', $ordering ); ?></span>
							<span class="add-on"><?php echo $this->pagination->orderDownIcon( $i, $n, true, 'orderdown', 'Move Down', $ordering ); ?></span>
							<?php $disabled = $ordering ?  '' : 'disabled="disabled"'; ?>
							<input type="text" name="order[]" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?> class="jsn-input-small-fluid" />
						</div>
		            </td>
		            <td class="center">
						<?php
							$part = explode('theme', @$themeProfile->theme_name);
							if (isset($part[1]))
							{
								echo 'Theme '.ucfirst($part[1]);
							}
							else
							{
								echo JText::_('N/A');
							}
						?>
					</td>
					<td class="center">
						<?php
							echo $row->showcase_id;
						?>
					</td>
				</tr>
				<?php
				$k = 1 - $k;
			}
			?>
			</tbody>
			</table>
			<input type="hidden" name="option" value="com_imageshow" />
			<input type="hidden" name="controller" value="showcase" />
			<input type="hidden" name="task" value="" />
			<input type="hidden" name="boxchecked" value="0" />
			<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
			<?php echo JHTML::_( 'form.token' ); ?>
		</form>
	</div>
</div>
<?php include_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'footer.php'); ?>