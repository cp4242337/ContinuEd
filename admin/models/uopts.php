<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelUOpts extends JModelList
{
	
	public function __construct($config = array())
	{
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ordering', 'o.ordering',
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$qId = $this->getUserStateFromRequest($this->context.'.filter.field', 'filter_field', '');
		$this->setState('filter.field', $qId);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('o.ordering', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('o.*');

		// From the hello table
		$query->from('#__ce_ufields_opts as o');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('o.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(o.published IN (0, 1))');
		}

		// Filter by poll.
		$qId = $this->getState('filter.field');
		if (is_numeric($qId)) {
			$query->where('o.opt_field = '.(int) $qId);
		}
				
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
	
	public function getFields() {
		$app = JFactory::getApplication('administrator');
		$query = 'SELECT uf_id AS value, uf_sname AS text' .
				' FROM #__ce_ufields' .
				' WHERE uf_type IN ("mcbox","multi","dropdown") ' .
				' ORDER BY ordering';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
