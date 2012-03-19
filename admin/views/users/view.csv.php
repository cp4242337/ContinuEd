<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

class ContinuEdViewUsers extends JView
{
	function display($tpl = 'csv') 
	{
		// Get data from the model
		$items = $this->get('ItemsCSV');
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		$model=$this->getModel();
		$items = $model->applyData($items);
		$fdata=$model->getFields();
		$adata=$model->getAnswers($fdata);
		
		// Assign data to the view
		$this->items = $items;
		$this->fdata = $fdata;
		$this->adata = $adata;

		// Display the template
		parent::display($tpl);

	}

}
