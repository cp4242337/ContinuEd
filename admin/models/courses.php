<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');


class ContinuEdModelCourses extends JModelList
{
	
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'course_name', 'c.course_name',
				'ordering', 'c.ordering',
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$courseId = $this->getUserStateFromRequest($this->context.'.filter.course', 'filter_cat',"");
		$this->setState('filter.cat', $courseId);

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);
		
		// Load the parameters.
		$params = JComponentHelper::getParams('com_continued');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('c.ordering', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('c.*');

		// From the hello table
		$query->from('#__ce_courses as c');
		// Join over the categories.
		$query->select('cat.cat_name AS category_name');
		$query->join('RIGHT', '#__ce_cats AS cat ON cat.cat_id = c.course_cat');
		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = c.access');
		// Join over the providers.
		$query->select('p.prov_name AS provider_name');
		$query->join('RIGHT', '#__ce_providers AS p ON p.prov_id = c.course_provider');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('c.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(c.published IN (0, 1))');
		}

				
		// Filter by course.
		$catId = $this->getState('filter.cat');
		if (is_numeric($catId)) {
			$query->where('c.course_cat = '.(int) $catId);
		}
				
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		if ($orderCol == 'c.ordering') {
			$orderCol = 'category_name '.$orderDirn.', c.ordering';
		}
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
	
	public function getCats() {
		$query = 'SELECT cat_id AS value, cat_name AS text' .
				' FROM #__ce_cats' .
				' ORDER BY cat_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	
}
