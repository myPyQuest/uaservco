<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: form_sources.php 15291 2012-08-21 02:32:45Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
global $componentVersion;
$document = JFactory::getDocument();
$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/contentclip.js?v='.$componentVersion);
$objJSNLightCart  = JSNISFactory::getObj('classes.jsn_is_lightcart');
$errorCode		  = $objJSNLightCart->getErrorCode('customer_verification');
$objJSNSource     = JSNISFactory::getObj('classes.jsn_is_source');
$objJSNUtils	  = JSNISFactory::getObj('classes.jsn_is_utils');
$baseURL 		  = $objJSNUtils->overrideURL();
$datas 			  = $objJSNSource->compareLocalSources();
$lists			  = $objJSNSource->getNeedUpdateList($datas);
$random			  = uniqid('').rand(1, 99);
$divTabID         = 'mod-jsncc-sliding-tab-'.$random;
$moduleID         = 'mod-jsncc-container-'.$random;
$buttonPreviousID = 'mod-jsncc-button-previous-'.$random;
$buttonNextID     = 'mod-jsncc-button-next-'.$random;
$colStyle 		  = null;
$itemPerSlide 	  = 3;
$showlistID 	  = JRequest::getVar('cid', array(0));
$showlistID 	  = $showlistID[0];
$uri			  = JFactory::getURI();
$return 		  = base64_encode($uri->toString());
$popup			  = JRequest::getInt('popup', 1);
$session 		  = JFactory::getSession();
$identifier		  = md5('jsn_imageshow_downloasource_identify_name');
$tmpIdentifyName  = $session->get($identifier, '', 'jsnimageshowsession');
$tmpl			  = JRequest::getVar('tmpl','');
$tmpl			  = ($tmpl!='')?'&tmpl='.$tmpl:'';
if(count($lists))
{
	$modContentClipsSlidingTab = 'modContentClipsSlidingTab'.$random;
?>
<script type="text/javascript">
function JSNAutoOpenModalWindow()
{
	var JSNISShowlistSourceFormAutoWindow = new jQuery.JSNISUIWindow('<?php echo JURI::root();?>administrator/index.php?option=com_imageshow&controller=showlist&task=profile&layout=form_profile&tmpl=component&source_identify=<?php echo $tmpIdentifyName; ?>&image_source_type=external&showlist_id=<?php echo $showlistID;?>&return=<?php echo $return;?>',{
		width: 450,
		height: 500,
		title: "<?php echo JText::_('SHOWLIST_PROFILE_SELECT_IMAGE_SOURCE_PROFILE', true);?>",
		scrollContent: true,
		buttons: {
			'Save': function (){
				if(typeof gIframeFunc != 'undefined')
				{
					gIframeFunc();
				}
				else
				{
					console.log('Iframe function not available')
				}
			},
			'Cancel': function (){
				jQuery(this).dialog('close');
			}
		}
	});
}

function JSNAutoSelectSourceAfterInstallion()
{
	var url = 'index.php?option=com_imageshow&controller=showlist&task=onSelectSource&image_source_type=internal&source_identify=<?php echo $tmpIdentifyName; ?>&showlist_id=<?php echo $showlistID;?><?php echo $tmpl; ?>';
	window.location=url;
}
</script>
<div class="jsn-showlist-source-select">
<h3 class="jsn-section-header"><?php echo JText::_('SHOWLIST_SELECT_IMAGE_SOURCE'); ?></h3>
<div id="<?php echo $moduleID; ?>">
<?php if (count($lists) > $itemPerSlide) { ?>
<script type="text/javascript" charset="utf-8">
	window.addEvent('domready', function () {
		var <?php echo $modContentClipsSlidingTab; ?> = new JSNISContentClip('', '<?php echo $divTabID; ?>', '<?php echo $buttonPreviousID; ?>', '<?php echo $buttonNextID; ?>', {slideEffect: {duration: 300}});
		$('<?php echo $buttonPreviousID; ?>').addEvent('click', <?php echo $modContentClipsSlidingTab; ?>.previous.bind(<?php echo $modContentClipsSlidingTab; ?>));
		$('<?php echo $buttonNextID; ?>').addEvent('click', <?php echo $modContentClipsSlidingTab; ?>.next.bind(<?php echo $modContentClipsSlidingTab; ?>));
		window.addEvent('resize', <?php echo $modContentClipsSlidingTab; ?>.recalcWidths.bind(<?php echo $modContentClipsSlidingTab; ?>));
	});
</script>
<?php } ?>
<div class="jsn-showlist-source-slide jsn-showlist-source-classic-bright">
	<div class="navigation-button clearafter">
		<span id="<?php echo $buttonPreviousID; ?>" class="jsn-showlist-source-slide-arrow <?php echo (count($lists) > $itemPerSlide)?'slide-arrow-pre':'';?>"></span>
		<span id="<?php echo $buttonNextID; ?>" class="jsn-showlist-source-slide-arrow <?php echo (count($lists) > $itemPerSlide)?'slide-arrow-next':'';?>"></span>
	</div>
	<div id="<?php echo $divTabID; ?>" class="sliding-content">
    	<div>
        <?php
            $index = 0;
            $j	   = 0;
            $itemLayout = 'horizontal';

            if(count($lists) < $itemPerSlide)
            {
                $itemPerSlide = count($lists);
            }
            if($itemLayout == 'horizontal')
            {
	            $objContentClip = JSNISFactory::getObj('classes.jsn_is_contentclip');
				$colStyle		= $objContentClip->calColStyle($itemPerSlide);
            }

			$countLists = count($lists);
            for($i = 0; $i < $countLists; $i++)
            {
				$text = JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_IMAGE_SOURCE');
				$rows = $lists[$i];
                $updateElementID = 'jsn-imagesource-update-id-'.$i;
				if ($i==$countLists-1 || ($index+1)%$itemPerSlide == 0) $itemOrderClass = ' last';
					else $itemOrderClass = '';
        ?>
        	<?php if($index%$itemPerSlide == 0) { $j = 0; ?>
        	<div class="sliding-pane <?php echo $itemLayout; ?> clearafter">
        	<?php } ?>
        		<div class="jsn-item jsn-item<?php echo $colStyle[$j]['class']; ?><?php echo $itemOrderClass; ?>" style="width:<?php echo $colStyle[$j]['width']; ?>">
					<?php
						$objInfoUpdate = new stdClass();
						$objInfoUpdate->identify_name		= $rows->identified_name;
						$objInfoUpdate->edition				= '';
						$objInfoUpdate->update 				= true;
						$objInfoUpdate->install 			= false;
						$objInfoUpdate->error_code 			= $errorCode;
						$objInfoUpdate->wait_text 			= JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_WAIT_TEXT', true);
						$objInfoUpdate->process_text 		= JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_PROCESS_TEXT', true);
						$objInfoUpdate->download_element_id	= $updateElementID;
						$objInfoUpdate = json_encode($objInfoUpdate);
						$addHTML = '';
					?>
        			<?php
        			if ($rows->needUpdate && $rows->identified_name != 'folder')
					{
						$actionLink			= 'index.php?option=com_imageshow&controller=updater&return='.$return;
						$actionClass		= ' jsn-showlist-imagesource-update ';
						$actionRel			= '';
						$onclick			= 'target="_blank"';
						$overlayTextClass	= 'jsn-imagesource-update-overlay-download';
						$text				= JText::_('SHOWLIST_IMAGE_SOURCE_UPDATE_IMAGE_SOURCE');
						$itemClass			= ' jsn-item-container ';
					}
					else if ($rows->type == 'external')
					{
						$actionLink			= 'index.php?option=com_imageshow&controller=showlist&task=profile&layout=form_profile&tmpl=component&source_identify='.$rows->identified_name.'&image_source_type='.$rows->type.'&showlist_id='.(int)$showlistID.'&return='.$return;
						$actionClass		= 'jsn-is-form-modal';
						$actionRel			= '{"size": {"x": 450, "y": 500}}';
						$onclick			= '';
						$overlayTextClass	= '';
						$itemClass			= ' jsn-item-container ';
						if ($tmpIdentifyName != '' && $tmpIdentifyName == $rows->identified_name && $popup)
						{
							echo '<script type="text/javascript">';
							echo 'window.addEvent("domready", function() {';
							echo 'JSNAutoOpenModalWindow();';
							echo '});';
							echo '</script>';
						}
					}
					else if (isset($rows->localInfo->componentInstall) && $rows->localInfo->componentInstall == false)
					{
						$actionLink			= '#';
						$actionClass		= 'jsn-showlist-imagesource-miss-component';
						$actionRel			= '';
						$onclick			= '';
						$overlayTextClass	= 'jsn-imagesource-install-overlay-miss-component';
						$addHTML			= '<p class="jsn-imagesource-install-overlay-text jsn-imagesource-install-miss-component">'. JText::sprintf('SHOWLIST_IMAGE_SOURCE_INSTALL_MISS_COMPONENT', $rows->localInfo->define->component_link) .'</p>';
						$itemClass			= '';
					}
					else
					{
						$actionLink			= 'index.php?option=com_imageshow&controller=showlist&task=onSelectSource&image_source_type='.$rows->type.'&source_identify='.$rows->identified_name.'&showlist_id='.(int)$showlistID.$tmpl;
						$actionClass		= '';
						$actionRel			= '';
						$onclick			= '';
						$overlayTextClass	= '';
						$itemClass			= ' jsn-item-container ';
						if ($tmpIdentifyName != '' && $tmpIdentifyName == $rows->identified_name && $popup)
						{
							echo '<script type="text/javascript">';
							echo 'window.addEvent("domready", function() {';
							echo 'JSNISImageShow._openModal();';
							echo 'JSNAutoSelectSourceAfterInstallion();';
							echo '});';
							echo '</script>';
						}
					}
					?>
					<div class="jsn-item-inner<?php echo $itemClass;?>">
						<a href="<?php echo $actionLink; ?>" class="<?php echo $actionClass; ?>" <?php echo $onclick; ?> rel='<?php echo $actionRel; ?>'>
							<div class="jsn-imagesource-install-overlay <?php echo $overlayTextClass; ?>">
								<span class="jsn-imagesource-install-loading"><img src="<?php echo dirname($baseURL).'/administrator/components/com_imageshow/assets/images/ajax-loader-lite.gif';?>"/></span>
								<p class="jsn-imagesource-install-overlay-text jsn-imagesource-install-imagesource"><?php echo $text;?></p>
								<p id="<?php echo $updateElementID; ?>" class="jsn-imagesource-install-overlay-text jsn-imagesource-install-download"><?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_DOWNLOAD');?><br/><span></span></p>
								<p class="jsn-imagesource-install-overlay-text jsn-imagesource-install-installing"><?php echo JText::_('SHOWLIST_IMAGE_SOURCE_INSTALL_INSTALLING');?></p>
							</div>
							<img class="jsn-imagesource-install-thumb" src="<?php echo ($rows->identified_name == 'folder') ? dirname($baseURL).'/'.$rows->thumbnail : $rows->thumbnail ;?>"/>
						</a>
						<?php echo $addHTML; ?>
					</div>
					<div class="jsn-source-name">
					<?php
						echo ($rows->name) ? $rows->name : JText::_('N/A');
					?>
					</div>
        	    </div>
        	<?php
        	$index++;
        	if($index%$itemPerSlide == 0) {
        	?>
        		</div>
        	<?php
        	}
        	?>
        <?php
        		$j++;
            }
        ?>
        </div>
	</div>
</div>
<?php
if(count($lists)%3 != 0 && $itemPerSlide%3 == 0)
{
   echo '</div>';
}
?>
<?php
if(count($lists)%3 == 0 && $itemPerSlide%3 != 0 && $itemPerSlide != 1 )
{
	echo '</div>';
}
//if ($itemPerSlide == 3) echo '</div>';
?>
</div>
</div>
<?php
}
?>
<?php $session->set($identifier, '', 'jsnimageshowsession'); ?>
