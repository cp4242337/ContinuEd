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

	function getCourse($guest,$courseid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$aid = $user->getAuthorisedViewLevels();
		$query  = 'SELECT c.*,p.*';
		if (!$guest) $query .= ',f.rec_pass';
		$query .= ',r.course_rating as catrating ';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'LEFT JOIN #__ce_providers as p ON c.course_provider = p.prov_id ';
		if (!$guest) $query .= 'LEFT JOIN #__ce_records AS f ON c.course_id = f.rec_course && f.rec_user = '.$userid.' && rec_recent=1 ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.course_id ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$aid).') ';
		$query .= ' && c.course_id = '.$courseid;
		$query .= ' GROUP BY c.course_id ORDER BY c.ordering ASC';
		if (!$guest) $query .= ', f.rec_end DESC';
		$db->setQuery( $query );
		$postlist = $db->loadObject();
		return $postlist;
	}
	
	function getCompletedList() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT course ';
		$query .= 'FROM #__ce_records';
		$query .= ' WHERE rec_user = '.$userid;
		$query .= ' && rec_pass = "pass"';
		$db->setQuery( $query );
		$clist = $db->loadResultArray();
		return $clist;
	}

}
