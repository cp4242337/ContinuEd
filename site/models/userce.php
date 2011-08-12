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
class ContinuEdModelUserCE extends JModel
{

	function getCatalog($userid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT * ';
		$query .= 'FROM #__ce_completed as f ';
		$query .= 'LEFT JOIN #__ce_courses as c ON f.course = c.id ';
		$query .= 'LEFT JOIN #__ce_cats as p ON c.ccat = p.cid ';
		$query .= 'WHERE user = '.$userid;
		$query .= ' ORDER BY f.ctime DESC';
		$db->setQuery( $query );
		$postlist = $db->loadAssocList();
		return $postlist;
	}



}
