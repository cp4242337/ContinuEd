<?php
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.model' );

class ContinuEdModelAnswers extends JModel
{
	var $_data;
	var $_total = null;
	var $_pagination = null;


	function __construct()
	{
		parent::__construct();

		global $context;
		$mainframe = JFactory::getApplication();

		//initialize class property


		//DEVNOTE: Get the pagination request variables
		$limit			= $mainframe->getUserStateFromRequest( $context.'limit', 'limit', $mainframe->getCfg('list_limit'), 0);
		$limitstart = $mainframe->getUserStateFromRequest( $context.'limitstart', 'limitstart', 0 );

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);

	}

	function _buildQuery()
	{
		$questionid = JRequest::getVar('question');
		$query = ' SELECT *,correct as published '
		. ' FROM #__ce_questions_opts'
		. ' WHERE question = '.$questionid.' ORDER BY ordering';

		return $query;
	}

	function getData()
	{
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
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
	function getCourseName($course) {
		$q='SELECT cname FROM #__ce_courses WHERE id = '.$course;
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadResult();
	}
	function getQuestion($q) {
		$q='SELECT qtext FROM #__ce_questions WHERE id = '.$q;
		$db =& JFactory::getDBO();
		$db->setQuery($q);
		return $db->loadResult();
	}

}
