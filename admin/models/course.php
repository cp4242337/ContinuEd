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
			if ($data->get('course_cat') == 0) {
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
	
	public function getItem($pk = null)
	{
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
		
		$q = 'SELECT cd_cert FROM #__ce_coursecerts WHERE cd_course = '.$item->course_id;
		$this->_db->setQuery($q);
		$item->coursecerts=$this->_db->loadResultArray();
		
		return $item;
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
		$query->from('#__ce_coursecerts');
		$query->where('cd_course = '.(int)$table->course_id);
		$db->setQuery((string)$query);
		$db->query();
		
		if (!empty($data['coursecerts'])) {
			foreach ($data['coursecerts'] as $cc) {
				$qc = 'INSERT INTO #__ce_coursecerts (cd_course,cd_cert) VALUES ('.(int)$table->course_id.','.(int)$cc.')';
				$db->setQuery($qc);
				if (!$db->query()) {
					$this->setError($db->getErrorMsg());
					return false;
				}
			} 
		}
		
		return true;
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
					
					//PreReqs
					$q='SELECT * FROM #__ce_prereqs WHERE pr_course = '.$oldcourse;
					$this->_db->setQuery($q);
					$qprs = $this->_db->loadObjectList();
					foreach($qprs as $qpr) {
						$q  = 'INSERT INTO #__ce_prereqs (pr_course,pr_reqcourse) ';
						$q .= 'VALUES ("'.$newcourse.'","'.$qpr->pr_reqcourse.'")';
						$this->_db->setQuery($q);
						if (!$this->_db->query($q)) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}
					}
		
					//MatPages
					$q='SELECT * FROM #__ce_material WHERE mat_course = '.$oldcourse;
					$this->_db->setQuery($q);
					$mtps = $this->_db->loadObjectList();
					foreach($mtps as $mtp) {
						$q  = 'INSERT INTO #__ce_material (mat_course,mat_title,mat_desc,mat_content,mat_type,access,ordering,published) ';
						$q .= 'VALUES ("'.$newcourse.'","'.$mtp->mat_title.'","'.addslashes($mtp->mat_desc).'","'.addslashes($mtp->mat_content).'","'.$mtp->mat_type.'","'.$mtp->access.'","'.$mtp->ordering.'","'.$mtp->published.'")';
						$this->_db->setQuery($q);
						if (!$this->_db->query($q)) {
							$this->setError($this->_db->getErrorMsg());
							return false;
						}
					}
		
					//PArts
					$q='SELECT * FROM #__ce_parts WHERE part_course = '.$oldcourse;
					$this->_db->setQuery($q);
					$qps = $this->_db->loadObjectList();
					foreach($qps as $qp) {
						$q  = 'INSERT INTO #__ce_parts (part_course,part_part,part_name,part_desc,part_area) ';
						$q .= 'VALUES ("'.$newcourse.'","'.$qp->part_part.'","'.addslashes($qp->part_name).'","'.addslashes($qp->part_desc).'","'.$qp->part_area.'")';
						$this->_db->setQuery($q);
						if (!$this->_db->query($q)) {
							$this->setError($this->_db->getErrorMsg());
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
							$this->setError($this->_db->getErrorMsg());
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
								$this->setError($this->_db->getErrorMsg());
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
										$this->setError($this->_db->getErrorMsg());
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
	
	public function getCertTypes() {
		$query = 'SELECT crt_id, crt_name' .
				' FROM #__ce_certifs' .
				' ORDER BY crt_name';
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
}
