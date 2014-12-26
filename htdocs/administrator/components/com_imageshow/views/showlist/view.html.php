<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 15638 2012-08-29 09:58:18Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'/elements/html');
class ImageShowViewShowlist extends JView
{
		function display($tpl = null)
		{
			global $mainframe, $option, $componentVersion;
			$images = array();
			$catid  = 0;
			$tmpjs = '';
			$albumID = '';
			$user     = JFactory::getUser();
			$objJSNImages = JSNISFactory::getObj('classes.jsn_is_images');
			$objJSNJSLanguages = JSNISFactory::getObj('classes.jsn_is_jslanguages');
			JHTML::_('behavior.modal', 'a.modal');
			$document = JFactory::getDocument();
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/layout-default-latest.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/jquery-ui-1.8.16.custom.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/view.showlist.css?v='.$componentVersion);

			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/utils.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installimagesources.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installdefault.js?v='.$componentVersion);

			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-ui.custom.min.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.layout-latest.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.contextmenu.r2.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.cookie.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-treeview.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/window.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/jquery.imageshow.js?v='.$componentVersion);

			$jsCode = "
				var baseUrl = '".JURI::root()."';
				var gIframeFunc = undefined;
				(function($){
				$(document).ready(function () {
						$('.jsn-is-view-modal').click(function(event){
							event.preventDefault();
							var data = jQuery.parseJSON($(this).attr('rel'));
							var link = $(this).attr('href');
							var title = $(this).attr('name');
							var JSNISShowlistSourceViewWindow = new $.JSNISUIWindow(baseUrl+'administrator/'+link,{
									width: data.size.x,
									height: data.size.y,
									title: title,
									scrollContent: true,
									buttons: {
										'Cancel': function (){
											$(this).dialog('close');
										}
									}
							});
						});
						$('.jsn-is-form-modal').click(function(event){
							event.preventDefault();
							var data = jQuery.parseJSON($(this).attr('rel'));
							var link = $(this).attr('href');
							var JSNISShowlistSourceFormWindow = new $.JSNISUIWindow(baseUrl+'administrator/'+link,{
									width: data.size.x,
									height: data.size.y,
									title: '".JText::_('SHOWLIST_PROFILE_SELECT_IMAGE_SOURCE_PROFILE', true)."',
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
											$(this).dialog('close');
										}
									}
							});
						});
					});
				})(jQuery);
			  ";
			$document->addScriptDeclaration($jsCode);

			$model  = $this->getModel();
			$lists 	= array();
			$items 	= $this->get('data');

			$countImage = 0;

			if (isset($items->image_source_name) && $items->image_source_name != '') {

				$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/image_selector.css?v='.$componentVersion);
				$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/lang.js?v='.$componentVersion);
				$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imagegrid.js?v='.$componentVersion);
				$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/utils.js?v='.$componentVersion);
				$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/tree.js?v='.$componentVersion);
				$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.topzindex.js?v='.$componentVersion);

				$imageSource = JSNISFactory::getSource($items->image_source_name, $items->image_source_type, $items->showlist_id);

				$objImages 	 = JSNISFactory::getObj('classes.jsn_is_images');
				$cat 		 = $objImages->getAllCatShowlist($items->showlist_id);

				if(!empty($cat)){
					$catid		 = $cat[0];
					$config		 = array('album'=>$catid);
					$sync 		 = $imageSource->getShowlistMode();
					if($sync=='sync'){
						$images 	 = $imageSource->loadImages($config);
					}else{
						$images 	 = $imageSource->loadImages($config);
					}
				}

				$document = JFactory::getDocument();
				if($imageSource->getShowlistMode()=='sync'){
					$rmcat = 'JSNISImageGrid.removecatSelected();';
				}else{
					$rmcat = '';
				}

				$totalimage = count($images);

				if ($totalimage)
				{
					$imageInfo = (array) @$images->images[0];
					$albumID = @$imageInfo['album_extid'];
					//$tmpjs = "JSNISImageGrid.reloadImageSource('".$albumID."');";
				}

				$js_code = "
					var baseUrl = '".JURI::root()."';
					var VERSION_EDITION_NOTICE = '".JText::_('VERSION_EDITION_NOTICE')."';
					function reshowtree(obj,e)
					{
						if(e>=1){
							if(e==1){
								obj.parent().parent().parent().find('>ul').css('display','block');
							}
							obj.parent().parent().css('display','block');
							reshowtree(obj.parent().parent());
						}
					}
					var JSNISImageGrid;
					var initImageGrid = false;
					(function($){
						$('#dialogbox:ui-dialog').dialog( 'destroy');
						$('#dialogbox2:ui-dialog').dialog( 'destroy');
						$(document).ready(function () {
							$('#jsn_is_showlist_tabs').tabs({
								show: function(event, ui){
									if(ui.index == 1 && !initImageGrid){
										JSNISImageGrid = $.JSNISImageGridGetInstaces({
											showListID   : '".$items->showlist_id."',
											sourceName   : '".$items->image_source_name."',
											sourceType   : '".$items->image_source_type."',
											selectMode   : '".$imageSource->getShowlistMode()."',
											pagination	 : '".$imageSource->_source['sourceDefine']->pagination."',
											layoutHeight : 500,
											layoutWidth  : '100%'
										});
										".$rmcat."
										".$tmpjs."
										JSNISImageGrid.initialize();
										if(!$('.media-item').length && !$('.jtree-selected',$('#images')).length){
											JSNISImageGrid.cookie.set('rate_of_west', 58 );
											JSNISImageGrid.UILayout.sizePane('west', '58%');
										}

										// process only show level 2 of tree

										$('#jsn-jtree-categories ul li:first').removeClass().addClass('jsn-jtree-open');
										$('#jsn-jtree-categories ul li ul li.secondchild').each(function(){
											if(!$(this).hasClass('jsn-jtree-children')){
												$(this).removeClass('secondchild').addClass('jsn-jtree-close');
											}
											$(this).find('ul').css('display','none');
										});
										// expand all parent of current 'li'
										$('#jsn-jtree-categories ul li ul').find('li.catselected').parent().parent().find('>ul').each(function(e){
											$(this).css('display','block');
											reshowtree($(this),e);
										});

										$('#jsn-jtree-categories ul li ul').find('li.catselected').parents().each(function(){
											$(this).removeClass('jsn-jtree-close').addClass('jsn-jtree-open');
											reshowtree($(this));
										});

										$('#jsn-jtree-categories ul li ul').find('li.catselected').parents().each(function(){
											$(this).css('display','block');
										})
										initImageGrid = true;
										JSNISImageGrid.overrideSaveEvent();
										JSNISImageShow.getScriptCheckThumb(".$items->showlist_id.");
									}
								}
							});


						});
					})(jQuery);
				  ";
				$document->addScriptDeclaration($objJSNJSLanguages->loadLang());
				$document->addScriptDeclaration($js_code);
			 	$this->assignRef('selectMode',$imageSource->getShowlistMode());
			}

			if($items->showlist_id != 0 && $items->showlist_id != '')
			{
				if($objJSNImages->checkImageLimition($items->showlist_id))
				{
					$msg = JText::_('SHOWLIST_YOU_HAVE_REACHED_THE_LIMITATION_OF_10_IMAGES_IN_FREE_EDITION');
					JError::raiseNotice(100, $msg);
				}

				$countImage = $objJSNImages->countImagesShowList($items->showlist_id);
				$countImage = $countImage[0];
			}

			$authorizationCombo = array(
				'0' => array('value' => '0',
				'text' => JText::_('SHOWLIST_NO_MESSAGE')),
				'1' => array('value' => '1',
				'text' => JText::_('SHOWLIST_JOOMLA_ARTICLE'))
			);

			$imagesLoadingOrder= array(
				'0' => array('value' => 'forward',
				'text' => JText::_('SHOWLIST_GENERAL_FORWARD')),
				'1' => array('value' => 'backward',
				'text' => JText::_('SHOWLIST_GENERAL_BACKWARD')),
				'2' => array('value' => 'random',
				'text' => JText::_('SHOWLIST_GENERAL_RANDOM'))
			);

			$showExifData= array(
				'0' => array('value' => 'no',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_NO')),
				'1' => array('value' => 'title',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_TITLE')),
				'2' => array('value' => 'description',
				'text' => JText::_('SHOWLIST_SHOW_EXIF_DATA_DESCRIPTION'))
			);

			$lists['imagesLoadingOrder'] 	= JHTML::_('select.genericList', $imagesLoadingOrder, 'image_loading_order', 'class="inputbox" '. '', 'value', 'text', $items->image_loading_order);
			$lists['showExifData'] 			= JHTML::_('select.genericList', $showExifData, 'show_exif_data', 'class="inputbox" '. '', 'value', 'text', $items->show_exif_data);
			$lists['authorizationCombo'] 	= JHTML::_('select.genericList', $authorizationCombo, 'authorization_status', 'class="inputbox" onchange="JSNISImageShow.ShowListCheckAuthorizationContent();"'. '', 'value', 'text', $items->authorization_status );
			$lists['published'] 	= JHTML::_('jsnselect.booleanlist',  'published', '', ($items->published !='')?$items->published:1 );
			$lists['overrideTitle'] = JHTML::_('jsnselect.booleanlist',  'override_title', '', $items->override_title);
			$lists['overrideDesc'] 	= JHTML::_('jsnselect.booleanlist',  'override_description', '', $items->override_description);
			$lists['overrideLink'] 	= JHTML::_('jsnselect.booleanlist',  'override_link', '', $items->override_link);

			$query 				= 'SELECT ordering AS value, showlist_title AS text'
									. ' FROM #__imageshow_showlist'
									. ' ORDER BY ordering';
			$lists['ordering'] 			= JHTML::_('list.specificordering',  $items, $items->showlist_id, $query );

			$canAutoDownload = true;
			$objJSNUtils 	 = JSNISFactory::getObj('classes.jsn_is_utils');

			if (!$objJSNUtils->checkEnvironmentDownload()) {
				$canAutoDownload = false;
			}

			$image_model  		= $this->getModel();
			$categories 		= $model->getTreeMenu();
			$articlesCatgories 	= $model->getTreeArticle();
			$this->assign('categories', $categories);
			$this->assign('articles_catgories', $articlesCatgories);
			$this->assignRef('canAutoDownload', $canAutoDownload);
			$this->assignRef('lists', $lists);
			$this->assignRef('items', $items);
			$this->assignRef('imageSource',$imageSource);
			$this->assignRef('countImage', $countImage);
			$this->assignRef('images',$images);
			$this->assignRef('catSelected',$catid);
			$this->assignRef('albumID',$albumID);
			$this->assignRef('totalImage',$totalimage);
			parent::display($tpl);
		}
}
?>