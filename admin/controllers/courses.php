<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class ContinuEdControllerQuestions extends JControllerAdmin
{

	protected $text_prefix = "COM_CONTINUEDL_COURSE";
	
	public function getModel($name = 'Course', $prefix = 'ContinuEdModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
