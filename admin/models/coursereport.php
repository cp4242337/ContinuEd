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

		$limit			= $mainframe->getUserStateFromRequest( 'global.list.limit',							'limit',			$mainframe->getCfg( 'list_limit' ),	'int' );
		$limitstart		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.limitstart',		'limitstart',		0,				'int' );
		$pf				= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.pf','pf','' );
		$type			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.type','type','' );
		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.enddate','enddate',date("Y-m-d") );
		
		
		$this->setState('startdate', $startdate);
		$this->setState('enddate', $enddate);
		$this->setState('pf', $pf);
		$this->setState('type', $type);
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
		$type = $this->getState('type');
		$cid = JRequest::getVar('course');
		$stnum = JRequest::getVar('stnum');
		$questions = $this->getQuestions($cid,$this->area);
		$q = 'SELECT STRAIGHT_JOIN DISTINCT r.*,c.course_name,u.email,u.name as fullname';
		$q .= ',ug.ug_name';
		$q .= ' FROM #__ce_records as r';
		$q .= ' STRAIGHT_JOIN #__ce_courses as c ON r.rec_course = c.course_id';
		$q .= ' LEFT JOIN #__ce_usergroup as g ON r.rec_user = g.userg_user';
		$q .= ' LEFT JOIN #__ce_ugroups as ug ON g.userg_group = ug.ug_id';
		$q .= ' RIGHT JOIN #__users as u ON r.rec_user = u.id';
		$q .= ' WHERE r.rec_course = '.$cid.' ';
		$q .= ' && date(r.rec_start) BETWEEN "'.$startdate.'" AND "'.$enddate.'"';
		if ($pf) $q .= ' && r.rec_pass = "'.$pf.'" ';
		if ($type) $q .= ' && r.rec_type = "'.$type.'" ';
		$q .= ' ORDER BY r.rec_start DESC';
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
	
	function getQuestions($cid,$area)
	{
		$stnum = JRequest::getVar('stnum');
		$query  = ' SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_type != "message" && q_area = "'.$area.'" && q_course ='.$cid.' ';
		$query .= 'ORDER BY ordering ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		$data = $db->loadObjectList();
		foreach ($data as $qu) {
			$this->_qidarr[] = $qu->q_id;
		}

		return $data;
	}
	
	function getOptions() {
		$qids = $this->_qidarr;
		$q  = 'SELECT * FROM #__ce_questions_opts ';
		$q .= 'WHERE opt_question IN ( '.implode(',',$qids).')';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadObjectList();
		foreach ($data as $d) {
			$optarr[$d->opt_id]->text = $d->opt_text;
			$optarr[$d->opt_id]->correct = $d->opt_correct;
		}
		return $optarr;
	}

	function applyAns($records) {
		$cid = JRequest::getVar('course');
		$db =& JFactory::getDBO();
		$db->setQuery($query);
		foreach ($records as $r) {
			$q2  = 'SELECT q.*,q.q_id,a.* FROM #__ce_evalans as a ';
			$q2 .= 'LEFT JOIN #__ce_questions as q ON q.q_id=a.question ';
			$q2 .= 'WHERE a.tokenid = "'.$r->rec_token.'" && a.userid = "'.$r->rec_user.'" && a.course = "'.$cid.'" ';
			$q2 .= 'GROUP BY q.q_id ';
			$q2 .= 'ORDER BY q.q_part, q.ordering';
			$db->setQuery($q2);
			$rdata = $db->loadObjectList();
			foreach ($rdata as $d) {
				$title = 'q'.$d->q_id.'ans';
				$r->$title = $d->answer;
			}
		}
		return $records;
			
			
	}

}
