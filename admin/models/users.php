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
	
	public function getItemsCSV() {
		$query=$this->getListQuery();
		$db = JFactory::getDBO();
		$db->setQuery($query);
		return $db->loadObjectLIst();
		
	}
	
	public function getFields() {
		$db =& JFactory::getDBO();
		$q2  = 'SELECT * FROM #__ce_ufields ';
		$q2 .= 'WHERE uf_cms = 0 && published >= 1  ';
		$q2 .= 'ORDER BY ordering';
		$db->setQuery($q2);
		$fields = $db->loadObjectList();
		return $fields;
	}
	
	public function applyData($records) {
		$db =& JFactory::getDBO();
		foreach ($records as $r) {
			$q2  = 'SELECT q.*,a.* FROM #__ce_users as a ';
			$q2 .= 'LEFT JOIN #__ce_ufields as q ON q.uf_id=a.usr_field ';
			$q2 .= 'WHERE q.published >= 1 && a.usr_user = "'.$r->id.'"  ';
			$q2 .= 'ORDER BY q.ordering';
			$db->setQuery($q2);
			$rdata = $db->loadObjectList();
			foreach ($rdata as $d) {
				$title = $d->uf_sname;
				$r->$title = $d->usr_data;
			}
		}
		return $records;	
	}
	
	public function getAnswers($fdata) {
		$db =& JFactory::getDBO();
		$fids = Array();
		foreach ($fdata as $f) {
			if (!$f->uf_cms) $fids[]=$f->uf_id;
		}
		$q2  = 'SELECT * FROM #__ce_ufields_opts ';
		$q2 .= 'WHERE opt_field IN ('.implode(",",$fids).') && published >= 1  ';
		$db->setQuery($q2);
		$opts = $db->loadObjectList();
		$fod = Array();
		foreach ($opts as $o) {
			$fod[$o->opt_id] = $o->opt_text;
		}
		return $fod;
	}

}
