<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );


class ContinuEdModelTally extends JModel
{
	function __construct()
	{
		parent::__construct();

		global $mainframe, $context;

		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.tally.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.tally.enddate','enddate',date("Y-m-d") );
		$this->setState('startdate', $startdate);
		$this->setState('enddate', $enddate);


	}
	
	function getCourse($cid)
	{
		$db =& JFactory::getDBO();
		$query = 'SELECT cname FROM #__ce_courses WHERE id = '.$cid.' && published=1';
		$db->setQuery( $query );
		$cdata = $db->loadAssoc();
		return $cdata;
	}
	function getQuestions($courseid,$area)
	{
		$db =& JFactory::getDBO();
		$query  = 'SELECT q.*,p.part_name FROM #__ce_questions as q ';
		$query .= 'LEFT JOIN #__ce_parts as p ON q.qsec = p.part_part && p.part_course = '.$courseid.' && p.part_area = "'.$area.'" ';
		$query .= 'WHERE q.q_area = "'.$area.'" && q.course = '.$courseid.' ORDER BY q.ordering ASC'; 
		$db->setQuery( $query ); 
		$qdata = $db->loadAssocList();
		return $qdata;
	}

}
