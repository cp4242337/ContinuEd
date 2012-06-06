<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.modellist');

class ContinuEdModelCourseAuths extends JModelList
{
	
	public function __construct($config = array())
	{
		
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'ordering', 'a.ordering',
			);
		}
		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		$published = $this->getUserStateFromRequest($this->context.'.filter.published', 'filter_published', '', 'string');
		$this->setState('filter.published', $published);

		$course = $this->getUserStateFromRequest($this->context.'.filter.course', 'filter_course','');
		$this->setState('filter.course', $course);

		// List state information.
		parent::populateState('a.ordering', 'asc');
	}
	
	protected function getListQuery() 
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the table
		$query->from('#__ce_courseauth as a');
		
		// Filter by article.
		$course = $this->getState('filter.course');
		if (is_numeric($course)) {
			$query->where('a.ca_course = '.(int) $course);
		}

		// Join over the authors.
		$query->select('auth.auth_name');
		$query->join('LEFT', '#__mams_authors AS auth ON auth.auth_id = a.ca_auth');
		
		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published)) {
			$query->where('a.published = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.published IN (0, 1))');
		}
		
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
		
		$query->order($db->getEscaped($orderCol.' '.$orderDirn));
				
		return $query;
	}
}
