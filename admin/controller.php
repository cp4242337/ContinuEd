<?php


jimport('joomla.application.component.controller');


class ContinuEdController extends JController
{

	function display()
	{
		// Set the submenu
		parent::display();
		ContinuEdHelper::addSubmenu(JRequest::getVar('view'));
	}

}
?>
