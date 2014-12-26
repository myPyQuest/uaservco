<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: base.php 12506 2012-05-09 03:55:24Z hiennh $
-------------------------------------------------------------------------*/
defined( '_JEXEC' ) or die( 'Restricted access' );

class JSNPowerAdminBarSupportBase extends JSNPowerAdminBarPreviewAdapter
{
	/**
	 * Preview links mapping
	 * @var array
	 */
	private $maps = array();
	
	/**
	 * (non-PHPdoc)
	 * @see JSNPowerAdminBarPreviewAdapter::getPreviewLink()
	 */
	public function getPreviewLink ()
	{
		$matchedMap = null;
		
		foreach ($this->maps as $map) {
			$params = array();
			parse_str($map['params'], $params);
			
			$isMatched = true;
			foreach ($params as $key => $value) {
				if (!isset($this->params[$key]) || $this->params[$key] != $value) {
					$isMatched = false;
					break;
				}
			}
			
			if ($isMatched) {
				$matchedMap = $map;
				break;
			}
		}
		
		if ($matchedMap != null) {
			$link = preg_replace('/\{@([^\}]+)\}/ie', '@$this->params[\'\\1\']', $matchedMap['link']);
			if (strpos($link, 'option=') === false)
				$link = 'option='.$this->option.'&'.$link;
			
			return sprintf('index.php?%s', $link);
		}
		
		return parent::getPreviewLink();
	}
	
	public function parseXml ($xmlFile)
	{
		if (!is_file($xmlFile))
			return;
		
		$xml = simplexml_load_file($xmlFile);
		foreach ($xml->xpath('/preview/map') as $map) {
			$attributes = $map->attributes();
			$links 		= array();
			
// 			foreach ($map->link as $link) {
// 				$linkAttr = $link->attributes();
// 				$links[]  = urldecode($linkAttr['params']);
// 			}
			
			$this->maps[] = array(
				'params' 	=> urldecode($attributes['params']),
				'link'		=> urldecode($attributes['link'])
			);
		}
	}
}