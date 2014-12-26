<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 14386 2012-07-25 09:25:28Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport('joomla.application.component.view');
class ImageShowViewUpgrader extends JView
{
	function display($tpl = null)
	{
		global $mainframe, $option, $componentVersion;
		$document = JFactory::getDocument();
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/accordion.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/jsn-gui.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/imageshow.css?v='.$componentVersion);

		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imageshow.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/accordions.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installdefault.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/installmanual.js?v='.$componentVersion);
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/upgrader.js?v='.$componentVersion);

		$objJSNUtil			= JSNISFactory::getObj('classes.jsn_is_utils');
		$objVersion			= new JVersion();
		$objJSNLightCart  	= JSNISFactory::getObj('classes.jsn_is_lightcart');
		$infoCore 			= $objJSNUtil->getComponentInfo();
		$paramsLang 		= JComponentHelper::getParams('com_languages');
		$adminLang 			= $paramsLang->get('administrator', 'en-GB');
		$edition				= $objJSNUtil->getEdition();
		$core 							= new stdClass();
		$core->identify_name 			= JSN_IMAGESHOW_IDENTIFIED_NAME;
		$core->edition 					= '';
		$core->joomla_version 			= $objVersion->RELEASE;
		$core->wait_text				= JText::_('UPGRADER_UPGRADE_INSTALL_WAIT_TEXT', true);
		$core->process_text				= JText::_('UPGRADER_UPGRADE_INSTALL_PROCESS_TEXT', true);
		$core->based_identified_name	= '';
		$core->error_code		  		= $objJSNLightCart->getErrorCode('upgrader');
		$core->manual_download_text 	  = JText::_('MANUAL_DOWNLOAD', true);
		$core->manual_install_button 	  = JText::_('MANUAL_INSTALL', true);
		$core->manual_then_select_it_text = JText::_('MANUAL_THEN_SELECT_IT', true);
		$core->dowload_installation_package_text = JText::_('MANUAL_DOWNLOAD_INSTALLATION_PACKAGE', true);
		$core->select_download_package_text = JText::_('MANUAL_SELECT_DOWNLOADED_PACKAGE', true);
		$core->downloadLink				  = JSN_IMAGESHOW_AUTOUPDATE_URL;
		$core->language					  = $adminLang;
		$canAutoDownload 				  = $objJSNUtil->checkEnvironmentDownload();
		$this->assignRef('edition', $edition);
		$this->assignRef('objJSNUtil', $objJSNUtil);
		$this->assignRef('canAutoDownload', $canAutoDownload);
		$this->assignRef('core', $core);
		parent::display($tpl);
	}
}