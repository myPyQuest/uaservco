<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: flex.php 8411 2011-09-22 04:45:10Z trungnq $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
$sourceType = JRequest::getVar('sourceType');
$baseurl 	= ($sourceType=='external')?'':JURI::root();
?>
<script type="text/javascript">
function jsnGetMenuItems(id, title, object,link)
{
	var id = '#item_link';
	if (imageLinkID !='')
	{
		 id = id + '_' + imageLinkID;
	}
	jQuery(id).val(link);
	JSNISLinkWindow.close();
}

function jsnGetArticle(id, title, catid, object,link)
{
	var id = '#item_link';
	if (imageLinkID !='')
	{
		 id = id + '_' + imageLinkID;
	}
	jQuery(id).val(link);
	JSNISLinkWindow.close();
}
</script>
<div id="edit-item-details" class="jsn-bootstrap">
	<form name="editForm" method="post" action="" id="jsn-is-link-image-form">
	<?php
	$countImage = count($this->image);
	if($countImage > 1)
	{
	?>
	<div class="jsn-section-striped">
	<?php
		for($i=0; $i<count($this->image); $i++)
		{
	?>
		<div id="edit-item-details-multiple">
			<div class="jsn-item-details">
				<div class="control-group pull-left">
					<div class="thumbnail jsn-item-thumbnail">
						<img class="jsn-box-shadow-light" src="<?php echo $baseurl.$this->image[$i]->image_small;?>" name="image" />
					</div>
				</div>
				<div class="control-group">
					<input type="text" class="jsn-input-large-fluid title" name="title[]" id="item-title" value="<?php echo htmlspecialchars($this->image[$i]->image_title);?>" />
					<input type="hidden" name="originalTitle[]" value="<?php echo htmlspecialchars($this->image[$i]->image_title);?>"/>
				</div>
				<div class="control-group">
					<textarea rows="3" class="jsn-input-large-fluid description" id="item-description" name="description[]"><?php echo htmlspecialchars($this->image[$i]->image_description);?></textarea>
					<input type="hidden" name="originalDescription[]" value="<?php echo htmlspecialchars($this->image[$i]->image_description);?>"/>
				</div>
				<div class="control-group">
					<div class="input-append">
						<input type="text" class="link" id="item_link_<?php echo $this->image[$i]->image_id;?>" value="<?php echo $this->image[$i]->image_link;?>" name="image_link[]"/><input class="btn select-link-edit" type="button" name="<?php echo $this->image[$i]->image_id;?>" value="..." />
					</div>
				</div>
				<input type="hidden" name="originalLink[]" value="<?php echo $this->image[$i]->image_link;?>"/>
				<input type="hidden" name="imageID[]" value="<?php echo $this->image[$i]->image_id;?>" />
				<input type="hidden" name="image_extid[]" value="<?php echo $this->image[$i]->image_extid;?>" />
				<div class="clearbreak"></div>
			</div>
		</div>
	<?php
		}
	?>
		<input type="hidden" name="numberOfImages" value="<?php echo count($this->image);?>"/>
		<input type="hidden" name="showlistID" value="<?php echo $this->image[0]->showlist_id ;?>" />
		<input type="hidden" name="option" value="com_imageshow" />
		<input type="hidden" name="controller" value="image" />
		<input type="hidden" name="task" value="apply" />
	</div>
	<?php
	}
	else
	{
	?>
		<div id="edit-item-details-single">
			<div class="jsn-item-details">
				<div class="control-group">
					<div class="thumbnail jsn-item-thumbnail">
						<img class="jsn-box-shadow-light" src="<?php echo $baseurl.$this->image->image_small;?>" name="image" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('SHOWLIST_EDIT_IMAGE_TITLE');?></label>
					<div class="controls">
						<input type="text" class="jsn-input-xxlarge-fluid title" name="title" id="item-title" value="<?php echo htmlspecialchars($this->image->image_title);?>" />
						<input type="hidden" name="originalTitle" value="<?php echo htmlspecialchars($this->image->image_title);?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('SHOWLIST_EDIT_IMAGE_DESCRIPTION');?></label>
					<div class="controls">
						<textarea class="jsn-input-xxlarge-fluid description" rows="5" id="item-description" name="description"><?php echo htmlspecialchars($this->image->image_description);?></textarea>
						<input type="hidden" name="originalDescription" value="<?php echo htmlspecialchars($this->image->image_description);?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label"><?php echo JText::_('SHOWLIST_EDIT_IMAGE_LINK');?></label>
					<div class="controls">
						<div class="input-append">
							<input type="text" id="item_link" class="link" value="<?php echo $this->image->image_link;?>" name="link" /><input class="btn select-link-edit" type="button" name="" value="..." />
						</div>
						<input type="hidden" name="originalLink" value="<?php echo $this->image->image_link;?>" />
					</div>
				</div>
			</div>
		</div>
		<input type="hidden" name="numberOfImages" value="1"/>
		<input type="hidden" name="option" value="com_imageshow" />
		<input type="hidden" name="controller" value="image" />
		<input type="hidden" name="task" value="apply" />
		<input type="hidden" name="imageID" value="<?php echo $this->image->image_id;?>" />
		<input type="hidden" name="image_extid" value="<?php echo $this->image->image_extid;?>" />
		<input type="hidden" name="showlistID" value="<?php echo $this->image->showlist_id ;?>" />
	<?php }?>
	</form>
</div>
