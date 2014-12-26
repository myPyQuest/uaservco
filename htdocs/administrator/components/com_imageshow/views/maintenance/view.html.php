<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 14191 2012-07-19 12:26:54Z haonv $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'/elements/html');
class ImageShowViewMaintenance extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		JHTML::_('behavior.modal','a.modal');
		JHTML::_('behavior.modal','a.jsn-modal');
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/bootstrap/jquery-ui-1.8.16.custom.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/view.maintenance.css?v='.$componentVersion);

		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery.min.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/jquery/jquery-ui.custom.min.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/conflict.js?v='.$componentVersion);
		//$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/bootstrap/bootstrap.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/window.js?v='.$componentVersion);
		//$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jquery/jquery-ui.custom.css?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/sampledata.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/sampledatamanual.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installimagesources.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installshowcasethemes.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installdefault.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/accordions.js?v='.$componentVersion);

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
						var JSNISMaintenanceViewWindow = new $.JSNISUIWindow(baseUrl+'administrator/'+link,{
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
						var title = $(this).attr('name');
						var JSNISMaintenanceFormWindow = new $.JSNISUIWindow(baseUrl+'administrator/'+link,{
								width: data.size.x,
								height: data.size.y,
								title: title,
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

		$screen				= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.msg_screen', 'msg_screen', '', 'string');
		$profileTitle 		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.configuration_title', 'config_title', '', 'string');
		$profileSource		= $mainframe->getUserStateFromRequest('com_imageshow.maintenance.img_source', 'img_source', '', 'string');

		$lists 		= array();
		$type  		= JRequest::getWord('type','backup');
		$model 		= $this->getModel();
		$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
		$objJSNMsg 	= JSNISFactory::getObj('classes.jsn_is_displaymessage');
		$task		= JRequest::getWord('task','');
		if ($task != 'login')
		{
			switch($type)
			{
				case 'inslangs':
					$this->assignRef('languages', json_decode(JSN_IMAGESHOW_LIST_LANGUAGE_SUPPORTED));
				break;
				case 'msgs':
					$arrayScreen 			= $objJSNMsg->listScreenDisplayMsg();
					$lists['arrayScreen'] 	= JHTML::_('select.genericList', $arrayScreen, 'msg_screen', 'class="inputbox" onchange="document.adminForm.submit();"'. '', 'value', 'text', $screen);
					$getMessages 			= $objJSNMsg->getMessages($screen);
					$this->assignRef('messages', $getMessages);
					$this->assignRef('screen', $screen);
				break;
				case 'profiles':
					jimport('joomla.html.pagination');
					$limitStart = $mainframe->getUserStateFromRequest('com_imageshow.sourceManager.limitstart', 'limitstart', 0, 'int');
					$limit 		= $mainframe->getUserStateFromRequest('com_imageshow.sourceManager.limit', 'limit', 0, 'int');

					$objJSNSource = JSNISFactory::getObj('classes.jsn_is_source');
					$listSources  = $objJSNSource->getListSources();
					$arraySources = array();

					if ($limit != 0) {
						$count = (($limit + $limitStart) > count($listSources)) ? count($listSources) - 1 :  ($limit + $limitStart);
					} else {
						$count = count($listSources) - 1;
					}

					for ($i = $limitStart; $i < $count; $i++)
					{
						$source = $listSources[$i];
						if ($source->type == ('external' || 'internal')) {
							$source->profiles = $objJSNProfile->getProfiles($profileTitle, $source->identified_name);
						}

						$arraySources[] = $source;
					}

					$lists['profileTitle'] = $profileTitle;

					$this->pagination = new JPagination(count($arraySources), $limitStart, $limit);
					$this->assignRef('listSources', $arraySources);
				break;
				case 'editprofile':
					$sourceID 		= JRequest::getInt('external_source_id');
					$countShowlist	= JRequest::getInt('count_showlist', 0);
					$source 		= JRequest::getString('source_type');
					$imageSource	= JSNISFactory::getSource($source, 'external');
					$imageSource->_source['sourceTable']->load($sourceID);
					$this->assignRef('sourceInfo', $imageSource->_source['sourceTable']);
					$this->assignRef('countShowlist', $countShowlist);
				break;
				case 'configs':
					$parameters 		= $objJSNProfile->getParameters();
					$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
					$modQuickIconInfo 	= $objJSNUtils->getModuleInformation('mod_imageshow_quickicon');
					$showQuickIcons		= (@$parameters->show_quick_icons == '')?'1':@$parameters->show_quick_icons;
					$jshowQuickIcons	= isset($modQuickIconInfo->published)?$modQuickIconInfo->published:'1';
					if ($jshowQuickIcons != $showQuickIcons)
					{
						$post['show_quick_icons'] = $jshowQuickIcons;
						$objJSNProfile = JSNISFactory::getObj('classes.jsn_is_profile');
						$objJSNProfile->saveParameters($post);
					}
					$parameters 					= $objJSNProfile->getParameters();
					if (is_null(@$parameters->show_quick_icons))
					{
						$show = '1';
					}
					else
					{
						$show = @$parameters->show_quick_icons;
					}

					if (is_null(@$parameters->enable_update_checking))
					{
						$enableUpdate = '1';
					}
					else
					{
						$enableUpdate = @$parameters->enable_update_checking;
					}
					$lists['showQuickIcons'] 		= JHTML::_('jsnselect.booleanlist', 'show_quick_icons','', $show);
					$lists['enableUpdateChecking'] 		= JHTML::_('jsnselect.booleanlist', 'enable_update_checking','', $enableUpdate);
					$this->assignRef('parameters', $parameters);
				break;
				case 'data':
					$objJSNUtils 		= JSNISFactory::getObj('classes.jsn_is_utils');
					$sampleData 		= JSNISFactory::getObj('classes.jsn_is_sampledata');
					$objReadXmlDetail	= JSNISFactory::getObj('classes.jsn_is_readxmldetails');
					$inforPackage 		= $objReadXmlDetail->parserXMLDetails();
					$sampleData->getPackageVersion(trim(strtolower($inforPackage['realName'])));
					$objJSNUtils->checkTmpFolderWritable();
				break;
				case 'themes':
					$filterState 		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_state', 'filter_state', '', 'word');
					$filterOrder		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order','filter_order', '', 'cmd');
					$filterOrderDir		= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.filter_order_Dir',	'filter_order_Dir',	'',	'word');
					//$filterPluginName	= $mainframe->getUserStateFromRequest('com_imageshow.themesManager.plugin_name', 'plugin_name', '', 'string');

					$lists['state']		= JHTML::_('grid.state',  $filterState);
					$lists['order_Dir'] = $filterOrderDir;
					$lists['order'] 	= $filterOrder;
					$pluginModel 		= JModel::getInstance('plugins', 'imageshowmodel');

					$listJSNPlugins		= $pluginModel->getData();
					$pagination 		= $pluginModel->getPagination();

					$this->assignRef('lists', $lists);
					$this->assignRef('pagination', $pagination);
					//$this->assignRef('filterPluginName', $filterPluginName);
					$this->assignRef('listJSNPlugins', $listJSNPlugins);
				break;
				case 'themeparameters':
				break;
				case 'profileparameters':
				break;				
				default:
					$mainframe->redirect('index.php?option=com_imageshow&controller=maintenance&type=configs');
			    break;
			}
		}
		$this->assignRef('lists', $lists);
		parent::display($tpl);
	}

	function canInstallLanguage ($locale, $section)
	{
		if($section == 'site')
		{
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_imageshow/languages/site/'.$locale.'.com_imageshow.ini';
			$langPath   = JPATH_SITE . '/language/'.$locale;
		}
		else
		{
			$sourcePath = JPATH_ADMINISTRATOR . '/components/com_imageshow/languages/admin/'.$locale.'.com_imageshow.ini';
			$langPath   = JPATH_ADMINISTRATOR . '/language/'.$locale;
		}

		return is_dir($langPath) && is_writable($langPath) && is_file($sourcePath);
	}

	function isInstalledLanguage ($locale, $section)
	{
		$langPath = ($section == 'site') ? JPATH_SITE . '/language/'.$locale : JPATH_ADMINISTRATOR . '/language/'.$locale;
		if (!is_dir($langPath))
		{
			return false;
		}
		$langFiles = glob("{$langPath}/{$locale}.com_imageshow.*");
		return count($langFiles) > 0;
	}

	function isJoomlaSupport ($locale, $area)
	{
		$path = ($area == 'site') ? JPATH_SITE : JPATH_ADMINISTRATOR;
		$path.= '/language/' . $locale;
		return is_dir($path);
	}
}