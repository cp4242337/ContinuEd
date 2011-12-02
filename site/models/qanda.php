<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelQandA extends JModel
{

	function getCourse($courseid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT id,course_name,course_cataloglink,course_cat FROM #__ce_courses WHERE id = '.$courseid;
		$db->setQuery( $query );
		$cinfo = $db->loadObject();
		return $cinfo;
	}
	function loadQuestions($courseid)
	{
		$db =& JFactory::getDBO();
		$user =& JFactory::getUser();
		$userid = $user->id;
		$query  = 'SELECT q.*,u.username ';
		$query .= 'FROM #__ce_questions as q ';
		$query .= 'RIGHT JOIN #__users as u ON q.q_addedby = u.id ';
		$query .= 'WHERE q.course = '.$courseid.' && q.q_area="qanda" && q.published = 1 ';
		$query .= 'ORDER BY q.ordering ASC ';
		$db->setQuery( $query );
		$qdata = $db->loadObjectList();
		return $qdata;
	}
}
