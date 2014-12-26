<?php
/**
 * @version     $Id: helper.php 16131 2012-09-19 02:10:04Z cuongnm $
 * @package     JSN_Framework
 * @subpackage  Update
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
 * Helper class for JSN Update implementation.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNUpdateHelper
{
	/**
	 * Parsed check update URL.
	 *
	 * @var	array
	 */
	protected static $versions;

	/**
	 * Communicate with JoomlaShine server for latest product version.
	 *
	 * If a product does not have sub-product, the <b>$products</b> parameter
	 * does not required when calling this method:
	 *
	 * <pre>JSNUpdateHelper::check();</pre>
	 *
	 * Result will be returned in the following format:
	 *
	 * <pre>If product update is available:
	 * <code>array(
	 *     'identified_name' => object(
     *         'name' => 'The product name',
     *         'identified_name' => 'The product identification',
     *         'description' => 'The product description',
     *         'version' => 'The latest product version',
     *         'authentication' => 'Indicates whether authentication is required when updating product'
     *     )
	 * )</code>
	 * If the product does not have update:
	 * <code>array(
	 *     'identified_name' => false
	 * )</code></pre>
	 *
	 * If a product has sub-product, this method need to be called as below to
	 * check all sub-product for latest version:
	 *
	 * <pre>JSNUpdateHelper::check(
	 *     array(
	 *         // Core component
	 *         'imageshow' => '4.2.0',
	 *
	 *         // Themes
	 *         'themeclassic' => '1.1.5',
	 *         'themeslider'  => '1.0.4',
	 *         'themegrid'    => '1.0.0',
	 *
	 *         // Sources
	 *         'picasa'      => '1.1.2',
	 *         'flickr'      => '1.1.2',
	 *         'phoca'       => '1.0.1',
	 *         'joomgallery' => '1.0.1',
	 *         'rsgallery2'  => '1.0.1',
	 *         'facebook'    => '1.0.1'
	 *     )
	 * );</pre>
	 *
	 * In this case, the returned result might look like below:
	 *
	 * <pre>array(
	 *     // Core component
	 *     'imageshow' => object(
     *         'name' => 'JSN ImageShow',
     *         'identified_name' => 'imageshow',
     *         'description' => 'Something about JSN ImageShow',
     *         'version' => '4.3.0',
     *         'editions' => array(
     *             0 => object(
     *                 'edition' => 'PRO STANDARD',
     *                 'authentication' => 1
     *             ),
     *             1 => object(
     *                 'edition' => 'PRO UNLIMITED',
     *                 'authentication' => 1
     *             ),
     *             2 => object(
     *                 'edition' => 'FREE',
     *                 'authentication' => 0
     *             )
     *         )
	 *     ),
	 *
	 *     // Themes
	 *     'themeclassic' => false, // Product update not available
	 *     'themeslider' => false,  // Product update not available
	 *     'themegrid' => object(
     *         'name' => 'Theme Grid',
     *         'identified_name' => 'themegrid',
     *         'description' => 'JSN ImageShow Theme Grid plugin',
     *         'version' => '1.0.1',
     *         'edition' => 'FREE',
     *         'authentication' => 0
	 *     ),
	 *
	 *     // Sources
	 *     'picasa' => false,      // Product update not available
	 *     'flickr' => false,      // Product update not available
	 *     'phoca' => false,       // Product update not available
	 *     'joomgallery' => false, // Product update not available
	 *     'rsgallery2' => false,  // Product update not available
	 *     'facebook' => object(
     *         'name' => 'FaceBook',
     *         'identified_name' => 'facebook',
     *         'description' => 'JSN ImageShow Image Source Facebook plugin',
     *         'version' => '1.0.2',
     *         'edition' => 'FREE',
     *         'authentication' => 0
	 *     )
	 * )</pre>
	 *
	 * @param   array  $products  Array of identified name for checking latest version.
	 * @param   array  $servers   Parameter for self recursive call.
	 *
	 * @return  mixed
	 */
	public static function check($products = array(), $servers = '')
	{
		// Only communicate with server if check update URLs is not load before
		if (empty($servers))
		{
			if ( ! isset(self::$versions))
			{
				// Communicate with JoomlaShine server via latest version checking URL
				try
				{
					self::$versions = JSNUtilsHttp::get(JSN_EXT_VERSION_CHECK_URL);
					self::$versions = json_decode(self::$versions['body']);
				}
				catch (Exception $e)
				{
					throw new Exception(JText::_('JSN_EXTFW_VERSION_CHECK_FAIL'));
				}
			}

			$servers = self::$versions;
		}

		// Preset return results
		static $results;
		is_array($results) OR $results = array();

		// Prepare product identification
		if ( ! is_array($products) OR ! count($products))
		{
			is_array($products) OR $products = array();

			// Get the product info
			$info = JSNUtilsXml::loadManifestCache();

			// Is identified name defined?
			if ($const = JSNUtilsText::getConstant('IDENTIFIED_NAME'))
			{
				$products[$const] = $info->version;
			}
			// Generate product identified name
			else
			{
				$products[strtolower($info->name)] = $info->version;
				$products['ext_' . preg_replace('/^com_/i', '', strtolower($info->name))] = $info->version;
			}
		}

		// Get the latest product version
		foreach ($products AS $product => $current)
		{
			if ( ! isset($results[$product]))
			{
				foreach ($servers->items AS $item)
				{
					if (isset($item->items))
					{
						self::check(array($product => $current), $item);
						continue;
					}

					if (isset($item->identified_name) AND $item->identified_name == $product)
					{
						$results[$product] = $item;
						break;
					}
				}

				// Does latest product info found?
				if (isset($results[$product]) AND is_object($results[$product]))
				{
					// Does product have newer version?
					if ( ! version_compare($results[$product]->version, $current, 'gt'))
					{
						$results[$product] = false;
					}
				}
			}
		}

		return $results;
	}

	/**
	 * Render the product update page.
	 *
	 * If a product does not have sub-product, the <b>$products</b> parameter
	 * does not required when calling this method:
	 *
	 * <pre>JSNUpdateHelper::render($info);</pre>
	 *
	 * If a product has sub-product, this method need to be called similar to
	 * the example below to update all sub-product to latest version:
	 *
	 * <pre>JSNUpdateHelper::render(
	 *     $info,
	 *     array(
	 *         // Core component
	 *         'imageshow' => '4.2.0',
	 *
	 *         // Themes
	 *         'themeclassic' => '1.1.5',
	 *         'themeslider'  => '1.0.4',
	 *         'themegrid'    => '1.0.0',
	 *
	 *         // Sources
	 *         'picasa'      => '1.1.2',
	 *         'flickr'      => '1.1.2',
	 *         'phoca'       => '1.0.1',
	 *         'joomgallery' => '1.0.1',
	 *         'rsgallery2'  => '1.0.1',
	 *         'facebook'    => '1.0.1'
	 *     )
	 * );</pre>
	 *
	 * @param   object  $info      JSON decoded extension's manifest cache.
	 * @param   array   $products  Array of identified name for checking latest version.
	 *
	 * @return  void
	 */
	public static function render($info, $products = array(), $redirAfterFinish = '')
	{		
		require dirname(__FILE__) . DS . 'tmpl' . DS . 'default.php';
	}
}
