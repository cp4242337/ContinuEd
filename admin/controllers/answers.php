<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

//DEVNOTE: import CONTROLLER object class
jimport( 'joomla.application.component.controller' );

class ContinuEdControllerAnswers extends JControllerAdmin
{

	protected $text_prefix = "COM_CONTINUED_ANSWER";
	
	public function getModel($name = 'Answer', $prefix = 'ContinuEdModel') 
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
}
?>
