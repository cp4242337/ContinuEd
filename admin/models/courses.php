<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelCourses extends JModel
{
	var $_data;
	var $_total = null;
	var $_pagination = null;


	function __construct()
	{
		parent::__construct();

		$mainframe = JFactory::getApplication();

		$limit				= $mainframe->getUserStateFromRequest( 'global.list.limit',							'limit',			$mainframe->getCfg( 'list_limit' ),	'int' );
		$limitstart			= $mainframe->getUserStateFromRequest( 'com_continued.courses.limitstart',		'limitstart',		0,				'int' );

		$filter_cat		= $mainframe->getUserStateFromRequest( 'com_continued.courses.filter_cat','filter_cat','' );
		$filter_prov = $mainframe->getUserStateFromRequest( 'com_continued.courses.filter_prov','filter_prov','' );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setState('filter_cat',$filter_cat);
		$this->setState('filter_prov',$filter_prov);

	}
	function _buildQuery()
	{
		//$cat = JRequest::getVar('filter_cat');
		$cat		= $this->getState('filter_cat');
		$prov = $this->getState('filter_prov');
		$query  = ' SELECT c.*,s.*,p.*,ag.title AS access_level ';
		$query .= ' FROM #__ce_courses as c ';
		$query .= ' LEFT JOIN #__ce_providers as p ON p.pid=c.provider ';
		$query .= ' LEFT JOIN #__ce_cats as s ON s.cid=c.ccat ';
		$query .= ' LEFT JOIN #__viewlevels AS ag ON ag.id = c.access';
		if ($cat || $prov) $query .= ' WHERE ';
		if ($cat) { if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' c.ccat = "'.$cat.'"'; $tand = 1; }
		if ($prov) { if ($tand) { $query .= ' && '; $tand = 0; } $query .= ' c.provider = "'.$prov.'"'; }
		$query .= ' ORDER BY s.catname ASC, c.ordering ASC';
		return $query;
	}
	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));;
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
