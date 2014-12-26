<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: template.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );
JSNFactory::localimport('libraries.joomlashine.database');

class JSNTemplate extends JSNDatabase{

	/* Private variable */
	private $_template = '';
	
	/* Private variable */
	private $_author   = 'default';
	
	/**
	* Constructor function
	*/
	public function __construct()
	{
		/* get template assigned */
		$this->_template = $this->getDefaultTemplate();
		
		/* get template mainfet*/
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);
		$this->_template->xml = new SimpleXMLElement( $client->path.DS.'templates'.DS.$this->_template->element.DS.'templateDetails.xml', null, true );
		
		/* get author template */
		$author = JString::trim(JString::strtolower($this->_template->xml->author));
		if ( !$author ){
			$author = JString::trim(JString::strtolower($this->_template->xml->authorEmail));
			if ( $author ){
				@list($eName, $eHost) = explode('@', $author);
				@list($this->_author, $dotCom) = explode('.', $author);
			}
		}else{
			@list($this->_author, $dotCom) = explode('.', $author);
		}

		if ( empty($this->_author) ){
			$this->_author = 'default';
		}
			
		switch( $this->_author )
		{
			case 'joomagic':
				//An template using T3 Framework
				$this->_author = 'joomlart';
			case 'joomlart':
				//$this->joomlart();
				break;
			case 'yootheme':
				//$this->yootheme();
				break;			
			case 'joomlaxtc':
				//$this->joomlaxtc();
				break;
		}
	}
	
	/**
	 * 
	 * Return global JSNTemplate object
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

		if (empty($instances['jsntemplate'])) {
			$instance	= new JSNTemplate();
			$instances['jsntemplate'] = &$instance;
		}

		return $instances['jsntemplate'];
	}
			
	/**
	 * 
	 * get template author
	 * 
	 * @return: String of author template
	*/
	public function getAuthor()
	{
		return $this->_author;
	}
	
	/**
	 * Load template position to javascript array
	 * 
	 * @return: Javascript array store position and attributes
	 * 
	*/
	public function loadArrayJavascriptTemplatePositions( $loadparameter = false )
	{	
		if ($loadparameter){
			$positions = $this->getTemplatePositions();
			if (count($positions) == 0){
				$positions = $this->loadXMLPositions();
			}

			$js_arr_positions[] = ' var positions = new Array('.count($positions).');';
			$js_arr_position_keys[] = 'var position_keys = new Array('.count($positions).');';
			for($i = 0; $i < count( $positions ); $i++){
				if (count($positions[$i]->params)){
					$params = '';
					foreach($positions[$i]->params as $key => $val){
						if ($params == ''){
							$params = $key.'='.$val;
						}else{
							$params .= ','.$key.'='.$val;
						}
					}
				}else{
					$params = '';
				}			
				$js_arr_positions[] = ' positions[\''.trim(strtolower($positions[$i]->name)).'\']= \''.trim(strtolower($positions[$i]->name)).'||'.$params.'\';';
				$js_arr_position_keys[] = ' position_keys[\''.$i.'\']= \''.trim(strtolower($positions[$i]->name)).'\';';
			}

			return implode(PHP_EOL, $js_arr_positions).PHP_EOL.implode(PHP_EOL, $js_arr_position_keys);

		}
		
		$positions = $this->loadXMLPositions();		
		
		$js_arr_positions[] = ' var positions = new Array();';
		for($i = 0; $i < count( $positions ); $i++){			
			$js_arr_positions[] = ' positions['.$i.']= \''.trim(strtolower($positions[$i]->name)).'\';';
		}	
		
		return implode(PHP_EOL, $js_arr_positions);
	}
	
	/**
	 * 
	 * Load template positions
	 * 
	 * @return: Array
	*/
	public function loadXMLPositions()
	{
		$specialProviders	=	array('joomlart');		
		if(in_array($this->_author, $specialProviders)){			
			$positions		= $this->loadOtherProviderPositions($this->_author);			
		}else{			
			$positions    	= array();
			$hasPositions 	= array();
	        $xml_positions 	= $this->_template->xml->xpath('//positions/position');
	        foreach ($xml_positions as $position)
	        {
	        	$position = (string) $position;
	        	if ( !in_array($position, $hasPositions) ){
					$_position = new stdClass();
					$_position->name = $position;
					$_position->params = array('style'=>'none');
					
					array_push($hasPositions, $position);
		            array_push($positions, $_position);
	        	}
        	}
		}
        return $positions;//JArrayHelper::sortObjects($positions, 'name', 1, true);
	}
	
	public function loadOtherProviderPositions ($author)
	{
		$funcname	=	$author.'PostionLoad';	
		if (method_exists ( $this,$funcname )){	
			return	$this->$funcname ();
		}
	}
	
	protected function joomlartPostionLoad ()
	{
		$positions    = array();
		$hasPositions = array();
		$jat3CommonFile = JPATH_ROOT.DS.'plugins'.DS.'system'.DS.'jat3'.DS.'jat3'.DS.'core'.DS.'common.php';
		if(file_exists($jat3CommonFile)){
			jimport($jat3CommonFile);
			$jat3_engine_layout_path	=	JPATH_ROOT.DS.'templates'.DS.'ja_puresite'.DS.'etc'.DS.'layouts'.DS.'default.xml';
			$layout_info =T3Common::getXML($jat3_engine_layout_path);		
			if (is_file($jat3_engine_layout_path)) {
	            $layout_info =T3Common::getXML($jat3_engine_layout_path);
		        foreach ($layout_info['children'] as $v)
		        {
		        	if($v['name'] == 'blocks'){
		        		foreach ($v['children'] as $block){
		        			if(!$block['data']){
		        				$position = (string) $block['attributes']['name'];
	        					if ( !in_array($position, $hasPositions) ){
			        				$_position = new stdClass();
									$_position->name = $position;
									$_position->params = array('style'=>'none');
									
									array_push($hasPositions, $position);
						            array_push($positions, $_position);
	        					}
		        			}else{
		        				$_l	=	explode(",", $block['data']);
		        				foreach ($_l as $position){
		        					$_position = new stdClass();
									$_position->name = $position;
									$_position->params = array('style'=>'none');
									
									array_push($hasPositions, $position);
						            array_push($positions, $_position);
		        				}
		        			}	
		        		}			        		
		        	}		        	
		        }                
	        }        
		}
        return $positions;
	}
	/**
	 * 
	 * Overwrite file modules loading of yootheme
	 * 
	 * @return: None return value. Overwrite file
	*/
	protected function yootheme()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);
		$versionFolder = 'joomla.'.substr(JVERSION, 0, 3);
		if (JVERSION >= 2.5){
			$modules_file = $client->path.DS.'templates'.DS.$this->_template->element.DS.'warp'.DS.'systems'.DS.'joomla'.DS.'layouts'.DS.'modules.php';
			$rename_path  = $client->path.DS.'templates'.DS.$this->_template->element.DS.'warp'.DS.'systems'.DS.'joomla'.DS.'layouts'.DS.'modules.JSN.ORG.php';
		}else{
			$modules_file = $client->path.DS.'templates'.DS.$this->_template->element.DS.'warp'.DS.'systems'.DS.$versionFolder.DS.'layouts'.DS.'modules.php';
			$rename_path  = $client->path.DS.'templates'.DS.$this->_template->element.DS.'warp'.DS.'systems'.DS.$versionFolder.DS.'layouts'.DS.'modules.JSN.ORG.php';
		}
		
		
		if ( file_exists($modules_file) ){
			$contents = JFile::read($modules_file);
		}else{
			$contents = '';
		}
		
		if (!preg_match('/jsn-element-container_inner/i', $contents)){
			jimport('joomla.filesystem.file');
			if ( !file_exists($rename_path) ){
				rename($modules_file, $rename_path);
			}
			if (JVERSION >= 2.5){
				JFile::copy( JSN_POWERADMIN_PLUGIN_CLASSES_OVERWRITE.'yootheme_modules_j25.php', $modules_file);
			}else{
				JFile::copy( JSN_POWERADMIN_PLUGIN_CLASSES_OVERWRITE.'yootheme_modules.php', $modules_file);
			}
		}
	}
	
	/**
	 * 
	 * Overwrite index.php of T3 template
	 * 
	 * @return: Overwrite file
	*/
	protected function joomlart()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);	
		$index_file = $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.php';
		jimport('joomla.filesystem.file');
		$contents = JFile::read($index_file);
		if ( !preg_match('/JSNT3Template/i', $contents) ){
			rename($index_file, $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.JSN.ORG.php');
			JFile::copy( JSN_POWERADMIN_PLUGIN_CLASSES_OVERWRITE.'joomlart.php', $index_file);
		}
	}
	/**
	 * 
	 * Helper JSNPOWERADMIN change joomlaxtc template 
	 * 
	 */
	protected function joomlaxtc()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);	
		$index_file = $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.php';
		jimport('joomla.filesystem.file');
		$contents = JFile::read($index_file);
		if ( !preg_match('/JSNJoomlaXTCHelper/i', $contents) ){
			rename($index_file, $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.JSN.ORG.php');
			JFile::copy( JSN_POWERADMIN_PLUGIN_CLASSES_OVERWRITE.'joomlaxtc.php', $index_file);
		}
	}
	/**
	 * 
	 * get information of positions in index.php of rockettheme template
	 * 
	 * @return: Array/NUll
	*/
	protected function rockettheme()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);
		$index_file_path = $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.php';
		
		if (file_exists( $index_file_path )){
			$file_contents = file_get_contents($index_file_path);

			if(preg_match_all('#displayModules(.*);#iU', $file_contents, $matches)) {
				$positions = $this->loadXMLPositions();
				$params = array();
				$i = 0;
				foreach($matches[1] as $matche){
					$params[$i] = explode(',', $matche);
					for($j = 0; $j < count($params[$i]); $j++){
						$params[$i][$j] = str_replace(array('(', "'", ')'), array('','',''), $params[$i][$j]);
					}
					$i++;
				}

				for($i = 0; $i < count($positions); $i++)
				{
					$position = $positions[$i];
					$positions[$i] = new stdClass();
					$positions[$i]->name = $position->name[0];
					$positions[$i]->params = array();
					foreach($params as $param)
					{
						if (preg_match('/'.$param[0].'/i', $positions[$i]->name)){
							$positions[$i]->params = array(
												'style'=>$param[1]
											);
							break;
						}
					}
				}
				return $positions;
			}
		}		
		return null;
	}	
	
	/**
	 * 
	 * get information of positions in index.php of gavickpro template
	 * 
	 * @return: Array/NULL
	*/
	protected function gavickpro()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);
		$layout_settings = $client->path.DS.'templates'.DS.$this->_template->element.DS.'lib'.DS.'framework'.DS.'gk.const.php';
		if ( !file_exists( $layout_settings ) ){
			return $this->defaultTemplate();
		}else{
			include( $layout_settings );

			$positions = array();
			foreach( $GK_TEMPLATE_MODULE_STYLES as $key => $value ){
				$position = new stdClass;
				$position->name = $key;
				$position->params = array(
									'style'=>$value
									);
				$positions[] = $position;
			}
			return $positions;
		}
	}
	/**
	 * 
	 * get information of positions index.php file
	 * 
	 * @return: Array
	*/
	protected function defaultTemplate()
	{
		$client = JApplicationHelper::getClientInfo($this->_template->client_id);
		$index_file_path = $client->path.DS.'templates'.DS.$this->_template->element.DS.'index.php';

		if (file_exists( $index_file_path )){
			$file_contents = file_get_contents($index_file_path);
			
			if(preg_match_all('#<jdoc:include\ type="([^"]+)" (.*)\/>#iU', $file_contents, $matches)) {
				$positions = array();
				
				$modules = $matches[2];
				foreach($modules as $module){
					if ($module != ""){
						$params = explode(' ', $module);
						$position = new stdClass;
						$position->name = str_replace(array('name="', '"'), array('', ''), $params[0]);
						$position->params = array();
						if (count($params) > 1){
							for($i = 1; $i < count($params); $i++){
								if ($params[$i] != ''){
									$tmp = explode('=', $params[$i]);
									if (count($tmp) > 1){							
										$position->params[$tmp[0]] = str_replace('"', '', $tmp[1]);
									}
								}
							}
						}
						$positions[] = $position;
					}					
				}
				return $positions;
			}
		}		
		return null;
	}
	
   /**
	 * 
	 * get includes in template
	 * 
	 * @return: Array/NULL
	*/
	public function getTemplatePositions()
	{
		switch( $this->_author )
		{
			case 'gavick':
				return $this->gavickPro();				
			case 'rockettheme':
				return $this->rockettheme();			
			case 'joomlajunkie':
			case 'yootheme':
				return;				
			case 'default':
			default:
				return $this->defaultTemplate();
		}
	}
}