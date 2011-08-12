<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewPart extends JView
{
	function display($tpl = null)
	{
		$courseid = JRequest::getVar('course');
		$answer		=& $this->get('Data');
		$isNew		= ($answer->id < 1);
		if ($isNew) $answer->disporder = JRequest::getVar('nextnum');
		$model = $this->getModel('part');
		$text = $isNew ? JText::_( 'New' ) : JText::_( 'Edit' );
		if ($isNew) $answer->part_area=JRequest::getVar('area');
		JToolBarHelper::title(   JText::_( 'ContinuEd Part Label' ).': <small><small>[ ' . $text.' ]</small></small>' );
		JToolBarHelper::save();
		if ($isNew)  {
			JToolBarHelper::cancel();
		} else {
			JToolBarHelper::cancel( 'cancel', 'Close' );
		}

		$numparts = $model->getNumParts($courseid,JRequest::getVar('area'));

		$this->assignRef('answer',$answer);
		$this->assignRef('courseid',$courseid);
		$this->assignRef('numparts',$numparts);
		parent::display($tpl);
	}
}
