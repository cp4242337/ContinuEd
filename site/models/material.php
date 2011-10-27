<?php
/**
 *
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelMaterial extends JModel
{

	function getMaterial($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT course_id,course_material,course_name,course_hasfm,course_hasmat,course_haseval,course_cataloglink,course_haspre,course_hasinter,course_qanda,course_startdate,course_enddate FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$mtext = $db->loadObject();
		return $mtext;
	}

	function getReqAns($courseid,$reqids) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$q2='SELECT question FROM #__ce_evalans WHERE userid = "'.$userid.'" && ans_area = "inter" && course = "'.$courseid.'" && question IN('.implode(",",$reqids).')';
		$db->setQuery($q2);
		return $db->loadResultArray();
	}

	function getReQids($courseid) {
		$db =& JFactory::getDBO();
		$q='SELECT q_id FROM #__ce_questions as q WHERE q.q_area = "inter" && q.q_course = "'.$courseid.'" AND q.q_req = 1';
		$db->setQuery($q);
		$data = $db->loadResultArray();
		return $data;
	}
}
