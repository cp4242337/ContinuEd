<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
class ContinuEdModelPart extends JModel
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('cid',  0, '', 'array');
		$this->setId((int)$array[0]);
	}
	function setId($id)
	{
		$this->_id		= $id;
		$this->_data	= null;
	}

	function &getData()
	{
		if (empty( $this->_data )) {
			$query = ' SELECT * FROM #__ce_parts '.
					'  WHERE part_id = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->part_id = 0;
		}
		return $this->_data;
	}
	function getNumParts($courseid,$area) {
		if ($area == 'post') $q='SELECT evalparts FROM #__ce_courses WHERE id = '.$courseid;
		if ($area == 'pre') $q='SELECT course_preparts FROM #__ce_courses WHERE id = '.$courseid;
		$this->_db->setQuery($q);
		return $this->_db->loadResult();
	}

	function store()
	{
		$row =& $this->getTable();
		$data->part_id = JRequest::getVar('part_id');
		$data->part_name = JRequest::getVar('part_name');
		$data->part_desc = JRequest::getVar('part_desc');
		$data->part_course = JRequest::getVar('part_course');
		$data->part_part = JRequest::getVar('part_part');
		$data->part_area = JRequest::getVar('part_area');

		if (!$row->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (!$row->store()) {
			$this->setError( $row->getErrorMsg() );
			return false;
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


}
?>
