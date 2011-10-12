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
		$clist = $model->getCompletedList();
		

		if (!$guest && in_array($course->course_prereq,$clist)) $cantake=true;
		else $cantake=false;
		if ($course->course_prereq==0) $cantake = true;
		if ($course->rec_pass == 'pass' && !$course->course_hasfm && !$course->course_hasmat) $cantake = false;
		if ((strtotime($course->course_enddate."+ 1 day") <= strtotime("now")) && ($course->course_enddate != '0000-00-00 00:00:00')) $expired=true; else $expired = false;
		if ($expired && !$course->course_hasmat) $cantake = false;
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
			if ($course->rec_pass == 'fail'  && $cantake && !$expired) { //Failed,Can Take,Not Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->rec_pass == 'pass' && $cantake && !$expired) {  //Passes, Can Take, NotExpired
				$url = JURI::current().'?option=com_continued&view=fmpass&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->rec_pass == 'incomplete' && $cantake && !$expired && $paid) { //Not Yet Taken, Can TAke, NOt Expired, Paid
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->rec_pass == 'incomplete' && $cantake && !$expired && !$paid) { //Not Yet Taken, Can TAke, NOt Expired, Not Paid
				//$url = $course->course_purchaselink;
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($course->rec_pass == 'pass' && !$cantake && !$expired){ //Passed, Cannot Take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if ($course->rec_pass == 'incomplete' && !$cantake && !$expired) { //Not Yet taken,Can't take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if ($expired && $course->rec_pass != 'pass' && $cantake) { //Failed, Can Take, Expired
				$url = JURI::current().'?option=com_continued&view=dispfm&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($expired && $course->rec_pass == 'pass' && $cantake) { //Passed, Can Take, Expired
				$url  = JURI::current().'?option=com_continued&view=dispfm&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course->course_id;
			} else if ($expired && !$cantake) { //Cannot take, Expired
				$url = JURI::current().'?option=com_continued&cat='.$course->course_cat.'&Itemid='.JRequest::getVar( 'Itemid' );
			}
		}
		$app->redirect($url);
		
	}
}
?>
