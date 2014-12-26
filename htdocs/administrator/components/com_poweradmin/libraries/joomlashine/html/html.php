<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: html.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;
class JSNHtml
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
     	JSNHtml::W3CValid($tagName, $attrs);
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
    	
    	JSNHtml::W3CValid($tagName, $attrs);
    	
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
    	JSNHtml::W3CValid($tagName, $attrs);
    	
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
		$HTML  = JSNHtml::openTag('select', $attrs);
		for($i = 0; $i < count($items); $i++){
			$HTML .= JSNHtml::openTag('option', array('value'=>$items[$i]->value)).$items[$i]->text.JSNHtml::closeTag('option');
		}
		$HTML .= JSNHtml::closeTag('select');
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
			return  JSNHtml::openTag($tagName, array('type'=>'text/javascript'))
			           .$code
			       .JSNHtml::closeTag($tagName);
		}else{
			return JSNHtml::openTag($tagName, array('src'=>$base_url.$filename, 'type'=>'text/javascript'))
			      .JSNHtml::closeTag($tagName);
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
			return  JSNHtml::openTag($tagName, array('type'=>'text/css'))
			          .$code
			       .JSNHtml::closeTag($tagName);
		}else{
			$tagName = 'link';
			return JSNHtml::addSingleTag($tagName, array('href'=>$base_url.$filename, 'type'=>'text/css', 'rel'=>'stylesheet'));
		}	
	}
}