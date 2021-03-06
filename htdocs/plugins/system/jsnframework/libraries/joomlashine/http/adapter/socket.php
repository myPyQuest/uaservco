<?php
/**
 * @version     $Id$
 * @package     JSN_Framework
 * @subpackage  Http
 * @author      JoomlaShine Team <support@joomlashine.com>
 * @copyright   Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
 * @license     GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.joomlashine.com
 * Technical Support:  Feedback - http://www.joomlashine.com/contact-us/get-support.html
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Http Socket Adapter class
 *
 * @package  Adapter
 * @since    1.0.0
 */
class JSNHttpAdapterSocket extends JSNHttpAdapter
{
	/**
	 * Retrieve HTTP response header from an URL
	 * 
	 * @param   string  $url      URL to request
	 * @param   array   $headers  Custom headers for this request
	 * 
	 * @return  boolean
	 */
	public function head ($url, array $headers = array())
	{
		$uri  = $this->_parseURL($url);
		$path = !isset($uri['query']) ? $uri['path'] : $uri['path'] . '?' . $uri['query'];

		// General header
		$requestHeaders = array(
			'host' 			=> $uri['host'],
			'user-agent'	=> $this->_options[JSNHttpClient::USER_AGENT],
			'connection' 	=> 'close'
		);

		// Apply custom headers
		$requestHeaders = array_merge($requestHeaders, $headers);
		$requestHeaders = $this->_buildHeaders('HEAD', $path, $requestHeaders);

		return $this->_request($this->_createConnection($uri), $requestHeaders);
	}

	/**
	 * Make a request that use GET as request method
	 * 
	 * @param   string  $url      URL to request
	 * @param   array   $headers  Custom headers for this request
	 * 
	 * @return  boolean
	 */
	public function get ($url, array $headers = array())
	{
		$uri = $this->_parseURL($this->_getLastUrl($url, $headers));
		$path = !isset($uri['query']) ? $uri['path'] : $uri['path'] . '?' . $uri['query'];

		// General header
		$requestHeaders = array(
			'host' 			=> $uri['host'],
			'user-agent'	=> $this->_options[JSNHttpClient::USER_AGENT],
			'connection' 	=> 'close'
		);

		// Apply custom headers
		$requestHeaders = array_merge($requestHeaders, $headers);
		$requestHeaders = $this->_buildHeaders('GET', $path, $requestHeaders);

		return $this->_request($this->_createConnection($uri), $requestHeaders);
	}

	/**
	 * Make a POST request to an URL
	 * 
	 * @param   string  $url      URL to request
	 * @param   array   $data     Data that will be posted to the URL
	 * @param   array   $headers  Custom headers for this request
	 * 
	 * @return  boolean
	 */
	public function post ($url, array $data = array(), array $headers = array())
	{
		$uri 		= $this->_parseURL($url);
		$postData 	= array();

		// Request path
		$path 		= !isset($uri['query']) ? $uri['path'] : $uri['path'] . '?' . $uri['query'];

		// General header
		$requestHeaders = array(
			'host' 				=> $uri['host'],
			'user-agent' 		=> $this->_options[JSNHttpClient::USER_AGENT],
			'content-type'		=> 'application/x-www-form-urlencoded',
			'content-length'	=> strlen($postData),
			'connection' 		=> 'close'
		);

		// Apply custom headers
		$requestHeaders  = array_merge($requestHeaders, $headers);
		$requestHeaders  = $this->_buildHeaders('POST', $path, $requestHeaders);
		$requestHeaders .= $postData;

		return $this->_request($this->_createConnection($uri), $requestHeaders);
	}

	/**
	 * Create a HTTP Request to download a file from another server
	 * 
	 * @param   string  $url      URL to the file
	 * @param   array   $path     Path to save file
	 * @param   array   $headers  Custom headers for this request
	 * 
	 * @return  boolean
	 */
	public function download ($url, $path, array $headers = array())
	{
		$lastUrl 		= $this->_getLastUrl($url, $headers);
		$uri			= $this->_parseURL($lastUrl);
		$headResponse 	= $this->head($lastUrl, $headers);

		// File information
		$filename 		= basename($uri['path']);
		$filesize 		= $headResponse->headers['content-length'];

		// Parse file name from header
		if (isset($headResponse->headers['content-disposition'])
			&& preg_match('/filename=(.*)/i', $headResponse->headers['content-disposition'], $matched))
			$filename = trim($matched[1], '"');

		// Destination file
		$fileHandle = fopen($path . DIRECTORY_SEPARATOR . $filename, 'wb+');
		$connection = $this->_createConnection($uri);

		$requestHeaders = array(
			'host'			=> $uri['host'],
			'user-agent'	=> $this->_options[JSNHttpClient::USER_AGENT],
			'connection'	=> 'close'
		);

		$requestHeaders = array_merge($requestHeaders, $headers);
		$isHeaderEnded = false;
		$downloadedSize = 0;
		$isBreak = false;

		// Send header
		fwrite($connection, $this->_buildHeaders('GET', $uri['path'], $requestHeaders));

		// Start download file
		while (!feof($connection))
		{
			$buffer = fread($connection, $this->_options[JSNHttpClient::BUFFER_SIZE]);

			if (false !== strpos($buffer, "\r\n\r\n"))
			{
				$buffer = substr($buffer, strpos($buffer, "\r\n\r\n") + strlen("\r\n\r\n"));
				$isHeaderEnded = true;
			}

			if (true === $isHeaderEnded)
			{
				fwrite($fileHandle, $buffer);

				$event = new stdClass;
				$event->stop = false;

				$downloadedSize += strlen($buffer);
				$this->_notify('download.progress', array($event, $filesize, $downloadedSize));

				if ($event->stop === true) {
					$isBreak = true;
					break;
				}
			}
		}

		fclose($fileHandle);
		fclose($connection);
		
		// Invoke complete event
		$this->_notify('download.complete', array($filename));

		return $headResponse;
	}

	/**
	 * Find last URL after redirected
	 * 
	 * @param   string  $url      Beginning URL
	 * @param   array   $headers  Custom headers
	 * 
	 * @return  string
	 */
	private function _getLastUrl ($url, $headers = array())
	{
		if ($this->_options[JSNHttpClient::FOLLOW_LOCATION] == false)
			return $url;

		// Get response header to detect redirection
		$headResponse = $this->head($url, $headers);

		while (isset($headResponse->headers['location']) && $this->_redirectedTimes < $this->_options[JSNHttpClient::MAX_REDIRECTS])
		{
			$this->_redirectedTimes++;
			$url = $headResponse->headers['location'];
			$headResponse = $this->head($headResponse->headers['location']);
		}

		return $url;
	}

	/**
	 * Open a connection
	 * 
	 * @param   array  $uri  Parsed url information
	 * 
	 * @return  boolean
	 */
	private function _createConnection ($uri)
	{
		$hostname = $uri['protocol'] == 'ssl' ? "ssl://{$uri['host']}" : $uri['host'];
		$errorNum = 0;
		$errorMsg = '';

		$connection = @fsockopen(
			$hostname,
			$uri['port'],
			$errorNum,
			$errorMsg,
			$this->_options[JSNHttpClient::CONNECTION_TIMEOUT]
		);

		if ($errorNum > 0 || !empty($errorMsg))
			throw new Exception($errorMsg);

		// Disable stream blocking
		stream_set_blocking($connection, 0);

		return $connection;
	}

	/**
	 * Send a request to an connection
	 * 
	 * @param   resource  $connection  Existing connection to send headers
	 * @param   string    $headers     Header string that will sent to server
	 * 
	 * @return  object
	 */
	private function _request ($connection, $headers)
	{
		if (is_resource($connection))
		{
			// Send request header
			fwrite($connection, $headers);

			// Read all content
			$content = '';
			while (!feof($connection))
				$content .= fread($connection, $this->_options[JSNHttpClient::BUFFER_SIZE]);

			return $this->_parseResponse($content);
		}

		return null;
	}
}
