<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCats extends JModel
{
	var $_data;


	function _buildQuery()
	{
		$questionid = JRequest::getVar('question');
		$query = ' SELECT * '
		. ' FROM #__ce_cats';

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
