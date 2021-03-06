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

		$limit			= $mainframe->getUserStateFromRequest( 'global.list.limit','limit',$mainframe->getCfg( 'list_limit' ),'int' );
		$limitstart		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.limitstart','limitstart',0,'int' );
		$pf				= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.pf','pf','' );
		$recent			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.recent','recent','0' );
		$type			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.type','type','' );
		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.enddate','enddate',date("Y-m-d") );
		$course			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.course','course','' );
		$cat			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.cat','cat','' );
		//$usergroup		= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.usergroup','usergroup','' );
		$qgroup			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.qgroup','qgroup','' );
		$qarea			= $mainframe->getUserStateFromRequest( 'com_continued.coursereport.area','qarea','' );
		
		$this->setState('startdate', $startdate);
		$this->setState('enddate', $enddate);
		$this->setState('pf', $pf);
		$this->setState('recent',$recent);
		$this->setState('type', $type);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('course', $course);
		$this->setState('cat', $cat);
		$this->setState('qarea', $qarea);
		$this->setState('usergroup', $usergroup);
		$this->setState('qgroup', $qgroup);

	}
	
	function _buildQuery($csv=false)
	{
		$cecfg = ContinuEdHelper::getConfig();
		$startdate = $this->getState('startdate');
		$enddate = $this->getState('enddate');
		$pf = $this->getState('pf');
		$recent = $this->getState('recent');
		$type = $this->getState('type');
		$cid = $this->getState('course');
		$cat = $this->getState('cat');
		$stnum = JRequest::getVar('stnum');
		if ($recent) $q .= ' && rec_recent = 1 ';
		$q  = 'SELECT STRAIGHT_JOIN DISTINCT r.*,c.course_name,c.course_cpeprognum,c.course_cat';
		$q .= ',TIMESTAMPDIFF(MINUTE,r.rec_start,r.rec_end) AS time_taken';
		$q .= ' FROM #__ce_records as r';
		$q .= ' STRAIGHT_JOIN #__ce_courses as c ON r.rec_course = c.course_id';
		$q .= ' WHERE date(r.rec_start) BETWEEN "'.$startdate.'" AND "'.$enddate.'"';
		if ($cid) $questions = $this->getQuestions($cid,$this->area);
		if ($cid) $q .= ' && r.rec_course = '.$cid.' ';
		else if ($cat) $q .= ' && c.course_cat = '.$cat.' '; 
		if ($pf) $q .= ' && r.rec_pass = "'.$pf.'" ';
		if ($type) $q .= ' && r.rec_type = "'.$type.'" ';
		$q .= ' ORDER BY r.rec_start DESC'; 
		return $q;
	}
	
	function getData($csv=false)
	{
		$cid = $this->getState('course');
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery($csv);
			if (!$csv) $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
			else $this->_data = $this->_getList($query);
		}
		if ($cid) $this->_data = $this->applyAns($this->_data);
		return $this->_data;
	}

	function getPharmID() {
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$q = 'SELECT usr_user,usr_data FROM #__ce_users WHERE usr_field = '.$cecfg->pharm_id_field;
		$db->setQuery($q);
		$data = $db->loadObjectList();
		$pharmids = Array();
		foreach ($data as $d) {
			$pharmids[$d->usr_user]=$d->usr_data;
		}
		return $pharmids;
	}
	
	function getPharmDOB() {
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$q = 'SELECT usr_user,usr_data FROM #__ce_users WHERE usr_field = '.$cecfg->pharm_dob_field;
		$db->setQuery($q);
		$data = $db->loadObjectList();
		$pharmdobs = Array();
		foreach ($data as $d) {
			$pharmdobs[$d->usr_user]=$d->usr_data;
		}
		return $pharmdobs;
		
	}	
	
	function getCatbyId() {
		$cecfg = ContinuEdHelper::getConfig();
		$db =& JFactory::getDBO();
		$q = 'SELECT cat_id,cat_name FROM #__ce_cats';
		$db->setQuery($q);
		$data = $db->loadObjectList();
		$catids = Array();
		foreach ($data as $d) {
			$catids[$d->cat_id]=$d->cat_name;
		}
		return $catids;
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
		$qgroup = $this->getState('qgroup');
		$qarea = $this->getState('qarea');
		$stnum = JRequest::getVar('stnum');
		$query  = ' SELECT * FROM #__ce_questions ';
		$query .= 'WHERE q_type != "message" && q_area = "'.$area.'" && q_course ='.$cid.' ';
		if ($qgroup) $query .= ' && q_group = '.$qgroup.' ';
		if ($qarea) $query .= ' && q_area = "'.$qarea.'" ';
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
			$q2  = 'SELECT a.* FROM #__ce_evalans as a ';//q.*,q.q_id,
			//$q2 .= 'LEFT JOIN #__ce_questions as q ON q.q_id=a.question ';
			$q2 .= 'WHERE (a.tokenid = "'.$r->rec_token.'" || a.tokenid = "interq") && a.userid = "'.$r->rec_user.'" && a.course = "'.$cid.'" ';
			$q2 .= 'GROUP BY a.question ';
			// .= 'GROUP BY q.q_id ';
			//$q2 .= 'ORDER BY q.q_part, q.ordering';
			$db->setQuery($q2);
			$rdata = $db->loadObjectList();
			foreach ($rdata as $d) {
				$title = 'q'.$d->question.'ans';
				$r->$title = $d->answer;
			}
		}
		return $records;
			
			
	}

	public function getCats() {
		$query = 'SELECT cat_id AS value, cat_name AS text' .
				' FROM #__ce_cats' .
				' ORDER BY cat_name';
		$this->_db->setQuery($query);
		$clist = $this->_db->loadObjectList();
		$clist[]->text='-- Select Category --';
		return $clist;
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
	
	public function getUserGroups() {
		$q  = 'SELECT ug.ug_name as text,ug.ug_id as value FROM #__ce_ugroups as ug';
		$q .= ' ORDER BY ug.ug_name';
		$this->_db->setQuery($q);
		$glist = $this->_db->loadObjectList();
		$glist[]->text='-- Select User Group --';
		return $glist;
	}
	
	public function getCourses() {
		$query = 'SELECT course_id AS value, course_name AS text' .
				' FROM #__ce_courses' ;
		if ($this->getState('cat')) $query .= ' WHERE course_cat = '.$this->getState('cat');
		$query .= ' ORDER BY course_name';
		$this->_db->setQuery($query);
		$clist = $this->_db->loadObjectList();
		$clist[]->text='-- Select Course --';
		return $clist;
	}
	
	public function getQGroups() {
		$query = 'SELECT qg_id AS value, qg_name AS text' .
				' FROM #__ce_questions_groups' .
				' ORDER BY qg_name';
		$this->_db->setQuery($query);
		$glist = $this->_db->loadObjectList();
		$glist[]->text='-- Select Question Group --';
		return $glist;
	}
	
	public function delete($cid) {
		foreach ($cid as $t) {
			$q = 'DELETE FROM #__ce_records WHERE rec_token = "'.$t.'"';
			$this->_db->setQuery($q);
			if (!$this->_db->query()) return false;
			$q2 = 'DELETE FROM #__ce_evalans WHERE tokenid = "'.$t.'"';
			$this->_db->setQuery($q2);
			if (!$this->_db->query()) return false;
		}
		return true;
	}

}
