<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: render.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
JSNFactory::localimport('libraries.joomlashine.html');
JSNFactory::localimport('libraries.joomlashine.database');

class JSNRender extends JSNDatabase
{	
	private   $_contents = '';   // Contents of page 
	public    $_renderUrl = '';  // current URL of page 
	protected $_isExternal = false; //Variable to able the url render is internal/external	
	/**
	 * 
	 *  Constructor function 
	 */
	public function __construct()
	{
		parent::__construct();		
	}	
	
	/**
	 * 
	 * Check link render is internal/external
	 * 
	 * @return: true/false
	 */
	public function isExternal()
	{
		return $this->_isExternal;
	}
	
	/**
	 * 
	 * Return global JSNRender object
	 * 
	 * @param: String of url	 
	 *  
	 */
	public static function getInstance()
	{
		static $instances;

		if (!isset($instances)) {
			$instances = array();
		}

		if (empty($url)){
			$url = JURI::root().'?poweradmin=1';
		}

		if (empty($instances['JSNRender'])) {
			$instance	= new JSNRender();
			$instances['JSNRender'] = &$instance;
		}

		return $instances['JSNRender'];
	}
	
	/**
	 * 
	 * Using CURL get page source
	 * 
	 * @param String $url
	 */
	protected function curlResponse($url){

		$posts = array();
		if (isset($_POST['return']) && count($_POST) > 0){
			$posts = $_POST;
			$uri   = new JURI($this->_renderUrl);
			if ($uri->hasVar('task') && ( $uri->getVar('task') == 'user.login' || $uri->getVar('task') == 'user.logout' )){
				$posts['option'] = 'com_users';
				$posts['view']   = 'user';
			}
		}
		$options = array(
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_HEADER         => false,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_USERAGENT      => "spider",
	        CURLOPT_AUTOREFERER    => true,
	        CURLOPT_CONNECTTIMEOUT => 120,
	        CURLOPT_TIMEOUT        => 120,
	        CURLOPT_MAXREDIRS      => 10,
	        CURLOPT_POSTFIELDS     => $posts,
	        CURLOPT_COOKIEJAR      => dirname(__FILE__).DS.'jsn_poweradmin_cookie.txt',
	        CURLOPT_COOKIEFILE     => dirname(__FILE__).DS.'jsn_poweradmin_cookie.txt'
	    );

	    $ch      = curl_init( $url );
	    curl_setopt_array( $ch, $options ); 
	    $contents= curl_exec( $ch ); 
	    $err     = curl_errno( $ch ); 
	    $errmsg  = curl_error( $ch ); 
	    $header  = curl_getinfo( $ch ); 
	    curl_close( $ch ); 

	    $response = new stdClass();
		$response->contents     = $contents;
		$response->redirect_url = $header['url'];

	    if ($response->redirect_url != $this->_renderUrl && JString::trim($response->redirect_url) != ''){
	    	$old_uri      = new JURI($this->_renderUrl);

	    	$redirect_uri = new JURI($response->redirect_url);

	    	if (!$redirect_uri->hasVar('poweradmin')){
	    		$redirect_uri->setVar('poweradmin', 1);
	    	}

	    	if ($old_uri->hasVar('tp') && !$redirect_uri->hasVar('tp')){
	    		$redirect_uri->setVar('tp', 1);
	    	}

	        if ($old_uri->hasVar('Itemid') && !$redirect_uri->hasVar('Itemid')){
	    		$redirect_uri->setVar('Itemid', $old_uri->getVar('Itemid'));
	    	}

	    	//save redirect url
			$this->_renderUrl  = $redirect_uri->toString();

	    	$this->curlResponse($this->_renderUrl);

	    }
	    return $response;
	}

	
	/**
	* 
	* get content front-end joomla
	* 
	* @return: format html
	*/
	protected function getContents( $viewMode )
	{
		/** get contents of front-end page **/		
		try{
			if (function_exists('curl_init')){
				$response = $this->curlResponse($this->_renderUrl);
			    //save contents
				$this->_contents = $response->contents;
			}else{
				JError::raiseWarning(500, 'Please enable C_URL lib.');
			}
		}catch (Exception $e){
			throw $e.getMessage();
		}
		
		if ( $viewMode == 'jsnrender' ){
			/** change the links of contents **/
			$this->changeLinks( JPATH_ROOT, JURI::root() );	
			
			/** remove <meta> tags in content **/
			$this->_contents = preg_replace('#</?meta[^>]*>#is', '', $this->_contents);
			
			/** remove <title> tag **/
			$this->_contents = preg_replace('/<title\>(.*?)<\/title>/is', '', $this->_contents);
		}
	}

	/**
	 * 
	 * Get current URL string
	 */
	public function getCurrentUrlInfos()
	{
		$currentUrl = $this->_renderUrl;
		$url = new JURI($currentUrl);
		$urlInfos = new stdClass();
		$urlInfos->urlString    = '';
		$urlInfos->showTemplatePosition = false;
		if ($url->hasVar('tp')&&$url->getVar('tp')==1){
			$url->delVar('tp');
			$urlInfos->showTemplatePosition = true;
		}
		$urlInfos->urlString = $url->toString();
		return $urlInfos;
	}
	
	/**
	* 
	* Changes the links (src/href) of HTML
	* 
	* @return: String 
	*/
	public function changeLinks( $root_path = JPATH_ROOT, $base_uri = '' )
	{
		/** change js links	**/
		$regex = '/src=(["\'])(.*?)\1/';
		$count = preg_match_all($regex, $this->_contents, $match);
		if ($count > 0)
		{
			$changes = $match[2];
			foreach($changes as $change)
			{
				$uri = new JURI($change);
				if ($_SERVER['HTTP_HOST'] != $uri->getHost() && $uri->getHost() != ''){
					$headers = @get_headers($change, 1);
					if ($headers[0] == 'HTTP/1.1 404 Not Found') {
						$this->_contents = str_replace('src="'.$change.'"', 'src=""', $this->_contents);
					}
				}
			}
		}
		
		/** change href reference **/
		$regex = '/href=(["\'])(.*?)\1/';
		$count = preg_match_all($regex, $this->_contents, $match);
		if ($count > 0)
		{
			$changes = $match[2];
			foreach($changes as $change)
			{
				$uri = new JURI($change);
				if ($_SERVER['HTTP_HOST'] != $uri->getHost() && $uri->getHost() != ''){
					$headers = @get_headers($change, 1);
					if ($headers[0] == 'HTTP/1.1 404 Not Found') {
						$this->_contents = str_replace('href="'.$change.'"', 'href=""', $this->_contents);
					}
				}
			}
		}

		/** change action form reference **/
		$regex = '/action=(["\'])(.*?)\1/';
		$count = preg_match_all($regex, $this->_contents, $match);
		if ($count > 0)
		{
			$changes = $match[2];
			foreach($changes as $change)
			{				
				$this->_contents = str_replace('action="'.$change.'"', 'action="'.JSN_RENDER_PAGE_URL.base64_encode($change).'"', $this->_contents);				
			}
		}

		/** change links **/
		$doc = new DOMDocument();
		if ( @$doc->loadhtml( $this->_contents ) ){
		    $xpath = new DOMXpath( $doc );
		    foreach($xpath->query('//html//a') as $eInput) {
				$href = $eInput->getAttribute('href');
				$uri  = new JURI($href);
				if (JString::trim($href) != '#' && JString::trim($href) != '' && ($_SERVER['HTTP_HOST'] == $uri->getHost() || $uri->getHost() == '')){
					$extend_url = JSN_RENDER_PAGE_URL.base64_encode($href);				
					$eInput->setAttribute('href', $extend_url);
				}else{
					$eInput->setAttribute('href', 'javascript:;');					
				}
		    }
		}

		/** SAVE HTML after changed **/
		$this->_contents = $doc->saveHTML();		
	}	
	
	/**
	* 
	* DOMDocument get inner HTML
	* 
	* @return: String
	*/
	protected function DOMinnerHTML( $element ) 
	{
		$innerHTML = ""; 
		$children = $element->childNodes; 
		if (count( $children ) > 0){
			foreach ($children as $child) 
			{
				$tmp_dom = new DOMDocument(); 
				$tmp_dom->appendChild($tmp_dom->importNode($child, true)); 
				$innerHTML.=trim($tmp_dom->saveHTML()); 
			}
		}

		return $innerHTML; 
	}
	
	/**
	* 
	* get html in <head> of front-end page
	* 
	* @return: Array: text is value after removed css links, links is css links
	*/
	public function getHeader()
	{
		return preg_replace("/.*<head]*>|<\/head>.*/si", "", $this->_contents);
	}
	
	/**
	* get html in <body> of front-end page
	* @return: string of HTML of page
	*/
	public function getBody()
	{
		$body = new stdClass();
		$body->attr = '';
		if(preg_match_all('#<body\ (.*)>#iU', $this->_contents, $matches)) {
			$body->attr = $matches[1][0];
		}
		preg_match('/<body(.*)>(.*)<\/body>/is', $this->_contents, $matches);
		$body->html = $matches[0];
		return $body;
	}
	
	/**
	* 
	* Get HTML of component
	* 
	* @return: String of HTML
	*/
	public function getComponent()
	{
		$html = '<div class="jsn-component-container" id="jsnrender-component" >';	
		$doc = new DOMDocument;
		if ( @$doc->loadhtml( $this->_contents ) ){
			$doc->preserveWhiteSpace = false; 
			$contentid = $doc->getElementById( 'jsnrender-component' );
			if (is_object($contentid)){
				$html .= $this->DOMinnerHTML( $contentid ); 
			}
		}
		$html .= '</div>';				
		return $html;
	}
	/**
	* 
	* Get Current Menu Id
	* 
	* @return: menu id
	*/
	public function getCurrentItemid()
	{
		$doc = new DOMDocument;
		if ( @$doc->loadhtml( $this->_contents ) ){
			$doc->preserveWhiteSpace = false;
			$component = $doc->getElementById( 'tableshow' );
			
			if (is_object($component)){
				return $component->getAttribute('itemid');
			}
		}
		
		return 0;
	}
	/**
	 * 
	 * Set URL for get front-end content. Correct URL
	 * 
	 * @param String $url
	 * @return: None
	 */
	public function setRenderUrl( $url = '' )
	{
		$uri = new JURI($url);
		if ($uri->getScheme() == ''){
			$scheme = 'http';
			if (@$_SERVER['HTTPS']){
				$scheme = 'https';
			}
			$uri->setScheme($scheme);
		}

		@list($host, $port) = explode(':', $_SERVER['HTTP_HOST']);
		
		if ( $uri->getHost() == '' ){
			$uri->setHost($host);
		}
		
		if ( $uri->getPort() == '' ){
			$uri->setPort($port);
		}

		if ( JString::strtolower($uri->getHost()) != JString::strtolower($host) ){
			$this->_isExternal = true;
		}else{
			if (!$uri->hasVar('poweradmin')){
				$uri->setVar('poweradmin', '1');
			}

			if ($uri->hasVar('Itemid') && $uri->getVar('Itemid') == ''){
				$uri->delVar('Itemid');
			}

			$this->_renderUrl = $uri->toString();
		}
	}
	/**
	 * 
	 * Get current render URL
	 * 
	 * @return: String
	 */
	public function getRenderUrl()
	{
		return $this->_renderUrl;
	}
	
	/**
	 * 
	 * Render site content
	 * 
	 * @param String $url
	 * @return: None
	 */
	public function renderPage( $url = '', $viewMode = 'jsnrender' )
	{
		$this->setRenderUrl($url);
		if ( !$this->isExternal() ){
			$this->getContents( $viewMode );
		}
	}	
}