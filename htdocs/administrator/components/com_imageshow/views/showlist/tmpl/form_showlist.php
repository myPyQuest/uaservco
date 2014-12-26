<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_showlist.php 15339 2012-08-21 11:15:01Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
$objJSNUtils 	= JSNISFactory::getObj('classes.jsn_is_utils');
JHTML::_('behavior.tooltip');
JToolBarHelper::title( JText::_('JSN_IMAGESHOW').': '.JText::_('SHOWLIST_SHOWLIST_SETTINGS'), 'showlist-settings' );
JToolBarHelper::apply();
JToolBarHelper::save();
JToolBarHelper::cancel('cancel', 'JTOOLBAR_CLOSE');
JToolBarHelper::divider();
$objJSNUtils = JSNISFactory::getObj('classes.jsn_is_utils');
$objJSNUtils->callJSNButtonMenu();
$showListID = (int) $this->items->showlist_id;
$task = JRequest::getVar('task');
$user = JFactory::getUser();
$showlistID = JRequest::getVar('cid');
$showlistID = $showlistID[0];
$tmpl	= JRequest::getVar('tmpl','');
$tmpl	= ($tmpl!='')?'&tmpl='.$tmpl:'';
?>
<script language="javascript" type="text/javascript">
	function submitform(pressbutton)
	{
		if (pressbutton) {
			document.adminForm.task.value = pressbutton;
		}
		document.adminForm.submit();
	}
	function saveform(){
		<?php if (!$showlistID) {?>
		document.adminForm.mainSite.value = 'false';
		<?php }?>
		Joomla.submitbutton('apply');
	}
	Joomla.submitbutton = function(pressbutton)
	{
		var form 		= document.adminForm;
		var link  		= form.showlist_link.value;

		var task 		= '<?php echo $task; ?>';
		if (pressbutton == 'cancel')
		{
			submitform( pressbutton );
			return;
		}

		if (form.showlist_title.value == "")
		{
			alert( "<?php echo JText::_('SHOWLIST_SHOWLIST_MUST_HAVE_A_TITLE', true); ?>");
			jQuery('#jsn_is_showlist_tabs').tabs({ selected: 0 });
			jQuery('#showlist_title').focus();
			return;
		}
		else
		{
			submitform(pressbutton);
		}
	}

	function selectArticle_auth_article_id(id, title, catid)
	{
		document.id("aid_name").value = title;
		document.id("aid_id").value = id;
		jQuery.closeAllJSNWindow();
	}

	function jInsertFieldValue(value,id)
	{
		var old_id = document.getElementById(id).value;
		if (old_id != id)
		{
			document.getElementById(id).value = value;
		}
	}

	document.addEvent('domready', function()
	{
	});

	(function($){
	$(document).ready(function () {
		});
	})(jQuery);
	parent.gIframeFunc = saveform;
</script>
<?php
$objJSNMsg = JSNISFactory::getObj('classes.jsn_is_displaymessage');
echo $objJSNMsg->displayMessage('SHOWLISTS');
?>
<div class="jsn-showlist-details jsn-section jsn-bootstrap">
<form name="adminForm" id="adminForm" action="index.php?option=com_imageshow&controller=showlist" method="post" class="form-horizontal">
	<div class="jsn-section-content" id="jsn-showlist-detail-slide">
		<div class="row-fluid show-grid">
			<div class="span6">
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_GENERAL'); ?></legend>
					<?php
						if($showListID != 0){
					?>
					<div class="control-group">
						<label class="control-label"><?php echo JText::_('ID');?></label>
						<div class="controls">
							<input type="text" value="<?php echo $showListID; ?>" class="readonly input-mini" size="10" readonly="readonly" aria-invalid="false">
						</div>
					</div>
					<?php
						}
					?>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_SHOWLIST'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_SHOWLIST')); ?>"><?php echo JText::_('SHOWLIST_TITLE_SHOWLIST');?></label>
						<div class="controls">
							<input class="jsn-input-xlarge-fluid" type="text" value="<?php echo htmlspecialchars($this->items->showlist_title);?>" name="showlist_title" id="showlist_title"/>
							<span class="help-inline">*</span>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_PUBLISHED'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_PUBLISHED')); ?>"><?php echo JText::_('SHOWLIST_TITLE_PUBLISHED');?></label>
						<div class="controls">
							<?php echo $this->lists['published']; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_ORDER'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_ORDER')); ?>"><?php echo JText::_('SHOWLIST_TITLE_ORDER');?></label>
						<div class="controls">
							<?php echo $this->lists['ordering']; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_HITS'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_HITS')); ?>"><?php echo JText::_('SHOWLIST_HITS');?></label>
						<div class="controls">
							<input class="input-mini" type="text" name="hits" value="<?php echo ($this->items->hits!='')?$this->items->hits:0;?>" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_DESCRIPTION'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_DESCRIPTION')); ?>"><?php echo JText::_('SHOWLIST_TITLE_DESCRIPTION');?></label>
						<div class="controls">
							<textarea class="jsn-input-xlarge-fluid" name="description" rows="8"><?php echo $this->items->description; ?></textarea>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_TITLE_LINK'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_DES_LINK')); ?>"><?php echo JText::_('SHOWLIST_LINK');?></label>
						<div class="controls">
							<input class="jsn-input-xlarge-fluid" type="text" name="showlist_link" value="<?php echo htmlspecialchars($objJSNUtils->decodeUrl($this->items->showlist_link)); ?>" />
						</div>
					</div>
				</fieldset>
			</div>
			<div class="span6">
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_IMAGES_DETAILS_OVERRIDE'); ?></legend>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_TITLE_DESC')); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_TITLE');?></label>
						<div class="controls">
							<?php echo $this->lists['overrideTitle']; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_DESCRIPTION'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_OVERRIDE_DESCRIPTION_DESC')); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_DESCRIPTION');?></label>
						<div class="controls">
							<?php echo $this->lists['overrideDesc']; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?>::<?php echo JText::_('SHOWLIST_OVERRIDE_LINK_DESC'); ?>"><?php echo JText::_('SHOWLIST_OVERRIDE_LINK');?></label>
						<div class="controls">
							<?php echo $this->lists['overrideLink']; ?>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_ACCESS_PERMISSION'); ?></legend>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?>::<?php echo JText::_('SHOWLIST_DES_ACCESS_LEVEL'); ?>"><?php echo JText::_('SHOWLIST_TITLE_ACCESS_LEVEL');?></label>
						<div class="controls">
							<select name="access" class="inputbox">
								<?php echo JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->items->access);?>
							</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?>::<?php echo JText::_('SHOWLIST_DES_AUTHORIZATION_MESSAGE'); ?>"><?php echo JText::_('SHOWLIST_TITLE_AUTHORIZATION_MESSAGE');?></label>
						<div class="controls">
							<?php echo $this->lists['authorizationCombo']; ?>
							<div style="<?php echo ($this->items->authorization_status == 1)?'display:"";':'display:none;'; ?>" id="wrap-aut-article">
								<span class="button-wrapper"><input class="input-large jsn-readonly" type="text" id="aid_name" value="<?php echo @$this->items->aut_article_title;?>" readonly="readonly" /></span>
								<span class="button-wrapper"><a class="btn jsn-is-view-modal" rel='{"size": {"x": 650, "y": 380}}' href="index.php?option=com_content&view=articles&layout=modal&tmpl=component&function=selectArticle_auth_article_id" title="Select Content" name="<?php echo JText::_('SHOWLIST_IMAGES_SELECT_ARTICLE');?>"><?php echo JText::_('SHOWLIST_SELECT');?></a></span>
								<input type="hidden" id="aid_id" name="alter_autid" value="<?php echo $this->items->alter_autid;?>" />
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<legend><?php echo JText::_('SHOWLIST_MISC'); ?></legend>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_IMAGES_LOADING_ORDER_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_IMAGES_LOADING_ORDER_DESC')); ?>"><?php echo JText::_('SHOWLIST_IMAGES_LOADING_ORDER_TITLE');?></label>
						<div class="controls">
							<?php echo $this->lists['imagesLoadingOrder']; ?>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label editlinktip hasTip" title="<?php echo htmlspecialchars(JText::_('SHOWLIST_SHOW_EXIF_TITLE'));?>::<?php echo htmlspecialchars(JText::_('SHOWLIST_SHOW_EXIF_DESC')); ?>"><?php echo JText::_('SHOWLIST_SHOW_EXIF_TITLE');?></label>
						<div class="controls">
							<?php echo $this->lists['showExifData']; ?>
						</div>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
	<input type="hidden" name="cid[]" value="<?php echo (int) $this->items->showlist_id;?>" />
	<input type="hidden" name="option" value="com_imageshow" />
	<input type="hidden" name="controller" value="showlist" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" id="redirectLink" name="redirectLink" value="<?php echo ((int) $this->items->showlist_id)?'index.php?option=com_imageshow&controller=showlist&task=edit&cid[]='.(int) $this->items->showlist_id.$tmpl:'';?>" />
	<input type="hidden" id="mainSite" name="mainSite" value="<?php
	if ($task == 'add')
	{
		echo 'true';
	}
	else
	{
		echo ($tmpl!='')?'false':'true';
	}
	?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="tmpl" value="<?php echo $tmpl; ?>" />
</form>
</div>