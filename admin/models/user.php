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
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	public function getItem($pk = null)
	{
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		
		//get data for user fields
		$q =  'SELECT u.*,f.uf_sname FROM #__ce_users as u ';
		$q .= 'RIGHT JOIN #__ce_ufields as f ON u.usr_field = f.uf_id ';
		$q .= 'WHERE usr_user = '.$pk;
		$this->_db->setQuery($qg);
		$data=$this->_db->loadResult();
		
		foreach ($data as $d) {
			$fieldname = $d->uf_sname;
			$item->$fieldname = $d->usr_data;
		}
				
		//get users group
		$qg = 'SELECT user_group FROM #__ce_usergroup WHERE userg_user = '.$item->usr_user;
		$this->_db->setQuery($qg);
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
		$db		= $this->getDbo();
		
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
			$query	= $db->getQuery(true);
			$query->delete();
			$query->from('#__ce_users');
			$query->where('usr_user = '.$userId);
			$db->setQuery((string)$query);
			$db->query();
			
			$q = 'SELECT * FROM #__ce_ufields WHERE uf_type != "password"';
			$db->setQuery($q);
			$flist = $db->loadObjectList();
			foreach ($flist as $fl) {
				$qf = 'INSERT INTO #__ce_users (usr_user,usr_field,usr_data) VALUES ("'.$userId.'","'.$fl->uf_id.'","'.$data[$fl->uf_sname].'")';
				$db->setQuery(); $db->query(); 
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
	
	public function getFields() {
		$q='SELECT * FROM ufields WHERE published > 0 ORDER BY ordering';
		$this->_db->setQuery($qg);
		$fields=$this->_db->loadResult();
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
		return $fields;
	}
	
}
