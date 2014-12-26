<?php
/**
 * @version     $Id: helper.php 15230 2012-08-20 04:36:33Z cuongnm $
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

/**
 * Helper class of JSN Config library.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNConfigHelper
{
	/**
	 * Variable for storing config parameters.
	 *
	 * @var  array  Array of JObject objects.
	 */
	protected static $config;

	/**
	 * Get configuration data from database.
	 *
	 * If configuration data does not exist in database, default configuration
	 * value declared in the <b>administrator/components/com_YourComponentName/config.xml</b>
	 * file will be returned.
	 *
	 * @param   string  $component  Component to get configuration data for. Leave empty to get configuration data for requested component.
	 *
	 * @return  object  An instance of JObject class.
	 */
	public static function get($component = '')
	{
		// Initialize component
		! empty($component) OR $component = JFactory::getApplication()->input->getCmd('option');

		if ( ! isset(self::$config) OR ! isset(self::$config[$component]))
		{
			self::$config[$component] = new JObject;

			// Parse config.xml file for default parameter value
			$xml = JSNUtilsXml::load(JPATH_ADMINISTRATOR . DS . 'components' . DS . $component . DS . 'config.xml');

			foreach ($xml->xpath('//field["name"]') AS $field)
			{
				self::$config[$component]->set((string) $field['name'], (string) $field['default']);
			}

			// Get name of config data table
			$table = '#__jsn_' . preg_replace('/^com_/i', '', $component) . '_config';

			// Query database for config params
			$db    = JFactory::getDbo();
			$query = $db->getQuery(true);

			$query->select(array('name', 'value'));
			$query->from($table);
			$query->where('1');

			$db->setQuery($query);
			if ($rows = $db->loadObjectList())
			{
				// Finalize config params
				foreach ($rows AS $row)
				{
					// Decode value if it is a JSON encoded string
					if (substr($row->value, 0, 1) == '{' AND substr($row->value, -1) == '}')
					{
						$row->value = json_decode($row->value);
					}

					self::$config[$component]->set($row->name, $row->value);
				}
			}
		}

		return self::$config[$component];
	}

	/**
	 * Render configuration form for customization.
	 *
	 * This method generates then displays configuration form based on parsed
	 * configuration declaration returned by <b>JSNConfigModel::getForm</b>
	 * method.
	 *
	 * @param   array  $config  Parsed configuration declaration.
	 *
	 * @return  void
	 */
	public static function render($config)
	{
		require dirname(__FILE__) . DS . 'tmpl' . DS . 'default.php';
	}
}
