<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');


class ContinuEdControllerCats extends JControllerAdmin
{

	protected $text_prefix = "COM_CONTINUED_CAT";
	
	public function getModel($name = 'Cat', $prefix = 'ContinuEdModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
