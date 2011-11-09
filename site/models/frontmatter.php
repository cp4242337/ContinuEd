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
	
	function checkPreReq($courseid) {
		$cmpllist =ContinuEdHelper::completedList();
		// can take
		$qp = 'SELECT pr_reqcourse FROM #__ce_prereqs WHERE pr_course = '.$courseid;
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
		return $prm;
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
