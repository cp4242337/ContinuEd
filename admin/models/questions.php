<?php


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelQuestions extends JModel
{
	/**
	 * Hellos data array
	 *
	 * @var array
	 */
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
		$courseid = JRequest::getVar('course');
		$filter_part = JRequest::getVar('filter_part');
		$query = ' SELECT q.*,u.username '
		. ' FROM #__ce_questions as q '
		. ' RIGHT JOIN #__users as u ON q.q_addedby = u.id '
		. ' WHERE q.course = '.$courseid.' && q_area = "'.JRequest::getVar('area').'"';
		if ($filter_part) $query .= ' && q.qsec = '.$filter_part;
		$query .= ' ORDER BY ordering';

		return $query;
	}

	/**
	 * Retrieves the hello data
	 * @return array Array of objects containing the data from the database
	 */
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
	/**
	 * Method to get the total number of helloworld items
	 *
	 * @access public
	 * @return integer
	 */
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

	/**
	 * Method to get a pagination object for the helloworld
	 *
	 * @access public
	 * @return integer
	 */
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
	function getParts($course,$area) {
		if ($area != 'inter') {
			if ($area == 'post') $q='SELECT evalparts FROM #__ce_courses WHERE id = '.$course;
			if ($area == 'pre') $q='SELECT course_preparts FROM #__ce_courses WHERE id = '.$course;
			$db =& JFactory::getDBO();
			$db->setQuery($q);
			$nump = $db->loadResult();
		} else {
			$nump=1;
		}
		$pf[0]=JHTML::_('select.option','','--All--');
		for ($l=1;$l<=$nump;$l++) {
			$pf[$l]=JHTML::_('select.option',$l,'Part '.$l);
		}
		return $pf;
	}
	function getCourseName($course) {
		$q='SELECT cname FROM #__ce_courses WHERE id = '.$course;
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadResult();
	}
}
