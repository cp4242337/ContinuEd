<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelParts extends JModelList
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
		$courseId = $this->getUserStateFromRequest($this->context.'.filter.course', 'filter_course','');
		$this->setState('filter.course', $courseId);
		$area = $this->getUserStateFromRequest($this->context.'.filter.area', 'filter_area', '');
		$this->setState('filter.area', $area);	
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('p.part_part', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('p.*');

		// From the hello table
		$query->from('#__ce_parts as p');
		
		// Filter by course.
		$courseId = $this->getState('filter.course');
		if (is_numeric($courseId)) {
			$query->where('p.part_course = '.(int) $courseId);
		}
		// Filter by area.
		$area = $this->getState('filter.area');
		if ($area) {
			$query->where('p.part_area = "'.$area.'" ');
		}
				
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
	
}
