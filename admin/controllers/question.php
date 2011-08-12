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
class ContinuEdControllerQuestion extends ContinuEdController
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
	 * display the copy page
	 * @return void
	 */
	function copy()
	{
		$model = $this->getModel('question');
		if ($model->copyQ()) {
			$msg = JText::_( 'Question(s) copied!' );
		} else {
			$msg = JText::_( "Don't Copy that Floppy" );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * display the edit form
	 * @return void
	 */
	function edit()
	{
		JRequest::setVar( 'view', 'question' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 */
	function save()
	{
		$model = $this->getModel('question');

		if ($model->store($post)) {
			$msg = JText::_( 'Question Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Course' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('q_area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 */
	function remove()
	{
		$model = $this->getModel('question');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Questions Could not be Deleted' );
		} else {
			$msg = JText::_( 'Question(s) Deleted' );
		}

		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('q_area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}
	function orderup()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$courseid = JRequest::getVar('course');
		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$area = JRequest::getVar('area');
			$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'question' );
		if ($model->orderItem($id, -1,$courseid)) {
			$msg = JText::_( 'Question Moved Up' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	function orderdown()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$courseid = JRequest::getVar('course');
		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$area = JRequest::getVar('area');
			$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'question' );
		if ($model->orderItem($id, 1, $courseid)) {
			$msg = JText::_( 'Question Moved Down' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);

	}
	function saveorder()
	{
		$courseid = JRequest::getVar('course');
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'question' );
		if ($model->setOrder($cid, $courseid)) {
			$msg = JText::_( 'New ordering saved' );
		} else {
			$msg = $model->getError();
		}
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}
	function publish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('question');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}


	function unpublish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('question');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$courseid = JRequest::getVar('course');
		$area = JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=questions&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

}
?>
