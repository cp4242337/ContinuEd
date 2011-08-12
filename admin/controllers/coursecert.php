<?php
defined('_JEXEC') or die();

class ContinuEdControllerCourseCert extends ContinuEdController
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
		JRequest::setVar( 'view', 'coursecert' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	/**
	 * save a record (and redirect to main page)
	 * @return void
	 */
	function save()
	{
		$model = $this->getModel('coursecert');

		if ($model->store($post)) {
			$msg = JText::_( 'Saved' );
		} else {
			$msg = JText::_( 'Error Saving' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=coursecerts&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * remove record(s)
	 * @return void
	 */
	function remove()
	{
		$model = $this->getModel('coursecert');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Could not be Deleted' );
		} else {
			$msg = JText::_( 'Deleted' );
		}

		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=coursecerts&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$courseid = JRequest::getVar('course');
		$link = 'index.php?option=com_continued&view=coursecerts&course='.$courseid;
		$this->setRedirect($link, $msg);
	}


}
?>
