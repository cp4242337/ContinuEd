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
class ContinuEdModelContinuEd extends JModel
{

	function getCatalog($guest,$cat)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$aid = $user->aid;
		$query  = 'SELECT c.*,p.*';
		if (!$guest) $query .= ',f.cpass';
		$query .= ',r.course_rating as catrating ';
		$query .= 'FROM #__ce_courses as c ';
		$query .= 'LEFT JOIN #__ce_providers as p ON c.provider = p.pid ';
		if (!$guest) $query .= 'LEFT JOIN #__ce_completed AS f ON c.id = f.course && f.user = '.$userid.' && crecent=1 ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.id ';
		$query .= 'WHERE c.published = 1 && c.access <= "'.$aid.'" ';
		if ($cat != 0) $query .= ' && c.ccat = '.$cat;
		$query .= ' GROUP BY c.id ORDER BY c.ordering ASC';
		if (!$guest) $query .= ', f.ctime DESC';
		$db->setQuery( $query );
		$postlist = $db->loadAssocList();
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
	function getCourseDegrees($courseid)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cert_id FROM #__ce_coursecerts WHERE course_id = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	function getCatInfo($cat)
	{
		$db =& JFactory::getDBO();
		$q='SELECT * FROM #__ce_cats WHERE cid = "'.$cat.'"';
		$db->setQuery($q);
		$cn = $db->loadAssoc();
		return $cn;
	}
	function getCertifAssoc($degree)
	{
		$db =& JFactory::getDBO();
		//determine which certif
		$q='SELECT cert FROM #__ce_degreecert WHERE degree = "'.$degree.'"';
		$db->setQuery($q);
		$cn = $db->loadResult();
		return $cn;
	}
	function getUserInfo() {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__comprofiler WHERE user_id="'.$userid.'"';
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		return $futext;
	}
	function viewedFM($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid) VALUES ("'.$userid.'","'.$catid.'","fm","'.$sessionid.'")';
		$db->setQuery( $query ); $db->query();
	}
	function viewedMenu($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid) VALUES ("'.$userid.'","'.$catid.'","menu","'.$sessionid.'")';
		$db->setQuery( $query ); $db->query();
	}
	function viewedDetails($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'INSERT INTO #__ce_cattrack (user,cat,viewed,sessionid) VALUES ("'.$userid.'","'.$catid.'","det","'.$sessionid.'")';
		$db->setQuery( $query ); $db->query();
	}
	function hasViewedFM($userid,$catid) {
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$db =& JFactory::getDBO();
		$query = 'SELECT * FROM #__ce_cattrack WHERE sessionid = "'.$sessionid.'" && viewed = "fm" && user="'.$userid.'" && cat = '.$catid;
		$db->setQuery( $query );
		$futext = $db->loadAssoc();
		if (count($futext) > 0) return true;
		else return false;
	}



}
