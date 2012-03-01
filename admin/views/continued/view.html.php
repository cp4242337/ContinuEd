<?php

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewContinuEd extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'ContinuEd: CE Manager' ), 'continued' );
		JToolBarHelper::preferences('com_continued');
		parent::display($tpl);
	}
}
