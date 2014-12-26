<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: subinstall.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class JSNSubInstaller extends JObject
{
    private $_source 		= null;
    private $_db 			= null;
    private $_app 			= null;
    private $_subinstall 	= null;
	private $_error 		= array();
	private $_lang 			= array();

    public function __construct()
    {
        global $languages;
    	$parent 			= JInstaller::getInstance();
        $manifest 			= $parent->getManifest();
        $this->_db 			= $parent->getDbo();
        $this->_source 		= $parent->getPath('source');
        $this->_subinstall 	= $manifest->subinstall;
        $this->_app 		= JFactory::getApplication();        
        $_lang 				= array();
        if(isset($languages)){
	        foreach ($languages as $k=>$v)
	        {
	        	$_lang[$v['code']] = $v['code'];
	        }
	        $this->_lang 		= $_lang;
        }
    }

	public function setError($value)
	{
		$this->_error[] = $value;
	}

	public function getError()
	{
		return $this->_error;
	}

    private function _msg($msg, $type = 'message')
    {
        if (!empty($msg))
		{
            $this->_app->enqueueMessage('Sub-Install: '.$msg, $type);
        }
    }

    private function _getExtID($element)
	{
		if (!is_object($element))
		{
            return false;
        }
        $query = '';
        switch ($element->type)
		{
			case 'module':
				$name = $element->name;
				if (strncmp($name, 'mod_', 4) != 0)
				{
					$name = 'mod_' . $name;
				}
				$query = 'SELECT extension_id FROM #__extensions WHERE type = "module" AND element = "'.$name.'" AND client_id = '.$element->client.' GROUP BY folder, element';
				break;
			case 'plugin':
				$query = 'SELECT extension_id FROM #__extensions WHERE type = "plugin" AND element = "'.$element->name.'" AND client_id = '.$element->client.' AND folder = "'.$element->folder.'" GROUP BY folder, element';
				break;
			case 'component':
				$name = $element->name;
				if (strncmp($name, 'com_', 4) != 0)
				{
					$name = 'com_' . $name;
				}
				 $query = 'SELECT extension_id FROM #__extensions WHERE type = "component" AND element = "'.$element->name.'" AND client_id = '.$element->client;
				break;
		}

        if ($query != '')
		{
            $this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
            return $this->_db->loadResult();
        }
        return false;
    }

    private function _enable($id, $name = '', $type = 'plugin')
	{
        $query = '';
        switch ($type)
		{
			case 'plugin':
				$query = 'UPDATE #__extensions SET enabled = 1 WHERE extension_id = '.$id;
            break;
         	case 'module':
            	$query = 'UPDATE #__modules SET published = 1, ordering = 99 WHERE module = "'.$name.'"';
				$this->_setPageForModule($name, 0);
            break;
			default:
			break;
        }

        if ($query != '')
		{
            $this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
        }
        return true;
    }

    private function _setposition($id, $name, $position, $type = 'module')
    {
        $query = '';
        switch ($type)
        {
        	case 'module':
            	$query = 'UPDATE #__modules SET position = "'.$position.'" WHERE module = "'.$name.'"';
            break;
 			default:
			break;
        }

        if ($query != '')
        {
            $this->_db->setQuery($query);
            if (!$this->_db->query())
            {
                return false;
            }
        }
        return true;
    }

    private function _setprotect($id, $type = 'plugin', $lock)
	{
        $query = '';
        switch ($type)
		{
			case 'module':
			case 'plugin':
			case 'component':
				$query = 'UPDATE #__extensions SET protected = '.$lock.' WHERE extension_id = '.$id;
			break;
			default:
			break;
        }
        if ($query != '')
		{
			$this->_db->setQuery($query);
            if (!$this->_db->query())
			{
                return false;
            }
        }
        return true;
    }

    private function _convertToBool($arg = null)
	{
        if (!empty($arg)) {
            return ((strcasecmp($arg, 'true') == 0) || ($arg == '1'));
        }
		
        return false;
    }

    function _parseAttributes($element, $status = 1)
	{
        $obj 		= new stdClass();
        $obj->skip   = ($element->name() != 'extension');
		
        if ($obj->skip) {
            return $obj;
        }
		
        $obj->type = (string) $element->attributes()->type;
        $obj->name = (string) $element->attributes()->name;
        $obj->data = (string) $element->data();
		
		$subdir = (string) $element->attributes()->subdir;
        if (empty($obj->data)) {
            $obj->data = $obj->name;
        }
		
        $obj->position  = $element->attributes()->position;
        $obj->folder 	= (string)$element->attributes()->folder;
        $obj->publish 	= $this->_convertToBool((string) $element->attributes()->publish) ? 1 : 0;
		$obj->core 		= $this->_convertToBool((string) $element->attributes()->lock) ? 1 : 0;
        $client 		= $element->attributes()->client;

        if (!$obj->type) {
            return false;
        }

        switch ($obj->type)
		{
			case 'plugin':
				if (!$obj->folder) {
					return false;
				}
				
				$client = 'site';
			break;
			
			case 'component':
				$client = 'site';
			break;
			
			case 'module':
			break;
			
			default:
				return false;
        }

        if (!$obj->name) {
            return false;
        }

        if (!$client) {
            return false;
        }
		
		if ($status) {
			if (!empty($subdir)) {
				$obj->source = $this->_source.DS.$subdir;
				if (!is_dir($obj->source)) {
					return false;
				}
			}
		}

        $obj->client = 0;
        switch ($client)
		{
			case 'site':
				break;
			case 'admin':
				$obj->client = 1;
				break;
			default:
				return null;
        }

        return $obj;
    }
	
	function _checkPermissions ()
	{
		$resultCheckPlgContent 	= false;
    	$resultCheckPlgSystem 	= false;
    	$resultCheckModule 		= false;
    	$resultCheckLangBO 		= false;
    	$resultCheckLangFO	    = false;

    	$resultCheckLangBO = $this->_checkFolderLangBOPermission();
    	if ($resultCheckLangBO === false) {
    		$this->setError('lgcheck');
    	}

    	$resultCheckLangFO = $this->_checkFolderLangFOPermission();
    	if ($resultCheckLangFO === false) {
    		$this->setError('lgcheckfo');
    	}

    	/*$resultCheckPlgContent 	= $this->_checkWritablePluginContent();
    	if ($resultCheckPlgContent === false) {
    		$this->setError('plgcontent');
    	}*/

    	$resultCheckPlgSystem 	= $this->_checkWritablePluginSystem();
     	if ($resultCheckPlgSystem === false) {
    		$this->setError('plgsystem');
    	}

    	/*$resultCheckModule 		= $this->_checkWritableModule();
      	if($resultCheckModule === false) {
    		$this->setError('module');
    	}*/

		if (//$resultCheckPlgContent == false || 
			$resultCheckPlgSystem == false || 
			//$resultCheckModule == false || 
			$resultCheckLangBO == false || 
			$resultCheckLangFO == false) {
			
			return false;
		}
		
		return true;
	}

    public function install()
	{
		$this->_disableAdminBarPlugin();
		
    	if (!$this->_checkPermissions()) {
			JInstaller::getInstance()->abort(JText::_('Invalid folder(s) permission'));
			return false;
		}

        if ($this->_subinstall instanceof JXMLElement)
		{
            $nodes = $this->_subinstall->children();
            if (count($nodes) == 0) {
                return false;
            }

            foreach ($nodes as $node)
			{
				$ext = $this->_parseAttributes($node);
				if (!is_object($ext)) {
                    return false;
                }
				
                if ($ext->skip) {
                    continue;
                }

                $objInstaller 	= new JInstaller();
				$result 		= $objInstaller->install($ext->source);

                $smsg 			= $objInstaller->get('message');
                $msg 			= $objInstaller->get('extension.message');
                
				if (!empty($msg)) {
                    echo $msg;
                }
				
                if ($result)
				{
					if ($ext->publish) {
                        $id = $this->_getExtID($ext);
						if ($id) {
							if (!$this->_enable($id, $ext->name, $ext->type)) {
								return false;
							}
                        }
                    }
					
                    if ($ext->core) {
                        $id = $this->_getExtID($ext);
                        if ($id) {
                            $this->_setprotect($id, $ext->type, 1);
                        }
                    }
					
					if (!is_null($ext->position) && $ext->position != '' && $ext->type == "module") {
						$id = $this->_getExtID($ext);
                        if ($id != null) {
                            $this->_setposition($id, $ext->name, $ext->position, $ext->type);
                        }
                    }
                }
				else {
                    return false;
                }
            }
        }
		
        return true;
    }

    function uninstall()
	{
        if ($this->_subinstall instanceof JXMLElement)
		{
            $nodes = $this->_subinstall->children();
			
            if (count($nodes) == 0) {
                return false;
            }

            foreach ($nodes as $node)
			{
                $ext = $this->_parseAttributes($node, 0);

                if (!is_object($ext)) {
                    return false;
                }
				
				$id = $this->_getExtID($ext);
                if ($id)
				{
					if ($ext->core) {
                        $this->_setprotect($id, $ext->type, 0);
                    }
					
					$objInstaller 	= new JInstaller();
                    $result 		= $objInstaller->uninstall($ext->type, $id);
                    $msg 			= $objInstaller->get('message');
                    $this->_msg($msg, $result ? 'message' : 'warning');
                    $msg = $objInstaller->get('extension.message');
					
                    if (!empty($msg)) {
                        echo $msg;
                    }
					
                    if ($result) {
                        $this->_msg('Successfully removed '.$ext->type.' "'.$ext->data.'".');
                    }
                }
            }
        }
		
        return true;
    }

	/**
	 * Remove adminbar plugin from joomla
	 * @return void
	 */
	private function _disableAdminBarPlugin ()
	{
		$pluginPath = JPATH_ROOT.DS.'plugins/system/jsnadminbar';
		
		$dbo = JFactory::getDBO();
		$dbo->setQuery("UPDATE #__extensions SET enabled=0 WHERE element='jsnadminbar' LIMIT 1");
		$dbo->query();
	}
	
	private function _checkWritablePluginContent()
	{
		return is_writable(JPATH_ROOT.DS.'plugins'.DS.'content');
	}

	private function _checkWritablePluginSystem()
	{
		return is_writable(JPATH_ROOT.DS.'plugins'.DS.'system');
	}

	private function _checkWritableModule()
	{
		return is_writable(JPATH_ROOT.DS.'modules');
	}

	private function _checkFolderLangPermission($base, $nameError)
	{
		jimport('joomla.filesystem.folder');
		
		$session 	= JFactory::getSession();
		$folders 	= JFolder::folders($base, '.', false, true);
		$arrayError	= array();
		
		foreach ($folders as $folder)
		{
			if (array_key_exists(basename($folder), $this->_lang))
			{
				if (is_writable($folder)) {
					$arrayError[basename($folder)] = 'yes';
				}
				else {
					$arrayError[basename($folder)] = 'no';
				}
			}
		}
		
		$session->set($nameError, $arrayError);
		return true;
	}

	private function _checkFolderLangBOPermission()
	{
		$filepath 	= JPATH_ROOT.DS.'administrator'.DS.'language';
		$this->_checkFolderLangPermission($filepath, 'jsn_install_folder_admin');
		$session 	= JFactory::getSession();
		$result 	= $session->get( 'jsn_install_folder_admin' );

		foreach ($result as $value) {
			if($value == 'no') return false;
		}
		
		return true;
	}

	private function _checkFolderLangFOPermission()
	{
		$filepath 	= JPATH_ROOT.DS.'language';
		$this->_checkFolderLangPermission($filepath, 'jsn_install_folder_client');
		$session 	= JFactory::getSession();
		$result 	= $session->get( 'jsn_install_folder_client' );

		foreach ($result as $value) {
			if($value == 'no') return false;
		}
		
		return true;
	}
	
	private function _getModuleInformation($moduleName)
	{
		$query 	= 'SELECT * FROM #__modules WHERE module = '.$this->_db->Quote($moduleName, false);
		$this->_db->setQuery($query);
		
		return $this->_db->loadObject();
	}
	
	private function _setPageForModule($moduleName, $value)
	{
		$moduleInfo = $this->_getModuleInformation($moduleName);
		$query = 'SELECT COUNT(*) FROM #__modules_menu WHERE moduleid = '. (int) $moduleInfo->id;
		$this->_db->setQuery($query);
		$result = (int) $this->_db->loadResult();
		
		if (!$result)
		{
			$query = 'INSERT INTO #__modules_menu (moduleid, menuid) VALUES ("'.(int) $moduleInfo->id.'", "'.(int) $value.'")';
			$this->_db->setQuery($query);
			
			return $this->_db->query();
		}
		
		return true;
	}
}