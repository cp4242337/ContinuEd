<?php
/**
 * Hello Model for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

/**
 * Hello Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdModelQuestion extends JModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}

	/**
	 * Method to set the hello identifier
	 *
	 * @access	public
	 * @param	int Hello identifier
	 * @return	void
	 */
	function setId($id)
	{
		// Set id and wipe data
		$this->_id		= $id;
		$this->_data	= null;
	}


	/**
	 * Method to get a hello
	 * @return object with data
	 */
	function &getData()
	{
		// Load the data
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__ce_questions '.
					'  WHERE id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
			$this->_date->q_area = JRequest::getVar('area');
		}
		return $this->_data;
	}


	/**
	 * Method to store a record
	 *
	 * @access	public
	 * @return	boolean	True on success
	 */
	function store()
	{
		$row =& $this->getTable();

		//$data = JRequest::get( 'post' );
		$data->id = JRequest::getVar('id');
		$data->course = JRequest::getVar('course');
		$data->ordering = JRequest::getVar('ordering');
		$data->qtext = JRequest::getVar('qtext',null,'default','none',4);
		$data->qtype = JRequest::getVar('qtype');
		$data->qcat = JRequest::getVar('qcat');
		$data->qsec = JRequest::getVar('qsec');
		$data->qreq = JRequest::getVar('qreq');
		$data->q_depq = JRequest::getVar('q_depq');
		$data->q_area = JRequest::getVar('q_area');
		$data->q_expl = JRequest::getVar('q_expl',null,'default','none',4);
		$data->published = JRequest::getVar('published');
		$data->q_addedby = JRequest::getVar('q_addedby');
		//i hate that i have so many fields


		//JRequest::_cleanVar($data->PostBody,4);


		// Bind the form fields to the hello table
		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Make sure the hello record is valid
		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// Store the web link table to the database
		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
		}

		return true;
	}

	function copyQ() {
		$cid = JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);
		$row =& $this->getTable();

		foreach($cid as $qu) {
			$row->load($qu);
			$row->id=0;
			$row->ordering=$row->getNextOrder('q_area = "'.JRequest::getVar('area').'" && course = '.$row->course);
			if(!$row->store()) return false;
			if ($row->qtype == 'multi' || $row->qtype == 'mcbox') {
				$newid = $row->id;
				$qoq='SELECT * FROM #__ce_questions_opts WHERE question = '.$qu;
				$this->_db->setQuery($qoq);
				$qos = $this->_db->loadObjectList();
				foreach($qos as $qo) {
					$q  = 'INSERT INTO #__ce_questions_opts (question,opttxt,correct,optexpl,ordering) ';
					$q .= 'VALUES ("'.$newid.'","'.$qo->opttxt.'","'.$qo->correct.'","'.$qo->optexpl.'","'.$qo->ordering.'")';
					$this->_db->setQuery($q);
					if (!$this->_db->query($q)) {
						return false;
					}
				}
			}
		}
		return true;
	}
	function delete()
	{
		$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $cids ))
		{
			foreach($cids as $cid) {
				if (!$row->delete( $cid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}

	function setOrder($items,$course) {
		$total		= count( $items );
		$row		=& $this->getTable();

		$order		= JRequest::getVar( 'order', array(), 'post', 'array' );
		JArrayHelper::toInteger($order);

		// update ordering values
		for( $i=0; $i < $total; $i++ ) {
			$row->load( $items[$i] );
			if ($row->ordering != $order[$i]) {
				$row->ordering = $order[$i];
				if (!$row->store()) {
					$this->setError($row->getError());
					return false;
				}
			} // if
		} // for
		$row->reorder('q_area = "'.JRequest::getVar('area').'" && course = '.$course);
		return true;
	}
	function orderItem($item, $movement, $course)
	{
		$row =& $this->getTable();
		$row->load( $item );
		if (!$row->move( $movement, 'q_area = "'.JRequest::getVar('area').'" && course = '.$course )) {
			$this->setError($row->getError());
			return false;
		}
		return true;
	}



	function publish($cid = array(), $publish = 1)
	{

		if (count( $cid ))
		{
			$cids = implode( ',', $cid );

			$query = 'UPDATE #__ce_questions'
			. ' SET published = ' . intval( $publish )
			. ' WHERE id IN ( '.$cids.' )'
				
			;
			$this->_db->setQuery( $query );
			if (!$this->_db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		return true;
	}
	function getNumParts($courseid,$area) {
		if ($area != 'inter' && $area != 'qanda') {
			if ($area == 'post') $q='SELECT evalparts FROM #__ce_courses WHERE id = '.$courseid;
			if ($area == 'pre') $q='SELECT course_preparts FROM #__ce_courses WHERE id = '.$courseid;
			$this->_db->setQuery($q);
			return $this->_db->loadResult();
		} else {
			return 1;
		}
	}

}
?>
