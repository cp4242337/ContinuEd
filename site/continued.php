<?php
/**
 * ContinuEd entry point file for ContinuEd Component
 * (C) 2008-2011 Corona Productions
 * Coded and Created by: Mike Amundsen
 */
// no direct access
defined('_JEXEC') or die('Restricted access');


// Require the base controller
require_once (JPATH_COMPONENT.DS.'controller.php');

// Require specific controller if requested
if($controller = JRequest::getVar('controller')) {
	require_once (JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php');
}


require_once(JPATH_COMPONENT.DS.'helpers'.DS.'continued.php');

// Create the controller
global $cecfg;
$cecfg = ContinuEdHelper::getConfig();

$doc = &JFactory::getDocument();
$doc->addStyleSheet('media/com_continued/template/'.$cecfg->TEMPLATE.'/continued.css');

$classname	= 'ContinuEdController'.$controller;
$controller = new $classname( );
JPluginHelper::importPlugin('continued');

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
?>

