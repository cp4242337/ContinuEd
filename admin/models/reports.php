<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelReports extends JModel
{
	var $_data;
	var $_total = null;
	var $_pagination = null;


	function __construct()
	{
		parent::__construct();

		global $context;
		$mainframe = JFactory::getApplication();

		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );

		$cat		= $mainframe->getUserStateFromRequest( 'com_continued.reports.cat','cat','' );
		$course		= $mainframe->getUserStateFromRequest( 'com_continued.reports.course','course','' );
		$pf			= $mainframe->getUserStateFromRequest( 'com_continued.reports.pf','pf','' );
		$prov		= $mainframe->getUserStateFromRequest( 'com_continued.reports.prov','prov','' );
		$startdate		= $mainframe->getUserStateFromRequest( 'com_continued.reports.startdate','startdate',date("Y-m-d",strtotime("-1 months") ));
		$enddate		= $mainframe->getUserStateFromRequest( 'com_continued.reports.enddate','enddate',date("Y-m-d") );
		
		
		$this->setState('startdate', $startdate);
		$this->setState('enddate', $enddate);

		$this->setState('prov', $prov);
		$this->setState('pf', $pf);
		$this->setState('course', $course);
		$this->setState('cat', $cat);
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}
	function _buildQuery()
	{
		global $cecfg;
		
		$course = $this->getState('course');
		$pf = $this->getState('pf');
		$cat = $this->getState('cat');
		$prov = $this->getState('prov');
		$startdate = $this->getState('startdate');
		$enddate = $this->getState('enddate');

		$query  = ' SELECT c.*,';
		if ($cecfg['NEEDS_DEGREE']) $query .= 'cb.*,';
		$query .= 'u.email,u.name as fullname,f.*,l.*,p.* FROM #__ce_completed as c ';
		if ($cecfg['NEEDS_DEGREE']) $query .= ' LEFT JOIN #__comprofiler as cb ON cb.user_id=c.user ';
		$query .= ' LEFT JOIN #__users as u ON u.id=c.user ';
		$query .= ' LEFT JOIN #__ce_courses as f ON f.id=c.course ';
		$query .= ' LEFT JOIN #__ce_cats as l ON l.cid=f.ccat ';
		$query .= ' LEFT JOIN #__ce_providers as p ON p.pid=f.provider ';
		$tand = 0;
		if ($course || $pf || $cat || $prov || $startdate || $enddate) $query .= ' WHERE ';
		if ($course) { $query .= ' c.course = '.$course; $tand = 1; }
		if ($pf) { if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' c.cpass = "'.$pf.'"'; $tand = 1; }
		if ($cat) { if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' f.ccat = "'.$cat.'"'; $tand = 1; }
		if ($prov) { if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' f.provider = "'.$prov.'"'; $tand = 1; }
		if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' date(c.ctime) BETWEEN "'.$startdate.'" AND "'.$enddate.'"';
		$query .= ' ORDER BY c.ctime DESC '		;

		return $query;
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
	function getCourseList() {
		$cat = $this->getState('cat');
		$q ='SELECT id,cname FROM #__ce_courses';
		$q.=' WHERE course_catlink != 1';
		if ($cat) $q.=' && ccat='.$cat;
		$q.=' ORDER BY cname ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadAssocList();
		$data[]->cname='--Show All Courses--';
		return $data;
	}
	function getCatList() {
		$q='SELECT * FROM #__ce_cats ORDER BY catname ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadAssocList();
		$data[]->catname='--Show All Categories--';
		return $data;
	}
	function getProvList() {
		$q='SELECT * FROM #__ce_providers ORDER BY pname ASC';
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		$data = $db->loadAssocList();
		$data[]->pname='--Show All Providers--';
		return $data;
	}
}
