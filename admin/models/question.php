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
	
	public function getItem($pk = null)
	{
		/*
		// Initialise variables.
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
		$table = $this->getTable();
	
		if ($pk > 0)
		{
			// Attempt to load the row.
			$return = $table->load($pk);
				
			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return false;
			}
		}
	
		// Convert to the JObject before adding other data.
		$properties = $table->getProperties(1);
		$item = JArrayHelper::toObject($properties, 'JObject');
	
		if (property_exists($item, 'params'))
		{
			$registry = new JRegistry();
			$registry->loadString($item->params);
			$item->params = $registry->toArray();
		}
	
		$q = 'SELECT qtag_tag FROM #__ce_questiontags WHERE qtag_q = '.$item->q_id;
		$this->_db->setQuery($q);
		$item->questiontags=$this->_db->loadResultArray();
	
		return $item;
		*/
		
		if ($item = parent::getItem($pk)) {
			$q = 'SELECT qtag_tag FROM #__ce_questiontags WHERE qtag_q = '.$item->q_id;
			$this->_db->setQuery($q);
			$item->questiontags=$this->_db->loadResultArray();
		}
		
		return $item;
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
						
						//Options
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
						
						//Tags
						$qtq='SELECT * FROM #__ce_questiontags WHERE qtag_q = '.$pk;
						$this->_db->setQuery($qtq);
						$qts = $this->_db->loadObjectList();
						foreach($qts as $qt) {
							$q  = 'INSERT INTO #__ce_questiontags (qtag_q,qtag_tag) ';
							$q .= 'VALUES ("'.$newid.'","'.$qt->qtag_tag.'")';
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
	
	public function save($data)
	{
		// Initialise variables;
		$dispatcher = JDispatcher::getInstance();
		$table = $this->getTable();
		$key = $table->getKeyName();
		$pk = (!empty($data[$key])) ? $data[$key] : (int) $this->getState($this->getName() . '.id');
		$isNew = true;
	
		// Include the content plugins for the on save events.
		JPluginHelper::importPlugin('content');
	
		// Allow an exception to be thrown.
		try
		{
			// Load the row if saving an existing record.
			if ($pk > 0)
			{
				$table->load($pk);
				$isNew = false;
			}
				
			// Bind the data.
			if (!$table->bind($data))
			{
				$this->setError($table->getError());
				return false;
			}
				
			// Prepare the row for saving
			$this->prepareTable($table);
				
			// Check the data.
			if (!$table->check())
			{
				$this->setError($table->getError());
				return false;
			}
				
			// Trigger the onContentBeforeSave event.
			$result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, &$table, $isNew));
			if (in_array(false, $result, true))
			{
				$this->setError($table->getError());
				return false;
			}
				
			// Store the data.
			if (!$table->store())
			{
				$this->setError($table->getError());
				return false;
			}
				
			// Clean the cache.
			$this->cleanCache();
				
			// Trigger the onContentAfterSave event.
			$dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, &$table, $isNew));
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
				
			return false;
		}
	
		$pkName = $table->getKeyName();
	
		if (isset($table->$pkName))
		{
			$this->setState($this->getName() . '.id', $table->$pkName);
		}
		$this->setState($this->getName() . '.new', $isNew);
	
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->delete();
		$query->from('#__ce_questiontags');
		$query->where('qtag_q = '.(int)$table->q_id);
		$db->setQuery((string)$query);
		$db->query();
	
		if (!empty($data['questiontags'])) {
			foreach ($data['questiontags'] as $cc) {
				$qc = 'INSERT INTO #__ce_questiontags (qtag_q,qtag_tag) VALUES ('.(int)$table->q_id.','.(int)$cc.')';
				$db->setQuery($qc);
				if (!$db->query()) {
					$this->setError($db->getErrorMsg());
					return false;
				}
			}
		}
	
		return true;
	}
	
	public function getQuestionTags() {
		$query = 'SELECT qt_id, qt_name' .
				' FROM #__ce_questions_tags' .
				' ORDER BY qt_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
