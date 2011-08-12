<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelDegrees extends JModel
{
	function getDegrees()
	{
		$db =& JFactory::getDBO();
		$q1 = 'SELECT fieldid FROM #__comprofiler_fields WHERE name = "cb_degree"';
		$db->setQuery($q1);
		$fieldid = $db->loadResult();
		$q2  = 'SELECT * FROM #__comprofiler_field_values as d ';
		$q2 .= 'LEFT JOIN #__ce_degreecert as l ON l.degree=d.fieldtitle ';
		$q2 .= 'LEFT JOIN #__ce_certifs as c ON c.crt_id=l.cert ';
		$q2 .= 'WHERE d.fieldid = "'.$fieldid.'"';
		$db->setQuery($q2);
		$data = $db->loadObjectList();
		return $data;

	}

}
