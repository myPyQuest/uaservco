<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 14117 2012-07-17 08:36:54Z thangbh $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewAbout extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;

		$document = JFactory::getDocument();

		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/jquery-ui-1.8.16.custom.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);

		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-ui.custom.min.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/window.js?v='.$componentVersion);
		$jsCode = "
			var baseUrl = '".JURI::root()."';
			(function($){
			$(document).ready(function () {
					$('.jsn-is-view-modal').click(function(event){
						event.preventDefault();
						var data = jQuery.parseJSON($(this).attr('rel'));
						var link = $(this).attr('href');
						var JSNISLinkWindow = new $.JSNISUIWindow(link,{
								width: data.size.x,
								height: data.size.y,
								title: '".JText::_('ABOUT_JOOMLA_TEMPLATES', true)."',
								scrollContent: false,
								buttons: {
									'Close': function (){
										$(this).dialog('close');
									}
								}
						});
					});
				});
			})(jQuery);
		  ";
		$document->addScriptDeclaration($jsCode);

		$objJSNUtils      	= JSNISFactory::getObj('classes.jsn_is_utils');
		$objJSNJSON       	= JSNISFactory::getObj('classes.jsn_is_json');

		$componentInfo 	  	= $objJSNUtils->getComponentInfo();
		$componentData 	  	= null;
		$edition		  	= $objJSNUtils->getEdition();
		$componentData  	= $objJSNJSON->decode($componentInfo->manifest_cache);
		$document->addScriptDeclaration("
				window.addEvent('domready', function(){
					var check = false;
						var actionVersionUrl = 'index.php';
						var resultVersionMsg = new Element('span');
						resultVersionMsg.set('class','jsn-version-checking');
						resultVersionMsg.set('html','".JText::_('ABOUT_CHECKING')."');
						resultVersionMsg.inject($('jsn-check-version-result'));
						var jsonRequest = new Request.JSON({url: actionVersionUrl, onSuccess: function(jsonObj){
							if(jsonObj.connection) {
								if(jsonObj.update) {
									resultVersionMsg.set('class','jsn-outdated-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_SEE_UPDATE_INSTRUCTIONS', true)."');
								} else {
									resultVersionMsg.set('class','jsn-latest-version');
									resultVersionMsg.set('html','".JText::_('ABOUT_THE_LATEST_VERSION', true)."');
								}
							} else {
								resultVersionMsg.set('class','jsn-connection-fail');
								resultVersionMsg.set('html','".JText::_('ABOUT_CONNECTION_FAILED', true)."');
							}
							resultVersionMsg.inject($('jsn-check-version-result'));
						}}).get({'option': 'com_imageshow',
									'controller': 'ajax',
									'task': 'checkUpdateAllElements'
								});
				});
			");
		$params = JComponentHelper::getParams('com_imageshow');
		$this->assignRef('edition',$edition);
		$this->assignRef('componentData', $componentData);
		$this->assignRef('params',$params);
		parent::display($tpl);

	}
}