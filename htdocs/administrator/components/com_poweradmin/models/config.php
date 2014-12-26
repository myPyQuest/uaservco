<?php
/*------------------------------------------------------------------------
# JSN PowerAdmin
# ------------------------------------------------------------------------
# author    JoomlaShine.com Team
# copyright Copyright (C) 2012 JoomlaShine.com. All Rights Reserved.
# Websites: http://www.joomlashine.com
# Technical Support:  Feedback - http://www.joomlashine.com/joomlashine/contact-us.html
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# @version $Id: config.php 12606 2012-05-12 03:55:46Z binhpt $
-------------------------------------------------------------------------*/

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

class PoweradminModelConfig extends JModelForm
{
	/**
	 * Config form instance
	 * @var JForm
	 */
	private $_form;
	
	/**
	 * Config data
	 * @var object
	 */
	private $_item;
	
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');
		
		if ($this->_form === null) {
			$this->_form = JForm::getInstance('com_poweradmin.config', 'config');
			foreach ($this->getItem() as $key => $value) {
				$this->_form->setValue($key, 'params', $value);
			}
		}
		
		return $this->_form;
	}

	public function getPermissionForm ($data = array (), $loadData = true)
	{
		$form = $this->loadForm('com_uniform.configuration', 'permissions', array ('control'   => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	/**
	 * Retrieve configuration information
	 * @return object
	 */
	public function getItem()
	{
		if (empty($this->_item)) {
			$this->_item = JComponentHelper::getParams('com_poweradmin')->toArray();
		}

		return $this->_item;
	}
	
	public function save ($params)
	{
		$encodedParams = json_encode($params);
		
		$this->_db->setQuery("UPDATE #__extensions SET params='{$encodedParams}' WHERE element='com_poweradmin' LIMIT 1");
		$this->_db->query();
	}
}

