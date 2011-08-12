<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCourseReport extends JModel
{

	var $_data;
	var $_total = null;
	var $_minqid = 99999999999;
	var $_maxqid = 0;
	var $_qidarr = Array();
	var $_pagination = null;
	var $area;

	function __construct()
	{
		parent::__construct();

		
		$mainframe = JFactory::getApplication();

		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit',							'limit',			$mainframe->getCfg( 'list_limit' ),	'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.limitstart',		'limitstart',		0,				'int' );
		$pf		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.pf','pf','' );
		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.enddate','enddate',date("Y-m-d") );
		
		
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
		$startdate = $this->getState('startdate');
		$enddate = $this->getState('enddate');
		$pf = $this->getState('pf');
		$cid = JRequest::getVar('course');
		$stnum = JRequest::getVar('stnum');
		$questions = $this->getQuestions($cid,$this->area);
		$q = 'SELECT STRAIGHT_JOIN DISTINCT r.*,c.cname,u.email,u.name as fullname';
		if ($cecfg['NEEDS_DEGREE']) $q .= ',m.cb_degree';
		$q .= ' FROM #__ce_completed as r';
		$q .= ' STRAIGHT_JOIN #__ce_courses as c ON r.course = c.id';
		if ($cecfg['NEEDS_DEGREE']) $q .= ' LEFT JOIN #__comprofiler as m ON r.user = m.user_id';
		$q .= ' RIGHT JOIN #__users as u ON r.user = u.id';
		$q .= ' WHERE r.course = '.$cid.' && r.crecent = 1';
		$q .= ' && date(r.ctime) BETWEEN "'.$startdate.'" AND "'.$enddate.'"';
		if ($pf) $q .= ' && r.cpass = "'.$pf.'" ';
		$q .= ' ORDER BY r.ctime DESC';
		return $q;
	}
	function getData($csv=false)
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			if (!$csv) $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			else $this->_data = $this->_getList($query);
		}
		$this->_data = $this->applyAns($this->_data);
		return $this->_data;
	}

	function getTotal()
	{
		//DEVNOTE: Lets load the content if it doesn't already exist
		if (empty($this->_total))
		{
			$query = $this->_buildQuery();
			$this->_total = $this->_getListCount($query);
		}

		return $this->_total;
	}

	function getPagination()
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination( $this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}

		return $this->_pagination;
	}
	/*	function getResponses($cid,$questions)
	 {
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadAssocList();
		return $data;
		}*/
	function getQuestions($cid,$area)
	{
		$stnum = JRequest::getVar('stnum');
		$query  = ' SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_area = "'.$area.'" && course ='.$cid.' ';
		//$query .= '&& ordering IN(';
		//$query .= $stnum.','.($stnum+1).','.($stnum+2).','.($stnum+3).','.($stnum+4);
		//$query .= ') ';
		$query .= 'ORDER BY ordering ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObjectList();
		foreach ($data as $qu) {
			$this->_qidarr[] = $qu->id;
		}

		return $data;
	}
	function getOptions() {
		$qids = $this->_qidarr;
		$q  = 'SELECT * FROM #__ce_questions_opts ';
		$q .= 'WHERE question IN ( '.implode(',',$qids).')';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadObjectList();
		foreach ($data as $d) {
			$optarr[$d->id] = $d->opttxt;
		}
		return $optarr;
	}

	function applyAns($records) {
		$cid = JRequest::getVar('course');
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		foreach ($records as $r) {
			$q2  = 'SELECT q.*,q.id as qid,a.* FROM #__ce_evalans as a ';
			$q2 .= 'LEFT JOIN #__ce_questions as q ON q.id=a.question ';
			$q2 .= 'WHERE a.sessionid = "'.$r->fsessionid.'" && a.userid = "'.$r->user.'" && a.course = "'.$cid.'" ';
			$q2 .= 'GROUP BY q.id ';
			$q2 .= 'ORDER BY q.qsec, q.ordering';
			$db->setQuery($q2);
			$rdata = $db->loadObjectList();
			foreach ($rdata as $d) {
				$title = 'q'.$d->qid.'ans';
				$r->$title = $d->answer;
			}
		}
		return $records;
			
			
	}

	/*function _buildQuery($type='normal')
	 {
		$month = $this->getState('month');
		$year = $this->getState('year');
		$cid = JRequest::getVar('course');
		$stnum = JRequest::getVar('stnum');
		$questions = $this->getQuestions($cid);
		$q = 'SELECT STRAIGHT_JOIN DISTINCT r.*,c.cname,m.firstname,m.lastname,m.cb_degree';
		foreach ($questions as $qu) {
		$q .= ',q'.$qu->id;
		$q .= '.answer';
		$q .= ' as q'.$qu->id.'ans';
		}
		$q .= ' FROM #__ce_completed as r';
		$q .= ' STRAIGHT_JOIN #__ce_courses as c ON r.course = c.id';
		$q .= ' LEFT JOIN #__comprofiler as m ON r.user = m.user_id';
		foreach ($questions as $qu) {
		$q .= ' RIGHT JOIN #__ce_evalans as q'.$qu->id.' ON r.fsessionid = q'.$qu->id.'.sessionid && q'.$qu->id.'.question = '.$qu->id.' && r.user = q'.$qu->id.'.userid ';
		//$q .= ' && MONTH(q'.$qu->id.'.anstime) = '.$month.' && YEAR(q'.$qu->id.'.anstime) = '.$year;
		}
		$q .= ' WHERE r.course = '.$cid.' && r.crecent = 1 && MONTH(r.ctime) = '.$month.' && YEAR(r.ctime) = '.$year;

		$q .= ' ORDER BY r.ctime DESC';
		return $q;
		}*/
}
