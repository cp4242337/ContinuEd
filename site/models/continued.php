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
		$query .= 'LEFT JOIN #__ce_providers as p ON c.course_provider = p.prov_id ';
		if (!$guest) $query .= 'LEFT JOIN #__ce_completed AS f ON c.course_id = f.course && f.user = '.$userid.' && crecent=1 ';
		$query .= 'LEFT JOIN #__ce_courses as r ON c.course_catrate = r.course_id ';
		$query .= 'WHERE c.published = 1 && c.access IN ('.implode(",",$user->getAuthorisedViewLevels()).') ';
		if ($cat != 0) $query .= ' && c.course_cat = '.$cat;
		$query .= ' GROUP BY c.course_id ORDER BY c.ordering ASC';
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
		$q='SELECT cd_id FROM #__ce_coursecerts WHERE cd_course = "'.$courseid.'"';
		$db->setQuery($q);
		$cn = $db->loadResultArray();
		return $cn;
	}
	function getCatInfo($cat)
	{
		$db =& JFactory::getDBO();
		$q='SELECT * FROM #__ce_cats WHERE cat_id = "'.$cat.'"';
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
		$query = 'SELECT userg_group FROM #__ce_usergroup WHERE userg_user="'.$userid.'"';
		$db->setQuery($query); $groupid=$db->loadResult();
		$user->group = $groupid;
		$qd = 'SELECT f.uf_sname,u.usr_data FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' RIGHT JOIN #__ce_users as u ON u.usr_field = f.uf_id && usr_user = '.$userid;
		$qd.= ' WHERE g.uguf_group='.$groupid;
		$db->setQuery( $qd ); echo $db->getQuery();
		$udata = $db->loadObjectList();
		foreach ($udata as $u) {
			$fn=$u->uf_sname;
			$user->$fn=$u->usr_data;
		}
		
		return $user;
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
