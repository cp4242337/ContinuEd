<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelQuestions extends JModelList
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
		
		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('q.ordering', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('q.*');

		// From the hello table
		$query->from('#__ce_questions as q');
		// Join over the users.
		$query->select('u.username AS username');
		$query->join('LEFT', '#__users AS u ON u.id = q.q_addedby');
		
		// Filter by course.
		$courseId = $this->getState('filter.course');
		if (is_numeric($courseId)) {
			$query->where('q.q_course = '.(int) $courseId);
		}
		// Filter by area.
		$area = $this->getState('filter.area');
		if ($area) {
			$query->where('q.q_area = "'.$area.'" ');
		}
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('q.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(q.published IN (0, 1))');
		}
				
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$orderCol = ' q.ordering';
		
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
