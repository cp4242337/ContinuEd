<?php

jimport( 'joomla.application.component.view');

class ContinuEdViewCourse extends JView
{
	function display($tpl = null)
	{
		global $cecfg, $mainframe;
		$courseid = JRequest::getVar('course');
		$model =& $this->getModel();
		$tracked = $model->ceLink($courseid);
		$user =& JFactory::getUser();
		$username = $user->guest ? 'Guest' : $user->name;
		$userid = $user->id;
		if ($username == 'Guest') $guest = true; else $guest=false;

		$course=$model->getCourse($guest,$courseid);
		$clist = $model->getCompletedList();

		if (!$guest && in_array($course['prereq'],$clist)) $cantake=true;
		else $cantake=false;
		if ($course['prereq']==0) $cantake = true;
		if ($course['cpass'] == 'pass' && !$course['hasfm'] && !$course['hasmat']) $cantake = false;
		if ((strtotime($course['enddate']."+ 1 day") <= strtotime("now")) && ($course['enddate'] != '0000-00-00 00:00:00')) $expired=true; else $expired = false;
		if ($expired && !$course['hasmat']) $cantake = false;
		if ($course['course_purchase']) {
			if ($course['course_purchasesku']) $paid = ContinuEdHelperCourse::SKUCheck($user->id,$course['course_purchasesku']);
			else $paid = ContinuEdHelperCourse::PurchaseCheck($user->id,$course['id']);
		}
		else $paid = true;
		if ($course['course_catlink']) { //Catagory Program
			$url = JURI::current().'?option=com_continued&cat='.$course['course_catmenu'].'&Itemid='.JRequest::getVar( 'Itemid' );
		} else if ($course['course_extlink']) { //External Link
			$url = $course['course_exturl'];
		} else{
			if ($course['cpass'] == 'fail'  && $cantake && !$expired) { //Failed,Can Take,Not Expired
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if ($course['cpass'] == 'pass' && $cantake && !$expired) {  //Passes, Can Take, NotExpired
				$url = JURI::current().'?option=com_continued&view=fmpass&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if (empty($course['cpass']) && $cantake && !$expired && $paid) { //Not Yet Taken, Can TAke, NOt Expired, Paid
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if (empty($course['cpass']) && $cantake && !$expired && !$paid) { //Not Yet Taken, Can TAke, NOt Expired, Not Paid
				//$url = $course['course_purchaselink'];
				$url = JURI::current().'?option=com_continued&view=frontmatter&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if ($course['cpass'] == 'pass' && !$cantake && !$expired){ //Passed, Cannot Take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course['ccat'].'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if (empty($course['cpass']) && !$cantake && !$expired) { //NOt Ye taken,Can't take, Not Expired
				$url = JURI::current().'?option=com_continued&cat='.$course['ccat'].'&Itemid='.JRequest::getVar( 'Itemid' );
			} else if ($expired && $course['cpass'] != 'pass' && $cantake) { //Failed, Can Take, Expired
				$url = JURI::current().'?option=com_continued&view=dispfm&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if ($expired && $course['cpass'] == 'pass' && $cantake) { //Passed, Can Take, Expired
				$url  = JURI::current().'?option=com_continued&view=dispfm&Itemid='.JRequest::getVar( 'Itemid' ).'&course='.$course['id'];
			} else if ($expired && !$cantake) { //Cannot take, Expired
				$url = JURI::current().'?option=com_continued&cat='.$course['ccat'].'&Itemid='.JRequest::getVar( 'Itemid' );
			}
		}
		$mainframe->redirect($url);
		
	}
}
?>
