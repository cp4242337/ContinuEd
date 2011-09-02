<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class ContinuEdModelUser extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'usr_id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_continued.user.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'User', $prefix = 'ContinuEdTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_continued.user', 'user', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_continued/models/forms/user.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_continued.edit.user.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			if ($this->getState('user.usr_id') == 0) {
				$app = JFactory::getApplication();
			}
		}
		return $data;
	}
	
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (empty($table->usr_id)) {
			// Set the values


		}
		else {
			// Set the values
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		return $condition;
	}
	
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();
		
		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);
			
			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}
		
		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');
		
		if (property_exists($item, 'params'))
		{
			$registry = new JRegistry();
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
		
		$q = 'SELECT user_group FROM #__ce_usergroup WHERE userg_user = '.$item->usr_user;
		$this->_db->setQuery($q);
		$item->usergroup=$this->_db->loadResult();
		
		return $item;
	}
	
	public function save($data)
	{
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;
		$userId=(int)$data['usr_user'];
		
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');
		
		// Allow an exception to be thrown.
		try
		{
			//Update Joomla User Info
			$user=JFactory::getUser($userId);
			$user->name=$data['fname']." ".$data['lname'];
			$user->email=$data['email'];
			$user->username=$data['username'];
			$user->password_clear=$data['password'];
			$user->block=$data['block'];
			$user->save();
			
			//Save ContinuEd Userinfo
			
			
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			
			return false;
		}
		
		
		
		if (isset($userId))
		{
			$this->setState($this->getName() . '.id', $userId);
		}
		$this->setState($this->getName() . '.new', $isNew);
		
		//Update Users Group
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->delete();
		$query->from('#__ce_usergroup');
		$query->where('user_user = '.$userId);
		$db->setQuery((string)$query);
		$db->query();
		
		if (!empty($data['usergroup'])) {
			$qc = 'INSERT INTO #__ce_usergroup (userg_user,userg_group) VALUES ('.$userId.','.(int)$data['usergroup'].')';
			$db->setQuery($qc);
			if (!$db->query()) {
				$this->setError($db->getErrorMsg());
				return false;
			} 
		}
		
		return true;
	}
	
	public function getUserGroups() {
		$query = 'SELECT ug_id, ug_name' .
				' FROM #__ce_ugroups' .
				' ORDER BY ug_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
