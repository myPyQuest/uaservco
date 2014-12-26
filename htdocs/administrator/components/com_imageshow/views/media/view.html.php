<?php
/**
 * @author JoomlaShine.com Team
 * @copyright JoomlaShine.com
 * @link joomlashine.com
 * @package JSN ImageShow
 * @version $Id: view.html.php 13759 2012-07-04 04:31:41Z giangnd $
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view');
class ImageShowViewMedia extends JView
{
	function display($tpl = null)
	{
		global $mainframe,$componentVersion;
		$document = JFactory::getDocument();
		$document->addScript(JURI::root(true).'/administrator/components/com_imageshow/assets/js/joomlashine/imagemanager.js?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/administrator/components/com_imageshow/assets/css/popup-imagemanager.css?v='.$componentVersion);
		$document->addStyleSheet(JURI::root(true).'/templates/system/css/system.css?v='.$componentVersion);

		$objJSNMediaManager = JSNISFactory::getObj('classes.jsn_is_mediamanager');
		$objJSNMediaManager->setMediaBasePath();
		$state 				= $objJSNMediaManager->getStateFolder();
		$folderList			= $objJSNMediaManager->getFolderList();
		$session			= JFactory::getSession();
		$this->assignRef('session', $session);
		$this->assignRef('state', $state);
		$this->assignRef('folderList', $folderList);
		parent::display($tpl);
	}
}
