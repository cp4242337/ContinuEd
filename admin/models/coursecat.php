<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');


class ContinuEdModelCourseCat extends JModelAdmin
{
	protected function allowEdit($data = array(), $key = 'cc_id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_contined.coursecat.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}

	public function getTable($type = 'CourseCat', $prefix = 'ContinuEdTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_continued.coursecat', 'coursecat', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}

	public function getScript() 
	{
		return 'administrator/components/com_continued/models/forms/coursecat.js';
	}

	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_continued.edit.coursecat.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			if ($this->getState('coursecat.cc_id') == 0) {
				$app = JFactory::getApplication();
				$data->set('cc_course', JRequest::getInt('cc_course', $app->getUserState('com_continued.coursecats.filter.course')));
			}
		}
		return $data;
	}
	
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if (empty($table->cc_id)) {
			// Set the values
			
			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ce_coursecat WHERE cc_course = "'.$table->cc_course.'"');
				$max = $db->loadResult();
				
				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
		}
	}
		protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'cc_course = '.(int) $table->cc_course;
		return $condition;
	}
	
}
