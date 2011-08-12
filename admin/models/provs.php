<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelProvs extends JModel
{
	var $_data;


	function _buildQuery()
	{
		$query = ' SELECT * '
		. ' FROM #__ce_providers';

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
