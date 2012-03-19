<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCourseStat extends JModel
{

	var $_data;
	var $_total = null;
	var $_pagination = null;

function __construct()
	{
		parent::__construct();

		
		$mainframe = JFactory::getApplication();

		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit',							'limit',			$mainframe->getCfg( 'list_limit' ),	'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( 'com_continued.coursestats.limitstart',		'limitstart',		0,				'int' );
		$pf		= $mainframe->getUserStateFromRequest( 'com_continued.coursestats.pf','pf','' );
		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.coursestats.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.coursestats.enddate','enddate',date("Y-m-d") );
		
		
		$this->setState('startdate', $startdate);
		$this->setState('enddate', $enddate);
		$this->setState('pf', $pf);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->area = JRequest::getVar('area');

	}
	function _buildQuery()
	{
		global $cecfg;
		$user = JRequest::getVar('filter_user');
		$session = JRequest::getVar('filter_session');
		$cat = JRequest::getVar('filter_cat');
		$course = JRequest::getVar('filter_course');
		$step = JRequest::getVar('filter_step');
		$startdate = $this->getState('startdate');
		$enddate = $this->getState('enddate');
		$tand = 0;

		$q  = 'SELECT s.*,c.course_name,ca.cat_name';
		$q .= ' FROM #__ce_track as s ';
		$q .= 'LEFT JOIN #__ce_courses as c ON s.course = c.course_id ';
		$q .= 'LEFT JOIN #__ce_cats as ca ON c.course_cat = ca.cat_id ';
		$q .= ' WHERE ';
		if ($user) { $q .= 's.user = "'.$user.'"'; $tand = 1; }
		if ($session) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.sessionid = "'.$session.'"'; $tand = 1; }
		if ($cat) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' c.course_cat = "'.$cat.'"'; $tand = 1; }
		if ($course) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.course = "'.$course.'"'; $tand = 1; }
		if ($step) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.step = "'.$step.'"'; $tand = 1; }
		if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' DATE(s.tdhit) BETWEEN "'.$startdate.'" AND "'.$enddate.'"';
		$q .= ' ORDER BY s.tdhit DESC';
		
		return $q;
	}

	function getData()
	{
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_data;
	}
	function getDataCSV()
	{
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query);
		}

		return $this->_data;
	}


	function getTotal()
	{
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
	function getCatList() {
		$db =& JFactory::getDBO();
		$query  = ' SELECT * FROM #__ce_cats as c ';
		$query .= 'ORDER BY c.cat_name ASC';
		$db->setQuery( $query );
		$catlist = $db->loadObjectList();
		$cats[]=JHTML::_('select.option','','--All--');
		foreach ($catlist as $cl) {
			$cats[]=JHTML::_('select.option',$cl->cat_id,$cl->cat_name);
		}
		return $cats;

	}
	function getCourseList($cat) {
		$db =& JFactory::getDBO();
		$query  = ' SELECT * FROM #__ce_courses as c WHERE c.course_catlink = 0 ';
		if ($cat) $query .= '&& course_cat = '.$cat.' ';
		$query .= 'ORDER BY c.course_name ASC';
		$db->setQuery( $query );
		$catlist = $db->loadObjectList();
		$cats[]=JHTML::_('select.option','','--All--');
		foreach ($catlist as $cl) {
			$cats[]=JHTML::_('select.option',$cl->course_id,$cl->course_name);
		}
		return $cats;

	}
	
	public function getUsers() {
		$q  = 'SELECT u.*,ug.ug_name as usergroup FROM #__users as u';
		$q .= ' LEFT JOIN #__ce_usergroup as g ON u.id = g.userg_user';
		$q .= ' RIGHT JOIN #__ce_ugroups as ug ON g.userg_group = ug.ug_id';
		$this->_db->setQuery($q);
		$ulist = $this->_db->loadObjectList();
		foreach ($ulist as $u) {
			$uarray[$u->id]=$u;
		}
		$uarray[0]->name="Guest User";
		$uarray[0]->usergroup="Guests";
		return $uarray;
	}

}
