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
		if (!$guest) $query .= ',f.cpass';
		$query .= ',r.course_rating as catrating ';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'LEFT JOIN #__ce_providers as p ON c.course_provider = p.pid ';
		if (!$guest) $query .= 'LEFT JOIN #__ce_completed AS f ON c.course_id = f.course && f.user = '.$userid.' && crecent=1 ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.id ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$aid).') ';
		$query .= ' && c.course_id = '.$courseid;
		$query .= ' GROUP BY c.course_id ORDER BY c.ordering ASC';
		if (!$guest) $query .= ', f.ctime DESC';
		$db->setQuery( $query );
		$postlist = $db->loadObject();
		return $postlist;
	}
	
	function getCompletedList() {
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT course ';
		$query .= 'FROM #__ce_completed';
		$query .= ' WHERE user = '.$userid;
		$query .= ' && cpass = "pass"';
		$db->setQuery( $query );
		$clist = $db->loadResultArray();
		return $clist;
	}

}
