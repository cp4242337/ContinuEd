<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelPurchases extends JModelList
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
		$cId = $this->getUserStateFromRequest($this->context.'.filter.course', 'filter_course', '');
		$this->setState('filter.course', $cId);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('p.purchase_time', 'desc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('p.*');

		// From the hello table
		$query->from('#__ce_purchased as p');
		
		// Join over the users.
		$query->select('u.name AS user_name');
		$query->join('LEFT', '#__users AS u ON u.id = p.purchase_user');
		
		// Join over the courses.
		$query->select('c.course_name AS course_name');
		$query->join('LEFT', '#__ce_courses AS c ON c.course_id = p.purchase_course');
		
		// Filter by poll.
		$cId = $this->getState('filter.course');
		if (is_numeric($cId)) {
			$query->where('p.purchase_course = '.(int) $cId);
		}
				
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
	
	public function getCourses() {
		$app = JFactory::getApplication('administrator');
		$query = 'SELECT course_id AS value, course_name AS text' .
				' FROM #__ce_courses' .
				' ORDER BY course_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
