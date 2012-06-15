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

// Load helper
require_once(JPATH_COMPONENT.DS.'helpers'.DS.'continued.php');

// Load StyleSheet for template, based on config
$cecfg = ContinuEdHelper::getConfig();
$doc = &JFactory::getDocument();
$doc->addStyleSheet('media/com_continued/template/'.$cecfg->TEMPLATE.'/continued.css');
$doc->addScript('media/com_continued/scripts/jquery.js');
$doc->addScript('media/com_continued/scripts/jquery.validate.js');
$doc->addScript('media/com_continued/scripts/additional-methods.js');
$doc->addScript('media/com_continued/scripts/jquery.metadata.js');
$doc->addScript('media/com_continued/scripts/jquery.simplemodal.js');

if ($cecfg->mams) {
	$doc->addScript('media/com_mams/vidplyr/jwplayer.js');
	$doc->addScript('media/com_mams/scripts/mams.js');
	$doc->addScriptDeclaration("var mamsuri = '".JURI::base( true )."';");
	require_once(JPATH_BASE.DS.'components'.DS.'com_mams'.DS.'helpers'.DS.'mams.php');
}



// Create the controller
$classname	= 'ContinuEdController'.$controller;
$controller = new $classname( );
JPluginHelper::importPlugin('continued');

// Perform the Request task
$controller->execute( JRequest::getVar('task'));

// Redirect if set by the controller
$controller->redirect();
?>

