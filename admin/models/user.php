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
	protected function allowEdit($data = array(), $key = 'usr_user')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_continued.user.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	
	/**
	 * Stock method to auto-populate the model state.
	 *
	 * @return  void
	 * @since   11.1
	 */
	protected function populateState()
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');
		//$table = $this->getTable();
		$key = 'usr_user';

		// Get the pk of the record from the request.
		$pk = JRequest::getInt($key);
		$this->setState($this->getName().'.id', $pk);

		// Load the parameters.
		$value = JComponentHelper::getParams($this->option);
		$this->setState('params', $value);
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
		//there is no table
		return 0;
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
		//there is no form
		return 0;
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : JRequest::getInt('id',0);;
		
		//set item variable
		$qu='SELECT username,block,email FROM #__users WHERE id = '.$pk;
		$this->_db->setQuery($qu);
		$item = $this->_db->loadObject();
		
		$fields = $this->getFields(false);
		foreach ($fields as $f) {
			$fn = $f->uf_sname;
			if (!isset($item->$fn)) $item->$fn = '';
		}
		
		//get data for user fields
		$q =  'SELECT u.*,f.uf_sname,f.uf_type FROM #__ce_users as u ';
		$q .= 'RIGHT JOIN #__ce_ufields as f ON u.usr_field = f.uf_id ';
		$q .= 'WHERE usr_user = '.$pk;
		$this->_db->setQuery($q); 
		$data=$this->_db->loadObjectList();
		
		$item->usr_user = $pk;
		foreach ($data as $d) {
			$fieldname = $d->uf_sname;
			if ($item->$fieldname == '') $item->$fieldname = $d->usr_data;
			if ($d->uf_type=="mcbox") $item->$fieldname = explode(" ",$item->$fieldname);
		}
				
		//get users group
		$qg = 'SELECT userg_group FROM #__ce_usergroup WHERE userg_user = '.$item->usr_user;
		$this->_db->setQuery($qg);
		$item->usergroup=$this->_db->loadResult();
		
		return $item;
	}
	
	public function save($data)
	{
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$isNew = true;
		$userId=(int)$data['usr_user'];
		$db		= $this->getDbo();
		
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');
		
		// Allow an exception to be thrown.
		try
		{
			//setup item and bind data
			$flist = $this->getFields(false);
			foreach ($flist as $d) {
				$fieldname = $d->uf_sname;
				$item->$fieldname = $data[$fieldname];
			}
			
			//Update Joomla User Info
			$user=JFactory::getUser($userId);
			$user->name=$item->fname." ".$item->lname;
			$user->email=$item->email;
			$user->username=$item->username;
			$user->password_clear=$item->password;
			$user->block=$item->block;
			$user->save();
			
			//remove joomla user info from item
			unset($item->email);
			unset($item->cemail);
			unset($item->password);
			unset($item->cpassword);
			unset($item->block);
			unset($item->username);
			
			
			//Save ContinuEd Userinfo
			$query	= $db->getQuery(true);
			$query->delete();
			$query->from('#__ce_users');
			$query->where('usr_user = '.$userId);
			$db->setQuery((string)$query);
			$db->query();
			
			$flist = $this->getFields(false);
			foreach ($flist as $fl) {
				$fieldname = $fl->uf_sname;
				if (isset($item->$fieldname)) {
					if ($fl->uf_type=="mcbox") $item->$fieldname = implode(" ",$item->$fieldname);
					$qf = 'INSERT INTO #__ce_users (usr_user,usr_field,usr_data) VALUES ("'.$userId.'","'.$fl->uf_id.'","'.$item->$fieldname.'")';
					$db->setQuery($qf);
					if (!$db->query()) {
						$this->setError($db->getErrorMsg());
						return false;
					}
				}
			}
			
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
		$query	= $db->getQuery(true);
		$query->delete();
		$query->from('#__ce_usergroup');
		$query->where('userg_user = '.$userId);
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
		$query = 'SELECT ug_id as value, ug_name as text' .
				' FROM #__ce_ugroups' .
				' ORDER BY ug_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
	public function getFields($all = true) {
		$q  = 'SELECT * FROM #__ce_ufields WHERE published > 0';
		$q .= ' && uf_type != "message"';
		$q .= ' ORDER BY ordering';
		$this->_db->setQuery($q);
		$fields=$this->_db->loadObjectList();
		if ($all) {
			foreach ($fields as &$f) {
				switch ($f->uf_type) {
					case 'multi':
					case 'dropdown':
					case 'mcbox':
						$qo = 'SELECT opt_id as value, opt_text as text FROM #__ce_ufields_opts WHERE opt_field='.$f->uf_id.' && published > 0 ORDER BY ordering';
						$this->_db->setQuery($qo);
						$f->options = $this->_db->loadObjectList();
						break;
				}
			}
		}
		return $fields;
	}
	
	/**
	 * Method to validate the form data.
	 *
	 * @param   object  $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the field group to validate.
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 *
	 * @see     JFormRule
	 * @see     JFilterInput
	 * @since   11.1
	 */
	function validate($data, $group = null)
	{
		// Filter and validate the form data.
		$return	= true; //$form->validate($data, $group);

		// Check for an error.
		if (JError::isError($return)) {
			$this->setError($return->getMessage());
			return false;
		}

		// Check the validation results.
		if ($return === false) {
			// Get the validation messages from the form.
			foreach ($form->getErrors() as $message) {
				$this->setError(JText::_($message));
			}

			return false;
		}

		return $data;
	}
	
}
