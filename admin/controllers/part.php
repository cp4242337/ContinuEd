<?php
defined('_JEXEC') or die();
class ContinuEdControllerPart extends ContinuEdController
{
	function __construct()
	{
		parent::__construct();

		$this->registerTask( 'add'  , 	'edit' );
	}

	function edit()
	{
		JRequest::setVar( 'view', 'part' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}
	function save()
	{
		$model = $this->getModel('part');

		if ($model->store($post)) {
			$msg = JText::_( 'Part Label Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Part Label' );
		}

		$courseid = JRequest::getVar('course');
		$area=JRequest::getVar('part_area');
		$link = 'index.php?option=com_continued&view=parts&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		$model = $this->getModel('part');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Part Labels Could not be Deleted' );
		} else {
			$msg = JText::_( 'Part Label(s) Deleted' );
		}

		$courseid = JRequest::getVar('course');
		$area=JRequest::getVar('area');
		$link = 'index.php?option=com_continued&view=parts&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$courseid = JRequest::getVar('course');
		$area=JRequest::getVar('part_area');
		$link = 'index.php?option=com_continued&view=parts&area='.$area.'&course='.$courseid;
		$this->setRedirect($link, $msg);
	}

}
?>
