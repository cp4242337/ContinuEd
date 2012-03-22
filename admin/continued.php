<?php
/**
 * ContinuEd entry point file for ContinuEd Admin Component
 * (C) 2008 Corona Productions
 * Coded and Created by: Mike Amundsen
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_continued')) 
{
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

// require helper file
JLoader::register('ContinuedHelper', dirname(__FILE__) . DS . 'helpers' . DS . 'continued.php');

//icon
$document = JFactory::getDocument();
$document->addStyleDeclaration('.icon-48-ContinuEd {background-image: url(../media/com_continued/images/continued-48x48.png);}');
$document->addStyleDeclaration('.icon-48-continued {background-image: url(../media/com_continued/images/continued-48x48.png);}');
$document->addStyleSheet('../media/com_continued/admin.css');

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by vidrev
$controller = JController::getInstance('ContinuEd');

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();

//Load Config

global $cecfg;
$cecfg = ContinuEdHelper::getConfig();

?>
