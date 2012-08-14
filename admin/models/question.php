<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

class ContinuEdModelQuestion extends JModelAdmin
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
	protected function allowEdit($data = array(), $key = 'q_id')
	{
		// Check specific edit permission then general edit permission.
		return JFactory::getUser()->authorise('core.edit', 'com_continued.question.'.((int) isset($data[$key]) ? $data[$key] : 0)) or parent::allowEdit($data, $key);
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
	public function getTable($type = 'Question', $prefix = 'ContinuEdTable', $config = array()) 
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
		$form = $this->loadForm('com_continued.question', 'question', array('control' => 'jform', 'load_data' => $loadData));
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
		return 'administrator/components/com_continued/models/forms/question.js';
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
		$data = JFactory::getApplication()->getUserState('com_continued.edit.question.data', array());
		if (empty($data)) 
		{
			$data = $this->getItem();
			if ($this->getState('question.id') == 0) {
				$app = JFactory::getApplication();
				$data->set('q_course', JRequest::getInt('q_course', $app->getUserState('com_continued.questions.filter.course')));
				$data->set('q_area', JRequest::getString('q_area', $app->getUserState('com_continued.questions.filter.area')));
				$data->set('q_group', JRequest::getString('q_group', $app->getUserState('com_continued.questions.filter.qgroup')));
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

		if (empty($table->q_id)) {
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db = JFactory::getDbo();
				$db->setQuery('SELECT MAX(ordering) FROM #__ce_questions WHERE q_area = "'.$table->q_area.'" && q_course = '.$table->q_course);
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
		$condition[] = 'q_area = "'.$table->q_area.'" && q_course = '.(int) $table->q_course;
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
				$table->q_id=0;
				$table->ordering=$table->getNextOrder('q_area = "'.$table->q_area.'" && q_course= '.$table->q_course);
				if (!$table->store()) {
					$this->setError($table->getError());
					return false;
				} else {
					if ($table->q_type == 'multi' || $table->q_type == 'mcbox') {
						$newid = $table->q_id;
						$qoq='SELECT * FROM #__ce_questions_opts WHERE opt_question = '.$pk;
						$this->_db->setQuery($qoq);
						$qos = $this->_db->loadObjectList();
						foreach($qos as $qo) {
							$q  = 'INSERT INTO #__ce_questions_opts (opt_question,opt_text,opt_correct,opt_expl,ordering,published) ';
							$q .= 'VALUES ("'.$newid.'","'.$qo->opt_text.'","'.$qo->opt_correct.'","'.$qo->opt_expl.'","'.$qo->ordering.'","'.$qo->published.'")';
							$this->_db->setQuery($q);
							if (!$this->_db->query($q)) {
								return false;
							}
						}
					}
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
