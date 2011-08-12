<?php
/**
 * Hello Controller for Hello World Component
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://dev.joomla.org/component/option,com_jd-wiki/Itemid,31/id,tutorials:components/
 * @license		GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class ContinuEdControllerAnswer extends ContinuEdController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'answer' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function copy()
	{
		$model = $this->getModel('answer');

		if ($model->copyans()) {
			$msg = JText::_( 'Answer Copied!' );
		} else {
			$msg = JText::_( 'Error Copying' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}
	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('answer');

		if ($model->store($post)) {
			$msg = JText::_( 'Answer Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Course' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('answer');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Posts Could not be Deleted' );
		} else {
			$msg = JText::_( 'Post(s) Deleted' );
		}

		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

	function orderup()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$area = JRequest::getVar('area');
			$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'answer' );
		if ($model->orderItem($id, -1,$questionid)) {
			$msg = JText::_( 'Answer Moved Up' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

	function orderdown()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$area = JRequest::getVar('area');
			$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'answer' );
		if ($model->orderItem($id, 1, $questionid)) {
			$msg = JText::_( 'Answer Moved Down' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);

	}
	function saveorder()
	{
		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'answer' );
		if ($model->setOrder($cid, $questionid)) {
			$msg = JText::_( 'New ordering saved' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

	function crctpublish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('answer');
		if(!$model->correct($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}


	function crctunpublish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('answer');
		if(!$model->correct($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$questionid = JRequest::getVar('question');
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=answers&area='.$area.'&course='.$courseid.'&question='.$questionid;
		$this->setRedirect($link, $msg);
	}

}
?>
