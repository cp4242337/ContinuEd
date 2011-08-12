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
class ContinuEdModelFrontMatter extends JModel
{

	function getFrontMatter($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,frontmatter,cname,hasfm,prereq,cataloglink,ccat,published,access,startdate,enddate,course_haspre,course_purchase,course_purchaselink,course_purchasesku FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		return $fmtext;
	}
	function AgreedFM($courseid,$token) {
		$db =& JFactory::getDBO();
		$sewn = JFactory::getSession();
		$sessionid = $sewn->getId();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$q = 'INSERT INTO #__ce_track	(user,course,step,sessionid,token,track_ipaddr) VALUES ("'.$userid.'","'.$courseid.'","fm","'.$sessionid.'","'.$token.'","'.$_SERVER['REMOTE_ADDR'].'")';
		$db->setQuery( $q );
		if ($db->query()) return 1;
		else return 0;
	}
	function checkPreReq($prereq) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_completed WHERE user = '.$userid.' && cpass = "pass" && course = '.$prereq;
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		if (count($fmtext) > 0) return true;
		else return false;
	}
	function hasPassed($courseid) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_completed WHERE user = '.$userid.' && cpass = "pass" && course = '.$courseid;
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		if (count($fmtext) > 0) return true;
		else return false;
	}
	function getCatInfo($cat)
	{
		$db =& JFactory::getDBO();
		$q='SELECT catfree,catsku FROM #__ce_cats WHERE cid = "'.$cat.'"';
		$db->setQuery($q);
		$cn = $db->loadAssoc();
		return $cn;
	}

}
