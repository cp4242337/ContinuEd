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
class ContinuEdModelUser extends JModel
{

	function getCERecords($userid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_records as f ';
		$query .= 'LEFT JOIN #__ce_courses as c ON f.rec_course = c.course_id ';
		$query .= 'LEFT JOIN #__ce_cats as p ON c.course_cat = p.cat_id ';
		$query .= 'WHERE f.rec_user = '.$userid;
		$query .= ' ORDER BY f.rec_start DESC';
		$db->setQuery( $query );
		$postlist = $db->loadObjectList();
		return $postlist;
	}

	/**
	* User Fields 
	*
	* @param int $group Group id for user
	*
	* @return object of user data.
	*
	* @since 1.20
	*/
	function getUserFields($group,$showhidden=false) {
		$db =& JFactory::getDBO();
		$qd = 'SELECT f.* FROM #__ce_uguf as g';
		$qd.= ' RIGHT JOIN #__ce_ufields as f ON g.uguf_field = f.uf_id';
		$qd.= ' WHERE f.published = 1 && g.uguf_group='.$group;
		if (!$showhidden) $qd.=" && f.uf_hidden = 0";
		$qd.= ' ORDER BY f.ordering';
		$db->setQuery( $qd ); 
		$ufields = $db->loadObjectList();
		return $ufields;
	}


}
