<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: media.php 12634 2012-05-14 03:55:29Z hiepnv $
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die;
function is_jquery($link)
{
	if ( strpos($link, 'com_poweradmin') !== false || strpos($link, 'com_imageshow') !== false ){
		return false;
	}
	$uri = new JURI($link);
	@list($host, $port) = explode(':', $_SERVER['HTTP_HOST']);
	if ( preg_match('/http|https/', $link) ){
		$src = $link;
	}else{
		if ($uri->getScheme() == ''){
			$scheme = 'http';
			if (@$_SERVER['HTTPS']){
				$scheme = 'https';
			}
			$uri->setScheme($scheme);
		}
		if ( $uri->getHost() == '' ){
			$uri->setHost($host);
		}
		if ( $uri->getPort() == '' ){
			$uri->setPort($port);
		}
		$src = $uri->toString();
	}
	//External link
	if ( JString::strtolower($uri->getHost()) != JString::strtolower($host) ){
		if (strpos($src, 'jquery.min.js') !== false ){
			return true;
		}
	}
	//Internal link
	else{
		$contents = @file_get_contents($src);
		//If jquery
		if ( preg_match('/a.jQuery/', $contents) && preg_match('/define.amd.jQuery/', $contents) ){
			//remove old jquery versions
			//if ( preg_match('/a.jQuery/', $contents) && preg_match('/clsid:D27CDB6E-AE6D-11cf-96B8-444553540000/', $contents)){
			return true;
		}
		//If jquery-ui
		elseif ( strpos($contents, 'ALT:18,BACKSPACE:8,CAPS_LOCK:20,COMMA:188,COMMAND:91,COMMAND_LEFT:91,COMMAND_RIGHT:93,CONTROL:17,DELETE:46,DOWN:40,END:35,ENTER:13,ESCAPE:27,HOME:36,INSERT:45,LEFT:37,MENU:93,NUMPAD_ADD:107,NUMPAD_DECIMAL:110,NUMPAD_DIVIDE:111,NUMPAD_ENTER:108,NUMPAD_MULTIPLY:106,NUMPAD_SUBTRACT:109,PAGE_DOWN:34,PAGE_UP:33,PERIOD:190,RIGHT:39,SHIFT:16,SPACE:32,TAB:9,UP:38,WINDOWS:91') !== false){
			return true;
		}
	}

	return false;
}
class JSNMedia{
	private $_scripts;
	private $_styles;
	private $_styleDeclaration;
	private $_scriptDeclaration;
	private $_customs;
	private $_lang = 'en';
	private $_dispatch = false;
	private $_docType;
	private $_conflict;
	private $_load_js_language;

	public function __construct()
	{
		$this->_scripts = Array();
		$this->_styles  = Array();
		$this->_customs = Array();
		$this->_scriptDeclaration = Array();
		$this->_styleDeclaration  = Array();
		$this->_docType = JFactory::getDocument()->getType();
		$this->_conflict = true;
		if ( !$this->_dispatch ){			
			require_once JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_poweradmin' . DS . 'defines.poweradmin.php' ;		
			$this->addStyleSheet( JSN_FRAMEWORK_ASSETS. '/3rd-party/jquery-ui/css/ui-bootstrap/jquery-ui-1.8.16.custom.css');
			@$this->addStyleSheet(JSN_FRAMEWORK_ASSETS . '/3rd-party/bootstrap/css/bootstrap.min.css');
            @$this->addStyleSheet(JSN_FRAMEWORK_ASSETS . '/joomlashine/css/jsn-gui.css');
            @$this->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/jquery/jquery-1.7.1.min.js');            
            @$this->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/bootstrap/js/bootstrap.min.js');
            $this->addScript(JSN_FRAMEWORK_ASSETS . '/3rd-party/jquery-ui/js/jquery-ui-1.8.16.custom.min.js');

            $this->addScript( JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.jquery.noconflict.js');
			$this->addScript( JSN_POWERADMIN_LIB_JS_URI. 'jquery.cook.js');
			$this->addScript( JSN_POWERADMIN_LIB_JS_URI. 'jquery.topzindex.js');
			$this->addScript( JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.window.js');
			$this->addScript( JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.lang.js');
            $this->addStyleSheet(JSN_POWERADMIN_STYLE_URI."poweradmin.css");

			//Load js lang
			if ( JRequest::getVar('option', '') == 'com_poweradmin' ){
				JSNFactory::localimport('libraries.joomlashine.language.javascriptlanguages');
				$jsLang = JSNJavascriptLanguages::getInstance();
				$this->addScriptDeclaration($jsLang->loadLang());
			}
		}
	}
	/**
	 *
	 * Get instance
	 *
	 */
	public static function getInstance()
	{
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}
		if ( empty($instances['JSNMedia']) ) {
			$instance	= new JSNMedia();
			$instances['JSNMedia'] = &$instance;
		}

		return $instances['JSNMedia'];
	}

	public function setLoadJSLang($load = true)
	{
		$this->_load_js_language = $load;
	}
	/**
	 *
	 * Set doctype to render
	 *
	 * @param String $type
	 */
	public function setType($type)
	{
		if ( !in_array($type, Array('raw', 'html') ) )
		{
			$this->_docType = 'html';
		}else{
			$this->_docType = $type;
		}
	}
	/**
	 *
	 * Return language key
	 */
	public function getLang()
	{
		return $this->_lang;
	}
	/**
	 *
	 * Queue store script file to array
	 *
	 * @param String $filename
	 */
	public function addScript( $filename )
	{
		if ( !in_array($filename, $this->_scripts) ){
			JSNFactory::localimport('helpers.poweradmin');
			$currentVersion		= PoweradminHelper::getVersion();
			$filename			.= '?v=' . $currentVersion;
			$this->_scripts[] 	= $filename;
		}
	}
	/**
	 *
	 * Conflict
	 *
	 * @param Boolean $conflict
	 */
	public function conflict($conflict = true)
	{
		$this->_conflict = ( $conflict == true || $conflict == false ) ? $conflict : true;
	}
	/**
	 *
	 * Queue store style file to array
	 *
	 * @param String $filename
	 */
	public function addStyleSheet( $filename )
	{
		if ( !in_array($filename, $this->_styles) ){
			JSNFactory::localimport('helpers.poweradmin');
			$currentVersion		= PoweradminHelper::getVersion();
			$filename			.= '?v=' . $currentVersion;
			$this->_styles[] = $filename;
		}
	}
	/**
	 *
	 * Queue store custom tag to array
	 *
	 * @param String $str
	 */
	public function addCustomTag( $str )
	{
		$this->_customs[] = $str;
	}
	/**
	 *
	 * Queue store style declaration to array
	 *
	 * @param String $str
	 */
	public function addStyleDeclaration( $str )
	{
		$this->_styleDeclaration[] = $str;
	}
	/**
	 *
	 * Queue store script declaration to array
	 *
	 * @param String $str
	 */
	public function addScriptDeclaration( $str )
	{
		$this->_scriptDeclaration[] = $str;
	}
	/**
	 *
	 * Parse all queue to page
	 *
	 */
	public function addMedia()
	{
		$document = JFactory::getDocument();
		$docType  = $document->getType();
		if ( $this->_load_js_language )
		{
			JSNFactory::localimport('libraries.joomlashine.language.javascriptlanguages');
			$jsLang = JSNJavascriptLanguages::getInstance();
			$this->addScriptDeclaration($jsLang->loadLang());
		}
		if ( $this->_docType == 'raw' ){
			$medias = Array();
			//Add all style file to page
			if ( count( $this->_styles  ) ){
				foreach( $this->_styles as $style ){
					$medias[] = '<link  type="text/css" rel="stylesheet" href="'.$style.'" />';
				}
			}
			//Add all script file to page
			if ( count( $this->_scripts ) ){
				foreach( $this->_scripts as $script ){
					$medias[] = '<script type="text/javascript" src="'.$script.'"></script>';
				}

				if ( !in_array( PoweradminHelper::makeUrlWithSuffix(JSN_POWERADMIN_LIB_JSNJS_URI. 'conflict.js'), $this->_scripts ) ){
					$medias[] = '<script type="text/javascript" src="'.PoweradminHelper::makeUrlWithSuffix(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.conflict.js').'"></script>';
				}
			}
			//Add all custom tag to page
			if ( count( $this->_customs ) ){
				foreach( $this->_customs as $custom ){
					$medias[] = $custom;
				}
			}
			//Add all style declaration to page
			if ( count( $this->_styleDeclaration ) ){
				$medias[] = '<style type="text/css">'. implode( PHP_EOL, $this->_styleDeclaration ) .'</style>';
			}
			//Add all script declaration to page
			if ( count( $this->_scriptDeclaration ) ){
				$medias[] = '<script type="text/javascript">'. implode( PHP_EOL, $this->_scriptDeclaration ) .'</script>';
			}

			echo implode(PHP_EOL, $medias);
		}else{
			//behavior mootools
			JHtml::_('behavior.mootools');
			//behavior modal
			JHtml::_('behavior.modal');
			//behavior tooltip
			JHtml::_('behavior.tooltip');
			//behavior formvalidation
			JHtml::_('behavior.formvalidation');
			//behavior combobox
			JHtml::_('behavior.combobox');

			//Add all style file to page
			if ( count( $this->_styles  ) ){
				foreach( $this->_styles as $style ){
					$document->addStyleSheet( $style );
				}
			}
			$system_js = Array();
			$user_js   = Array();
			$docScripts = $document->_scripts;
			if ( count($docScripts) ){
				foreach ($docScripts as $key => $script ){
					if ( strpos($key, '/media/system/' ) !== false ){
						$system_js[$key] = $script;
					}else if ( !is_jquery($key) ){
						$user_js[$key] = $script;
					}

				}
				$document->_scripts = Array();
			}
			//Add all script file to page
			if ( count( $this->_scripts ) ){
				foreach( $this->_scripts as $script ){
					$document->addScript( $script );
				}

				if ( !in_array( PoweradminHelper::makeUrlWithSuffix(JSN_POWERADMIN_LIB_JSNJS_URI. 'conflict.js'), $this->_scripts ) ){
					$document->addScript( PoweradminHelper::makeUrlWithSuffix(JSN_POWERADMIN_LIB_JSNJS_URI. 'jsn.conflict.js') );
				}
			}
			$docScripts = $document->_scripts;
			$index = 0;
			$jsn_jquery = Array();
			foreach ( $docScripts as $key => $script ){
				if ( $index < 2 ){
					$jsn_jquery[$key] = $script;
				}else{
					$user_js[$key] = $script;
				}
				$index++;
			}

			$document->_scripts = $system_js + $jsn_jquery + $user_js;

			//Add all custom tag to page
			if ( count( $this->_customs ) ){
				foreach( $this->_customs as $custom ){
					$document->addCustomTag( $custom );
				}
			}
			//Add all style declaration to page
			if ( count( $this->_styleDeclaration ) ){
				$document->addStyleDeclaration( implode( PHP_EOL, $this->_styleDeclaration ) );
			}
			//Add all script declaration to page
			if ( count( $this->_scriptDeclaration ) ){
				$document->addScriptDeclaration( implode( PHP_EOL, $this->_scriptDeclaration ) );
			}
		}

		$this->_dispatch = true;
		$this->__construct();
	}
}