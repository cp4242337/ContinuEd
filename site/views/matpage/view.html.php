<?php
/**
 * @version		$Id: view.html.php 2011-12-12 $
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @copyright	Copyright (C) 2008 - 2011 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd MatPage View
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	matpage
 * @since		1.20
 */
class ContinuEdViewMatpage extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$dispatcher	= JDispatcher::getInstance();
		$model =& $this->getModel();
		$pageid = JRequest::getVar( 'matid' );
		$courseid = JRequest::getVar( 'course' );
		$token = JRequest::getVar( 'token' );
		$ret = JRequest::getVar( 'ret',0 );
		$nocredit = JRequest::getVar('nocredit','0');
		$user = JFactory::getUser();
		
		//Get page data
		$matpage=$model->getMatPage($pageid);
		
		//Page exists, Token exists, no return flag
		if ($matpage && $token && !$ret) {
			
			//get user status of page
			$matstatus = ContinuEdHelper::checkMat($pageid);
			
			//Start matpage view, if non existant
			if (!$matstatus) { ContinuEdHelper::startMat($pageid); $tracktype="view"; }
			else { $tracktype="review"; }
			
			if ($nocredit || !$user->id) $tracktype="nocredit";
			
			ContinuEdHelper::trackMat($pageid,$tracktype);
			
			//run plugins
			$results = $dispatcher->trigger('onContinuEdPrepare', array(&$matpage->mat_content));
			
			//assign vars and show page
			$this->assignRef('matpage',$matpage);
			$this->assignRef('token',$token);
			$this->assignRef('nocredit',$nocredit);
			parent::display($tpl);
			
		} else if ($matpage && $token && $ret) {
		//Page exists, Token exists, return flag set
			
			//End matpage View if text/html
			if ($matpage->mat_type == "text") ContinuEdHelper::endMat($pageid);
			
			//return to material page
			if ($nocredit == 0) $app->redirect('index.php?option=com_continued&view=material&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&token='.$token);
			else  $app->redirect('index.php?option=com_continued&view=material&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid.'&nocredit=1&token='.$token);
			 
		} else {
		//return to course, not entered properly or no page exists
			$app->redirect('index.php?option=com_continued&view=course&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$courseid); 
		}
	}
}
?>
