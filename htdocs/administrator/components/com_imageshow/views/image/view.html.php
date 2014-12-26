<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN VideoShow
 * @version $Id: view.html.php 10238 2011-12-14 08:09:07Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.view');
class ImageShowViewImage extends JView
{
		function display($tpl = null)
		{
			global $componentVersion;
			$model  = $this->getModel();
			$document = JFactory::getDocument();
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-ui.custom.min.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
			$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/window.js?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/jquery-ui-1.8.16.custom.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
			$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/image_selector.css?v='.$componentVersion);
			$document = JFactory::getDocument();
				$jsCode = "
					var baseUrl = '".JURI::root()."';
					var imageLinkID = '';
					var JSNISLinkWindow;
					(function($){
					$(document).ready(function () {
							$('.select-link-edit').click(function(){
								imageLinkID = $(this).attr('name');
								JSNISLinkWindow = new $.JSNISUIWindow(baseUrl+'administrator/index.php?option=com_imageshow&controller=image&task=linkpopup&tmpl=component',{
										width: 800,
										height: 550,
										id:'popup-link',
										title: '".JText::_('SHOWLIST_POPUP_IMAGE_CHOOSE_LINK', true)."',
										buttons: {
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
			$app = JFactory::getApplication();
			$showListID  			= $app->getUserState('com_imageshow.images.showlistID');
			$sourceName  			= $app->getUserState('com_imageshow.images.sourceName');
			$sourceType 			= $app->getUserState('com_imageshow.images.sourceType');
			$imageID     			= $app->getUserState('com_imageshow.images.imageID');
			$image 	 				= $model->getItems($imageID,$showListID);
			$this->assign('image', $image);
			parent::display($tpl);
		}
}
?>
