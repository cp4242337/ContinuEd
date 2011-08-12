<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewDegrees extends JView
{
	function display($tpl = null)
	{
		JToolBarHelper::title(   JText::_( 'ContinuEd Certificate Degrees Manager' ), 'continued' );

		$model = $this->getModel('degrees');
		$items		= $model->getDegrees();

		$this->assignRef('items',$items);

		parent::display($tpl);
	}
}
