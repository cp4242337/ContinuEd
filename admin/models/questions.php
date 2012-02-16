<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelQuestions extends JModelList
{
	
	public function __construct($config = array())
	{
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ordering', 'q.ordering',
			);
		}
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
		$qgroup = $this->getUserStateFromRequest($this->context.'.filter.qgroup', 'filter_qgroup', '');
		$this->setState('filter.qgroup', $qgroup);	
		
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
		
		// Join over the question group.
		$query->select('qg.qg_name');
		$query->join('LEFT', '#__ce_questions_groups AS qg ON qg.qg_id = q.q_group');
				
		// Filter by course.
		$courseId = $this->getState('filter.course');
		if (is_numeric($courseId)) {
			$query->where('q.q_course = '.(int) $courseId);
		}
		
		// Filter by group.
		$qgroupId = $this->getState('filter.qgroup');
		if (is_numeric($qgroupId)) {
			$query->where('q.q_group = '.(int) $qgroupId);
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
	
	public function getQGroups() {
		$query = 'SELECT qg_id AS value, qg_name AS text' .
				' FROM #__ce_questions_groups' .
				' ORDER BY qg_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
