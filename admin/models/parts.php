<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelParts extends JModel
{
	var $_data;


	function _buildQuery()
	{
		$courseid = JRequest::getVar('course');
		$query = ' SELECT * '
		. ' FROM #__ce_parts'
		. ' WHERE part_course = '.$courseid.' && part_area = "'.JRequest::getVar('area').'"'
		. ' ORDER BY part_part';

		return $query;
	}

	function getData()
	{
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList( $query );
		}

		return $this->_data;
	}

}
