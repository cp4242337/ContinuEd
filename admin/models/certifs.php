<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCertifs extends JModel
{
	var $_data;


	function _buildQuery()
	{
		$query = ' SELECT * '
		. ' FROM #__ce_certiftempl as t'
		. ' LEFT JOIN #__ce_certifs as c ON t.ctmpl_cert = c.crt_id'
		. ' LEFT JOIN #__ce_providers as p ON t.ctmpl_prov = p.pid';
			

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
