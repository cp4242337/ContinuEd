<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelUsers extends JModelList
{
	
	public function __construct($config = array())
	{
		
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		
		// Load the filter state.
		$groupId = $this->getUserStateFromRequest($this->context.'.filter.group', 'filter_group','');
		$this->setState('filter.group', $groupId);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('u.name', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('u.*');

		// From the hello table
		$query->from('#__users as u');
		// Join over the users.
		$query->select('ug.userg_update as lastUpdate');
		$query->join('LEFT', '#__ce_usergroup AS ug ON u.id = ug.userg_user');
		$query->select('g.ug_name');
		$query->join('LEFT', '#__ce_ugroups AS g ON ug.userg_group = g.ug_id');
		
		// Filter by group.
		$groupId = $this->getState('filter.course');
		if (is_numeric($groupId)) {
			$query->where('g.ug_id = '.(int) $groupId);
		}
		
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}

}
