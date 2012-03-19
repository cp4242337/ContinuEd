<?php
defined('_JEXEC') or die();

jimport( 'joomla.application.component.view' );

class ContinuEdViewCatStat extends JView
{
	function display($tpl = 'csv')
	{
				
		$model = $this->getModel();

		$items		= $model->getDataCSV();
		$users		= $model->getUsers();

		
		$this->assignRef('items',$items);
		$this->assignRef('users',$users);

		parent::display($tpl);
	}
}
