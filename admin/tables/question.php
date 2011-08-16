<?php


// No direct access
defined('_JEXEC') or die('Restricted access');

// import Joomla table library
jimport('joomla.database.table');

class ContinuEdTableQuestion extends JTable
{
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__ce_questions', 'q_id', $db);
	}
	
	public function store($updateNulls = false)
	{
		$user	= JFactory::getUser();
		if ($this->q_id) {
			// Existing item
		} else {
			if (empty($this->q_addedby)) {
				$this->q_addedby = $user->get('id');
			}
		}

		// Attempt to store the user data.
		return parent::store($updateNulls);
	}
	
	
	
}