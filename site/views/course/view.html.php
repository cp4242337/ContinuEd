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
class ContinuEdViewCourse extends JView
{
	function display($tpl = null)
	{
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar('course');
		$model =& $this->getModel();
		$tracked = ContinuEdHelper::trackViewed("lnk",$courseid,"PageLinkNoToken");
		$user =& JFactory::getUser();
		$cecfg = ContinuEdHelper::getConfig();
		
		//Get course info
		$course=$model->getCourse($courseid);
		
		//Check purchase
		if ($course->course_purchase && $cecfg->purchase) {
			$paid = ContinuEdHelper::PurchaseCheck($course->course_id);
		}
		else $paid = true;
		
		//Get Route to first step
		if ($course->course_catlink) { //Category Program
			$url = JURI::current().'?option=com_continued&cat='.$course->course_catmenu.'&Itemid='.JRequest::getVar( 'Itemid' );
		} else if ($course->course_extlink) { //External Link
			$url = $course->course_exturl;
		} else{
			if ($course->status == 'fail'  && $course->cantake && !$course->expired) { //Failed,Can Take,Not Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->status == 'flunked' || $course->status == 'pass' || $course->status == 'complete'  || $course->status == 'audit' && $course->cantake && !$course->expired) {  //Passes, Can Take, NotExpired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if (($course->status == 'incomplete' || !$course->status) && $course->cantake && !$course->expired && $paid) { //Not Yet Taken, Can TAke, NOt Expired, Paid
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if (($course->status == 'incomplete' || !$course->status) && $course->cantake && !$course->expired && !$paid) { //Not Yet Taken, Can TAke, NOt Expired, Not Paid
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->status == 'pass' || $course->status == 'complete'  || $course->status == 'audit' && !$course->cantake && !$course->expired){ //Passed, Cannot Take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if (($course->status == 'incomplete' || !$course->status) && !$course->cantake && !$course->expired) { //Not Yet taken,Can't take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if ($course->expired && $course->status != 'pass' && $course->cantake) { //Failed, Can Take, Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->expired && $course->status == 'pass' || $course->status == 'complete'  || $course->status == 'audit' && $course->cantake) { //Passed, Can Take, Expired
				$url  = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->expired && !$course->cantake) { //Cannot take, Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			}
		}
		
		//redirect user
		$app->redirect($url);
		
	}
}
?>
