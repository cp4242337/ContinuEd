<?php
defined('_JEXEC') or die();

class ContinuEdControllerProv extends ContinuEdController
{
	function __construct()
	{
		parent::__construct();

		$this->registerTask( 'add'  , 	'edit' );
	}

	function edit()
	{
		JRequest::setVar( 'view', 'prov' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('prov');

		if ($model->store($post)) {
			$msg = JText::_( 'Provider Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Provider' );
		}

		$link = 'index.php?option=com_continued&view=provs';
		$this->setRedirect($link, $msg);
	}

	function remove()
	{
		$model = $this->getModel('prov');
		if(!$model->delete()) {
			$msg = JText::_( 'Error: One or More Providers Could not be Deleted' );
		} else {
			$msg = JText::_( 'Provider(s) Deleted' );
		}

		$link = 'index.php?option=com_continued&view=provs';
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled, All Members Deleted' );
		$link = 'index.php?option=com_continued&view=provs';
		$this->setRedirect($link, $msg);
	}



}
?>
