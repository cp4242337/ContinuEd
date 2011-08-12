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
class ContinuEdControllerCourse extends ContinuEdController
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
		JRequest::setVar( 'view', 'course' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function copy()
	{
		$model = $this->getModel('course');
		if ($model->copyC()) {
			$msg = JText::_( 'Course copied!' );
		} else {
			$msg = JText::_( "Don't Copy that Floppy" );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=courses';
		$this->setRedirect($link, $msg);
	}
	function save()
	{
		$model = $this->getModel('course');

		if ($model->store($post)) {
			$msg = JText::_( 'Course!' );
		} else {
			$msg = JText::_( 'Error Saving Course' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_continued&view=courses';
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('course');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Courses Could not be Deleted' );
		} else {
			$msg = JText::_( 'Course(s) Deleted' );
		}

		$this->setRedirect( 'index.php?option=com_continued&view=courses', $msg );
	}

	function publish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to publish' ) );
		}

		$model = $this->getModel('course');
		if(!$model->publish($cid, 1)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_continued&view=courses' );
	}


	function unpublish()
	{
		global $mainframe;

		$cid 	= JRequest::getVar( 'cid', array(0), 'post', 'array' );

		if (!is_array( $cid ) || count( $cid ) < 1) {
			JError::raiseError(500, JText::_( 'Select an item to unpublish' ) );
		}

		$model = $this->getModel('course');
		if(!$model->publish($cid, 0)) {
			echo "<script> alert('".$model->getError(true)."'); window.history.go(-1); </script>\n";
		}

		$this->setRedirect( 'index.php?option=com_continued&view=courses' );
	}


	function orderup()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$link = 'index.php?option=com_continued&view=courses';
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'course' );
		if ($model->orderItem($id, -1)) {
			$msg = JText::_( 'Course Moved Up' );
		} else {
			$msg = $model->getError();
		}
		$link = 'index.php?option=com_continued&view=courses&';
		$this->setRedirect($link, $msg);
	}

	function orderdown()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		if (isset($cid[0]) && $cid[0]) {
			$id = $cid[0];
		} else {
			$link = 'index.php?option=com_continued&view=courses';
			$msg = 'No Items Selected';
			$this->setRedirect($link, $msg);
			return false;
		}

		$model =& $this->getModel( 'course' );
		if ($model->orderItem($id, 1, $courseid)) {
			$msg = JText::_( 'Course Moved Down' );
		} else {
			$msg = $model->getError();
		}
		$link = 'index.php?option=com_continued&view=courses';
		$this->setRedirect($link, $msg);

	}
	function saveorder()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'course' );
		if ($model->setOrder($cid)) {
			$msg = JText::_( 'New ordering saved' );
		} else {
			$msg = $model->getError();
		}
		$link = 'index.php?option=com_continued&view=courses';
		$this->setRedirect($link, $msg);
	}
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_continued&view=courses', $msg );
	}
	function accesspublic()
	{
		// Get some variables from the request
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'course' );
		if ($model->setAccess($cid, 0)) {
			$msg = JText::sprintf( 'Course(s) Set Public', count( $cid ) );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_continued&view=courses', $msg );
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	function accessregistered()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'course' );
		if ($model->setAccess($cid, 1)) {
			$msg = JText::sprintf( 'Course(s) Set Registered', count( $cid ) );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_continued&view=courses', $msg );
	}

	/**
	 * Save the item(s) to the menu selected
	 */
	function accessspecial()
	{
		$cid	= JRequest::getVar( 'cid', array(), 'post', 'array' );
		JArrayHelper::toInteger($cid);

		$model =& $this->getModel( 'course' );
		if ($model->setAccess($cid, 2)) {
			$msg = JText::sprintf( 'Course(s) Set Special', count( $cid ) );
		} else {
			$msg = $model->getError();
		}
		$this->setRedirect( 'index.php?option=com_continued&view=courses', $msg );
	}

}
?>
