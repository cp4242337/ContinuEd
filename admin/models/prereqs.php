<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelPreReqs extends JModelList
{
	
	public function __construct($config = array())
	{
		
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		//Filters
		$courseId = $this->getUserStateFromRequest($this->context.'.filter.course', 'filter_course','');
		$this->setState('filter.course', $courseId);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('req_course', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('pr.*');

		// From the hello table
		$query->from('#__ce_prereqs as pr');
		
		// Join over the certifs.
		$query->select('c.course_name');
		$query->join('RIGHT', '#__ce_courses AS c ON c.course_id = pr.pr_course');
		
		// Join over the groups.
		$query->select('r.course_name as req_course');
		$query->join('RIGHT', '#__ce_courses AS r ON r.course_id = pr.pr_reqcourse');
		
		// Filter by course.
		$courseId = $this->getState('filter.course');
		if (is_numeric($courseId)) {
			$query->where('pr.pr_course = '.(int) $courseId);
		}
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
	public function getCourses() {
		$query = 'SELECT course_id AS value, course_name AS text' .
				' FROM #__ce_courses' .
				' ORDER BY course_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
