<?php
/**
 * @version		$Id: view.html.php 2012-01-09 $
 * @package		ContinuEd.Site
 * @subpackage	course
 * @copyright	Copyright (C) 2008 - 2012 Corona Productions.
 * @license		GNU General Public License version 2
 */

jimport( 'joomla.application.component.view');

/**
 * ContinuEd Course Entry
 *
 * @static
 * @package		ContinuEd.Site
 * @subpackage	course
 * @since		always
 */
class ContinuEdViewNoCredit extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar('course');
		$model =& $this->getModel();
		$tracked = ContinuEdHelper::trackViewed("lnk",$courseid,"PageLinkNoTokenNoCredit");
		$user =& JFactory::getUser();
		$cecfg = ContinuEdHelper::getConfig();

		//Get course info
		$course=$model->getCourse($courseid);
		
		//Check purchase
		if ($course->course_purchase && $cecfg->purchase) {
			$this->paid = ContinuEdHelper::PurchaseCheck($course->course_id);
		}
		else $paid = true;
		
		//Get Route to first step
		if ($course->course_catlink) { //Category Program
			$url = JURI::current().'?option=com_continued&cat='.$course->course_catmenu.'&Itemid='.JRequest::getVar( 'Itemid' );
		} else if ($course->course_extlink) { //External Link
			$url = $course->course_exturl;
		} else{
			$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&nocredit=1&course='.$course->course_id;
		}
		if (!$user->id && $cecfg->VO_REGREQ) {
			$url = 'index.php?option=com_continued&view=login&layout=login&return='.base64_encode($url);
		}
		//redirect user
		$app->redirect($url);
		
	}
}
?>
