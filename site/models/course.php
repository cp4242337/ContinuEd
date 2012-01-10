<?php
/**
 * Catalog Portion of ContinuEd Component
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

/**
 *
 */
class ContinuEdModelCourse extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$aid = $user->getAuthorisedViewLevels();
		$query  = 'SELECT c.*,p.*';
		$query .= ',r.course_rating as catrating ';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'LEFT JOIN #__ce_providers as p ON c.course_provider = p.prov_id ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.course_id ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$aid).') ';
		$query .= ' && c.course_id = '.$courseid;
		$query .= ' GROUP BY c.course_id ORDER BY c.ordering ASC';
		$db->setQuery( $query );
		$courseinfo = $db->loadObject();
		$cmpllist = ContinuEdHelper::completedList();
		// expired
		if ((strtotime($courseinfo->course_enddate."+ 1 day") <= strtotime("now")) && ($courseinfo->course_enddate != '0000-00-00 00:00:00')) {
			$courseinfo->expired=true;
		} else {
			$courseinfo->expired=false;
		}
		// status
		$courseinfo->status=$cmpllist[$courseinfo->course_id];
		// can take
		if (!$courseinfo->course_prereq || $courseinfo->expired) $courseinfo->cantake = true;
		else {
			$qp = 'SELECT pr_reqcourse FROM #__ce_prereqs WHERE pr_course = '.$courseinfo->course_id;
			$this->_db->setQuery($qp);
			$prlist = $this->_db->loadResultArray();
			$prm=true;
			foreach ($prlist as $p) {
				if ($cmpllist[$p]) {
					if ($cmpllist[$p] == 'incomplete' || $cmpllist[$p] == 'fail') $prm=false;
				} else {
					$prm=false;
				}
			} 	
			$courseinfo->cantake=$prm;
		}
		if ($courseinfo->status == 'pass' && !$courseinfo->course_hasfm && !$courseinfo->course_hasmat) $courseinfo->cantake = false;
		if ($courseinfo->expired && !$courseinfo->course_hasmat) $courseinfo->cantake = false;
		return $courseinfo;
	}
	

}
