<?php
/**
 * @version     $Id$
 * @package     JSN_Framework
 * @subpackage  Config
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');


class JSNHtmlHelper
{
	/**
	 * 
	 * To valid W3C types
	 * @param unknown_type $tagName
	 * @param unknown_type $attrs
	 */
	public static function W3CValid(&$tagName, &$attrs){
		$tagName = strtolower(trim($tagName));
		switch($tagName)
		{
			case 'img':
				if (!array_key_exists('alt', $attrs)){
					$attrs += array('alt'=>'');
				}
				break;

			case 'a':
				if (!array_key_exists('title', $attrs)){
					$attrs += array('title'=>'');
				}
				break;
			
			case 'link':
		        if (!array_key_exists('rel', $attrs)){
					$attrs += array('rel'=>'stylesheet');
				}
				break;			
			
		}
	}
    
	/**
	 * 
	 * Open HTML tag and add attributes
	 * @param unknown_type $tagName
	 * @param unknown_type $attrs
	 */
	public static function openTag($tagName, $attrs = array())
    {
     	JSNHtmlHelper::W3CValid($tagName, $attrs);
     	$openTag = '<'.$tagName.' ';
     	if (count($attrs)){
	     	foreach($attrs as $key => $val){
	     		$openTag .= $key.'="'.$val.'" ';
	     	}
     	}
     	return $openTag.'>';
    }
    
    /**
     * 
     * Close HTML tag
     * @param $tagName
     */
    public static function closeTag($tagName)
    {
    	$tagName = strtolower(trim($tagName));
    	return '</'.$tagName.'>';
    }
    
    /**
     * 
     * Add an input tag and attributes
     * @param $type
     * @param $attrs
     */
    public static function addInputTag($type, $attrs = array())
    {
    	$tagName = 'input';
    	
    	JSNHtmlHelper::W3CValid($tagName, $attrs);
    	
    	$inputTag = '<'.$tagName.' type="'.$type.'" ';
    	if (count($attrs)){
	    	foreach($attrs as $key => $val){
	    		$inputTag .= $key.'="'.$val.'" ';
	    	}
    	}
    	return $inputTag.' />';
    }
    
    /**
     * 
     * Add an single HTML tag. <br />, <hr />,
     * @param $tagName
     * @param $attrs
     */
    public static function addSingleTag($tagName, $attrs)
    {
    	JSNHtmlHelper::W3CValid($tagName, $attrs);
    	
    	$singleTag = '<'.$tagName.' ';
    	if (count($attrs)){
	    	foreach($attrs as $key => $val){
	    		$singleTag .= $key.'="'.$val.'" ';
	    	}
    	}
    	
    	return $singleTag.'/>';
    }
    
    /**
	 * 
	 * Make an html select dropdown list
	 * @param unknown_type $attrs
	 */
	public static function makeDropDownList($items, $attrs = array())
	{
		$HTML  = JSNHtmlHelper::openTag('select', $attrs);
		for($i = 0; $i < count($items); $i++){
			$HTML .= JSNHtmlHelper::openTag('option', array('value'=>$items[$i]->value)).$items[$i]->text.JSNHtmlHelper::closeTag('option');
		}
		$HTML .= JSNHtmlHelper::closeTag('select');
		return $HTML;
	}
	
   /**
	 * 
	 * Return javascript tag 
	 * @param String $base_url
	 * @param String $filename
	 * @param String $code
	 */
	public static function addCustomScript( $base_url = '', $filename = '', $code = '')
	{
		$tagName = 'script';
		if ($code){
			return  JSNHtmlHelper::openTag($tagName, array('type'=>'text/javascript'))
			           .$code
			       .JSNHtmlHelper::closeTag($tagName);
		}else{
			return JSNHtmlHelper::openTag($tagName, array('src'=>$base_url.$filename, 'type'=>'text/javascript'))
			      .JSNHtmlHelper::closeTag($tagName);
		}
	}
	
	/**
	 * Return style tag and add css file to your page
	 * 
	 * @param: String $base_url is http path to your folder
	 * @param: String $filename is css file in your folder
	 * @param: String $code is your css codes
	 */
	public static function addCustomStyle( $base_url = '', $filename = '', $code = '')
	{
	    if ($code){
	    	$tagName = 'style';
			return  JSNHtmlHelper::openTag($tagName, array('type'=>'text/css'))
			          .$code
			       .JSNHtmlHelper::closeTag($tagName);
		}else{
			$tagName = 'link';
			return JSNHtmlHelper::addSingleTag($tagName, array('href'=>$base_url.$filename, 'type'=>'text/css', 'rel'=>'stylesheet'));
		}	
	}
}