<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCatStat extends JModel
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
		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}

	function _buildQuery()
	{
		$user = JRequest::getVar('filter_user');
		$session = JRequest::getVar('filter_session');
		$month = JRequest::getVar('filter_month');
		$year = JRequest::getVar('filter_year');
		$cat = JRequest::getVar('filter_cat');
		$step = JRequest::getVar('filter_step');
		$tand = 0;

		$q  = 'SELECT s.*,u.username,ca.catname FROM #__ce_cattrack as s ';
		$q .= 'LEFT JOIN #__users as u ON s.user = u.id ';
		$q .= 'LEFT JOIN #__ce_cats as ca ON s.cat = ca.cid ';
		if ($user || $session || $month || $year || $cat || $step) $q .= ' WHERE ';
		if ($user) { $q .= 's.user = "'.$user.'"'; $tand = 1; }
		if ($session) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.sessionid = "'.$session.'"'; $tand = 1; }
		if ($month) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' MONTH(s.tdhit) = "'.$month.'"'; $tand = 1; }
		if ($year) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' YEAR(s.tdhit) = "'.$year.'"'; $tand = 1; }
		if ($cat) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.cat = "'.$cat.'"'; $tand = 1; }
		if ($step) { if ($tand) { $q .= ' && '; $tand = 0; } $q .= ' s.viewed = "'.$step.'"'; $tand = 1; }
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
		$query .= 'ORDER BY c.catname ASC';
		$db->setQuery( $query );
		$catlist = $db->loadObjectList();
		$cats[]=JHTML::_('select.option','','--All--');
		foreach ($catlist as $cl) {
			$cats[]=JHTML::_('select.option',$cl->cid,$cl->catname);
		}
		return $cats;

	}

}
