<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class ContinuEdModelCourse extends JModelAdmin
{
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	protected function allowEdit($data = array(), $key = 'course_id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_continued.course.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
	}
	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	 * @since	1.6
	 */
	public function getTable($type = 'Course', $prefix = 'ContinuEdTable', $config = array()) 
	{
		return JTable::getInstance($type, $prefix, $config);
	}
	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true) 
	{
		// Get the form.
		$form = $this->loadForm('com_continued.course', 'course', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) 
		{
			return false;
		}
		return $form;
	}
	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	Script files
	 */
	public function getScript() 
	{
		return 'administrator/components/com_continued/models/forms/course.js';
	}
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_continued.edit.course.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			if ($this->getState('course.course_id') == 0) {
				$app = JFactory::getApplication();
				$data->set('course_cat', JRequest::getInt('course_cat', $app->getUserState('com_continued.courses.filter.cat')));
			}
		}
		return $data;
	}
	
	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @since	1.6
	 */
	protected function prepareTable(&$table)
	{
		jimport('joomla.filter.output');
		$date = JFactory::getDate();
		$user = JFactory::getUser();

		if (empty($table->course_id)) {
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ce_courses WHERE course_cat = '.$table->course_cat);
				$max = $db->loadResult();

				$table->ordering = $max+1;
			}
		}
		else {
			// Set the values
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to add to ordering queries.
	 * @since	1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'course_cat = "'.$table->course_cat.'" ';
		return $condition;
	}
	
	public function copy(&$pks)
	{
		// Initialise variables.
		$user = JFactory::getUser();
		$pks = (array) $pks;
		$table = $this->getTable();
		
		// Include the content plugins for the on delete events.
		JPluginHelper::importPlugin('content');
		
		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
		
			if ($table->load($pk))
			{
				$table->course_id=0;
				$table->ordering=$table->getNextOrder('course_cat = '.$table->course_cat);
				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				} else {
					
					$newcourse = $table->course_id;
					$oldcourse = $pk;
					
					//PArts
					$q='SELECT * FROM #__ce_parts WHERE part_course = '.$oldcourse;
					$this->_db->setQuery($q);
					$qps = $this->_db->loadObjectList();
					foreach($qps as $qp) {
						$q  = 'INSERT INTO #__ce_parts (part_course,part_part,part_name,part_desc,part_area) ';
						$q .= 'VALUES ("'.$newcourse.'","'.$qp->part_part.'","'.addslashes($qp->part_name).'","'.addslashes($qp->part_desc).'","'.$qp->part_area.'")';
						$this->_db->setQuery($q);
						if (!$this->_db->query($q)) {
							return false;
						}
					}
		
					//Course Certificates
					$q='SELECT * FROM #__ce_coursecerts WHERE course_id = '.$oldcourse;
					$this->_db->setQuery($q);
					$qcs = $this->_db->loadObjectList();
					foreach($qcs as $qc) {
						$q  = 'INSERT INTO #__ce_coursecerts (qd_course,qd_cert) ';
						$q .= 'VALUES ("'.$newcourse.'","'.$qc->qd_cert.'")';
						$this->_db->setQuery($q);
						if (!$this->_db->query($q)) {
							return false;
						}
					}
		
		
					//Questions and Answers
					$q='SELECT * FROM #__ce_questions WHERE q_course = '.$oldcourse;
					$this->_db->setQuery($q);
					$qus = $this->_db->loadObjectList();
					if ($qus)
					{
						foreach($qus as $qu) {
							$q  = 'INSERT INTO #__ce_questions (q_course,ordering,q_text,q_type,q_cat,q_part,q_req,q_depq,q_area,published) ';
							$q .= 'VALUES ("'.$newcourse.'","'.$qu->ordering.'","'.addslashes($qu->q_text).'","'.$qu->q_type.'","'.$qu->q_cat.'","'.$qu->q_part.'","'.$qu->q_req.'","'.$qu->q_depq.'","'.$qu->q_area.'","'.$qu->published.'")';
							$this->_db->setQuery($q);
							if (!$this->_db->query($q)) {
								return false;
							}
							if ($qu->q_type == 'multi' || $qu->q_type == 'mcbox' || $qu->q_type == 'dropdown') {
								$newid = $this->_db->insertid();
								$qoq='SELECT * FROM #__ce_questions_opts WHERE opt_question = '.$qu->q_id;
								$this->_db->setQuery($qoq);
								$qos = $this->_db->loadObjectList();
								foreach($qos as $qo) {
									$q  = 'INSERT INTO #__ce_questions_opts (opt_question,opt_text,opt_correct,opt_expl,ordering,published) ';
									$q .= 'VALUES ("'.$newid.'","'.addslashes($qo->opt_text).'","'.$qo->opt_correct.'","'.addslashes($qo->opt_expl).'","'.$qo->ordering.'","'.$qo->published.'")';
									$this->_db->setQuery($q);
									if (!$this->_db->query($q)) {
										return false;
									}
								}
							}
						}
					} else return false;
					
					
					
					
				}
			}
			else
			{
				$this->setError($table->getError());
				return false;
			}
		}
		
		// Clear the component's cache
		$this->cleanCache();
		
		return true;
	}
}
