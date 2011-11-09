<?php

jimport( 'joomla.application.component.view');

class ContinuEdViewCourse extends JView
{
	function display($tpl = null)
	{
		global $cecfg;
		$app = JFactory::getApplication();
		$courseid = JRequest::getVar('course');
		$model =& $this->getModel();
		$tracked = ContinuEdHelper::trackViewed("lnk",$courseid,"PageLinkNoToken");
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$userid = $user->id;
		if ($username == 'Guest') $guest = true; else $guest=false;

		$course=$model->getCourse($guest,$courseid);
		

		if ($course->course_purchase) {
			if ($course->course_purchasesku) $paid = ContinuEdHelper::SKUCheck($user->id,$course->course_purchasesku);
			else $paid = ContinuEdHelper::PurchaseCheck($user->id,$course->course_id);
		}
		else $paid = true;
		if ($course->course_catlink) { //Catagory Program
			$url = JURI::current().'?option=com_continued&cat='.$course->course_catmenu.'&Itemid='.JRequest::getVar( 'Itemid' );
		} else if ($course->course_extlink) { //External Link
			$url = $course->course_exturl;
		} else{
			if ($course->status == 'fail'  && $course->cantake && !$course->expired) { //Failed,Can Take,Not Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->status == 'pass' && $course->cantake && !$course->expired) {  //Passes, Can Take, NotExpired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if (($course->status == 'incomplete' || !$course->status) && $course->cantake && !$course->expired && $paid) { //Not Yet Taken, Can TAke, NOt Expired, Paid
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if (($course->status == 'incomplete' || !$course->status) && $course->cantake && !$course->expired && !$paid) { //Not Yet Taken, Can TAke, NOt Expired, Not Paid
				//$url = $course->course_purchaselink;
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->status == 'pass' && !$course->cantake && !$course->expired){ //Passed, Cannot Take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if (($course->status == 'incomplete' || !$course->status) && !$course->cantake && !$course->expired) { //Not Yet taken,Can't take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if ($course->expired && $course->status != 'pass' && $course->cantake) { //Failed, Can Take, Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->expired && $course->status == 'pass' && $course->cantake) { //Passed, Can Take, Expired
				$url  = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->expired && !$course->cantake) { //Cannot take, Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			}
		}
		$app->redirect($url);
		
	}
}
?>
