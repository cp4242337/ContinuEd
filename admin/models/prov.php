<?php
defined('_JEXEC') or die();

jimport('joomla.application.component.model');

class ContinuEdModelProv extends JModel
{
	function __construct()
	{
		parent::__construct();

		$array = JRequest::getVar('lid',  0, '', 'array');
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
			$query = ' SELECT * FROM #__ce_providers '.
					'  WHERE pid = '.$this->_id;
			$this->_db->setQuery( $query );
			$this->_data = $this->_db->loadObject();
		}
		if (!$this->_data) {
			$this->_data = new stdClass();
			$this->_data->id = 0;
		}
		return $this->_data;
	}

	function store()
	{
		$row =& $this->getTable();

		$data->pid = JRequest::getVar('pid');
		$data->pname = JRequest::getVar('pname');
		$data->plogo = JRequest::getVar('plogo');

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
		$lids = JRequest::getVar( 'lid', array(0), 'post', 'array' );

		$row =& $this->getTable();

		if (count( $lids ))
		{
			foreach($lids as $lid) {
				if (!$row->delete( $lid )) {
					$this->setError( $row->getErrorMsg() );
					return false;
				}
			}
		}
		return true;
	}


}
?>
