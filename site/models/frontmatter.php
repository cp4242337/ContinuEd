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
		$query = 'SELECT course_id,course_frontmatter,course_name,course_hasfm,course_prereq,course_cataloglink,course_cat,published,access,course_startdate,course_enddate,course_haspre,course_haseval,course_purchase,course_purchaselink,course_purchasesku FROM #__ce_courses WHERE course_id = '.$courseid;
		$db->setQuery( $query );
		$fmtext = $db->loadObject();
		return $fmtext;
	}
	
	function checkPreReq($prereq) {
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query = 'SELECT * FROM #__ce_records WHERE rec_user = '.$userid.' && rec_pass = "pass" && rec_course = '.$prereq;
		$db =& JFactory::getDBO();
		$db->setQuery( $query );
		$fmtext = $db->loadAssoc();
		if (count($fmtext) > 0) return true;
		else return false;
	}
	function getCatInfo($cat)
	{
		$db =& JFactory::getDBO();
		$q='SELECT cat_free,cat_sku FROM #__ce_cats WHERE cat_id = "'.$cat.'"';
		$db->setQuery($q);
		$cn = $db->loadObject();
		return $cn;
	}

}
