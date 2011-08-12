<?php
defined('_JEXEC') or die();

class ContinuEdControllerCfgE extends ContinuEdController
{
	function __construct()
	{
		parent::__construct();

		$this->registerTask( 'add'  , 	'edit' );
	}

	function edit()
	{
		JRequest::setVar( 'view', 'cfge' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('cfge');

		if ($model->store($post)) {
			$msg = JText::_( 'Provider Saved!' );
		} else {
			$msg = JText::_( 'Error Saving Provider' );
		}

		$link = 'index.php?option=com_continued&view=config';
		$this->setRedirect($link, $msg);
	}

	function cancel()
	{
		$msg = JText::_( 'Operation Canceled, ICBM\'s Have Been Launched' );
		$link = 'index.php?option=com_continued&view=config';
		$this->setRedirect($link, $msg);
	}
	function setDefault() {
		$model = $this->getModel('cfge');
		if ($model->setDefault())
		{ $msg = JText::_( 'Default Settings Restored' ); $type='message'; }
		else
		{ $msg = JText::_( 'Default Settings Not Restored, you have declared war on China instead' ); $type='error'; }
		$link = 'index.php?option=com_continued&view=config';
		$this->setRedirect($link, $msg,$type);

	}



}
?>
