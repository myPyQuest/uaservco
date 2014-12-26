<?php
/**
 * @version     $Id: model.php 15515 2012-08-27 04:21:01Z cuongnm $
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

// ImportJoomla model library
jimport('joomla.application.component.modelform');

/**
 * Model class of JSN Config library.
 *
 * To implement <b>JSNConfigModel</b> class, create a model file in
 * <b>administrator/components/com_YourComponentName/models</b> folder
 * then put following code into that file:
 *
 * <code>class YourComponentPrefixModelConfig extends JSNConfigModel
 * {
 * }</code>
 *
 * The <b>JSNConfigModel</b> class pre-defines <b>getForm</b> method for
 * parsing the file <b>administrator/components/com_YourComponentName/config.xml</b>
 * for configuration declaration. So, if you <b>overwrite the getForm</b>
 * method in your model class then JSN Config library will <b>fail to work</b>.
 *
 * @package  JSN_Framework
 * @since    1.0.0
 */
class JSNConfigModel extends JModelForm
{
	/**
	 * Parse configuration declaration.
	 *
	 * This method parses the <b>administrator/components/com_YourComponentName/config.xml</b>
	 * file for configuration declaration.
	 *
	 * @param   array    $data        Data to preset into form field.
	 * @param   boolean  $loadData    Load data for form field or not?
	 * @param   string   $configFile  Relative path from component's admin folder to config declaration XML file.
	 *
	 * @return  array  Array of stdClass objects.
	 */
	public function getForm($data = array(), $loadData = true, $configFile = 'config.xml')
	{
		// Load configuration declaration
		$xml = JSNUtilsXml::load(JPATH_COMPONENT_ADMINISTRATOR . DS . $configFile);

		// Parse configuration declaration
		return $this->loadSections($xml);
	}

	/**
	 * Load configuration sections.
	 *
	 * @param   object  $xml  Parsed XML declaration.
	 *
	 * @return  array  Array of stdClass objects.
	 */
	protected function loadSections($xml)
	{
		$sections = array();

		foreach ($xml->xpath('//section') AS $node)
		{
			// Parse section data then store
			$section = new stdClass;
			$section->name		= (string) $node['name'];
			$section->label		= (string) $node['label'];
			$section->groups	= $this->loadGroups($node);

			// Generate section key for storing
			$k = ! empty($section->name) ? $section->name : count($sections) - 1;

			$sections[$k] = $section;
		}

		return $sections;
	}

	/**
	 * Load configuration groups.
	 *
	 * @param   object  $xml  Parsed XML declaration.
	 *
	 * @return  array  Array of stdClass objects.
	 */
	protected function loadGroups($xml)
	{
		$groups = array();

		foreach ($xml->xpath('group') AS $node)
		{
			// Parse group data then store
			$group = new stdClass;
			$group->name	= (string) $node['name'];
			$group->label	= (string) $node['label'];
			$group->icon	= isset($node['icon']) ? (string) $node['icon'] : $node->name;
			$group->ajax 	= isset($node['ajax']) ? (int) $node['ajax']    : 1;

			if (count($tabs = $node->xpath('tab')))
			{
				// Parse tabs
				$group->tabs = $this->loadTabs($tabs);
			}
			else
			{
				// Parse field-sets
				$group->fieldsets = $this->loadFieldsets($node);
			}

			// Generate group key for storing
			$k = ! empty($group->name) ? $group->name : count($groups) - 1;

			$groups[$k] = $group;
		}

		return $groups;
	}

	/**
	 * Load configuration tabs.
	 *
	 * @param   array  $tabs  Array of tab declaration.
	 *
	 * @return  array  Array of stdClass objects.
	 */
	protected function loadTabs($tabs)
	{
		$_tabs = array();

		foreach ($tabs AS $tab)
		{
			// Parse tab data then store
			$_tab = new stdClass;
			$_tab->name			= (string) $tab['name'];
			$_tab->label		= (string) $tab['label'];
			$_tab->fieldsets	= $this->loadFieldsets($tab);

			$_tabs[] = $_tab;
		}

		return $_tabs;
	}

	/**
	 * Load configuration field-sets.
	 *
	 * @param   object  $xml  Parsed XML declaration.
	 *
	 * @return  array  Array of stdClass objects.
	 */
	protected function loadFieldsets($xml)
	{
		$fieldsets = array();

		foreach ($xml->xpath('fieldset') AS $node)
		{
			// Parse field-set data then store
			$fieldset = new stdClass;
			$fieldset->name		= (string) $node['name'];
			$fieldset->label	= (string) $node['label'];
			$fieldset->form		= $this->setupForm($node);

			$fieldsets[] = $fieldset;
		}

		return $fieldsets;
	}

	/**
	 * Setup form elements.
	 *
	 * @param   object  $xml  Parsed XML declaration.
	 *
	 * @return  object  Initialized JForm object.
	 */
	protected function setupForm($xml)
	{
		// Add field renderer paths
		if ( ! defined('JSN_CONFIG_FIELD_PATH_ADDED'))
		{
			JForm::addFieldPath(JSN_PATH_LIBRARIES . DS . 'joomlashine' . DS . 'config' . DS . 'fields');
			JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . DS . 'models' . DS . 'fields');

			define('JSN_CONFIG_FIELD_PATH_ADDED', 1);
		}

		// Prepare form declaration
		$formXML = '<config>' . preg_replace(array('/<(group|tab)\s/i', '#</(group|tab)>#i'), array('<fieldset ', '</fieldset>'), $xml->asXML()) . '</config>';

		// Load form object from XML
		$form = $xml['name'] ? (string) $xml['name'] : (string) $xml['label'];
		$form = $this->loadForm($form, $formXML, array('control' => 'jsnconfig', 'load_data' => true), false, '/config');

		// Store declared form actions
		foreach ($xml->xpath('action') AS $node)
		{
			$action = new stdClass;
			$action->label	= (string) $node['label'];
			$action->task	= (string) $node['task'];
			$action->ajax	= isset($node['ajax'])  ? (int) $node['ajax']  : 1;
			$action->track	= isset($node['track']) ? (int) $node['track'] : 1;

			$form->actions[] = $action;
		}

		return $form;
	}

	/**
	 * Load data for the configuration form.
	 *
	 * @return  array
	 */
	protected function loadFormData()
	{
		if ( ! isset($this->params))
		{
			// Preset the configuration data
			$this->params = array();

			// Get name of config data table
			$table = '#__jsn_' . preg_replace('/^com_/i', '', JFactory::getApplication()->input->getCmd('option')) . '_config';

			// Read config data from database
			$db = JFactory::getDbo();
			$db->setQuery("SELECT name, value FROM `{$table}` WHERE 1");
			if ($rows = $db->loadObjectList())
			{
				foreach ($rows AS $row)
				{
					// Decode value if it is a JSON encoded string
					if (substr($row->value, 0, 1) == '{' AND substr($row->value, -1) == '}')
					{
						$row->value = json_decode($row->value);
					}

					$this->params[$row->name] = $row->value;
				}
			}
		}

		return $this->params;
	}

	/**
	 * Validate the submitted configuration data.
	 *
	 * The <b>save</b> method of this class automatically calls this method
	 * before saving configuration data to database. So, you <b>DO NOT NEED</b>
	 * to call this method manually.
	 *
	 * @param   array   $config  Parsed XML config declaration.
	 * @param   array   $data    The data to validate.
	 * @param   string  $group   The name of the field group to validate.
	 *
	 * @return  void
	 */
	public function validate($config, $data, $group = null)
	{
		// Get input object
		$input = JFactory::getApplication()->input;

		// Get keys for first section and group
		$key      = array_keys($config);
		$fSection = array_shift($key);

		$key      = array_keys($config[$fSection]->groups);
		$fGroup   = array_shift($key);

		// Get requested section and group keys
		$rSection = $input->getCmd('s', $fSection);
		$rGroup   = $input->getCmd('g', $fGroup);

		// Validate config data
		if ( ! isset($config[$rSection]->groups[$rGroup]->tabs))
		{
			$config[$rSection]->groups[$rGroup]->tabs[] = (object) array('fieldsets' => $config[$rSection]->groups[$rGroup]->fieldsets);
		}

		foreach ($config[$rSection]->groups[$rGroup]->tabs AS $tab)
		{
			foreach ($tab->fieldsets AS $fieldset)
			{
				if (isset($fieldset->form) AND count($fieldset->form->getFieldset()))
				{
					$return = parent::validate($fieldset->form, $data);
					if ($return === false)
					{
						throw new Exception(JText::_('JSN_EXTFW_CONFIG_VALIDATION_FAIL'));
					}
				}
			}
		}
	}

	/**
	 * Save the submitted configuration data to database.
	 *
	 * @param   array  $config  Parsed XML declaration.
	 * @param   array  $data    The data to save.
	 *
	 * @return  void
	 */
	public function save($config, $data)
	{
		// Validate submitted form data
		try
		{
			$this->validate($config, $data);
		}
		catch (Exception $e)
		{
			throw $e;
		}

		// Get name of config data table
		$table = '#__jsn_' . preg_replace('/^com_/i', '', JFactory::getApplication()->input->getCmd('option')) . '_config';

		// Get database object
		$db = JFactory::getDbo();

		// Update config data table
		foreach ($data AS $k => $v)
		{
			if ( ! isset($this->params[$k]) OR json_encode($v) != json_encode($this->params[$k]))
			{
				if ($v != 'JSN_CONFIG_SKIP_SAVING')
				{
					// Encode value if it is either an array or object
					if (is_array($v) OR is_object($v))
					{
						$v = json_encode($v);
					}

					// Set query then execute
					$db->setQuery(
						isset($this->params[$k])	? "UPDATE `{$table}` SET value = '{$v}' WHERE name = '{$k}'"
													: "INSERT INTO `{$table}` (name, value) VALUES ('{$k}', '{$v}')"
					);
					try
					{
						$db->query();
					}
					catch (Exception $e)
					{
						throw $e;
					}
				}

				// Do additional instant update according to config change
				try
				{
					$this->instantUpdate($k, $data[$k]);
				}
				catch (Exception $e)
				{
					throw $e;
				}
			}
		}
	}

	/**
	 * Additional instant update according to configuration change.
	 *
	 * @param   string  $name   Name of changed config parameter.
	 * @param   mixed   $value  Recent config parameter value.
	 *
	 * @return  void
	 */
	protected function instantUpdate($name, $value)
	{
		// Get input object
		$input = JFactory::getApplication()->input;

		// Update message publishing state
		if ($name == 'messagelist')
		{
			// Get variables
			$before = $input->getVar('messagelist', array(), 'default', 'array');
			$after  = $input->getVar('messages', array(), 'default', 'array');

			// Update message configuration
			JSNUtilsMessage::saveConfig($before, $after);
		}
		// Install additional language
		elseif ($name == 'languagemanager')
		{
			// Get variable
			$lang = $input->getVar('languagemanager', array(), 'default', 'array');

			// Install backend languages
			if (isset($lang['a']))
			{
				JSNUtilsLanguage::install($lang['a']);
			}

			// Install frontend languages
			if (isset($lang['s']))
			{
				JSNUtilsLanguage::install($lang['s'], true);
			}
		}
		// Save the permissions
		elseif ($name == 'permissions')
		{
			// Initialize variables
			$component = $input->getCmd('option');
			$rules     = new JAccessRules($value);
			$asset     = JTable::getInstance('asset');

			if ( ! $asset->loadByName($component))
			{
				$root = JTable::getInstance('asset');
				$root->loadByName('root.1');

				$asset->name  = $component;
				$asset->title = $component;
				$asset->setLocation($root->id, 'last-child');
			}

			$asset->rules = (string) $rules;

			if ( ! $asset->check() OR ! $asset->store())
			{
				throw new Exception($asset->getError());
			}
		}
	}
}
